@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>编号</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>面单名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>视图</strong>: {{ $model->view }}
            </div>
            <div class="col-lg-3">
                <strong>尺寸</strong>: {{ $model->size }}
            </div>
            <div class="col-lg-3">
                <strong>是否确认</strong>: {{ $model->is_confirm == '1' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop