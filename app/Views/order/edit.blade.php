@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('order.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">基础信息</div>--}}
        {{--<div class="panel-body">--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="channel_id">渠道类型</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select name="channel_id" class="form-control" id="channel_id">--}}
                    {{--@foreach($channels as $channel)--}}
                        {{--<option value="{{$channel->id}}" {{$channel->id == $model->channel_id ? 'selected' : ''}}>--}}
                            {{--{{$channel->name}}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="channel_account_id">渠道账号</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select name="channel_account_id" class="form-control channel_account_id" id="channel_account_id">--}}
                    {{--@foreach($aliases as $alias)--}}
                        {{--<option value="{{$alias->id}}" {{$alias->id == $model->channel_account_id ? 'selected' : ''}}>--}}
                            {{--{{$alias->alias}}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="ordernum" class='control-label'>订单号</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $model->ordernum }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="channel_ordernum" class='control-label'>渠道订单号</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="channel_ordernum" placeholder="渠道订单号" name='channel_ordernum' value="{{ old('channel_ordernum') ? old('channel_ordernum') : $model->channel_ordernum }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="email" class='control-label'>邮箱</label>--}}
                {{--<input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') ? old('email') : $model->email }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="by_id" class='control-label'>买家ID</label>--}}
                {{--<input class="form-control" id="by_id" placeholder="买家ID" name='by_id' value="{{ old('by_id') ? old('by_id') : $model->by_id }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="status" class='control-label'>订单状态</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select class="form-control" name="status" id="status">--}}
                    {{--@foreach(config('order.status') as $status_key => $status)--}}
                        {{--<option value="{{ $status_key }}" {{ old('status') ? (old('status') == $status_key ? 'selected' : '') : ($model->status == $status_key ? 'selected' : '') }}>--}}
                            {{--{{ $status }}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="active" class='control-label'>售后状态</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select class="form-control" name="active" id="active">--}}
                    {{--@foreach(config('order.active') as $active_key => $active)--}}
                        {{--<option value="{{ $active_key }}" {{ old('active') ? (old('active') == $active_key ? 'selected' : '') : ($model->active == $active_key ? 'selected' : '') }}>--}}
                            {{--{{ $active }}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="customer_service">客服人员</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select name="customer_service" class="form-control" id="customer_service">--}}
                    {{--@foreach($users as $user)--}}
                        {{--<option value="{{$user->id}}" {{$user->id == $model->customer_service ? 'selected' : ''}}>--}}
                            {{--{{$user->name}}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="operator">运营人员</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select name="operator" class="form-control" id="operator">--}}
                    {{--@foreach($users as $user)--}}
                        {{--<option value="{{$user->id}}" {{$user->id == $model->operator ? 'selected' : ''}}>--}}
                            {{--{{$user->name}}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="address_confirm" class='control-label'>地址验证</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select class="form-control" name="address_confirm" id="address_confirm">--}}
                    {{--@foreach(config('order.address') as $address_key => $address)--}}
                        {{--<option value="{{ $address_key }}" {{ old('address_confirm') ? (old('address_confirm') == $address_key ? 'selected' : '') : ($model->address_confirm == $address_key ? 'selected' : '') }}>--}}
                            {{--{{ $address }}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="create_time" class='control-label'>渠道创建时间</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="create_time" placeholder="渠道创建时间" name='create_time' value="{{ old('create_time') ? old('create_time') : $model->create_time }}" readonly>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">支付信息</div>--}}
        {{--<div class="panel-body">--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="payment" class='control-label'>支付方式</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select class="form-control" name="payment" id="payment">--}}
                    {{--@foreach(config('order.payment') as $payment)--}}
                        {{--<option value="{{ $payment }}" {{ old('payment') ? (old('payment') == $payment ? 'selected' : '') : ($model->payment == $payment ? 'selected' : '') }}>--}}
                            {{--{{ $payment }}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="currency" class='control-label'>币种</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<select class="form-control" name="currency" id="currency">--}}
                    {{--@foreach($currencys as $currency)--}}
                        {{--<option value="{{ $currency->code }}" {{ old('currency') ? (old('currency') == $currency->code ? 'selected' : '') : ($model->currency == $currency->code ? 'selected' : '') }}>--}}
                            {{--{{ $currency->code }}--}}
                        {{--</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="rate" class='control-label'>汇率</label>--}}
                {{--<input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') ? old('rate') : $model->rate }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="amount" class='control-label'>总金额</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="amount" placeholder="总金额" name='amount' value="{{ old('amount') ? old('amount') : $model->amount }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="amount_product" class='control-label'>产品金额</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') ? old('amount_product') : $model->amount_product }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="amount_shipping" class='control-label'>运费</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="amount_shipping" placeholder="运费" name='amount_shipping' value="{{ old('amount_shipping') ? old('amount_shipping') : $model->amount_shipping }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="amount_coupon" class='control-label'>折扣金额</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') ? old('amount_coupon') : $model->amount_coupon }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="transaction_number" class='control-label'>交易号</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="transaction_number" placeholder="交易号" name='transaction_number' value="{{ old('transaction_number') ? old('transaction_number') : $model->transaction_number }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="payment_date" class='control-label'>支付时间</label>--}}
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                {{--<input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') ? old('payment_date') : $model->payment_date }}" readonly>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="panel panel-default">
        <div class="panel-heading">物流信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="shipping_firstname" class='control-label'>发货名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') ? old('shipping_firstname') : $model->shipping_firstname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_lastname" class='control-label'>发货姓氏</label>
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') ? old('shipping_lastname') : $model->shipping_lastname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address" class='control-label'>发货地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') ? old('shipping_address') : $model->shipping_address }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address1" class='control-label'>发货地址1</label>
                <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') ? old('shipping_address1') : $model->shipping_address1 }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_city" class='control-label'>发货城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') ? old('shipping_city') : $model->shipping_city }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_state" class='control-label'>发货省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') ? old('shipping_state') : $model->shipping_state }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>国家</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="shipping_country" class="form-control shipping_country" id="shipping_country">
                    @foreach($countries as $country)
                        <option value="{{ $country->code }}" {{ $country->code == $model->shipping_country ? 'selected' : ''}}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>发货国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="shipping_country" class="form-control shipping_country" id="shipping_country">
                    @foreach($countries as $country)
                        <option value="{{ $country->code }}" {{ $country->code == $model->shipping_country ? 'selected' : ''}}>{{ $country->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_zipcode" class='control-label'>发货邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') ? old('shipping_zipcode') : $model->shipping_zipcode }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_phone" class='control-label'>发货电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') ? old('shipping_phone') : $model->shipping_phone }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_shipping" class='control-label'>运费</label>
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                <input class="form-control" id="amount_shipping" placeholder="运费" name='amount_shipping' value="{{ old('amount_shipping') ? old('amount_shipping') : $model->amount_shipping }}">
            </div>
        </div>
    </div>
    {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">账单信息</div>--}}
        {{--<div class="panel-body">--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_firstname" class='control-label'>账单名字</label>--}}
                {{--<input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') ? old('billing_firstname') : $model->billing_firstname }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_lastname" class='control-label'>账单姓氏</label>--}}
                {{--<input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') ? old('billing_lastname') : $model->billing_lastname }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_address" class='control-label'>账单地址</label>--}}
                {{--<input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') ? old('billing_address') : $model->billing_address }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_city" class='control-label'>账单城市</label>--}}
                {{--<input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') ? old('billing_city') : $model->billing_city }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_state" class='control-label'>账单省/州</label>--}}
                {{--<input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') ? old('billing_state') : $model->billing_state }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_country" class='control-label'>账单国家/地区</label>--}}
                {{--<input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') ? old('billing_country') : $model->billing_country }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_zipcode" class='control-label'>账单邮编</label>--}}
                {{--<input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') ? old('billing_zipcode') : $model->billing_zipcode }}">--}}
            {{--</div>--}}
            {{--<div class="form-group col-lg-2">--}}
                {{--<label for="billing_phone" class='control-label'>账单电话</label>--}}
                {{--<input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') ? old('billing_phone') : $model->billing_phone }}">--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="panel panel-primary">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body" id="itemDiv">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="channel_sku" class='control-label'>渠道sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>数量</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label'>单价</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label'>是否有效</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="is_gift" class='control-label'>是否赠品</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="remark" class='control-label'>备注</label>
                </div>
                <div class="form-group col-sm-1">
                    <label for="ship_status" class='control-label'>发货状态</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($orderItems as $key => $orderItem)
                <div class='row'>
                    <div class="form-group col-sm-2">
                        <select name="arr[sku][{{$key}}]" class="form-control sku" id="arr[sku][{{$key}}]">
                            <option value="{{ $orderItem->item ? $orderItem->item->sku : '' }}">{{ $orderItem->item ? $orderItem->sku : ''}}</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control channel_sku" id="arr[channel_sku][{{$key}}" placeholder="渠道sku" name='arr[channel_sku][{{$key}}]' value="{{ old('arr[channel_sku][$key]') ? old('arr[channel_sku][$key]') : $orderItem->channel_sku }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $orderItem->quantity }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control price" id="arr[price][{{$key}}]" placeholder="单价" name='arr[price][{{$key}}]' value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $orderItem->price }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control is_active" name="arr[is_active][{{$key}}]" id="arr[is_active][{{$key}}]">
                            @foreach(config('order.is_active') as $is_active_key => $is_active)
                                <option value="{{ $is_active_key }}" {{ old('arr[is_active][$key]') ? (old('arr[is_active][$key]') == $is_active_key ? 'selected' : '') : ($orderItem->is_active == $is_active_key ? 'selected' : '') }}>
                                    {{ $is_active }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control is_gift" name="arr[is_gift][{{$key}}]" id="arr[is_gift][{{$key}}]">
                            @foreach(config('order.whether') as $is_gift_key => $is_gift)
                                <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][$key]') ? (old('arr[is_gift][$key]') == $is_gift_key ? 'selected' : '') : ($orderItem->is_gift == $is_gift_key ? 'selected' : '') }}>
                                    {{ $is_gift }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control remark" id="arr[remark][{{$key}}]" placeholder="备注" name='arr[remark][{{$key}}]' value="{{ old('arr[remark][$key]') ? old('arr[remark][$key]') : $orderItem->remark }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control status" name="arr[status][{{$key}}]" id="arr[status][{{$key}}]">
                            @foreach(config('order.item_status') as $ship_status_key => $status)
                                <option value="{{ $ship_status_key }}" {{ old('arr[status][$key]') ? (old('arr[status][$key]') == $ship_status_key ? 'selected' : '') : ($orderItem->status == $ship_status_key ? 'selected' : '') }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
        <div class="panel-footer">
            <div class="create" id="addItem"><i class="glyphicon glyphicon-plus"></i><strong>新增产品</strong></div>
        </div>
    </div>
    @if($model->customer_remark)
        <div class="panel panel-primary">
            <div class="panel-heading">客户留言</div>
            <div class="panel-body">
                <div class='row text-danger'>
                    <div class="col-lg-12"><font size='4px'>{{ $model->customer_remark }}</font></div>
                </div>
            </div>
        </div>
    @endif
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#create_time, #payment_date, #affair_time, #refund_time').cxCalendar();

            var payment = $('#payment').val();
            $('#refund').val(payment);
            var currency = $('#currency').val();
            $('#refund_currency').val(currency);

            var refund_time = $('#refund_time').val();
            if(refund_time == '0000-00-00') {
                $('#refund').val(null);
                $('#refund_currency').val(null);
                $('#refund_account').val(null);
                $('#refund_amount').val(null);
                $('#refund_time').val(null);
            }

            var affair_time = $('#affair_time').val();
            if(affair_time == '0000-00-00') {
                $('#affair_time').val('');
            }

            var current = "{{ $rows }}";
            $('#create_form').click(function(){
                $.ajax({
                    url:"{{ route('orderAdd') }}",
                    data:{current:current},
                    dataType:'html',
                    type:'get',
                    success:function(result) {
                        $('.addpanel').before(result);
                    }
                });
                current++;
            });

            $('#addItem').click(function () {
                $.ajax({
                    url: "{{ route('orderAdd') }}",
                    data: {current: current},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $('#itemDiv').append(result);
                        $('.sku').select2({
                            ajax: {
                                url: "{{ route('order.ajaxSku') }}",
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        sku: params.term,
                                        page: params.page
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
                            }
                        });
                    }
                });
                current++;
                if(current >= 1) {
                    $('.sub').prop('disabled', false);
                }
            });

            $('#channel_id').click(function(){
                var channel_id = $("#channel_id").val();
                $.ajax({
                    url : "{{ route('account') }}",
                    data : { id : channel_id },
                    dataType : 'json',
                    type : 'get',
                    success : function(result) {
                        $('.channel_account_id').html();
                        str = '';
                        for(var i=0; i<result.length; i++)
                            str += "<option value='"+result[i]['id']+"'>"+result[i]['alias']+"</option>";
                        $('.channel_account_id').html(str);
                    }
                });
            });

            $(document).on('blur', '.sku', function(){
                var tmp = $(this);
                var sku = $(this).val();
                $.ajax({
                    url : "{{ route('getMsg') }}",
                    data : {sku : sku},
                    dataType : 'json',
                    type : 'get',
                    success : function(result) {
                        if(result != false) {
                            tmp.parent().parent().find('.image').html("<img src='/"+result+"' width='25px' height='25px'>");
                        }else{
                            alert('sku有误');
                            tmp.val('');
                        }
                    }
                });
            });

            $('.shipping_country').select2();

            $('.sku').select2({
                ajax: {
                    url: "{{ route('order.ajaxSku') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            sku: params.term,
                            page: params.page
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
                }
            });

        });

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

    </script>
@stop