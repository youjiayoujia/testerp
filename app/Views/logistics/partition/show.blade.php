@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class='row'>
                <div class="col-lg-2">
                    <strong>ID</strong>: {{ $model->id }}
                </div>
                <div class="col-lg-2">
                    <strong>物流分区名</strong>: {{ $model->name }}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">所含国家信息</div>
        <div class="panel-body">
            @foreach($partitionSorts as $partitionSort)
                <div class='col-lg-2'>
                    <input type='text' class='form-control' value="{{ $partitionSort->country->cn_name }}">
                </div>
            @endforeach
        </div>
    </div>
@stop