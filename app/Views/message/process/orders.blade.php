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
                    订单:
                    <a href="{{ route('order.show',$relatedOrder->order->id) }}" target="_blank">
                        <strong>{{ $relatedOrder->order->ordernum }}</strong>
                    </a>
                    <small>{{ '<'.$relatedOrder->order->email.'>' }}</small>
                    -
                    <strong>{{ $relatedOrder->order->status_text }}</strong>
                    -
                    <strong>{{ $relatedOrder->order->active_text }}</strong>

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
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                @foreach($relatedOrder->order->items as $item)
                                    <tr>
                                        <td>

                                            <a href="{{ route('item.show', $item->sku ) }}" target="_blank">{{$item->sku}}</a>
                                        </td>
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
                    </div>
                </div>
            </div>
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
                                    <a href="{{ $package->shipping->url }}" target="_blank">
                                        {{ $package->shipping->type }}
                                    </a>
                                </div>
                                <div class="col-lg-6">
                                    <strong>物流网址</strong>
                                    {{  $package->shipping->url }}
                                </div>
                                <div class="col-lg-6">
                                    <strong>追踪号</strong>: {{ $package->tracking_no }}
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