@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>shipmentId</strong>: {{ $model->report ? $model->report->shipment_id : '' }}
            </div>
            <div class="col-lg-4">
                <strong>箱号</strong>: {{ $model->boxNum }}
            </div>
            <div class="col-lg-4">
                <strong>长(m)</strong>: {{ $model->length }}
            </div>
            <div class="col-lg-4">
                <strong>宽(m)</strong>: {{ $model->width }}
            </div>
            <div class="col-lg-4">
                <strong>高(m)</strong>: {{ $model->height }}
            </div>
            <div class="col-lg-4">
                <strong>重量(kg)</strong>: {{ $model->weight }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $model->logistics ? $model->logistics->code : '' }}
            </div>
            <div class="col-lg-4">
                <strong>追踪号</strong>: {{ $model->tracking_no }}
            </div>
            <div class="col-lg-4">
                <strong>状态</strong>: {{ $model->status ? '已发货' : '未发货' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">箱子sku信息</div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                    <th>sku</th>
                    <th>fnsku</th>
                    <th>数量</th>
                </tr>
                </thead>
                <tbody>
                @foreach($forms as $form)
                <tr>
                    <td>{{ $form->sku }}</td>
                    <td>{{ $form->fnsku }}</td>
                    <td>{{ $form->quantity }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop