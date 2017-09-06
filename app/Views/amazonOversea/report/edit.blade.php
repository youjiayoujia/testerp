@extends('common.form')
@section('formAction') {{ route('report.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
    <div class='form-group col-lg-2'> 
        <label>shipment名称</label> 
        <input type='text' class="form-control" placeholder="shipment 名称" name='shipment_name' value="{{ old('shipment_name') ? old('shipment_name') : $model->shipment_name }}">
    </div>
    <div class='form-group col-lg-2'> 
        <label for='渠道帐号'>渠道帐号</label> 
        <select name='account_id' class='form-control account_id'>
            @foreach($accounts as $account)
                <option value="{{ $account->id}}" {{ $account->id == $model->account_id ? 'selected' : ''}}>{{ $account->account }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="fba_address" class='control-label'>plan Id</label>
        <input type='text' class="form-control" placeholder="plan Id" name='plan_id' value="{{ old('plan_id') ? old('plan_id') : $model->plan_id }}">
    </div>
    <div class="form-group col-lg-2">
        <label for='from_address'>shipment Id</label>
        <input type='text' class="form-control" placeholder="shipment Id" name='shipment_id' value="{{ old('shipment_id') ? old('shipment_id') : $model->shipment_id }}">
    </div>
    <div class='form-group col-lg-4'> 
        <label for='渠道帐号'>reference Id</label> 
        <input type='text' class="form-control" placeholder="reference Id" name='reference_id' value="{{ old('reference_id') ? old('reference_id') : $model->reference_id }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-3">
        <label>收货人姓</label>
        <input type='text' class="form-control" placeholder="姓" name='shipping_firstname' value="{{ old('shipping_firstname') ? old('shipping_firstname') : $model->shipping_firstname }}">
    </div>
    <div class="form-group col-lg-3">
        <label>收货人名</label>
        <input type='text' class="form-control" placeholder="名" name='shipping_lastname' value="{{ old('shipping_lastname') ? old('shipping_lastname') : $model->shipping_lastname }}">
    </div>
    <div class="form-group col-lg-3">
        <label>收货地址</label>
        <input type='text' class="form-control" placeholder="收货地址" name='shipping_address' value="{{ old('shipping_address') ? old('shipping_address') : $model->shipping_address }}">
    </div>
    <div class="form-group col-lg-3">
        <label>城市</label>
        <input type='text' class="form-control" placeholder="城市" name='shipping_city' value="{{ old('shipping_city') ? old('shipping_city') : $model->shipping_city }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-3">
        <label>省(州)</label>
        <input type='text' class="form-control" placeholder="省(州)" name='shipping_state' value="{{ old('shipping_state') ? old('shipping_state') : $model->shipping_state }}">
    </div>
    <div class="form-group col-lg-3">
        <label>国家</label>
        <input type='text' class="form-control" placeholder="国家" name='shipping_country' value="{{ old('shipping_country') ? old('shipping_country') : $model->shipping_country }}">
    </div>
    <div class="form-group col-lg-3">
        <label>邮编</label>
        <input type='text' class="form-control" placeholder="邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') ? old('shipping_zipcode') : $model->shipping_zipcode }}">
    </div>
    <div class="form-group col-lg-3">
        <label>电话</label>
        <input type='text' class="form-control" placeholder="电话" name='shipping_phone' value="{{ old('shipping_phone') ? old('shipping_phone') : $model->shipping_phone }}">
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
        @foreach($forms as $key => $form)
        <div class='row'>
            <div class="form-group col-sm-2">
                <select class='form-control sku sku1' name="arr[item_id][{{ $key }}}]">
                    <option value="{{ $form->item_id }}">{{ $form->item ? $form->item->sku : ''}}</option>
                </select>
            </div>
            <div class="form-group col-sm-2 position_html">
                <select class='form-control warehouse_position_id' name="arr[warehouse_position_id][{{ $key }}}]">
                @foreach($positions[$key] as $position)
                    <option value="{{ $position['id'] }}">{{ $position['name'] }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control access_quantity" placeholder="可用数量" name='arr[access_quantity][{{$key}}]' value="{{ old('arr[access_quantity][$key]') ? old('arr[access_quantity][$key]') : $available_quantity[$key] }}" readonly>
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control report_quantity" placeholder="数量" name='arr[report_quantity][{{$key}}]' value="{{ old('arr[report_quantity][$key]') ? old('arr[report_quantity][$key]') : $form->report_quantity }}">
            </div>
            <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
        </div>
        @endforeach
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
                        url: "{{ route('stock.ajaxSku') }}",
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

    $(document).on('click', '.bt_right', function(){
        $(this).parent().remove();
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