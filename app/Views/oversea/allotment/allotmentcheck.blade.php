@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('overseaAllotment.checkResult', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_num }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outwarehouse ? $model->outwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inwarehouse ? $model->inwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotmentBy ? $model->allotmentBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨状态</strong>: {{ $model->status_name }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">调拨单sku列表</div>
    <div class="panel-body">
    @foreach($allotments as $allotment)
    <div class='row'>
        <div class="col-lg-2">
            <strong>sku</strong>: {{ $allotment->item ? $allotment->item->sku : '' }}
        </div>
        <div class="col-lg-2">
            <strong>库位</strong>: {{ $allotment->position ? $allotment->position->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>数量</strong>: {{ $allotment->quantity }}
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
@stop
@section('formButton')
    <button type="submit" name='result' value='1' class="btn btn-success">审核通过</button>
    <button type="submit" name='result' value='0' class="btn btn-default">审核未通过</button>
@stop