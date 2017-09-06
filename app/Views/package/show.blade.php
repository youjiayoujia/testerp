@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <strong>ID</strong>: {{ $model->id }}
                </div>
                <div class="col-lg-2">
                    <strong>渠道</strong>: {{ $model->channel ? $model->channel->name : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>订单号</strong>: {{ $model->order ? $model->order->id : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>状态</strong>: {{ $model->status_name }}
                </div>
                <div class="col-lg-2">
                    <strong>类型</strong>: {{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}
                </div>
                <div class="col-lg-2">
                    <strong>仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : '' }}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <tr>
                    <th>图片</th>
                    <th>SKU</th>
                    <th>库位</th>
                    <th>数量</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach($model->items as $item)
                    <tr>
                        <td><img src="{{ $item->item->product->dimage }}" width="100"></td>
                        <td>{{ $item->item ? $item->item->sku : '' }}</td>
                        <td>{{ $item->warehousePosition ? $item->warehousePosition->name : '' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->remark }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">拣货信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <strong>拣货单</strong>: {!! $model->picklist ? $model->picklist->picknum : '<small>未拣货</small>' !!}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">发货信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <strong>物流方式</strong>: {!! $model->logistics ? $model->logistics->code : '<small>未分配物流</small>' !!}
                </div>
                <div class="col-lg-2">
                    <strong>物流单号</strong>: {{ $model->tracking_no }}
                </div>
                <div class="col-lg-2">
                    <strong>查询地址</strong>: {{ $model->tracking_link }}
                </div>
                <div class="col-lg-2">
                    <strong>物流成本</strong>: {{ $model->cost + $model->cost1 }} 元
                </div>
                <div class="col-lg-2">
                    <strong>重量</strong>: {{ $model->weight }} Kg
                </div>
                <div class="col-lg-2">
                    <strong>体积</strong>: {{ $model->length }} cm * {{ $model->width }} cm * {{ $model->height }} cm
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">地址信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3"><strong>姓名</strong>:
                    {{ $model->shipping_firstname }} {{ $model->shipping_lastname }}
                </div>
                <div class="col-lg-3"><strong>地址</strong>: {{ $model->shipping_address }}</div>
                <div class="col-lg-3"><strong>地址1</strong>: {{ $model->shipping_address1 }}</div>
                <div class="col-lg-3"><strong>市</strong>: {{ $model->shipping_city }}</div>
            </div>
            <div class="row">
                <div class="col-lg-3"><strong>省/洲</strong>: {{ $model->shipping_state }}</div>
                <div class="col-lg-3"><strong>国家</strong>: {{ $model->shipping_country }}</div>
                <div class="col-lg-3"><strong>邮编</strong>: {{ $model->shipping_zipcode }}</div>
                <div class="col-lg-3"><strong>电话</strong>: {{ $model->shipping_phone }}</div>
            </div>
        </div>
    </div>
    <div class="panel panel-warning">
        <div class="panel-heading">备注</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    {{ $model->remark }}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <strong>分配物流时间</strong>: {{ $model->logistic_assigned_at }}
                </div>
                <div class="col-lg-2">
                    <strong>打印时间</strong>: {{ $model->printed_at }}
                </div>
                <div class="col-lg-2">
                    <strong>发货时间</strong>: {{ $model->shipped_at }}
                </div>
                <div class="col-lg-2">
                    <strong>妥投时间</strong>: {{ $model->delivered_at }}
                </div>
                <div class="col-lg-2">
                    <strong>创建时间</strong>: {{ $model->created_at }}
                </div>
                <div class="col-lg-2">
                    <strong>更新时间</strong>: {{ $model->updated_at }}
                </div>
            </div>
        </div>
    </div>
@stop