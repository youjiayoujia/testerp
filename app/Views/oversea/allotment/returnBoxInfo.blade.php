@extends('common.form')
@section('formAction') {{ route('overseaAllotment.returnBoxInfoStore', ['id' => $model->id]) }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label class='control-label'>调拨单号</label> 
            <input type='text' class="form-control" name='allotment_num' value="{{ $model->allotment_num }}" readonly>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">列表</div>
        <div class="panel-body">
                <div class='row'>
                    <div class='form-group col-lg-1'>
                        <label>箱号</label>
                    </div>
                    <div class='form-group col-lg-1'><small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <label>长(cm)</label>
                    </div>
                    <div class='form-group col-lg-1'><small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <label>宽(cm)</label>
                    </div>
                    <div class='form-group col-lg-1'><small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <label>高(cm)</label>
                    </div>
                    <div class='form-group col-lg-2'><small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <label>重量(kg)</label>
                    </div>
                    <div class='from-group col-lg-2'><small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <label>物流方式</label>
                    </div>
                </div>
            @foreach($model->boxes as $key => $box)
                <div class='row'>
                    <div class='form-group col-lg-1'>
                        <input type='text' class='form-control' value="{{$box->boxnum}}">
                    </div>
                    <div class='form-group col-lg-1'>
                        <input type='text' name="boxinfo[{{$box->id}}][length]" class='form-control' placeholder='长(cm)' value="{{ old('boxinfo.'.$box->id.'.length') ? old('boxinfo.'.$box->id.'.length') : ($box->length != 0 ? $box->length : '')}}">
                    </div>
                    <div class='form-group col-lg-1'>
                        <input type='text' name="boxinfo[{{$box->id}}][width]" class='form-control' placeholder='宽(cm)' value="{{ old('boxinfo.'.$box->id.'.width') ? old('boxinfo.'.$box->id.'.width') : ($box->width != 0 ? $box->width : '')}}">
                    </div>
                    <div class='form-group col-lg-1'>
                        <input type='text' name="boxinfo[{{$box->id}}][height]" class='form-control' placeholder='高(cm)' value="{{ old('boxinfo.'.$box->id.'.height') ? old('boxinfo.'.$box->id.'.height') : ($box->height != 0 ? $box->height : '')}}">
                    </div>
                    <div class='form-group col-lg-2'>
                        <input type='text' name="boxinfo[{{$box->id}}][weight]" class='form-control' placeholder="重量(kg)" value="{{ old('boxinfo.'.$box->id.'.weight') ? old('boxinfo.'.$box->id.'.weight') : ($box->weight != 0 ? $box->weight : '')}}">
                    </div>
                    <div class='from-group col-lg-2'>
                        <select name="boxinfo[{{$box->id}}][logistics_id]" class='form-control logistics'>
                        <option value=''>请选择物流方式</option>
                        @foreach($logisticses as $logistics)
                            <option value='{{$logistics->id}}' {{ old('boxinfo.'.$box->id.'.logistics_id') ? old('boxinfo.'.$box->id.'.logistics_id') == $logistics->id ? 'selected' : '' : ''}}>{{$logistics->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div> 
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.logistics').select2();

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
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

        $(document).on('change', '#out_warehouse_id', function(){
            flag = $(this).attr('flag');
            if(flag != 'false') {
               $('.block_item').remove(); 
               $(this).attr('flag', 'true');
            } else {
                $(this).attr('flag', 'true');
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
                            str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                            for(i=0; i<result[0].length; i++)
                            {
                                str += "<option value='"+result[0][i]['position']['id']+"'>"+result[0][i]['position']['name']+"</option>";
                            }
                            str += "</select>";
                            block.find('.position_html').html(str);
                            block.find('.access_quantity').val(result[0][0]['available_quantity']);
                            block.find('.quantity').val('');
                        }
                    }
                });
            }
        });
    });
</script>
@stop