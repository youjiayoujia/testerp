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
                <strong>详细地址</strong>: {{ $model->address }}
            </div>
            <div class="col-lg-2">
                <strong>联系人</strong>: {{ $model->contactByName ? $model->contactByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>联系电话</strong>: {{ $model->telephone }}
            </div>
            <div class="col-lg-2">
                <strong>类型</strong>: {{ $model->type == 'local' ? '本地仓库' : ($model->type == 'oversea' ? '海外仓库' : ($model->type == 'third' ? '第三方仓库' : 'fba本地仓')) }}
            </div>
            <div class="col-lg-2">
                <strong>容积(m3)</strong>: {{ $model->volumn }}
            </div>
            <div class="col-lg-2">
                <strong>仓库编码</strong>: {{ $model->code }}
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