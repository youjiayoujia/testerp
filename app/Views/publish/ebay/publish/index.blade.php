<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-05
 * Time: 14:35
 */
        ?>
@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"></th>
    <th class="sort text-center" data-field="id">ID</th>
    <th class="text-center">站点</th>
    <th class="text-center">帐号</th>
    <th class="text-center">类型</th>
    <th class="text-center">标题</th>
    <th class="text-center">EbaySku</th>
    <th class="sort text-center" data-field="start_price">价格</th>
    <th class=" text-center">币种</th>
    <th class="text-center">PayPal</th>
    <th class="text-center">状态</th>
    <th class="text-center">刊登人</th>
    <th class="text-center">信息提示</th>
    <th class="text-center">创建时间</th>
    <th class="text-center">操作</th>
    {{--
        <th class="sort" data-field="created_at">创建时间</th>
    --}}
    {{--<th>日志</th>--}}
@stop

@section('tableBody')
    @foreach($data as $v)
        <tr class="text-center">
            <td><input type='checkbox' name='tribute_id'  value="{{ $v->id }}"></td>
            <td>{{$v->id }}</td>
            <td>{{$v->site_name }}</td>
            <td>{{$v->channelAccount->alias }}</td>
            <td>{{$v->listing_type }}</td>
            <td>{{$v->title }}</td>
            <td>{{$v->sku }}</td>
            <td>{{$v->start_price }}</td>
            <td>{{$v->currency }}</td>
            <td>{{$v->paypal_email_address }}</td>
            <td>{{config('ebaysite.status')[$v->status] }}</td>
            <td>@if(isset($v->operator->name ))
                {{$v->operator->name }}
            @endif</td>
            @if($v->status==0)
                <td>{{$v->note }}</td>
            @else
                <td>{{$v->start_time }}</td>
            @endif

            <td>{{$v->created_at }}</td>
            <td>
                @if($v->status==0)
                <a href="{{ route('ebayPublish.edit', ['id'=>$v->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑草稿信息
                </a>
                @elseif($v->status==1)
                    <a class="btn btn-danger btn-xs returnDraft" data-id="{{$v->id}}">
                        <span class="glyphicon glyphicon-pencil"></span> 退至草稿状态
                    </a>
                @endif

            </td>
        </tr>
    @endforeach
@stop

@section('childJs')
    <script type="text/javascript">
        $(".returnDraft").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('ebayPublish.returnDraft') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result) {
                        location.reload();
                    }
                }
            });
        });
    </script>

@stop