@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>order ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>shipping</strong>: {{ $model->logistics ? $model->logistics->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>tracking_no</strong>: {{ $model->tracking_no }}
        </div>
        <div class="col-lg-2">
            <strong>pint_time</strong>: {{ $model->printed_at }}
        </div>
        <div class="col-lg-2">
            <strong>发往南京</strong>: {{ $model->is_tonanjing ? '是' : '否' }}
        </div>
        <div class="col-lg-2">
            <strong>海关审结</strong>: {{ $model->is_over ? '是' : '否' }}
        </div>
    </div>
</div>
@stop