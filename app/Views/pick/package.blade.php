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
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
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
                    <td class='col-lg-1'>按钮</td>
                </thead>
                <tbody class='new'>
                @foreach($packages as $package)
                    @if($package->has_pick)
                        @if(empty($package->deleted_at))
                            @foreach($package->items as $key => $packageitem)
                                <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='SIMPLE'>
                                    @if($key == '0')
                                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
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
                                        @if($package->status != 'PACKED')
                                        <td class='status col-lg-1' rowspan="{{$package->items()->count()}}"><font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                                        @else
                                        <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">{{ $package->status ? $package->status_name : '' }}</td>
                                        @endif
                                    @endif
                                    @if($key == '0')
                                    <td class='col-lg-1' rowspan="{{$package->items()->count()}}"><button type='button' class='cz btn btn-info'>撤销</button></td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            @foreach($package->items()->withTrashed()->get() as $key => $packageitem)
                                <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='CANCEL'>
                                    @if($key == '0')
                                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
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
                                        <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">CANCEL</td>
                                    @endif
                                    @if($key == '0')
                                    <td class='col-lg-1' rowspan="{{$package->items()->count()}}"><button type='button' class='cz btn btn-info'>撤销</button></td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
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
                    @if(!$package->has_pick)
                        @if(empty($package->deleted_at))
                            @foreach($package->items as $key => $packageitem)
                                <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='SIMPLE'>
                                    @if($key == '0')
                                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
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
                                    <td class='status col-lg-1' rowspan="{{$package->items()->count()}}"><font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            @foreach($package->items()->withTrashed()->get() as $key => $packageitem)
                                <tr data-id="{{ $package->id}}" class="{{ $package->id}}" data-status='CANCEL'>
                                    @if($key == '0')
                                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
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
                                    <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">CANCEL</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
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
$(document).ready(function(){
    $('.searchsku').focus();

    $(document).on('keypress', function (event) {
        if(event.keyCode == '13') {
            $('.search').click(); 
            return false;
        }
    });

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
        val = $('.searchsku').val();
        $('.notFindSku').text('');
        extern_flag = 0;
        out_js = 0;
        $('.searchsku').val('');
        $('.searchsku').focus();
        if(val) {
            $.each($('.new tr'), function(){
                tmp = $(this);
                if(tmp.find('.sku').text() == val) {
                    if(tmp.data('status') == 'CANCEL') {
                        return true;
                    }
                    picked_quantity = parseInt(tmp.find('.picked_quantity').text());
                    quantity = parseInt(tmp.find('.quantity').text());
                    if(quantity > picked_quantity) {
                        extern_flag = 1;
                        package_id = tmp.data('id');
                        tmp.find('.picked_quantity').text(picked_quantity + 1);
                        needId = tmp.data('id');
                        out_js = 1;
                        id = tmp.data('id');
                        sku = tmp.find('.sku').text();
                        if(parseInt(tmp.find('.picked_quantity').text()) == quantity) {
                            $.ajax({
                                url:"{{ route('pickList.packageItemUpdate')}}",
                                data:{package_id:package_id},
                                dataType:'json',
                                type:'get',
                                success:function(result) {
                                    if(!result) {
                                        return false;
                                    }
                                }
                            });
                            $("."+id).find('.status').text('已包装');
                            $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
                            $('#barcode').load(function(){
                                $('#barcode')[0].contentWindow.focus();
                                $('#barcode')[0].contentWindow.print();
                            });
                        }
                    }
                }
            });
            if(out_js) {
                return false;
            }
            $.each($('.old tr'), function(){
                tmp = $(this);
                old_flag = 0;
                package_id = tmp.data('id');
                if(tmp.find('.sku').text() == val && parseInt(tmp.find('.quantity').text()) >  parseInt(tmp.find('.picked_quantity').text())) {
                    old_flag = 1;
                    if(tmp.data('status') == 'CANCEL') {
                        return true;
                    }
                    tmp.find('.picked_quantity').text(parseInt(tmp.find('.picked_quantity').text()) + 1);
                    sku = tmp.find('.sku').text();
                    if(parseInt(tmp.find('.picked_quantity').text()) == parseInt(tmp.find('.quantity').text())) {
                        $.ajax({
                            url:"{{ route('pickList.packageItemUpdate')}}",
                            data:{package_id:package_id},
                            dataType:'json',
                            type:'get',
                            success:function(result) {
                                if(!result) {
                                    return false;
                                }
                            }
                        });
                        needId = tmp.data('id');
                        flag = 1;
                        $.each($('.old tr'), function(){
                            innerNeedId = $(this).data('id');
                            if(innerNeedId == needId) {
                                if(parseInt($(this).find('.picked_quantity').text()) != parseInt($(this).find('.quantity').text())) {
                                    flag = 0;
                                }
                            }
                        });
                        if(flag) {
                            out_js = 1;
                            tmp.find('.status').text('已包装');
                        }
                        $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
                        $('#barcode').load(function(){
                            $('#barcode')[0].contentWindow.focus();
                            $('#barcode')[0].contentWindow.print();
                        });
                    }
                    needId = tmp.data('id');
                    arr = new Array();
                    i=0;
                    str = '';
                    $.each($('.old tr'), function(){
                        if($(this).data('id') == needId) {
                            arr[i] = $(this).html();
                            $(this).remove();
                            i++;
                        }
                    });
                    len = arr.length;
                    for(j=0;j<len;j++) {
                        if(j == 0) {
                            str = "<tr data-id='" + tmp.data('id') + "' class='"+ tmp.data('id') +"'>" + arr[j] + "<td class='col-lg-1' rowspan='" + len + "'><button type='button' class='cz btn btn-info'>撤销</button></td></tr>";
                        } else {
                            str += "<tr data-id='" + tmp.data('id') + "' class='"+ tmp.data('id') + "'>" + arr[j] + "</tr>";
                        }
                    }
                    $('.new').prepend(str);
                    out_js = 1;
                    return false;
                }
            });
        }
        if(out_js) {
            return false;
        }
        if(!extern_flag && val) {
            $('.notFindSku').text('sku不存在或者该对应的拣货单上sku已满');
            $('.searchsku').val('');
            $('.searchsku').focus();
            return false;
        }
    });
});
</script>