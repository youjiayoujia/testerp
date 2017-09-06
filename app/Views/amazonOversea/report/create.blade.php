@extends('common.form')
@section('formAction') {{ route('report.store') }} @stop
@section('formBody')
<div class='row'>
    <div class='form-group col-lg-2'> 
        <label for='渠道帐号'>shipment名称</label> 
        <input type='text' class="form-control" placeholder="shipment 名称" name='shipment_name' value="{{ old('shipment_name') }}">
    </div>
    <div class='form-group col-lg-2'> 
        <label for='渠道帐号'>渠道帐号</label> 
        <select name='account_id' class='form-control account_id'>
            @foreach($accounts as $account)
                <option value="{{ $account->id}}">{{ $account->account }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="fba_address" class='control-label'>plan Id</label>
        <input type='text' class="form-control" placeholder="plan Id" name='plan_id' value="{{ old('plan_id') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for='from_address'>shipment Id</label>
        <input type='text' class="form-control" placeholder="shipment Id" name='shipment_id' value="{{ old('shipment_id') }}">
    </div>
    <div class='form-group col-lg-4'> 
        <label for='渠道帐号'>reference Id</label> 
        <input type='text' class="form-control" placeholder="reference Id" name='reference_id' value="{{ old('reference_id') }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-3">
        <label>收货人姓</label>
        <input type='text' class="form-control" placeholder="姓" name='shipping_firstname' value="{{ old('shipping_firstname') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>收货人名</label>
        <input type='text' class="form-control" placeholder="名" name='shipping_lastname' value="{{ old('shipping_lastname') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>收货地址</label>
        <input type='text' class="form-control" placeholder="收货地址" name='shipping_address' value="{{ old('shipping_address') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>城市</label>
        <input type='text' class="form-control" placeholder="城市" name='shipping_city' value="{{ old('shipping_city') }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-3">
        <label>省(州)</label>
        <input type='text' class="form-control" placeholder="省(州)" name='shipping_state' value="{{ old('shipping_state') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>国家</label>
        <input type='text' class="form-control" placeholder="国家" name='shipping_country' value="{{ old('shipping_country') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>邮编</label>
        <input type='text' class="form-control" placeholder="邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') }}">
    </div>
    <div class="form-group col-lg-3">
        <label>电话</label>
        <input type='text' class="form-control" placeholder="电话" name='shipping_phone' value="{{ old('shipping_phone') }}">
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">列表</div>
    <div class="panel-body add_row">
        <div class='row'>
            <div class="form-group col-sm-2">
                <label for="sku">sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-sm-2">
                <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-sm-2">
                <label for="access_quantity" class='control-label'>可用数量</label>
            </div>
            <div class="form-group col-sm-2">
                <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
        </div>
        <div class='row'>
            <div class="form-group col-sm-2">
                <select class='form-control sku sku1' name="arr[item_id][0]"></select>
            </div>
            <div class="form-group col-sm-2 position_html">
                <input type='text' class="form-control warehouse_position_id" placeholder="库位" name='arr[warehouse_position_id][0]' value="{{ old('arr[warehouse_position_id][0]') }}">
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control access_quantity" placeholder="可用数量" name='arr[access_quantity][0]' value="{{ old('arr[access_quantity][0]') }}" readonly>
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control report_quantity" id="arr[report_quantity][0]" placeholder="数量" name='arr[report_quantity][0]' value="{{ old('arr[report_quantity][0]') }}">
            </div>
            <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
        </div>
    </div>
    <div class="panel-footer create_form">
        <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
    </div>
</div> 
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.account_id').select2();

    current = 1;
    $(document).on('click', '.create_form', function(){
        warehouse = $('#out_warehouse_id').val();
        $.ajax({
            url:"{{ route('report.add') }}",
            data:{current:current},
            dataType:'html',
            type:'get',
            success:function(result) {
                $('.add_row').children('div:last').after(result);
                $('.sku1').select2({
                    ajax: {
                        url: "{{ route('stock.overseaSku') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                          return {
                            sku: params.term, // search term
                            page: params.page,
                            'warehouse_id': $('#out_warehouse_id').val(),
                          };
                        },
                        results: function(data, page) {
                            if((data.results).length > 0) {
                                var more = (page * 20)<data.total;
                                return {results:data.results,more:more};
                            } else {
                                return {results:data.results};
                            }
                        }
                    },
                });
                current++;
            }
        })
    });

    $(document).on('click', '.bt_right', function(){
        $(this).parent().remove();
    });

    $(document).on('blur', '.report_quantity', function(){
        available = parseInt($(this).parent().parent().find('.access_quantity').val());
        val = $(this).val();
        if(val > available) {
            alert('数量超出可用数量');
            $(this).val('');
            return false;
        }
    });

    $('.sku1').select2({
        ajax: {
            url: "{{ route('stock.overseaSku') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                sku: params.term, // search term
                page: params.page,
              };
            },
            results: function(data, page) {
                if((data.results).length > 0) {
                    var more = (page * 20)<data.total;
                    return {results:data.results,more:more};
                } else {
                    return {results:data.results};
                }
            }
        },
    });

    $(document).on('change', '.quantity', function(){
        if($(this).val()) {
            var reg = /^(\d)+$/gi;
            if(!reg.test($(this).val())) {
                alert('输入数量有问题');
                $(this).val('');
                return;
            }
            obj = $(this).parent().parent();
            if($(this).val() > parseInt(obj.find('.access_quantity').val())) {
                alert('超出可用数量');
                $(this).val('');
                return;
            }
        }
    });

    $(document).on('change', '.warehouse_position_id', function(){
        tmp = $(this);
        block = tmp.parent().parent();
        sku = block.find('.sku').val();
        position = tmp.val();
        if(position) {
            $.ajax({
                url:"{{route('stock.ajaxGetOnlyPosition')}}",
                data:{position:position, sku:sku},
                dataType:'json',
                type:'get',
                success:function(result){
                    block.find('.access_quantity').val(result);
                    block.find('.report_quantity').val('');
                }
            });
        }
    });

    $(document).on('change', '.sku', function(){
        tmp = $(this);
        block = $(this).parent().parent();
        position_name = block.find('.warehouse_position_id').prop('name');
        item_id = $(this).val();
        if(item_id) {
            $.ajax({
                url:"{{ route('stock.overseaPosition' )}}",
                data: {item_id:item_id},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result == 'none') {
                        alert('sku有误或对应没有库存');
                        tmp.html('');
                        return;
                    }
                    if(result != false) {
                        str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                        str += "</select>";
                        block.find('.position_html').html(str);
                        block.find('.access_quantity').val('');
                        str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                        for(i=0; i<result[0].length; i++)
                        {
                            str += "<option value='"+result[0][i]['position']['id']+"'>"+result[0][i]['position']['name']+"</option>";
                        }
                        str += "</select>";
                        block.find('.position_html').html(str);
                        block.find('.access_quantity').val(result[0][0]['available_quantity']);
                    }
                }
            });
        }
    });
})
</script>
@stop