<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 16:53
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>模板名称</th>
    <th>站点</th>
    <th>仓库</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $v)
        <tr class="">
            <td>{{$v->id}}</td>
            <td>{{$v->name}}</td>
            <td>{{array_search($v->site, config('ebaysite.site_name_id'))}}</td>
            <td>{{config('ebaysite.warehouse')[$v->warehouse]}}</td>
            <td>
                <a href="{{ route('ebayDataTemplate.edit', ['id'=>$v->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $v->id }}"
                   data-url="{{ route('ebayDataTemplate.destroy', ['id' => $v->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>


            </td>
        </tr>
    @endforeach
@stop