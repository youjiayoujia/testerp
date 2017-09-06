@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('allotment.checkformUpdate', ['id' => $allotment->id]) }} @stop
@section('formBody') 
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label>
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ $allotment->allotment_id }}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_by" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_by" placeholder="调拨人" name='allotment_by' value="{{ $allotment->allotment_by}}" readonly>
        </div>
        <div class="form-group col-lg-3">
            <label for="out_warehouse_id" class='control-label'>调出仓库</label> 
            <input type='text' class="form-control" id="out_warehouse_id" placeholder="调出仓库" name='out_warehouse_id' value="{{ $allotment->outwarehouse->name}}" readonly>
        </div>
        <div class="form-group col-lg-3">
            <label for="in_warehouse_id" class='control-label'>调入仓库</label> 
            <input type='text' class="form-control" id="in_warehouse_id" data-warehouse="{{ $allotment->in_warehouse_id }}" placeholder="调入仓库" name='in_warehouse_id' value="{{ $allotment->inwarehouse->name}}" readonly>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            列表
        </div>  
        <div class='panel-body'>
            <div class='row'>
                <div class='col-sm-1'>
                    <label for='searchsku'>sku:</label>
                </div>
                <div class='col-sm-2'>
                    <input type='text' name='searchsku' class='searchsku form-control' value="{{ old('searchsku') }}"/>
                </div>
                <div class='col-sm-1'>
                    <input type='button' name='search' class='search btn btn-info form-control' value='搜索'/>
                </div>
            </div>
            @foreach($allotmentforms as $key => $allotmentform)
                <div class='row'>
                    <div class='form-group col-sm-1'>
                        <label for='sku' class='control-label'>sku</label>
                        <input type='text' data-sku="{{$allotmentform->item_id}}" class='form-control sku' id='arr[sku][{{$key}}]' placeholder='sku' name='arr[item_id][{{$key}}]' value='{{ $allotmentform->item->sku }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='quantity' class='control-label'>实发数量</label>
                        <input type='text' class='form-control quantity' id='arr[quantity][{{$key}}]' placeholder='quantity' name='arr[quantity][{{$key}}]' value={{ $allotmentform->quantity }} readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='amount' class='control-label'>总金额(￥)</label>
                        <input type='text' class='form-control amount' id='arr[amount][{{$key}}]' placeholder='总金额(￥)' name='arr[amount][{{$key}}]' value='{{ $allotmentform->amount }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='receive_quantity' class='control-label'>之前收到数量</label>
                        <input type='text' class='form-control old_receive_quantity' id='arr[old_receive_quantity][{{$key}}]' name='arr[old_receive_quantity][{{$key}}]' value='{{ $allotmentform->receive_quantity }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='new_receive_quantity' class='control-label'>新收到数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control new_receive_quantity' id='arr[new_receive_quantity][{{$key}}]' placeholder='新收到数量' name='arr[new_receive_quantity][{{$key}}]'>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='warehouse_position_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                            @if($positions[$key][1] == '2')
                            <select name='arr[warehouse_position_id][{{$key}}]' id='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id'>
                                @foreach($positions[$key][0] as $single)
                                    <option value="{{ $single->position->id}}">{{$single->position->name}}</option>
                                @endforeach
                            </select>
                            @endif
                            @if($positions[$key][1] == '1')
                            <select name='arr[warehouse_position_id][{{$key}}]' id='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id warehouse_position_id1'>
                                @foreach($positions[$key][0] as $single)
                                    <option value="{{ $single->position->id}}">{{$single->position->name}}</option>
                                @endforeach
                            </select>
                            @endif
                            @if($positions[$key][1] == '0')
                                <select name='arr[warehouse_position_id][{{$key}}]' id='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id warehouse_position_id1'>
                                </select>
                            @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <textarea name='remark' class='form-control'>{{$allotment->remark}}</textarea>
    </div>
@stop
@section('formButton')
@parent
<a href="{{ route('allotment.over', ['id'=>$allotment->id]) }}" class='btn btn-info allotmentover'>结束调拨</a>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.new_receive_quantity').blur(function(){
        obj = $(this);
        if($(this).val()) {
            var reg=/^(\d)+$/gi;
            if(!reg.test(obj.val())) {
                alert('你输入的是整数吗？');
                obj.val('');
                return;
            }
            quantity = parseInt(obj.parent().parent().find('.quantity').val());
            old_quantity = parseInt(obj.parent().parent().find('.old_receive_quantity').val());
            new_quantity = parseInt(obj.val());
            if(quantity < (old_quantity + new_quantity)) {
                alert('超出数量了');
                $(this).val('');
                return;
            }
        }
    });

    $('.search').click(function(){
        area = $(this).parent().parent().parent();
        searchsku = area.find('.searchsku').val();
        if(searchsku) {
            sku = area.find('.sku');
            $.each(sku, function(){
                sku = $(this).val();
                if(sku == searchsku) {
                    $(this).parent().parent().css('background', "#D6D6FF");
                    $(this).parent().parent().find('.new_receive_quantity').focus();
                }
                else
                    $(this).parent().parent().css('background', 'none');
            });
        }
    });

    $('.warehouse_position_id1').select2({
        ajax: {
            url: "{{ route('stock.ajaxGetByPosition') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                position: params.term, // search term
                page: params.page,
                warehouse_id: $('#in_warehouse_id').data('warehouse'),
                sku: $(this).parent().parent().find('.sku').data('sku'),
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
});
</script>