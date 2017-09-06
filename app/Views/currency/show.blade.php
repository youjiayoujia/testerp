@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>货币简称</strong>: {{ $model->code }}
        </div>
        <div class="col-lg-2">
            <strong>货币名称</strong>: {{ $model->name }}
        </div>
        <div class="col-lg-2">
            <strong>货币标识</strong>: {{ $model->identify }}
        </div>
        <div class="col-lg-2">
            <strong>汇率</strong>: {{ $model->rate }}
        </div>
    </div>
</div>
@stop