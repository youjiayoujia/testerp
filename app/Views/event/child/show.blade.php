@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>内容</strong>: {{ $model->what }}
        </div>
        <div class="col-lg-2">
            <strong>操作人</strong>: {{ $model->whoName ? $model->whoName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>操作时间</strong>: {{ $model->when }}
        </div>
        <div class="col-lg-2">
            <strong>父类</strong>: {{ $model->parentName ? $model->parentName->model_name : '' }}
        </div>
    </div>
</div>
@stop