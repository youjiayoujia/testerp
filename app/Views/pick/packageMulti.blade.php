@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('pickList.packageStore', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>状态</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='text' class='form-control searchsku' placeholder='sku'>
        </div>
        <div class='form-group col-lg-2'>
            <button type='button' class='btn btn-info search'>确认</button>
            <button type='button' class='btn btn-warning printException'>打印异常</button>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-2'>数量</td>
                    <td class='col-lg-2'>按钮</td>
                </thead>
                <tbody class='new'>
                
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">未扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-1'>package ID</td>
                    <td class='col-lg-1'>订单号</td>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-1'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($packages as $package)
                    @if(empty($package->deleted_at))
                        @foreach($package->items as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='SIMPLE'>
                                @if($key == '0')
                                <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-2'>{{ $package->id }}</td>
                                <td rowspan="{{$package->items()->count()}}" class='col-lg-1'>{{ $package->order ? $package->order->id : '订单号有误' }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>
                                    @foreach($packageitem->item->product->wrapLimit as $limit)  
                                        {{ $limit->name }}
                                    @endforeach
                                </td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                    @if($key == '0')
                                    <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">
                                    @if($package->status == 'PACKED')
                                    {{ $package->status ? $package->status_name : '' }}</td>
                                    @else
                                    <font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    @else
                        @foreach($package->items()->withTrashed()->get() as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='CANCEL'>
                                @if($key == '0')
                                <td rowspan="{{$package->items()->withTrashed()->count()}}" class='package_id col-lg-2'>{{ $package->id }}</td>
                                <td rowspan="{{$package->items()->withTrashed()->count()}}" class='col-lg-1'>{{ $package->order ? $package->order->id : '订单号有误' }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>
                                    @foreach($packageitem->item->product->wrapLimit as $limit)  
                                        {{ $limit->name }}
                                    @endforeach
                                </td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                    @if($key == '0')
                                    <td class='status col-lg-1' rowspan="{{$package->items()->withTrashed()->count()}}">
                                    CANCEL</td>
                                    @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none;width:100px;height:100px'></iframe>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">包装完成</button>
    <button type="reset" class="btn btn-default">取消</button>
@stop
<script type='text/javascript'>
$(document).on('keydown', function (event) {
    if(event.keyCode == '13') {
        searchSku = $('.searchSku').val();
        if(searchSku) {
            flag = 0;
            $.each($('.new tr'),function(){
                if(searchSku == $(this).find('.new_sku').text()) {
                    flag = 1;
                    $(this).find('.new_quantity').text((parseInt($(this).find('.new_quantity').text()) + 1));
                    return false;
                }
            })
            if(flag == 1) {
                $('.searchsku').val('');
                $('.searchsku').focus();
                return false;
            }
            remark = '';
            $.each($('.sku'), function(){
                if($(this).text() == searchSku) {
                    remark = $(this).parent().find('.remark').text();
                }
            });
            str = "<tr><td class='new_sku'>"+searchSku+"</td><td>"+remark+"</td><td class='new_quantity'>1</td><td><button type='button' class='new_del_item btn btn-info'>撤销</button>";
            $('.new').append(str);
            $('.searchsku').val('');
            $('.searchsku').focus();
            return false;
        } else {
            return false;
        }
    }
    if(event.keyCode == '46') {
        $('.search').click();
    }
});

$(document).ready(function(){
    $('.searchsku').focus();

    $('.printException').click(function(){
        arr = new Array();
        i=0;
        $.each($('.sku'), function(){
            tmp = $(this).parent();
            package_id = tmp.data('id');
            sku = $(this).text();
            picked_quantity = parseInt(tmp.find('.picked_quantity').text());
            quantity = parseInt(tmp.find('.quantity').text());
            if(quantity > picked_quantity) {
                arr[i] = package_id + '.' + sku + '.' + (quantity - picked_quantity);
                i+=1;
            }
        });
        location.href="{{ route('pickList.printException', ['arr' => ''])}}"+arr;
    });

    $(document).on('click', '.new_del_item', function(){
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.cz', function(){
        block = $(this).parent().parent();
        packageId = block.data('id');
        $.get("{{route('package.ctrlZ')}}",
          {packageId:packageId},
          function(result){
            if(result) {
                location.reload();
            }
        });
    });

    $(document).on('click', '.search', function(){
        $('.notFindSku').text('');
        arr = new Array();
        flag = 0;
        i=0;
        $.each($('.new tr'), function(){
            new_quantity = parseInt($(this).find('.new_quantity').text());
            for(j=0;j<new_quantity;j++) {
                arr[i] = $(this).find('.new_sku').text();
                i++; 
            }
        });
        buf = new Array();
        $.each($('.sku'), function(){
            if($(this).parent().find('.picked_quantity').text() == '0' && $(this).text() == arr[0]) {
                if($(this).parent().data('status') == 'CANCEL') {
                    return true;
                }
                package_id = $(this).parent().data('id');
                n = 0;
                $.each($('.'+package_id), function(k,v){
                    quantity = $(v).find('.quantity').text();
                    quantity = parseInt(quantity);
                    for(m=0;m<quantity;m++) {
                        buf[n] = $(v).find('.sku').text();
                        n++;
                    }
                })
                if(arr.sort().toString() == buf.sort().toString()) {
                    flag = 1;
                    $.get(
                        "{{ route('package.multiPackage')}}",
                        {package_id:package_id},
                        function(result) {
                            if(!result) {
                                $('.notFindSku').text('找不到该包裹');
                                return false;
                            }
                        }
                    );
                    $.each($('.'+package_id), function(){
                        $(this).find('.picked_quantity').text($(this).find('.quantity').text());
                        $(this).find('.status').text('已包装');
                        $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
                        $('.new').html('');
                    });
                    $('#barcode').load(function(){
                        $('#barcode')[0].contentWindow.focus();
                        $('#barcode')[0].contentWindow.print();
                    });
                    return false;
                }
            }
        });
        if(flag == 0) {
            $('.notFindSku').text('根据所有sku匹配不到包裹信息');
            return false;
        }
    });
});
</script>