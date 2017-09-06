@extends('common.form')
@section('formAction') {{ route('order.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="channel_id">渠道类型</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_id" class="form-control" id="channel_id">
                    @foreach($channels as $channel)
                        <option value="{{$channel->id}}" {{ Tool::isSelected('channel_id', $channel->id) }}>
                            {{$channel->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_account_id">渠道账号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_account_id" class="form-control channel_account_id">
                    @foreach($accounts as $account)
                        <option value="{{$account->id}}" {{$account->id == old('$account->account->id') ? 'selected' : ''}}>
                            {{$account->alias}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="ordernum" class='control-label'>订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_ordernum" class='control-label'>渠道订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="channel_ordernum" placeholder="渠道订单号" name='channel_ordernum' value="{{ old('channel_ordernum') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="email" class='control-label'>邮箱</label>
                <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="by_id" class='control-label'>买家ID</label>
                <input class="form-control" id="by_id" placeholder="买家ID" name='by_id' value="{{ old('by_id') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="status" class='control-label'>订单状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="status" id="status">
                    <option value="REVIEW" {{ old('status') == 'REVIEW' ? 'selected' : '' }}>需审核</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="active" class='control-label'>售后状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="active" id="active">
                    @foreach(config('order.active') as $active_key => $active)
                        <option value="{{ $active_key }}" {{ old('active') == $active_key ? 'selected' : '' }}>
                            {{ $active }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="customer_service">客服人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="customer_service" class="form-control" id="customer_service">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{ Tool::isSelected('customer_service', $user->id) }}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="operator">运营人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="operator" class="form-control" id="operator">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{ Tool::isSelected('operator', $user->id) }}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="address_confirm" class='control-label'>地址验证</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="address_confirm" id="address_confirm">
                    @foreach(config('order.address') as $address_key => $address)
                        <option value="{{ $address_key }}" {{ $address_key == '1' ? 'selected' : '' }}>
                            {{ $address }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="create_time" class='control-label'>渠道创建时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="create_time" placeholder="渠道创建时间" name='create_time' value="{{ old('create_time') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">支付信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="payment" class='control-label'>支付方式</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="payment" id="payment">
                    @foreach(config('order.payment') as $payment)
                        <option value="{{ $payment }}" {{ old('payment') == $payment ? 'selected' : '' }}>
                            {{ $payment }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="currency" class='control-label'>币种</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="currency" id="currency">
                    @foreach($currencys as $currency)
                        <option value="{{ $currency->code }}" {{ Tool::isSelected('currency', $currency->code) }}>
                            {{ $currency->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="rate" class='control-label'>汇率</label>
                <input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount" class='control-label'>总金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount" placeholder="总金额" name='amount' value="{{ old('amount') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_product" class='control-label'>产品金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_shipping" class='control-label'>运费</label>
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                <input class="form-control" id="amount_shipping" placeholder="运费" name='amount_shipping' value="{{ old('amount_shipping') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_coupon" class='control-label'>折扣金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="transaction_number" class='control-label'>交易号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="transaction_number" placeholder="交易号" name='transaction_number' value="{{ old('transaction_number') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="payment_date" class='control-label'>支付时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="shipping_firstname" class='control-label'>发货名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_lastname" class='control-label'>发货姓氏</label>
                {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address" class='control-label'>发货地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address1" class='control-label'>发货地址1</label>
                <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_city" class='control-label'>发货城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_state" class='control-label'>发货省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>发货国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control shipping_country" id="shipping_country" name='shipping_country'></select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_zipcode" class='control-label'>发货邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_phone" class='control-label'>发货电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">账单信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="billing_firstname" class='control-label'>账单名字</label>
                <input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_lastname" class='control-label'>账单姓氏</label>
                <input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_address" class='control-label'>账单地址</label>
                <input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_city" class='control-label'>账单城市</label>
                <input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_state" class='control-label'>账单省/州</label>
                <input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_country" class='control-label'>账单国家/地区</label>
                <input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_zipcode" class='control-label'>账单邮编</label>
                <input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_phone" class='control-label'>账单电话</label>
                <input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') }}">
            </div>
        </div>
    </div>
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
            <div class='row'>
                <div class="form-group col-sm-2">
                    <select class="form-control sku" id="arr[sku][0]" name='arr[sku][0]'></select>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control channel_sku" id="arr[channel_sku][0]" placeholder="渠道sku" name='arr[channel_sku][0]' value="{{ old('arr[channel_sku][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control price" id="arr[price][0]" placeholder="单价" name='arr[price][0]' value="{{ old('arr[price][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control is_active" name="arr[is_active][0]" id="arr[is_active][0]">
                        @foreach(config('order.is_active') as $is_active_key => $is_active)
                            <option value="{{ $is_active_key }}" {{ $is_active_key == '1' ? 'selected' : '' }}>
                                {{ $is_active }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control is_gift" name="arr[is_gift][0]" id="arr[is_gift][0]">
                        @foreach(config('order.whether') as $is_gift_key => $is_gift)
                            <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][0]') == $is_gift_key ? 'selected' : '' }}>
                                {{ $is_gift }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control remark" id="arr[remark][0]" placeholder="备注" name='arr[remark][0]' value="{{ old('arr[remark][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control status" name="arr[status][0]" id="arr[status][0]">
                        @foreach(config('order.item_status') as $ship_status_key => $status)
                            <option value="{{ $ship_status_key }}" {{ old('arr[status][0]') == $ship_status_key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
        </div>
        <div class="panel-footer">
            <div class="create" id="addItem"><i class="glyphicon glyphicon-plus"></i><strong>新增产品</strong></div>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success sub" id="tj">提交</button>
    <button type="reset" class="btn btn-default" id="qx">取消</button>
@show
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('#create_time, #payment_date, #affair_time, #refund_time').cxCalendar();

            //隐藏
            document.getElementById('tj').style.display='none';
            document.getElementById('qx').style.display='none';

            $('#payment').click(function() {
                var payment = $('#payment').val();
                $('#refund').val(payment);
            });
            $('#currency').click(function() {
                var currency = $('#currency').val();
                $('#refund_currency').val(currency);
            });

            var current = 1;
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

            $(document).on('click', '.bt_right', function () {
                $(this).parent().remove();
                current--;
                if(current < 1) {
                    $('.sub').prop('disabled', true);
                    alert('请输入sku');
                }
            });

            $('.shipping_country').select2({
                ajax: {
                    url: "{{ route('order.ajaxCountry') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            shipping_country: params.term,
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
    </script>
@stop