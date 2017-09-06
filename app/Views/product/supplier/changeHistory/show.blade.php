@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>供货商</strong>: {{ $model->supplierName? $model->supplierName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>原采购员</strong>: {{ $model->fromName? $model->fromName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>变更采购员</strong>: {{ $model->toName? $model->toName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>调整人</strong>: {{ $model->adjustByName? $model->adjustByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>调整时间</strong>: {{ $model->created_at }}
            </div>            
        </div>
    </div>
@stop