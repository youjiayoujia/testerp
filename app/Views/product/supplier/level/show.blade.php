@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>描述</strong>: {{ $model->description }}
            </div>            
        </div>
    </div>
@stop