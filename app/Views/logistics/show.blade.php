@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>优先级</strong>: {{ $model->priority != 0 ? $model->priority : '未设置' }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式简码</strong>: {{ $model->code }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-4">
                <strong>仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : '无' }}
            </div>
            <div class="col-lg-4">
                <strong>物流商</strong>: {{ $model->supplier ? $model->supplier->name : '无' }}
            </div>
            <div class="col-lg-4">
                <strong>物流商物流方式</strong>: {{ $model->type }}
            </div>
            <div class="col-lg-4">
                <strong>对接方式</strong>: {{ $model->docking_name }}
            </div>
            <div class="col-lg-4">
                <strong>物流分类</strong>: {{ $model->logistics_catalog_id == '0' ? '未选择' : $model->catalog->name }}
            </div>
            <div class="col-lg-4">
                <strong>回邮模版</strong>: {{ $model->logistics_email_template_id == '0' ? '未选择' : $model->emailTemplate->name }}
            </div>
            <div class="col-lg-4">
                <strong>面单模版</strong>: {{ $model->logistics_template_id == '0' ? '未选择' : $model->template->name }}
            </div>
            <div class="col-lg-4">
                <strong>驱动名</strong>: {{ $model->driver }}
            </div>
            <div class="col-lg-4">
                <strong>物流编码</strong>: {{ $model->logistics_code }}
            </div>
            <div class="col-lg-4">
                <strong>平邮or快递</strong>: {{ $model->is_express == '1' ? '快递' : '平邮' }}
            </div>
            <div class="col-lg-4">
                <strong>是否启用</strong>: {{ $model->is_enable == '1' ? '是' : '否' }}
            </div>
            @foreach($channelNames as $channelName)
            <div class="col-lg-4">
                <strong>{{$channelName->channel ? $channelName->channel->name : ''}}渠道回传编码</strong>: {{ $channelName->name }}
            </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">渠道是否回传信息</div>
        <div class="panel-body">
            @if($logisticsChannels->count() > 0)
                @foreach($logisticsChannels as $logisticsChannel)
                    <div class="col-lg-2">
                        <strong>{{ $logisticsChannel->channel ? $logisticsChannel->channel->name : '' }}平台</strong>:
                        {{ $logisticsChannel->is_up == '1' ? '上传' : '不上传' }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">缺货是否标记发货</div>
        <div class="panel-body">
            @if($logisticsChannels->count() > 0)
                @foreach($logisticsChannels as $logisticsChannel)
                    <div class="col-lg-2">
                        <strong>{{ $logisticsChannel->channel ? $logisticsChannel->channel->name : '' }}平台</strong>:
                        {{ $logisticsChannel->delivery == '1' ? '是' : '否' }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流追踪网址信息</div>
        <div class="panel-body">
            @if($logisticsChannels->count() > 0)
                @foreach($logisticsChannels as $logisticsChannel)
                    <div class="col-lg-2">
                        <strong>{{ $logisticsChannel->channel ? $logisticsChannel->channel->name : '' }}平台</strong>:
                        {{ $logisticsChannel->url }}
                    </div>
                @endforeach
            @endif
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