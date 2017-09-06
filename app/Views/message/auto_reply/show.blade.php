@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{$model->id}}
            </div>
            <div class="col-lg-3">
                <strong>中文名称</strong>:  {{$model->cn_name}}
            </div>
            <div class="col-lg-3">
                <strong>英文名称</strong>: {{$model->en_name}}
            </div>
            <div class="col-lg-3">
                <strong>创建时间</strong>: {{$model->created_at}}
            </div>
        </div>
    </div>
@stop