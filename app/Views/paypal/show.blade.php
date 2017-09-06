<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 15:19
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
                <strong>paypalEmail地址</strong>: {{ $model->paypal_email_address }}
            </div>
            <div class="col-lg-12">
                <strong>paypalAPI账号</strong>: {{ $model->paypal_account }}
            </div>
            <div class="col-lg-12">
                <strong>paypalAPI密码</strong>: {{ $model->paypal_password }}
            </div>
            <div class="col-lg-12">
                <strong>paypalAPI口令</strong>: {{ $model->paypal_token }}
            </div>
            <div class="col-lg-12">
                <strong>paypal是否启用</strong>: {{ $model->PaypalEnable }}
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