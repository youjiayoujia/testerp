@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('allotment.checkResult', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_id }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outwarehouse ? $model->outwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inwarehouse ? $model->inwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotmentByName ? $model->allotmentByName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨状态</strong>: {{ $model->status_name }}
        </div>
        <div class="col-lg-2">
            <strong>对单人</strong>: {{ $model->checkformByName ? $model->checkformByName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>对单时间</strong>: {{ $model->checkform_time }}
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
        <div class="col-lg-2">
            <strong>金额</strong>: {{ $allotment->amount }}
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>审核人</strong>: {{ $model->checkByName ? $model->checkByName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>审核状态</strong>: {{ $model->check_status == 'N' ? '未审核' : '已审核' }}
        </div>
        <div class="col-lg-2">
            <strong>审核时间</strong>: {{ $model->check_time }}
        </div>
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
<div class='form-group'>
    <label for='remark'>备注</label>
    <textarea name='remark' class='form-control'>{{ old('remark') ? old('remark') : $model->remark }}</textarea>
</div>
@stop
@section('formButton')
    <button type="submit" name='result' value='1' class="btn btn-success">审核通过</button>
    <button type="submit" name='result' value='0' class="btn btn-default">审核未通过</button>
@stop