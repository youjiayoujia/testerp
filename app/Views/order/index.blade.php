@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuanOrder()">全选</th>
    <th class="sort" data-field="id">内单号</th>
    <th class="sort" data-field="channel_ordernum">平台订单号</th>
    <th class="sort" data-field="channel_id">渠道</th>
    <th class="sort" data-field="channel_account_id">销售账号</th>
    <th>买家ID</th>
    <th>收货人</th>
    <th>国家</th>
    <th class="sort" data-field="amount"><strong class="text-success">总金额</strong></th>
    <th class="sort" data-field="amount_shipping"><strong class="text-danger">运费</strong></th>
    <th class="sort" data-field="profit_rate"><strong class="text-success">预测毛利率</strong></th>
    <th>订单状态</th>
    <th>运营人员</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>详情</th>
@stop
@section('tableBody')
    @foreach($data as $order)
        <tr class="dark-{{ $order->status_color }}">
            <td>
                <input type="checkbox" name="tribute_id" value="{{$order->id}}">
                @if(($order->packages ? $order->packages->count() : 0) > 1)
                    <span class='glyphicon glyphicon-adjust'></span>
                @endif
            </td>
            <td class='orderId' data-id="{{ $order->id }}"><strong>{{ $order->id }}</strong></td>
            <td>
                {{ $order->channel_ordernum }}
            </td>
            <td>{{ $order->channel ? $order->channel->name : '' }}</td>
            <td>{{ $order->channelAccount ? $order->channelAccount->alias : '' }}</td>
            <td>
                {{ $order->by_id }}  <br/>{{ $order->email }} <br/>
                @if($order->is_send_ebay_msg ==1) <font color="red">(已发消息)</font>  @endif
            </td>
            <td>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</td>
            <td>{{ $order->shipping_country }}</td>
            <td>{{ $order->currency . ' ' . $order->amount }}</td>
            <td><strong class="text-danger">{{ $order->currency . ' ' . $order->amount_shipping }}</strong></td>
            <td>
                <div>{{ round($order->profit_rate ,4)*100 }}%</div>
                <div>产品成本: {{ $order->all_item_cost }} RMB</div>
                运费成本: <div class='logisticsFee'></div>
                <div>平台费: {{ sprintf("%.2f", $order->channel_fee) }} USD</div>
                <div>
                    毛利润: {{ round($order->profit, 2) }} USD
                </div>
                @if(($order->channel ? $order->channel->driver : '') == 'ebay')
                    手续费: {{ $order->fee_amt * $order->rate }} USD
                @endif
            </td>
            <td>{{ $order->status_name }}</td>
            <td>{{ $order->userOperator ? $order->userOperator->name : '未分配' }}</td>
            <td>{{ $order->created_at }}</td>
            <td>
                <a class="btn btn-primary btn-xs"
                   role="button"
                   data-toggle="collapse"
                   href=".collapseExample{{$order->id}}"
                   aria-expanded="false"
                   aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
            </td>
        </tr>
        <tr class="collapse collapseExample{{$order->id}} {{ $order->status_color }} fb">
            <td colspan="3">
                <address>
                    <strong>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</strong><br>
                    {{ $order->shipping_address }} {{ $order->shipping_address1 }}<br>
                    {{ $order->shipping_city . ', ' . $order->shipping_state.' '.$order->shipping_zipcode }}<br>
                    {{ $order->country ? $order->country->name.' '.$order->country->cn_name : '' }}<br>
                    <abbr title="ZipCode">Z:</abbr> {{ $order->shipping_zipcode }}
                    <abbr title="Phone">P:</abbr> {{ $order->shipping_phone }}
                </address>
                @if($order->customer_remark)
                    <div class="divider"></div>
                    <div class="text-danger">
                        {!! $order->customer_remark !!}
                    </div>
                @endif
                @if($order->remarks)
                    @foreach($order->remarks as $remark)
                        <div class="divider"></div>
                        <div class="text-danger">
                            {{ $remark->remark }}
                        </div>
                    @endforeach
                @endif
                @if($order->unpaidOrder)
                    <div class="divider"></div>
                    <div class="text-danger">
                        {{ '未付款: ' . $order->unpaidOrder->note }}
                    </div>
                @endif
                @if(count($order->refunds) > 0)
                    @foreach($order->refunds as $refund)
                        <div class="divider"></div>
                        <div class="text-danger">
                            <label>退款ID:</label>{{ $refund->id }}
                            <label>退款金额:</label>{{ $refund->refund_amount }}
                            <label>原因:</label>{{ $refund->reason ? $refund->reason_name : '' }}
                            <label>申请时间:</label>{{ $refund->created_at }}
                        </div>
                    @endforeach
                @endif
            </td>
            <td colspan="25">
                <div class="col-lg-12 text-center">
                    @foreach($order->items as $orderItem)
                        <div class="row">
                            <div class="col-lg-3">
                                ID:{{ $orderItem->item ? $orderItem->item->product_id : '' }}
                                <br>
                                @if($order->channel)
                                    @if($order->channel->driver == 'ebay')
                                        ebay站点: {{ $order->shipping_country }}
                                    @endif
                                @endif
                                <br>
                                {{ $orderItem->orders_item_number }}
                                <br>
                                @if(($order->channel ? $order->channel->driver : '') == 'ebay')
                                    成交费: {{ $order->deal_fee }} USD
                                @endif
                            </div>
                            {{--<div class="col-lg-1">{{ $orderItem->id . '@' . $orderItem->sku }}</div>--}}
                            {{--@if($orderItem->item)--}}
                            {{--<div class="col-lg-2">--}}
                            {{--<img src="{{ asset($orderItem->item->product->dimage) }}" width="50px">--}}
                            {{--</div>--}}
                            {{--@else--}}
                            {{--<div class="col-lg-2">--}}
                            {{--<img src="{{ asset('default.jpg') }}" width="50px">--}}
                            {{--</div>--}}
                            {{--@endif--}}
                            <div class="col-lg-2 text-primary">
                                {{ $orderItem->sku }} <br/>
                                [{{$orderItem->channel_sku}}]<br/>
                                {{ $orderItem->item ? ($orderItem->item->warehouse ? $orderItem->item->warehouse->name : '') : '' }}
                            </div>
                            @if($orderItem->item)
                                <div class="col-lg-1">
                                    <strong>{{ $orderItem->item ? $orderItem->item->status_name : '' }}</strong>
                                </div>
                                <div class="col-lg-2">{{ $orderItem->item ? $orderItem->item->c_name : '' }}</div>
                            @else
                                <div class="col-lg-2">
                                    <strong class="text-danger">未匹配</strong>
                                </div>
                                <div class="col-lg-1"></div>
                            @endif
                            <div class="col-lg-1">{{ $order->currency . ' ' . $orderItem->price }}</div>
                            <div class="col-lg-1">{{ 'X' . ' ' . $orderItem->quantity }}
                                @if($order->channel->driver == 'ebay')
                                    @if($orderItem->ebay_unpaid_status != 1)
                                        <button class="label label-danger  ebay-unpaid-case" data-toggle="modal" data-target="#ebay-unpaid-case-{{ $orderItem->id }}">
                                            消
                                        </button>
                                        <div class="modal fade in" id="ebay-unpaid-case-{{$orderItem->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form id="ebay-unpaid-form" action="{{route('message.ebayUnpaidCase')}}" methon="POST">
                                                        {!! csrf_field() !!}
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                            <h4 class="modal-title text-left" id="myModalLabel">Ebay
                                                                unpaid case</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="input-group">
                                                                <label>
                                                                    <input type="radio" name="disputeType" value="complaints"/>Unpaid
                                                                    case(the buyer has not paid)
                                                                </label>
                                                                <br/>
                                                                <label>
                                                                    <input type="radio" name="disputeType" value="cancel"/>取消交易(cancel)
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                取消
                                                            </button>
                                                            <button type="submit" class="btn btn-primary  ebay-unpaid-form-button">
                                                                提交
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="order_item_id" value="{{$orderItem->id}}"/>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="label label-success">已消</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="divider"></div>
                    @endforeach
                </div>
                <div class="col-lg-12 text-center">
                    <div class="row">
                        <div class="col-lg-3">物品数量: {{ $order->items->sum('quantity') }}</div>
                        <div class="col-lg-3">包裹个数: {{ $order->packages->count() }}</div>
                        <div class="col-lg-3">包裹总重: {{ $order->packages->sum('weight') }} Kg</div>
                        <div class="col-lg-3">运费合计: {{ $order->packages->sum('cost') }} RMB</div>
                    </div>
                    <div class="divider"></div>
                </div>
                <div class="col-lg-12">
                    @if($order->packages->count() > 0)
                        @foreach($order->packages as $package)
                            <div class="row">
                                <div class="col-lg-1">
                                    <strong>包裹ID</strong> :
                                    <a href="{{ route('package.show', ['id'=>$package->id]) }}">{{ $package->id }}</a>
                                </div>
                                <div class="col-lg-3">
                                    <strong>物流方式</strong>
                                    : {{ $package->logistics ? $package->logistics->name : '' }}
                                </div>
                                <div class="col-lg-2">
                                    <strong>追踪号</strong> :
                                    <a href="http://{{ $package->tracking_link }}">{{ $package->tracking_no }}</a>
                                </div>
                                <div class="col-lg-2">
                                    <strong>仓库</strong> : {{ $package->warehouse ? $package->warehouse->name : '' }}
                                </div>
                                <div class="col-lg-2">
                                    <strong>包裹状态</strong> : {{ $package->status_name }}
                                </div>
                                <div class="col-lg-1">
                                    <strong>是否标记</strong> : {{ $package->is_mark == '1' ? '是' : '否' }}
                                </div>
                                <div class="col-lg-1">
                                    <button class="btn btn-primary btn-xs split"
                                            data-toggle="modal"
                                            data-target="#split{{ $package->id }}" title='拆分包裹'>
                                        <span class="glyphicon glyphicon-tasks"></span>
                                    </button>
                                </div>
                                <div class="modal fade" id="split{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">拆分包裹</div>
                                                <div class="panel-body">
                                                    <div class='row'>
                                                        <div class='col-lg-5'>
                                                            <input type='text' class='form-control package_num' placeholder='需要拆分的包裹数'>
                                                        </div>
                                                        <div class='col-lg-1'>
                                                            <button type='button' class='btn btn-primary confirm_quantity' name=''>
                                                                确认
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class='split_package'></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </td>
        </tr>
        <tr class="collapse collapseExample{{$order->id}} {{ $order->status_color }} fb">
            <td colspan="30" class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-3">
                            收款方式 : {{ $order->payment }}
                        </div>
                        <div class="col-lg-5">
                            交易号 : {{ $order->transaction_number }}
                        </div>
                        <div class="col-lg-4">
                            物流方式 : {{ $order->shipping }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW' || $order->status == 'NEED')
                        <a href="{{ route('order.edit', ['id'=>$order->id]) }}" class="btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 编辑
                        </a>
                    @endif
                    @if($order->status == 'PICKING' && ($order->amount * $order->rate) >= 20)
                        <button class="btn btn-danger btn-xs"
                                data-toggle="modal"
                                data-target="#withdraw{{ $order->id }}"
                                title="撤单">
                            <span class="glyphicon glyphicon-link"></span> 撤单
                        </button>
                    @endif
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'NEED' || $order->status == 'PACKED' || $order->status == 'REVIEW')
                        <button class="btn btn-danger btn-xs"
                                data-toggle="modal"
                                data-target="#withdraw{{ $order->id }}"
                                title="撤单">
                            <span class="glyphicon glyphicon-link"></span> 撤单
                        </button>
                    @endif
                    {{--@if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW')--}}
                    {{--<a href="javascript:" class="btn btn-danger btn-xs delete_item"--}}
                    {{--data-id="{{ $order->id }}"--}}
                    {{--data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">--}}
                    {{--<span class="glyphicon glyphicon-pencil"></span> 删除--}}
                    {{--</a>--}}
                    {{--@endif--}}
                            <button class="btn btn-primary btn-xs"
                                    data-toggle="modal"
                                    data-target="#refund{{ $order->id }}"
                                    title="退款">
                                <span class="glyphicon glyphicon-link"></span> 退款
                            </button>
                    @if($order->channel->name == 'Ebay')
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#send_ebay_message_{{ $order->id }}" title="Send ebay Message">
                            <span class="glyphicon glyphicon-envelope"></span>Send ebay Message
                        </button>
                    @endif
                    <button class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#remark{{ $order->id }}"
                            title="备注">
                        <span class="glyphicon glyphicon-link"></span> 备注
                    </button>
                    @if($order->status == 'REVIEW')
                        <a href="javascript:" class="btn btn-primary btn-xs review" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 审核
                        </a>
                    @endif
                    @if($order->status == 'PREPARED' && $order->active != 'STOP')
                        <a href="javascript:" class="btn btn-primary btn-xs prepared" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 暂停发货
                        </a>
                    @endif
                    @if($order->active != 'NORMAL')
                        <a href="javascript:" class="btn btn-primary btn-xs normal" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 恢复正常
                        </a>
                    @endif
                    @if($order->status == 'CANCEL')
                        <a href="javascript:" class="btn btn-primary btn-xs recover" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 恢复订单
                        </a>
                    @endif
                    <a href="{{ route('invoice', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 德国发票
                    </a>
                    <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                    <button class="btn btn-primary btn-xs dialog"
                            data-toggle="modal"
                            data-target="#dialog" data-table="{{ $order->table }}" data-id="{{$order->id}}">
                        <span class="glyphicon glyphicon-road"></span>
                    </button>
                </div>
            </td>
        </tr>
        <div class="modal fade" id="withdraw{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('withdrawUpdate', ['id' => $order->id])}}" method="POST">
                        <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
                        <input type='hidden' name='page' value="{{$page}}">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">撤单</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw" class='control-label'>撤单原因</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="withdraw" id="withdraw">
                                        <option value="NULL">==选择原因==</option>
                                        @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                            <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                                                {{ $withdraw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw_reason" class='control-label'>原因</label>
                                    <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason">{{ old('withdraw_reason') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="refund{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('refundUpdate', ['id' => $order->id])}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="channel_id" value="{{$order->channel_id}}"/>
                        <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
                        <input type='hidden' name='page' value="{{$page}}">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">退款信息</h4>
                        </div>
                        <div class="modal-body">
                            <label class='control-label'>历史退款</label>
                            @if($order->refunds->toArray())
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
                                @foreach($order->refunds as $refund)
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
                                    <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $order->ordernum }}" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="channel_account_id">渠道账号</label>
                                    <input class="form-control" id="channel_account_id" placeholder="渠道账号" name='channel_account_id' value="{{ old('channel_account_id') ? old('channel_account_id') : ($order->channelAccount ? $order->channelAccount->alias : '') }}" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="refund_amount" class='control-label'>退款金额</label>
                                    <input class="form-control" id="refund_amount{{ $order->id }}" placeholder="退款金额" name='refund_amount' value="{{ old('refund_amount') }}">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="price" class='control-label'>确认金额</label>
                                    <input class="form-control" id="price{{ $order->id }}" placeholder="确认金额" name='price' value="{{ old('price') }}">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="refund_currency" class='control-label'>退款币种</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="refund_currency" id="refund_currency">
                                        @foreach($currencys as $refund_currency)
                                            <option value="{{ $refund_currency->code }}" {{ $refund_currency->code == 'USD' ? 'selected' : '' }}>
                                                {{ $refund_currency->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="refund" class='control-label'>退款方式</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control refund-type" name="refund" id="refund" order-id="{{ $order->id }}">
                                        <option value="NULL">==退款方式==</option>
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
                                    <select class="form-control" name="reason" id="reason">
                                        <option value="NULL">==退款原因==</option>
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
                                    <select class="form-control type" name="type" id="{{ $order->id }}">
                                        <option value="NULL">==退款类型==</option>
                                        @foreach(config('order.type') as $type_key => $type)
                                            <option value="{{ $type_key }}" {{ old('type') }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="paypal-input-{{ $order->id }}" style="display: none;">
                                <div class="col-lg-6 paypal-account">
                                    <label for="refund_currency" class='control-label'>客户退款Paypal账号</label>
                                    <input type="text" class="form-control" name="user_paypal_account" value="">
                                </div>

                                <div class="col-lg-6 refund-voucher">
                                    <label for="refund_currency" class='control-label'>退款凭证：</label>
                                    <input type="text" class="form-control" name="refund_voucher" value="">
                                </div>
                            </div>
                            @if($order->items->toArray())
                                <div class='row'>
                                    <div class="form-group col-sm-2">
                                        <input type="checkbox" isCheck="true" id="checkall{{ $order->id }}" placeholder="" onclick="quanxuan('{{ $order->id }}')">全选
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
                                @foreach($order->items as $key => $orderItem)
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
                                @endforeach
                            @endif
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
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="remark{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('remarkUpdate', ['id' => $order->id])}}" method="POST">
                        <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
                        <input type='hidden' name='page' value="{{$page}}">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">补充备注</h4>
                        </div>
                        <div class="modal-body">
                            <label class='control-label'>历史备注</label>
                            @if($order->remarks->toArray())
                                @foreach($order->remarks as $remark)
                                    <div class="row">
                                        <div class="col-lg-2">{{ $remark->user?$remark->user->name:'系统创建' }}</div>
                                        <div class="col-lg-4">{{ $remark->created_at }}</div>
                                        <div class="col-lg-6">{{ $remark->remark }}</div>
                                    </div>
                                    <div class="divider"></div>
                                @endforeach
                            @else
                                <div class="divider"></div>
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="remark" class='control-label'>订单备注</label>
                                    <textarea class="form-control myRemark" rows="3" id="remark" name='remark'>{{ old('remark') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary confirm_remark">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="send_ebay_message_{{ $order->id }}" role="dialog">
            <div class="modal-dialog" role="document" style="width:800px;">
                <div class="modal-content">
                    <form action="{{ route('message.sendEbayMessage')}}" method="POST" id="send-ebay-message-{{ $order->id }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="message-order-id" value="{{$order->id}}"/>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Send ebay Message</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label">买家ID：</label>
                                <div class="col-xs-10">
                                    <input class="form-control" type="text" value="Artisanal kale" id="example-text-input" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2">
                                    <label>物 品:</label>
                                </div>
                                <div class="col-xs-10">
                                    <div class="row">
                                        <div class="form-group col-sm-1">
                                            <label for="id" class="control-label">选择</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="id" class="control-label">渠道ItemID</label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="sku" class="control-label">sku</label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="price" class="control-label">物品名称</label>
                                        </div>
                                        <div class="form-group col-sm-1">
                                            <label for="quantity" class="control-label">数量</label>
                                        </div>
                                    </div>
                                    @foreach($order->items as $item)
                                        <div class="row">
                                            <div class="form-group col-sm-1">
                                                <input type="checkbox" name="item-ids[]" value="{{$item->orders_item_number}}"/>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="id" class="control-label">{{$item->orders_item_number}}</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="sku" class="control-label">{{$item->sku}}</label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="price" class="control-label">{{$item->ItemChineseName}}</label>
                                            </div>
                                            <div class="form-group col-sm-1">
                                                <label for="quantity" class="control-label">{{$item->quantity}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <label class="panel-title">回信面板</label>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-xs-2 col-form-label">标题：</label>
                                                <div class="col-xs-10">
                                                    <input class="form-control" type="text" value="" id="example-text-input" name="message-title">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-xs-2 col-form-label">内容：</label>
                                                <div class="col-xs-10">
                                                    <textarea class="form-control" rows="3" name="message-content"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <label class="panel-title">历史记录</label>
                                        </div>
                                        <div class="panel-body">
                                            @if($order->SendEbayMessageHistory)
                                                <div class="list-group">
                                                    @foreach($order->SendEbayMessageHistory as $item)
                                                        <a href="#" class="list-group-item">
                                                            <h4 class="list-group-item-heading ">{{$item->title}}
                                                                <span class="badge" style="float:right;">{{$item->created_at}}</span>
                                                            </h4>
                                                            <p class="list-group-item-text">{{$item->content}}</p>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="list-group">
                                                    <h4 class="list-group-item-heading ">无</h4>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a type="submit" class="btn btn-primary form-submit" order-id="{{ $order->id }}">提交</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@section('doAction')
    <div class="row">
        <div class="col-lg-2">
            <strong>当前小计</strong> : {{ '$' . $subtotal }}
        </div>
        <div class="col-lg-4 text-danger">
            <strong>统计</strong> : {{ $orderStatistics }}
        </div>
    </div>
@stop
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            展示类型
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='easy' data-type='easy'>简单</a></li>
            <li><a href="javascript:" class='easy' data-type='full'>完整</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-success partReview" href="javascript:">
            批量审核
        </a>
    </div>
    <div class="btn-group">
        <button class="btn btn-info"
                data-toggle="modal"
                data-target="#withdraw"
                title="批量撤单">批量撤单
        </button>
    </div>
    <div class="modal fade" id="withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">批量撤单</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group text-left col-lg-6">
                            <label for="withdraw" class='control-label'>撤单原因</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <select class="form-control withdraw" name="withdraw" id="withdraw">
                                <option value="NULL">==选择原因==</option>
                                @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                    <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                                        {{ $withdraw }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group text-left col-lg-6">
                            <label for="withdraw_reason" class='control-label'>原因</label>
                            <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason">{{ old('withdraw_reason') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary sub">提交</button>
                </div>
            </div>
        </div>
    </div>
    @parent
@stop
@section('childJs')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#start_date, #end_date').cxCalendar();

            $(document).on('click', '.easy', function () {
                type = $(this).data('type');
                if (type == 'easy') {
                    $('.fb').hide();
                } else {
                    $('.fb').show();
                }
            });

            //加载运费
            arr = new Array();
            i = 0;
            $.each($('.orderId'), function () {
                arr[i] = $(this).data('id');
                i++;
            });
            $.get(
                    "{{ route('order.logisticsFee')}}",
                    {'arr': arr},
                    function (result) {
                        j = 0;
                        $.each($('.orderId'), function () {
                            block = $(this).parent();
                            block.find('.logisticsFee').text(result[j][1]);
                            j++;
                        })
                    }
            );

            //备注是否为空
            $(document).on('click', '.confirm_remark', function () {
                var remark = $(this).parent().prev().find('.myRemark').val();
                if (!remark) {
                    alert('请输入备注!!!');
                    return false;
                }
            });

            //审核
            $('.review').click(function () {
                if (confirm("确认审核?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateStatus') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            $('.special').change(function () {
                var special = $('.special').val();
                if (special != null) {
                    location.href = "{{ route('order.index') }}?special=" + special;
                }
            });

            $('.sx').change(function () {
                var lr = $('.lr').val();
                if (lr == '') {
                    alert('请输入利润!');
                    $('.sx').val('null');
                } else {
                    var sx = $('.sx').val();
                    if (sx != null) {
                        location.href = "{{ route('order.index') }}?sx=" + sx + "&lr=" + lr;
                    }
                }
            });

            //暂停发货
            $('.prepared').click(function () {
                if (confirm("确认暂停发货?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updatePrepared') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            //恢复正常
            $('.normal').click(function () {
                if (confirm("确认恢复正常?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateNormal') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            //恢复订单
            $('.recover').click(function () {
                if (confirm("确认恢复订单?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateRecover') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });
            $(".refund-type").change(function () {
                if ($(this).val() == '1') {
                    $('#paypal-input-' + $(this).attr('order-id')).show();
                } else {
                    $('#paypal-input-' + $(this).attr('order-id')).hide();
                }
            });

            $('.form-submit').click(function () {
                var order_id = $(this).attr('order-id');

                var message_form = $('#send-ebay-message-' + order_id).serializeArray();
                //console.log($('input[name="item-ids"]:checked').val());
                if ($('input[name="item-ids[]"]:checked').val() == undefined) {
                    alert('请先勾选商品！');
                    return;
                }
                var is_empty = false;
                $.each(message_form, function (i, field) {
                    if (field.value == '' || field.value == undefined) {
                        alert('请把数据填充完整，' + field.name);
                        is_empty = true;
                        return false;
                    }
                });
                if (!is_empty) {
                    $('#send-ebay-message-' + order_id).submit();
                }

            });

            $(document).on('click', '.split_button', function () {
                if (confirm('确认拆分')) {
                    id = $(this).parent().prev().find('.confirm_quantity').attr('name');
                    arr = new Array();
                    i = 0;
                    j = 0;
                    $.each($(this).parent().find('table'), function (k, v) {
                        $.each($(v).find('tr'), function (k1, v1) {
                            if ($(v1).find(':radio').prop('checked')) {
                                arr[i] = j + '.' + $(v1).find('.item_id').data('itemid');
                                i += 1;
                            }
                        })
                        j += 1;
                    })
                    location.href = "{{ route('package.actSplitPackage', ['arr' => '']) }}/" + arr + "/" + id;
                }
            });

            $(document).on('click', '.confirm_quantity', function () {
                quantity = $(this).parent().prev().find(':input').val();
                id = $(this).attr('name');
                if (quantity > 1) {
                    $.get(
                        "{{ route('package.returnSplitPackage')}}",
                        {quantity: quantity, id: id},
                        function (result) {
                            $('.split_package').html('');
                            $('.split_package').html(result);
                        }, 'html'
                    );
                } else {
                    alert('数量不能小于1');
                }

            });

            $(document).on('click', '.split', function () {
                id = $(this).data('id');
                $('.confirm_quantity').attr('name', id);
                $('.package_num').val('');
                $('.split_package').html('');
            })
        });

        $('.statistics').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date && end_date) {
                $.ajax({
                    url: "{{ route('orderStatistics') }}",
                    data: {start_date: start_date, end_date: end_date},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        $("#statistics").text(
                            '总计金额:$' + result['totalAmount'] + ' ' +
                            '平均利润率:' + result['averageProfit'] + '%' + ' ' +
                            '总平台费:$' + result['totalPlatform']
                        );
                    }
                });
            } else {
                $("#statistics").text('请选择正确的日期!!!');
            }
        });

        //全选订单产品
        function quanxuan(id) {
            var collid = document.getElementById("checkall" + id);
            var coll = document.getElementsByName("tribute_id[]");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
                $('.price').style.readonly = 'false';
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }

        $('.type').click(function () {
            var type = $(this).val();
            var id = $(this).attr('id');
            if (type == 'FULL') {
                document.getElementById('price' + id).readOnly = true;
                document.getElementById('refund_amount' + id).readOnly = true;
            } else {
                document.getElementById('price' + id).readOnly = false;
                document.getElementById('refund_amount' + id).readOnly = false;
            }
        });

        //批量审核
        $('.partReview').click(function () {
            if (confirm("确认审核")) {
                var checkbox = document.getElementsByName("tribute_id");
                var ids = "";

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    ids += checkbox[i].value + ",";
                }
                ids = ids.substr(0, (ids.length) - 1);
                $.ajax({
                    url: "{{ route('partReview') }}",
                    data: {ids: ids},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }
        });

        //SMT批量撤单
        $('.sub').click(function () {
            if (confirm('确认提交?')) {
                var checkbox = document.getElementsByName("tribute_id");
                var order_ids = "";
                var withdraw = $('.withdraw').val();
                var withdraw_reason = $('#withdraw_reason').val();
                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)
                        continue;
                    order_ids += checkbox[i].value + ",";
                }
                order_ids = order_ids.substr(0, (order_ids.length) - 1);
                $.ajax({
                    url: "{{ route('withdrawAll') }}",
                    data: {order_ids: order_ids, withdraw: withdraw, withdraw_reason: withdraw_reason},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                });
            }
        });

        //全选订单
        function quanxuanOrder() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop