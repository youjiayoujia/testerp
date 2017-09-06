@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>物流商名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>客户ID</strong>: {{ $model->customer_id }}
            </div>
            <div class="col-lg-3">
                <strong>密码</strong>: {{ $model->password }}
            </div>
            <div class="col-lg-3">
                <strong>URL</strong>: {{ $model->url }}
            </div>
            <div class="col-lg-3">
                <strong>密钥</strong>: {{ $model->secret_key }}
            </div>
            <div class="col-lg-3">
                <strong>客户经理</strong>: {{ $model->client_manager }}
            </div>
            <div class="col-lg-3">
                <strong>客户经理联系方式</strong>: {{ $model->manager_tel }}
            </div>
            <div class="col-lg-3">
                <strong>技术人员</strong>: {{ $model->technician }}
            </div>
            <div class="col-lg-3">
                <strong>技术联系方式</strong>: {{ $model->technician_tel }}
            </div>
            <div class="col-lg-3">
                <strong>客服名称</strong>: {{ $model->customer_service_name }}
            </div>
            <div class="col-lg-3">
                <strong>客服QQ</strong>: {{ $model->customer_service_qq }}
            </div>
            <div class="col-lg-3">
                <strong>客服电话</strong>: {{ $model->customer_service_tel }}
            </div>
            <div class="col-lg-3">
                <strong>财务名称</strong>: {{ $model->finance_name }}
            </div>
            <div class="col-lg-3">
                <strong>财务QQ</strong>: {{ $model->finance_qq }}
            </div>
            <div class="col-lg-3">
                <strong>财务电话</strong>: {{ $model->tel }}
            </div>
            <div class="col-lg-3">
                <strong>取件司机</strong>: {{ $model->driver }}
            </div>
            <div class="col-lg-3">
                <strong>司机电话</strong>: {{ $model->driver_tel }}
            </div>
            <div class="col-lg-3">
                <strong>收款信息</strong>: {{ $model->collectionInfo ? $model->collectionInfo->bank : '没有收款信息' }}
            </div>
            <div class="col-lg-3">
                <strong>是否有API</strong>: {{ $model->is_api == '1' ? '有' : '没有' }}
            </div>
            <div class="col-lg-12">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
            <div class="col-lg-12">
                <strong>企业证件</strong>:
                @if($model->credentials)
                    <img src="{{ asset($model->credentials) }}" width="200px">
                @else
                    未上传
                @endif
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