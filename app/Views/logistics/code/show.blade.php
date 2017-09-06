@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $model->logistics->name }}
            </div>
            <div class="col-lg-4">
                <strong>跟踪号</strong>: {{ $model->code }}
            </div>
            <div class="col-lg-4">
                <strong>包裹ID</strong>: {{ $model->package_id }}
            </div>
            <div class="col-lg-4">
                <strong>状态</strong>: {{ $model->status == '1' ? '启用' : '未启用' }}
            </div>
            <div class="col-lg-4">
                <strong>使用时间</strong>: {{ $model->used_at }}
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