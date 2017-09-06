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
            <div class="col-lg-2">
                <strong>仓库名</strong>: {{ $model->warehouse ? $model->warehouse->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
            <div class="col-lg-2">
                <strong>库位大小</strong>: {{ $model->size == 'small' ? '小' : ($model->size == 'middle' ? '中' : '大') }}
            </div>
            <div class="col-lg-2">
                <strong>长(cm)</strong>: {{ $model->length }}
            </div>
            <div class="col-lg-2">
                <strong>宽(cm)</strong>: {{ $model->width }}
            </div>
            <div class="col-lg-2">
                <strong>高(cm)</strong>: {{ $model->height }}
            </div>
            <div class="col-lg-2">
                <strong>是否启用</strong>: {{ $model->is_available == '1' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop