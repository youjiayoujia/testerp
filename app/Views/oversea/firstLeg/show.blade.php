@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-4">
                <strong>运输方式</strong>: {{ $model->transport == '0' ? '海运' : '空运' }}
            </div>
            <div class="col-lg-4">
                <strong>时效(天)</strong>: {{ $model->days }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流单价信息</div>
        <div class="panel-body">
        @foreach($forms as $form)
            <div class="col-lg-4">
                <strong>起始重量(kg)</strong>: {{ $form->weight_from }}
            </div>
            <div class="col-lg-4">
                <strong>结束重量(kg)</strong>: {{ $form->weight_to }}
            </div>
            <div class="col-lg-4">
                <strong>单价(￥)</strong>: {{ $model->cost }}
            </div>
        @endforeach
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