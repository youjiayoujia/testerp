<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:51
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>渠道</th>
    <th>规则名称</th>
    <th>订单状态</th>
    <th>订单创建后N小时</th>
    <th>订单支付后N小时</th>
    <th>承运商</th>
    <th>追踪号上传方式</th>
    <th>设置人员</th>
    <th title="数字越大越先执行">优先级</th>
    <th>是否启用</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $mark)
        <tr>
            <td>{{ $mark->id }}</td>
            <td>{{ $mark->channel->name }}</td>
            <td>{{ $mark->name }}</td>
            <td>
                <?php   $orderStatus = json_decode($mark->order_status);?>
                @foreach($orderStatus as $st)
               {{-- {{ $status[$mark->order_status] }}--}}
                    {{$order_status[$st]}}
                    @endforeach
            </td>
            <td>{{ $mark->order_create }}</td>
            <td>{{ $mark->order_pay }}</td>
            <td>{{ $mark->AssignShipping }}
                    @if($mark->assign_shipping_logistics==2)
                        <br/>
                {{($mark->shipping_logistics_name)}}
                    @endif
            </td>
            <td>{{ $mark->IsUploaded }}</td>
            <td>{{ $mark->userOperator->name }}</td>
            <td>{{ $mark->priority }}</td>
            <td>{{ $mark->IsUsed }}</td>

            <td>
                <a href="{{ route('orderMarkLogic.show', ['id'=>$mark->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('orderMarkLogic.edit', ['id'=>$mark->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $mark->id }}"
                   data-url="{{ route('orderMarkLogic.destroy', ['id' => $mark->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop