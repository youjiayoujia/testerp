@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3">
                    <strong>ID</strong>: {{ $model->id }}
                </div>
                <div class="col-lg-3">
                    <strong>订单号</strong>: {{ $model->order_id }}
                </div>
                <div class="col-lg-3">
                    <strong>买家ID</strong>: {{ $model->Order->by_id }}
                </div>
                <div class="col-lg-3">
                    <strong>状态</strong>: {{ $model->ProcessStatusName }}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <strong>退款金额</strong>: {{$model->refund_amount}}
                </div>
                <div class="col-lg-3">
                    <strong>币种</strong>: {{ $model->refund_currency }}
                </div>
                <div class="col-lg-3">
                    <strong>退款类型</strong>: {{ $model->TypeName }}
                </div>
                <div class="col-lg-3">
                    <strong>退款原因</strong>: {{$model->ReasonName}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <strong>退款方式</strong>: {{$model->RefundName}}
                </div>
                <div class="col-lg-3">
                    <strong>客服</strong>: {{ $model->CustomerName }}
                </div>
                <div class="col-lg-3">
                    <strong>渠道</strong>: {{$model->ChannelName}}
                </div>
                <div class="col-lg-3">
                    <strong>销售账号</strong>: {{ $model->ChannelAccountName }}
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3">
                    <strong>截图</strong>:
                    @if($model->image)
                        <a href="../../{{$model->image}}" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                    @else
                        无
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <strong>创建人</strong>: {{$model->CustomerName}}

                </div>
                <div class="col-lg-4">
                    <strong>创建时间</strong>: {{ $model->created_at }}
                </div>
                <div class="col-lg-4">
                    <strong>更新时间</strong>: {{ $model->updated_at }}
                </div>
            </div>
        </div>
    </div>
@stop