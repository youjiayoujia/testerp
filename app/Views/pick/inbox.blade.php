@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('pickList.inboxStore', ['id'=>$model->id]) }} @stop
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
    <div class='row'>
        <div class='col-lg-6 inboxNum text-center'>
            <div class='form-group'>
                <font class='result'></font>
            </div>
            <div class='form-group'>
                <font class='result_cnname'></font>
                <font class='result_sku'></font>
            </div>
        </div>
        <div class='col-lg-6 inboxImage image'>

        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
        <td class='col-lg-1'>package ID</td>
        <td class='col-lg-2'>订单号</td>
        <td class='col-lg-6'>sku</td>
        <td class='col-lg-1'>应拣数量</td>
        <td class='col-lg-1'>实拣数量</td>
        <td class='col-lg-1'>状态</td>
        </thead>
        <tbody>
        @foreach($packages as $k => $package)
            <table class='table table-bordered table-condensed'>
                @foreach($package->items()->withTrashed()->get() as $key => $packageitem)
                    <tr data-id="{{$package->id}}">
                        @if($key == '0')
                            <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1' name="{{ $k+1 }}" data-cname="{{ $packageitem->item->c_name}}">{{ $package->id }}</td>
                            <td rowspan="{{$package->items()->count()}}" class='col-lg-2'>{{ $package->order ? $package->order->id : '订单号有误' }}</td>
                        @endif
                        <td class='sku col-lg-6' name="{{ $k+1 }}">{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                        <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                        <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                        @if($key == '0')
                            <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">
                                <font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endforeach
        </tbody>
    </table>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">分拣完成</button>
@stop
<script type='text/javascript'>
    $(document).on('keypress', function (event) {
        if (event.keyCode == '13') {
            $('.search').trigger("click");
            return false;
        }
    });

    $(document).ready(function () {
        $('.printException').click(function () {
            arr = new Array();
            i = 0;
            $.each($('.sku'), function () {
                tmp = $(this).parent();
                package_id = tmp.data('id');
                sku = $(this).text();
                picked_quantity = parseInt(tmp.find('.picked_quantity').text());
                quantity = parseInt(tmp.find('.quantity').text());
                if (quantity > picked_quantity) {
                    arr[i] = package_id + '.' + sku + '.' + (quantity - picked_quantity);
                    i += 1;
                }
            });
            location.href = "{{ route('pickList.printException', ['arr' => ''])}}" + arr;
        });

        $(document).on('click', '.search', function () {
            val = $('.searchsku').val();
            if (val) {
                $('.result').html('');
                $('.notFindSku').html('');
                $('.image').html('');
                outflag = 0;
                $.each($('.sku'), function () {
                    if ($(this).text() == val) {
                        row = $(this).parent();
                        if (parseInt(row.find('.quantity').text()) > parseInt(row.find('.picked_quantity').text())) {
                            outflag = 1;
                            packageid = row.data('id');
                            row.find('.picked_quantity').text(parseInt(row.find('.picked_quantity').text()) + 1);
                            $.get("{{route('item.getImage')}}",
                                    {sku: row.find('.sku').text()},
                                    function (result) {
                                        if (result) {
                                            $('.image').html("<img class='inboxImage' src=" + result + ">");
                                        }
                                    });
                            $('.result').text(row.find('.sku').attr('name') + '号');
                            $('.result_cname').text('中文名:' + row.find('.package_id').data('cname'));
                            $('.result_sku').html("<div class='row'>" + row.find('.sku').text() + "</div>");
                            if (parseInt(row.find('.quantity').text()) == parseInt(row.find('.picked_quantity').text())) {
                                flag = '1';
                                $.each($('tr[data-id='+packageid+']'), function(k,v){
                                    $.each($(v).find('.picked_quantity'), function (k1, v1) {
                                        if (parseInt($(v1).text()) != parseInt($(v1).parent().find('.quantity').text())) {
                                            flag = '0';
                                        }
                                    });
                                })
                                if (flag == '1') {
                                    row.find('.status').text('待包装');
                                }
                                return false;
                            }
                            return false;
                        }
                    }
                });
                if (outflag == 0) {
                    $('.notFindSku').text("sku不存在或者对应的拣货单上sku已满");
                }
                $('.searchSku').val('');
                $('.searchSku').focus();
                return false;
            }
        });
    });
</script>