@extends('common.form')
@section('formAction') {{ route('report.checkResult', ['id' => $model->id]) }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-4">
        <label for="fba_address" class='control-label'>fba地址</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="fba地址" name='fba_address' value="{{ old('fba_address') ? old('fba_address') : $model->fba_address }}">
    </div>
    <div class='form-group col-lg-4'> 
        <label for='渠道帐号'>shipment名称</label> 
        <input type='text' class="form-control" placeholder="shipment 名称" name='shipment_name' value="{{ old('shipment_name') ? old('shipment_name') : $model->shipment_name }}">
    </div>
    <div class='form-group col-lg-4'> 
        <label for='渠道帐号'>渠道帐号</label> 
        <input type='text' class="form-control" value="{{ $model->account ? $model->account->account : '' }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-4">
        <label for="fba_address" class='control-label'>plan Id</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="plan Id" name='plan_id' value="{{ old('plan_id') ? old('plan_id') : $model->plan_id }}">
    </div>
    <div class="form-group col-lg-4">
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
                <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
        </div>
        @foreach($forms as $form)
        <div class='row'>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control" value="{{ $form->item ? $form->item->sku : '' }}">
            </div>
            <div class="form-group col-sm-2 position_html">
                <input type='text' class="form-control" value="{{ $form->position ? $form->position->name : '' }}">
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control" value="{{ $form->report_quantity }}">
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