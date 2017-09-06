@extends('common.form')
@section('formAction') {{ route('orderBlacklist.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="channel_id">平台</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="channel_id" class="form-control" id="channel_id">
                @foreach($channels as $channel)
                    <option value="{{$channel->id}}" {{$channel->id == $model->channel_id ? 'selected' : ''}}>
                        {{$channel->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="ordernum" class="control-label">订单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $model->ordernum }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="姓名" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="email" class="control-label">邮箱</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') ? old('email') : $model->email }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="by_id" class="control-label">买家ID</label>
            <input class="form-control" id="by_id" placeholder="买家ID" name='by_id' value="{{ old('by_id') ? old('by_id') : $model->by_id }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="zipcode" class="control-label">邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="邮编" name='zipcode' value="{{ old('zipcode') ? old('zipcode') : $model->zipcode }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="channel_account" class="control-label">销售账号</label>
            <input class="form-control" id="channel_account" placeholder="销售账号" name='channel_account' value="{{ old('channel_account') ? old('channel_account') : $model->channel_account }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="refund_order" class="control-label">退款订单数</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="refund_order" placeholder="退款订单数" name='refund_order' value="{{ old('refund_order') ? old('refund_order') : $model->refund_order }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="total_order" class="control-label">订单总数</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="total_order" placeholder="订单总数" name='total_order' value="{{ old('total_order') ? old('total_order') : $model->total_order }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="refund_rate" class="control-label">退款率</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="refund_rate" placeholder="退款率" name='refund_rate' value="{{ old('refund_rate') ? old('refund_rate') : $model->refund_rate }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="type" class='control-label'>类型</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="type" id="type">
                @foreach(config('order.blacklist_type') as $type_key => $type)
                    <option value="{{ $type_key }}" {{ old('type') ? (old('type') == $type_key ? 'selected' : '') : ($model->type == $type_key ? 'selected' : '') }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-10">
            <label for="remark" class="control-label">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
        </div>
    </div>
@stop