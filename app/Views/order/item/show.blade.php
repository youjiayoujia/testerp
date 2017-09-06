@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>订单ID</strong>: {{ $model->order_id }}
            </div>
            <div class="col-lg-4">
                <strong>sku</strong>: {{ $model->sku }}
            </div>
            <div class="col-lg-4">
                <strong>数量</strong>: {{ $model->quantity }}
            </div>
            <div class="col-lg-4">
                <strong>金额</strong>: {{ $model->price }}
            </div>
            <div class="col-lg-4">
                <strong>订单状态</strong>: {{ $model->status }}
            </div>
            <div class="col-lg-4">
                <strong>发货状态</strong>: {{ $model->ship_status }}
            </div>
            <div class="col-lg-4">
                <strong>是否赠品</strong>: {{ $model->is_gift }}
            </div>
            <div class="col-lg-4">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
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