@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>国家名</strong>: {{ $model->name }}
        </div>
        <div class="col-lg-2">
            <strong>中文名</strong>: {{ $model->cn_name }}
        </div>
        <div class="col-lg-2">
            <strong>地区</strong>: {{ $model->countriesSort ? $model->countriesSort->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>简码</strong>: {{ $model->code }}
        </div>
        <div class="col-lg-2">
            <strong>number</strong>: {{ $model->number }}
        </div>
    </div>
</div>
@stop