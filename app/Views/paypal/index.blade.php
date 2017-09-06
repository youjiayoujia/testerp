<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 13:49
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>paypalEmail地址</th>
    <th>paypalAPI账号</th>
    <th>paypalAPI密码</th>
    <th>paypalAPI口令</th>
    <th>是否启用</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $paypal)
        <tr>
            <td>{{ $paypal->id }}</td>
            <td>{{ $paypal->paypal_email_address }}</td>
            <td>{{ $paypal->paypal_account }}</td>
            <td>{{ $paypal->paypal_password }}</td>
            <td>{{ $paypal->paypal_token }}</td>
            <td>{{ $paypal->PaypalEnable }}</td>

            <td>
                <a href="{{ route('paypal.show', ['id'=>$paypal->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('paypal.edit', ['id'=>$paypal->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $paypal->id }}"
                   data-url="{{ route('paypal.destroy', ['id' => $paypal->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop