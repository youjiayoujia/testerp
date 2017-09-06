<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-29
 * Time: 13:56
 */
?>
@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"></th>
    <th class="sort text-center" data-field="id">ID</th>
    <th class="text-center">站点</th>
    <th class="text-center">帐号</th>
    <th class="text-center">类型</th>
    <th class="text-center">产品号</th>
    <th class="text-center">标题</th>
    <th class="text-center">EbaySku</th>
    <th class="sort text-center" data-field="start_price">价格</th>
    <th class=" text-center">币种</th>
    <th class="text-center">PayPal</th>
    <th class="text-center">状态</th>
    <th class="text-center">刊登人</th>
    <th class="text-center">刊登时间</th>
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
            <td> <a  target="_blank" href="http://www.ebay.com/itm/{{$v->item_id}}">{{$v->item_id}}</a></td>
            <td>{{$v->title }}</td>
            <td>{{$v->sku }}</td>
            <td>{{$v->start_price }}</td>
            <td>{{$v->currency }}</td>
            <td>{{$v->paypal_email_address }}</td>
            <td>{{config('ebaysite.status')[$v->status] }}</td>
            <td>@if(isset($v->operator->name ))
                    {{$v->operator->name }}
                @endif</td>

            <td>{{$v->start_time }}</td>
            <td>
                @if($v->status==2)
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="glyphicon glyphicon-cog"></i> 修改
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}"  data-name="changeSku">Sku信息</a></li>
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}" data-name="changeTitle">标题信息</a></li>
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}" data-name="changeDescription">描述信息</a></li>
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}" data-name="changeShipping">运输选项</a></li>
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}" data-name="changePicture">橱窗图片</a></li>
                        <li><a href="javascript:" class='singledit' data-value="{{$v->id}}" data-name="changeSpecifics">物品属性</a></li>

                    </ul>
                </div>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <i class="glyphicon glyphicon-cog"></i> 批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='batchedit' data-name="changeTitle">修改标题信息</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changeDescription">修改描述信息</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changePicture">修改橱窗图片</a></li>
            <li><a href="javascript:" class='batchedit' data-name="endItem">下架</a></li>
        </ul>
    </div>
@stop

@section('childJs')
<script type="text/javascript">
    $('.singledit').click(function () {
        var param = $(this).data("name");
        var id = $(this).data("value");
        var url = "{{ route('ebayOnline.productSingleEdit') }}";
        window.location.href = url + "?id=" + id + "&param=" + param;
    });

    $('.batchedit').click(function () {
        var checkbox = document.getElementsByName("tribute_id");
        var ids = "";
        var param = $(this).data("name");
        for (var i = 0; i < checkbox.length; i++) {
            if (!checkbox[i].checked)continue;
            ids += checkbox[i].value + ",";
        }
        if(ids==''){
            alert('请先勾选');
            return false;
        }
        ids = ids.substr(0, (ids.length) - 1);
        var url = "{{ route('ebayOnline.productBatchEdit') }}";
        window.location.href = url + "?ids=" + ids + "&param=" + param;
    });
</script>
@stop
