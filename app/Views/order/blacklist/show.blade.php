@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>平台</strong>: {{ $model->channel->name }}
            </div>
            <div class="col-lg-3">
                <strong>订单号</strong>: {{ $model->ordernum }}
            </div>
            <div class="col-lg-3">
                <strong>姓名</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>邮箱</strong>: {{ $model->email }}
            </div>
            <div class="col-lg-3">
                <strong>买家ID</strong>: {{ $model->by_id }}
            </div>
            <div class="col-lg-3">
                <strong>邮编</strong>: {{ $model->zipcode }}
            </div>
            <div class="col-lg-3">
                <strong>销售账号</strong>: {{ $model->channel_account }}
            </div>
            <div class="col-lg-3">
                <strong>退款订单数</strong>: {{ $model->refund_order }}
            </div>
            <div class="col-lg-3">
                <strong>订单总数</strong>: {{ $model->total_order }}
            </div>
            <div class="col-lg-3">
                <strong>退款率</strong>: {{ $model->refund_rate }}
            </div>
            <div class="col-lg-3">
                <strong>类型</strong>: {{ $model->type_name }}
            </div>
            <div class="col-lg-12">
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