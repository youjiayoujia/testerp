@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>买家ID/Email/订单号</strong>: {{ $model->ordernum }}
            </div>
            <div class="col-lg-4">
                <strong>要求</strong>: {{ $model->remark . ' ' . $model->note }}
            </div>
            <div class="col-lg-2">
                <strong>日期</strong>: {{ $model->date }}
            </div>
            <div class="col-lg-2">
                <strong>销售账号</strong>: {{ $model->channel->name }}
            </div>
            <div class="col-lg-4">
                <strong>客服</strong>: {{ $model->user->name }}
            </div>
            <div class="col-lg-4">
                <strong>状态</strong>: {{ $model->status_name }}
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