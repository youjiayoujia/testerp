@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{$model->id}}
            </div>
            <div class="col-lg-3">
                <strong>变量代码</strong>:  {{$model->code}}
            </div>
            <div class="col-lg-3">
                <strong>变量名称</strong>: {{$model->name}}
            </div>
            <div class="col-lg-12">
                <strong>变量描述</strong>: {{$model->description}}
            </div>
            <div class="col-lg-12">
                <strong>变量值</strong>: {{$model->value}}
            </div>
        </div>
    </div>
@stop