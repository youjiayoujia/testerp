@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>渠道账号</strong>: {{ $model->account }}
            </div>
            <div class="col-lg-3">
                <strong>渠道账号别名</strong>: {{ $model->alias }}
            </div>
            <div class="col-lg-3">
                <strong>账号对应域名</strong>: {{ $model->domain }}
            </div>
            <div class="col-lg-3">
                <strong>渠道类型</strong>: {{ $model->channel ? $model->channel->name : '' }}
            </div>
            <div class="col-lg-3">
                <strong>订单同步周期</strong>: {{ $model->sync_cycle }}
            </div>
            <div class="col-lg-3">
                <strong>上传追踪号配置</strong>: {{ $model->tracking_config }}
            </div>
            <div class="col-lg-3">
                <strong>订单前缀</strong>: {{ $model->order_prefix }}
            </div>
            <div class="col-lg-3">
                <strong>客服邮箱地址</strong>: {{ $model->service_email }}
            </div>
            <div class="col-lg-3">
                <strong>产品图片域名</strong>: {{ $model->image_domain }}
            </div>
            <div class="col-lg-3">
                <strong>所在国家</strong>: {{ $model->country ? $model->country->cn_name : '' }}
            </div>
            <div class="col-lg-3">
                <strong>默认运营人员</strong>: {{ $model->operator ? $model->operator->name : '' }}
            </div>
            <div class="col-lg-3">
                <strong>默认客服人员</strong>: {{ $model->customer_service ? $model->customer_service->name : '' }}
            </div>
            <div class="col-lg-3">
                <strong>默认发货仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : '' }}
            </div>
            <div class="col-lg-3">
                <strong>是否激活</strong>: {{ $model->available }}
            </div>
            <div class="col-lg-3">
                <strong>是否相同地址合并包裹</strong>: {{ $model->merge_package }}
            </div>
            <div class="col-lg-3">
                <strong>是否打印感谢信</strong>: {{ $model->thanks }}
            </div>
            <div class="col-lg-3">
                <strong>是否打印拣货单</strong>: {{ $model->picking_list }}
            </div>
            <div class="col-lg-3">
                <strong>是否无规则生成渠道SKU</strong>: {{ $model->rand_sku }}
            </div>
            <div class="col-lg-3">
                <strong>可否通关</strong>: {{ $model->clearance }}
            </div>
            <div class="col-lg-3">
                <strong>已选运营人员</strong>:
                {{ $model->operator ? $model->operator->name : '无' }}
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