@if($message->relatedOrders()->count() > 1)
    <ul class="nav nav-tabs" role="tablist">
        @foreach($message->relatedOrders as $key => $relatedOrder)
            <li role="presentation" class="{{ $key == 0 ? 'active' : '' }}">
                <a href="#{{ str_replace('.','_',$relatedOrder->order->ordernum) }}"
                   aria-controls="{{ str_replace('.','_',$relatedOrder->order->ordernum) }}"
                   role="tab"
                   data-toggle="tab">
                    {{ $relatedOrder->order->ordernum }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
<div class="tab-content">
    @foreach($message->relatedOrders as $key => $relatedOrder)
        <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" role="tabpanel" id="{{ str_replace('.','_',$relatedOrder->order->ordernum) }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ERP订单号:
                    <a href="{{ route('order.index') }}?mixedSearchFields%5BfilterFields%5D%5Bchannel_ordernum%5D={{$relatedOrder->order->channel_ordernum}}" target="_blank">
                        <strong>{{ $relatedOrder->order->id }}</strong>
                    </a>
                    <small>{{ '<'.$relatedOrder->order->email.'>' }}</small>
                    -
                    <strong>{{ $relatedOrder->order->status_text }}</strong>
                    -
                    <strong>{{ $relatedOrder->order->active_text }}</strong>
                    @if(! empty($relatedOrder->order->EbayFeedbackComment))
                        -
                        <strong style="color: red">{{ $relatedOrder->order->EbayFeedbackComment }}</strong>
                    @endif
                    <div class="close">
                        <a href="javascript:void(0);" onclick="if(confirm('确认取消此关联订单: {{ $relatedOrder->order->ordernum }} ?')){location.href='{{ route('message.cancelRelatedOrder', ['id'=>$message->id,'relatedOrderId'=>$relatedOrder->id]) }}'}">
                            <small class="glyphicon glyphicon glyphicon-off"></small>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-lg-3">
                            <strong>总额</strong>: {{ $relatedOrder->order->amount }} {{ $relatedOrder->order->currency }}
                        </div>
                        <div class="col-lg-3">
                            <strong>产品</strong>: {{ $relatedOrder->order->amount_product }} {{ $relatedOrder->order->currency }}
                        </div>
                        <div class="col-lg-3">
                            <strong>运费</strong>: {{ $relatedOrder->order->amount_shipping }} {{ $relatedOrder->order->currency }}
                        </div>
                        <div class="col-lg-3">
                            <strong>促销</strong>: {{ $relatedOrder->order->amount_coupon }} {{ $relatedOrder->order->currency }}
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-12">
                            <strong>运送地址</strong>:
                            {{ $relatedOrder->order->shipping_firstname }}
                            {{ $relatedOrder->order->shipping_lastname }},
                            {{ $relatedOrder->order->shipping_address }},
                            {{ $relatedOrder->order->shipping_address1 ? $relatedOrder->order->shipping_address1.',' : '' }}
                            {{ $relatedOrder->order->shipping_city }},
                            {{ $relatedOrder->order->shipping_state }},
                            {{ $relatedOrder->order->shipping_country }},
                            {{ $relatedOrder->order->shipping_zipcode }},
                            {{ $relatedOrder->order->shipping_phone }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                @foreach($relatedOrder->order->items as $item)
                                    <tr>
                                        <td>

                                            <a href="{{ route('item.show', $item->item_id ) }}" target="_blank">{{$item->sku}}</a>
                                        </td>
                                        <td>{{ $item->item->c_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->status_text }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-6">
                            <strong>创建时间</strong>: {{ $relatedOrder->order->create_time }}
                        </div>
						<div class="col-lg-6">
                            <strong>支付方式</strong>: {{ $relatedOrder->order->payment }}
                        </div>
                        <div class="col-lg-6">
                            <strong>支付时间</strong>: {{ $relatedOrder->order->create_time }}
                        </div>
                        <div class="col-lg-6">
                            <strong>订单操作</strong>:
                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#refund-{{$relatedOrder->id}}" id="button-refund-order-{{$relatedOrder->id}}" title="订单退款">
                                订单退款
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--order refund-->
            <div class="modal fade" id="refund-{{ $relatedOrder->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('refundUpdate', ['id' => $relatedOrder->id])}}" method="POST" enctype="multipart/form-data" id="reufnd-form-{{$relatedOrder->id}}">
                            <input type="hidden" name="channel_id" value="{{$relatedOrder->order->channel_id}}"/>
                            <input type="hidden" name="order_id" value="{{$relatedOrder->order->id}}"/>

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">退款信息</h4>
                            </div>
                            <div class="modal-body">
                                <label class='control-label'>历史退款</label>
                                @if(! $relatedOrder->order->refunds->isEmpty())
                                    <div class='row'>
                                        <div class="form-group col-sm-2">
                                            <label for="id" class='control-label'>退款ID</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="refund_amount" class='control-label'>退款金额</label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="reason" class='control-label'>退款原因</label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="created_at" class='control-label'>申请时间</label>
                                        </div>
                                    </div>
                                    @foreach($relatedOrder->order->refunds as $refund)
                                        <div class="row text-danger">
                                            <div class="col-lg-2">{{ $refund->id }}</div>
                                            <div class="col-lg-2">{{ $refund->refund_amount }}</div>
                                            <div class="col-lg-4">{{ $refund->reason ? $refund->reason_name : '' }}</div>
                                            <div class="col-lg-4">{{ $refund->created_at }}</div>
                                        </div>
                                        <div class="divider"></div>
                                    @endforeach
                                @else
                                    <div class="divider"></div>
                                @endif
                                <div class='row'>
                                    <div class="form-group col-lg-4">
                                        <label for="ordernum" class='control-label'>订单号</label>
                                        <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $relatedOrder->order->id }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="channel_account_id">渠道账号</label>
                                        <input class="form-control" id="channel_account_id" placeholder="渠道账号" name='channel_account_id' value="{{ old('channel_account_id') ? old('channel_account_id') : ($relatedOrder->order->channelAccount ? $relatedOrder->order->channelAccount->alias : '') }}" readonly>
                                    </div>
                                    {{--<div class="form-group col-lg-2" id="payment">--}}
                                    {{--<label for="payment_date" class='control-label'>支付时间</label>--}}
                                    {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                                    {{--<input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') }}">--}}
                                    {{--</div>--}}
                                    <div class="form-group col-lg-4">
                                        <label for="refund_amount" class='control-label'>退款金额</label>
                                        <input class="form-control" id="refund_amount{{ $relatedOrder->id }}" placeholder="退款金额" name='refund_amount' value="{{ old('refund_amount') }}">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="price" class='control-label'>确认金额</label>
                                        <input class="form-control" id="price{{ $relatedOrder->id }}" placeholder="确认金额" name='price' value="{{ old('price') }}">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="refund_currency" class='control-label'>退款币种</label>
                                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                        <select class="form-control" name="refund_currency" id="refund_currency">
                                            @foreach($currencys as $refund_currency)
                                                <option value="{{ $refund_currency->code }}" <?php if ($refund_currency->code == 'USD') echo 'selected'; ?> {{ old('refund_currency') }}>
                                                    {{ $refund_currency->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="refund" class='control-label'>退款方式</label>
                                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                        <select class="form-control refund-type refund-type-{{ $relatedOrder->id }}" name="refund" id="refund" order-id="{{ $relatedOrder->id }}">
                                            <option value="">==退款方式==</option>
                                            @foreach(config('order.refund') as $refund_key => $refund)
                                                <option value="{{ $refund_key }}" {{ old('refund') }}>
                                                    {{ $refund }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="reason" class='control-label'>退款原因</label>
                                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                        <select class="form-control reason-{{ $relatedOrder->id }}"  name="reason" id="reason">
                                            <option value="">==退款原因==</option>
                                            @foreach(config('order.reason') as $reason_key => $reason)
                                                <option value="{{ $reason_key }}" {{ old('reason') }}>
                                                    {{ $reason }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="type" class='control-label'>退款类型</label>
                                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                        <select class="form-control type-{{ $relatedOrder->id }} type" name="type" id="{{ $relatedOrder->id }}">
                                            <option value="">==退款类型==</option>
                                            @foreach(config('order.type') as $type_key => $type)
                                                <option value="{{ $type_key }}" {{ old('type') }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="paypal-input-{{ $relatedOrder->id }}" style="display: none;">
                                    <div class="col-lg-6 paypal-account">
                                        <label for="refund_currency" class='control-label'>客户退款Paypal账号</label>
                                        <input type="text" class="form-control" name="user_paypal_account" value="">
                                    </div>

                                    <div class="col-lg-6 refund-voucher">
                                        <label for="refund_currency" class='control-label'>退款凭证：</label>
                                        <input type="text" class="form-control" name="refund_voucher" value="">
                                    </div>
                                </div>
                                <item-group class="dom-items" style="display:none;">
                                @if($relatedOrder->order->items->toArray())
                                    <div class='row'>
                                        <div class="form-group col-sm-2">
                                            <input type="checkbox" isCheck="true" id="checkall{{ $relatedOrder->id }}" placeholder="" onclick="quanxuan('{{ $relatedOrder->id }}')">全选
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="id" class='control-label'>ID</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="sku" class='control-label'>sku</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="price" class='control-label'>单价</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="quantity" class='control-label'>数量</label>
                                        </div>
                                    </div>
                                    @foreach($relatedOrder->order->items as $key => $orderItem)
                                        @if($orderItem->is_refund == 0)
                                            <div class='row'>
                                                <div class="form-group col-sm-2">
                                                    <input type="checkbox" name="tribute_id[]" value="{{$orderItem->id}}">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type='text' class="id" id="arr[id][{{$key}}]" style="border: 0" placeholder="id" name='arr[id][{{$key}}]' value="{{ old('arr[id][$key]') ? old('arr[id][$key]') : $orderItem->id }}" readonly>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type='text' class="sku" id="arr[sku][{{$key}}]" style="border: 0" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $orderItem->sku }}" readonly>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type='text' class="form-control price" id="arr[price][{{$key}}]" placeholder="单价" name='arr[price][{{$key}}]' value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $orderItem->price }}" readonly>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $orderItem->quantity }}" readonly>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                </item-group>
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="memo" class='control-label'>Memo(只能填写英文)</label>
                                        <label class="text-danger">发给客户看的</label>
                                        <input class="form-control" id="memo" placeholder="" name='memo' value="{{ old('memo') }}">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="detail_reason" class='control-label'>详细原因</label>
                                        <label class="text-danger">挂号的,必须填写查询结果</label>
                                        <textarea class="form-control" rows="3" name="detail_reason" id="detail_reason">{{ old('detail_reason') }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="image">上传截图：</label>
                                        <label class="text-danger">(图片最大支持上传40Kb)</label>
                                        <input name='image' type='file'/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                <button type="button" class="btn btn-primary" onclick="refundOrder({{$relatedOrder->id}})">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--order refund-->

        @foreach($relatedOrder->order->packages as $package)
                <div class="panel panel-success">
                    <div class="panel-heading">
                        包裹:
                        <a href=" {{route('package.show',$package->id) }} " target="_blank">
                            <strong>{{ $package->id }}</strong>
                        </a>
                        -
                        <strong>{{ $package->StatusText }}</strong>
                    </div>
                        <div class="panel-body">
                            @if($package->shipping)
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <strong>物流</strong>:
                                        {{ $package->shipping->type }}
                                </div>
                                <div class="col-lg-6">
                                    <strong>物流网址</strong>
                                    <a target="_blank" href="{{$package->ThisPackageLogistic}}">
                                        {{$package->ThisPackageLogistic}}
                                    </a>
                                </div>

                            </div>
                            @endif
                                <div class="row form-group">
                                <div class="col-lg-6">
                                    <strong>创建</strong>: {{ $package->created_at }}
                                </div>
                                <div class="col-lg-6">
                                    <strong>打印</strong>: {{ $package->printed_at }}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <strong>发货</strong>: {{ $package->shipped_at }}
                                </div>
                                <div class="col-lg-6">
                                    <strong>妥投</strong>:
                                    @if($package->delivered_at)
                                        {{ $package->delivered_at }}
                                        {{--({{ $package->delivery_age }}天)--}}
                                    @else
                                        --
                                    @endif
                                </div>
                            </div>
                                <div class="row form-group">
                                    <div class="col-lg-6">
                                        <strong>是否标记发货</strong>:
                                        {{ $package->is_mark== 1 ?  '是' : '否' }}
                                    </div>
                                    <div class="col-lg-6">
                                        <strong>追踪号</strong>: {{ $package->tracking_no }}
                                    </div>

                                </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Item #</th>
                                            <th>Qty</th>
                                        </tr>
                                        </thead>
                                        @foreach($package->items as $item)
                                            <tr>
                                                <td>{{ $item->item->sku }}</td>
                                                <td>{{ $item->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">追踪信息</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    {{ $package->latest_trackinginfo ? $package->latest_trackinginfo : '暂无追踪信息' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <strong>更新时间</strong>: {{ $package->updated_at }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>