<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-01
 * Time: 17:13
 */
 ?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>站点</th>
    <th>是否启用</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $ebaySite)
        <tr>
            <td>{{  $ebaySite->id }}</td>
            <td>{{  $ebaySite->site }}</td>
            <td>{{  $ebaySite->SiteEnable }}</td>
            <td>
                 <a href="{{ route('ebayDetail.edit', ['id'=>$ebaySite->id]) }}" class="btn btn-warning btn-xs">
                      <span class="glyphicon glyphicon-pencil"></span> 更新站点信息
                  </a>

                {{-- <a href="{{ route('wish.editOnlineProduct', ['id'=>$wishProduct->id]) }}" class="btn btn-warning btn-xs">
                     <span class="glyphicon glyphicon-pencil"></span> 编辑在线广告
                 </a>--}}
                {{-- <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                    data-id="{{ $wishProduct->id }}"
                    data-url="{{ route('wish.destroy', ['id' => $wishProduct->id]) }}">
                     <span class="glyphicon glyphicon-trash"></span> 删除
                 </a>--}}
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')


@stop