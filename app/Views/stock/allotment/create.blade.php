@extends('common.form')
@section('formAction') {{ route('stockAllotment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label> 
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ old('allotment_id') ? old('allotment_id') : 'DB'.time()}}" readonly>
        </div>
        <div class="form-group col-lg-3">
            <label for="out_warehouse_id" class='control-label'>调出仓库</label>  <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id='out_warehouse_id' name='out_warehouse_id' class='form-control'>
            <option value=''>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('out_warehouse_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="in_warehouse_id" class='control-label'>调入仓库</label>  <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id='in_warehouse_id' name='in_warehouse_id' class='form-control'>
            <option value=''>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('in_warehouse_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select> 
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label>
        <textarea name='remark' class='form-control'>{{ old('remark') }}</textarea>
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
                <div class="form-group col-sm-2">
                    <label for="amount" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
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
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control unit_cost" id="arr[unit_cost][0]" placeholder="单价" name='arr[unit_cost][0]' value="{{ old('arr[unit_cost][0]') }}" readonly>
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
        current = 1;
        $(document).on('click', '.create_form', function(){
            warehouse = $('#out_warehouse_id').val();
            $.ajax({
                url:"{{ route('allotment.add') }}",
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

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

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

        $(document).on('change', '#out_warehouse_id, #in_warehouse_id', function(){
            out_warehouse = $('#out_warehouse_id').val();
            in_warehouse = $('#in_warehouse_id').val();
            if(out_warehouse && in_warehouse && out_warehouse == in_warehouse)
            {
                alert('调入调出仓库不能相同');
            }
        });

        $(document).on('change', '#out_warehouse_id', function(){
            $('.sku').val('');
            $('.warehouse_position_id').val('');
            $('.access_quantity').val('');
            $('.quantity').val('');
            $('.unit_cost').val('');
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
                        block.find('.quantity').val('');
                    }
                });
            }
        });

        $(document).on('change', '.sku', function(){
            tmp = $(this);
            block = $(this).parent().parent();
            warehouse = $('#out_warehouse_id').val();
            position = block.find('.warehouse_position_id');
            position_name = position.prop('name');
            item_id = $(this).val();
            if(item_id) {
                $.ajax({
                    url:"{{ route('stock.allotSku' )}}",
                    data: {warehouse:warehouse, item_id:item_id},
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
                            block.find('.unit_cost').val('');
                            str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                            for(i=0; i<result[0].length; i++)
                            {
                                str += "<option value='"+result[0][i]['position']['id']+"'>"+result[0][i]['position']['name']+"</option>";
                            }
                            str += "</select>";
                            block.find('.position_html').html(str);
                            block.find('.access_quantity').val(result[0][0]['available_quantity']);
                            block.find('.quantity').val('');
                            block.find('.unit_cost').val(result[2]);
                        }
                    }
                });
            }
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
    });
</script>
@stop