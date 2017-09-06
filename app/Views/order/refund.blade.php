@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('refundUpdate', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="panel panel-default">
        <div class="panel-heading">退款信息</div>
        <div class="panel-body">
            <div class='row'>
                <div class="form-group col-lg-3">
                    <label for="ordernum" class='control-label'>订单号</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <label style="color: red">(已付款时长:</label>
                    <input class="time" id="time" placeholder="" name="time" style="color: red; border: 0; height: 20px; width: 40px">
                    <label style="color: red">天)</label>
                    <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $model->ordernum }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <label for="channel_account_id">渠道账号</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name="channel_account_id" class="form-control channel_account_id" id="channel_account_id" disabled>
                        @foreach($aliases as $alias)
                            <option value="{{$alias->id}}" {{$alias->id == $model->channel_account_id ? 'selected' : ''}}>
                                {{$alias->alias}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2" id="payment">
                    <label for="payment_date" class='control-label'>支付时间</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') ? old('payment_date') : $model->payment_date }}">
                </div>
                <div class="form-group col-lg-2">
                    <label for="refund_amount" class='control-label'>退款金额</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" id="refund_amount" placeholder="退款金额" name='refund_amount' value="{{ old('refund_amount') ? old('refund_amount') : $model->refund_amount }}">
                </div>
                <div class="form-group col-lg-2">
                    <label for="price" class='control-label'>确认金额</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" id="price" placeholder="确认金额" name='price' value="{{ old('price') ? old('price') : $model->price }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="refund_currency" class='control-label'>退款币种</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control" name="refund_currency" id="refund_currency">
                        @foreach($currencys as $refund_currency)
                            <option value="{{ $refund_currency->code }}" {{ old('refund_currency') ? (old('refund_currency') == $refund_currency->code ? 'selected' : '') : ($model->refund_currency == $refund_currency->code ? 'selected' : '') }}>
                                {{ $refund_currency->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2">
                    <label for="refund" class='control-label'>退款方式</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control" name="refund" id="refund">
                        <option value="NULL">==退款方式==</option>
                        @foreach(config('order.refund') as $refund_key => $refund)
                            <option value="{{ $refund_key }}" {{ old('refund') ? (old('refund') == $refund_key ? 'selected' : '') : ($model->refund == $refund_key ? 'selected' : '') }}>
                                {{ $refund }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <label for="reason" class='control-label'>退款原因</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control" name="reason" id="reason">
                        <option value="NULL">==退款原因==</option>
                        @foreach(config('order.reason') as $reason_key => $reason)
                            <option value="{{ $reason_key }}" {{ old('reason') ? (old('reason') == $reason_key ? 'selected' : '') : ($model->reason == $reason_key ? 'selected' : '') }}>
                                {{ $reason }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2">
                    <label for="type" class='control-label'>退款类型</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control" name="type" id="type">
                        <option value="NULL">==退款类型==</option>
                        @foreach(config('order.type') as $type_key => $type)
                            <option value="{{ $type_key }}" {{ old('type') ? (old('type') == $type_key ? 'selected' : '') : ($model->type == $type_key ? 'selected' : '') }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='row'>
                <div class="form-group col-sm-1">
                    <input type="checkbox" isCheck="true" id="checkall" placeholder="" onclick="quanxuan()">全选
                </div>
                <div class="form-group col-sm-2">
                    <label for="id" class='control-label'>ID</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label'>单价</label>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>数量</label>
                </div>
            </div>
            @foreach($orderItems as $key => $orderItem)
                <div class='row'>
                    <div class="form-group col-sm-1">
                        <input type="checkbox" name="tribute_id[]" placeholder="全选" value="{{$orderItem->id}}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="id" id="arr[id][{{$key}}]" style="border: 0" placeholder="id" name='arr[id][{{$key}}]' value="{{ old('arr[id][$key]') ? old('arr[id][$key]') : $orderItem->id }}" readonly>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="sku" id="arr[sku][{{$key}}]" style="border: 0" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $orderItem->sku }}" readonly>
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control price" id="arr[price][{{$key}}]" placeholder="单价" name='arr[price][{{$key}}]' value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $orderItem->price }}" readonly>
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $orderItem->quantity }}" readonly>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="form-group col-lg-12">
                    <label for="memo" class='control-label'>Memo(只能填写英文)</label>
                    <label style="color: red;">发给客户看的</label>
                    <input class="form-control" id="memo" placeholder="" name='memo' value="{{ old('memo') ? old('memo') : $model->memo }}">
                </div>
                <div class="form-group col-lg-12">
                    <label for="detail_reason" class='control-label'>详细原因</label>
                    <label style="color: red;">挂号的,必须填写查询结果</label>
                    <textarea class="form-control" rows="3" name="detail_reason" id="detail_reason">{{ old('detail_reason') ? old('detail_reason') : $model->detail_reason }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-12">
                    <label for="image">上传截图：</label>
                    <label>(图片最大支持上传40Kb)</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input name='image' type='file'/>
                </div>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#refund_time').cxCalendar();

            document.getElementById('payment').style.display='none';
            document.getElementById('tj').style.display='none';
            document.getElementById('qx').style.display='none';

            var nowTime = new Date().getTime();
            var payTime = new Date($('#payment_date').val()).getTime();
            var time = (nowTime - payTime) / (1000 * 60 * 60 * 24);
            $('#time').val(time.toFixed(2));
        });

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

        //全选
        function quanxuan()
        {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id[]");
            if (collid.checked){
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
                $('.price').style.readonly = 'false';
            }else{
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop