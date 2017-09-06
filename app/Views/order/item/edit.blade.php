@extends('common.form')
@section('formAction') {{ route('orderItem.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="order_id" class='control-label'>订单ID</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="order_id" placeholder="订单ID" name='order_id' value="{{ old('order_id') ? old('order_id') : $model->order_id }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="sku" class='control-label'>sku</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $model->sku }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="quantity" class='control-label'>数量</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="quantity" placeholder="数量" name='quantity' value="{{ old('quantity') ? old('quantity') : $model->quantity }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="price" class='control-label'>金额</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="金额" name='price' value="{{ old('price') ? old('price') : $model->price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="status" class='control-label'>订单状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="status" placeholder="订单状态" name='status' value="{{ old('status') ? old('status') : $model->status }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="ship_status" class='control-label'>发货状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="ship_status" placeholder="发货状态" name='ship_status' value="{{ old('ship_status') ? old('ship_status') : $model->ship_status }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="is_gift" class='control-label'>是否赠品</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="is_gift" placeholder="是否赠品" name='is_gift' value="{{ old('is_gift') ? old('is_gift') : $model->is_gift }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="remark" class='control-label'>备注</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
    </div>
@stop