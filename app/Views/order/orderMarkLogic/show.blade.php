<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-07-15
 * Time: 16:31
 */
?>

@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-12">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-12">
                <strong>渠道</strong>: {{ $model->channel->name }}
            </div>
            <div class="col-lg-12">
                <strong>订单状态</strong>:

                <?php   $orderStatus = json_decode($model->order_status);?>
                @foreach($orderStatus as $st)
                    {{-- {{ $status[$mark->order_status] }}--}}
                    {{$order_status[$st]}}
                @endforeach
            </div>
            <div class="col-lg-12">
                <strong>订单创建后N小时</strong>: {{ $model->order_create }}
            </div>
            <div class="col-lg-12">
                <strong>订单支付后N小时</strong>: {{ $model->order_pay }}
            </div>
            <div class="col-lg-12">
                <strong>承运商</strong>: {{ $model->AssignShipping }}
            </div>

            @if($model->assign_shipping_logistics==2)
                <div class="col-lg-12">
                    <strong>指定承运商名称</strong>: {{ $model->shipping_logistics_name }}
                </div>
            @endif

            <div class="col-lg-12">
                <strong>追踪号上传方式</strong>: {{ $model->IsUploaded }}
            </div>

            @if($model->wish_upload_tracking_num==1)
                <div class="col-lg-12">
                    <strong>wish 上传追踪号（针对已经标记发货,但未上传追踪号）</strong>: {{ $model->WishUploadTracking }}
                </div>
            @endif

            <div class="col-lg-12">
                <strong>速卖通最后标记发货天数（针对未发货订单）</strong>: {{ $model->expired_time }}
            </div>

            <div class="col-lg-12">
                <strong>优先级</strong>: {{ $model->priority }}
            </div>


            <div class="col-lg-12">
                <strong>设置人员</strong>: {{ $model->userOperator->name }}
            </div>


            <div class="col-lg-12">
                <strong>是否启用</strong>: {{ $model->IsUsed }}
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