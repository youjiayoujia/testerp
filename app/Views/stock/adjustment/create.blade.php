@extends('common.form')
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-sm-4'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : 'CD'.time() }}" readonly>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' flag='false' class='form-control'>
                <option value=''>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class='form-group col-sm-12'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') }}</textarea>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">sku</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <label>出入库类型</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="access_quantity" class='control-label'>可用数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="quantity" class='control-label'>数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <select name='arr[type][0]' class='form-control type'>
                        <option value='IN' {{ old('arr[type][0]') == 'IN' ? 'selected' : '' }}>入库</option>
                        <option value='OUT' {{ old('arr[type][0]') == 'OUT' ? 'selected' : '' }}>出库</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <select class='form-control sku sku1' name="arr[item_id][0]"></select>
                </div>
                <div class="form-group col-sm-2 position_html">
                    <select name="arr[warehouse_position_id][0]" class="form-control warehouse_position_id warehouse_position_id1"></select>
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control access_quantity" id="arr[access_quantity][0]" placeholder="可用数量" name='arr[access_quantity][0]' value="{{ old('arr[access_quantity][0]') }}" readonly>
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
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script type='text/javascript'>
    $(document).ready(function(){
        var current = 1;    
        $(document).on('click', '.create_form', function(){
            warehouse = $('#warehouse_id').val();
            $.ajax({
                url:"{{ route('stockAdjustment.adjustAdd') }}",
                data:{current:current, warehouse:warehouse},
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
                                'warehouse_id': $('#warehouse_id').val()
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
                }
            });
            current++;
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
                    'warehouse_id': $('#warehouse_id').val(),
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

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
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

        $(document).on('blur', '.quantity', function(){
            tmp = $(this);
            block = tmp.parent().parent();
            type = block.find('.type').val();
            access_quantity = block.find('.access_quantity').val();
            quantity = block.find('.quantity').val();
            if(quantity && type == 'OUT') {
                if(parseInt(quantity) > parseInt(access_quantity)) {
                    alert('数量超出可用数量');
                    tmp.val('');
                }
            }
        });

        $(document).on('change', '.type', function(){
            block = $(this).parent().parent();
            name = block.find('.warehouse_position_id').attr('name');
            if($(this).val() == 'IN') {
                block.find('.position_html').html("<select name='"+name+"' class='form-control warehouse_position_id warehouse_position_id1'></select>");
            }
            block.find('.sku').val('');
            block.find('.quantity').val('');
            block.find('.access_quantity').val('');
            block.find('.unit_cost').val('');
        });



        $(document).on('change', '.sku', function(){
            var tmp = $(this);
            var block = $(this).parent().parent();
            var type = block.find('.type').val();
            var item_id = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            var position_name = block.find('.warehouse_position_id').prop('name');
            if(!warehouse_id) {
                alert('请选择仓库');
                location.reload();
                return false;
            }
            if(item_id && warehouse_id){
                $.ajax({
                    url: "{{route('stock.getMessage')}}",
                    data: {item_id:item_id, warehouse_id:warehouse_id, type:type},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(result == 'false' || result == 'sku_none') {
                            alert('sku有误');
                            tmp.val('');
                            return;
                        }
                        if(result == 'stock_none') {
                            if(block.find('.type').val() == 'OUT') {
                                alert('该sku没有对应的库存了');
                                block.find('.quantity').val('');
                                return false;
                            }
                        }
                        if(type == 'IN') {
                            str = '';
                            if(result[0] == '2') {
                                str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                                str +=
                                "<option value='"+result[1][0]['position']['id']+"'>"+result[1][0]['position']['name']+"</option>"+
                                "<option value='"+result[1][1]['position']['id']+"'>"+result[1][1]['position']['name']+"</option>";
                                str += '</select>';
                            }
                            if(result[0] == '1') {
                                str = "<select name='"+position_name+"' class='form-control warehouse_position_id warehouse_position_id1'>";
                                str +=
                                "<option value='"+result[1][0]['position']['id']+"'>"+result[1][0]['position']['name']+"</option>";
                                str += '</select>';
                            }
                            if(result[0] == '0') {
                                str = "<select name='"+position_name+"' class='form-control warehouse_position_id warehouse_position_id1'></select>";
                            }
                            block.find('.position_html').html(str);
                            $('.warehouse_position_id1').select2({
                                ajax: {
                                    url: "{{ route('stock.ajaxGetByPosition') }}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                      return {
                                        position: params.term, // search term
                                        page: params.page,
                                        warehouse_id: $('#warehouse_id').val(),
                                        sku: $(this).val(),
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
                            if(result[0]) {
                                block.find('.access_quantity').val(result[1][0]['available_quantity']);
                                block.find('.quantity').val('');
                                block.find('.unit_cost').val(result[2]);
                                return false;
                            }
                            block.find('.access_quantity').val('');
                            block.find('.quantity').val('');
                            block.find('.unit_cost').val();
                        } else {
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
                            block.find('.unit_cost').val(result[1]);
                        }
                    } 
                });
            }
        });

        $(document).on('change', '#warehouse_id', function(){
            flag = $(this).attr('flag');
            $(this).attr('flag', 'true');
            if(flag == 'true') {
                location.reload();
            }
        });
        
        $('#check_time').cxCalendar();
    });
</script>
@stop