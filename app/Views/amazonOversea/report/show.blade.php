@extends('common.detail')
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-2'> 
        <label for='渠道帐号'>shipment名称</label> 
        <input type='text' class="form-control" placeholder="shipment 名称" name='shipment_name' value="{{ old('shipment_name') ? old('shipment_name') : $model->shipment_name }}">
    </div>
    <div class='form-group col-lg-2'> 
        <label for='渠道帐号'>渠道帐号</label> 
        <input type='text' class="form-control" value="{{ $model->account ? $model->account->account : '' }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="fba_address" class='control-label'>plan Id</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
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
    <table class="table table-bordered">
        <thead>
            <tr class='danger'>
            <th>名称</th>
            <th>值</th>
            <th>公式</th>
            </tr>
        </thead>
        <tbody>
        <tr class='success'><td>ERP总重量</td><td>{{$all_weight}}kg</td><td>ERP总重量=erp重量x商品实发数 ，累加</td></tr>
        <tr class='success'><td>实测总重量</td><td>{{$actual_weight}}kg</td><td>实测总重量=各箱重量之和</td></tr>
        <tr class='success'><td>实测体积</td><td>{{$volumn}}</td><td>实测体积=各箱体积重之和体积重=（长*宽*高）÷5000</td></tr>
        <tr class='success'><td>实际总运费</td><td>{{$fee}}kg</td><td>人工手动输入</td></tr>
        </tbody>
    </table>
</div>
<div class="panel panel-info">
    <div class="panel-heading">调拨单sku信息</div>
    <div class="panel-body add_row">
        <table class="table table-bordered">
            <thead>
                <tr class='danger'>
                <th>sku</th>
                <th>库位</th>
                <th>调拨数量</th>
                <th>实发数量</th>
                </tr>
            </thead>
            <tbody>
            @foreach($forms as $form)
            <tr class='success'>
                <td>{{ $form->item ? $form->item->sku : '' }}</td>
                <td>{{ $form->position ? $form->position->name : '' }}</td>
                <td>{{ $form->report_quantity }}</td>
                <td>{{ $form->out_quantity }}</td>
            </tr>        
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">装箱信息</div>
    <div class="panel-body">
    @foreach($boxes as $key => $box)
    <div class='row'>
        <div class="form-group col-lg-3">
            <label>箱号</label>
            <input type='text' class="form-control" value="{{ $box->boxNum }}">
        </div>
        <div class="form-group col-lg-3">
            <label>物流方式</label>
            <input type='text' class="form-control" value="{{ $box->logistics ? $box->logistics->code : '' }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积(m3)</label>
            <input type='text' class="form-control" value="{{ $box->length . '*' . $box->width . '*' . $box->height }}">
        </div>
        <div class="form-group col-lg-3">
            <label>预估重量(kg)</label>
            <input type='text' class="form-control" value="{{ $arr[$key] }}">
        </div>
        <div class="form-group col-lg-3">
            <label>实际重量(kg)</label>
            <input type='text' class="form-control" value="{{ $box->weight }}">
        </div>
        <div class="form-group col-lg-3">
            <label>物流费</label>
            <input type='text' class="form-control" value="{{ $box->fee }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积重</label>
            <input type='text' class="form-control" value="{{ round($box->length * $box->height * $box->width / 5000, 3) }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积系数</label>
            <input type='text' class="form-control" value="{{ round($box->length * $box->height * $box->width / 5000 / $box->weight, 4) }}">
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
            <th>sku</th>
            <th>fnsku</th>
            <th>数量</th>
            </tr>
        </thead>
        <tbody>
        @foreach($box->forms as $form)
        <tr class='success'>
            <td>{{ $form->sku }}</td>
            <td>{{ $form->fnsku }}</td>
            <td>{{ $form->quantity }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    @endforeach
    </div>
</div>
@stop