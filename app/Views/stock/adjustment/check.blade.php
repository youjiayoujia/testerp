@extends('common.form')
@section('formAction') {{ route('stockAdjustment.checkResult', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-sm-4'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : $model->adjust_form_id }}">
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control warehouse_id'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : $model->warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="adjust_by">调整人</label>
            <input type='text' class="form-control adjust_by" id="adjust_by" placeholder="调整人" name='adjust_by' value="{{ $model->adjustByName->name }}">
        </div>
    </div>
    <div class='form-group'>
        <label for='label'>备注(原因)</label>
        <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') ? old('remark') : $model->remark }}</textarea>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">sku</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class='form-group col-sm-2'>
                    <label>出入库类型</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="quantity" class='control-label'>数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="unit_cost" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($adjustments as $key => $adjustment)
                <div class='row'>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control sku" id="arr[sku][{{$key}}]" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $adjustment->item->sku }}">
                    </div>
                    <div class='form-group col-sm-2'>
                        <select name='arr[type][{{$key}}]' class='form-control type'>
                            <option value='IN' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'IN' ? 'selected' : '' :$adjustment->type == 'IN' ? 'selected' : '' }}>入库</option>
                            <option value='OUT' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'OUT' ? 'selected' : '' :$adjustment->type == 'OUT' ? 'selected' : '' }}>出库</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' name='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id' placeholder='库位' value="{{ old('arr[warehouse_position_id][$key]') ? old('arr[warehouse_position_id][$key]') : $adjustment->position->name }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $adjustment->quantity }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control unit_cost" id="arr[unit_cost][{{$key}}]" placeholder="单价" name='arr[unit_cost][{{$key}}]' value="{{ old('arr[unit_cost][$key]') ? old('arr[unit_cost][$key]') : round($adjustment->amount/$adjustment->quantity, 3) }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" name='result' value='1' class="btn btn-success">审核通过</button>
    <button type="submit" name='result' value='0' class="btn btn-default">审核未通过</button>
@stop