<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-23
 * Time: 17:24
 */

 ?>

@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>wish销售代码</th>
    <th>对应人员</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $sellerCode)
        <tr>
            <td>{{ $sellerCode->id }}</td>
            <td>{{ $sellerCode->seller_code }}</td>
            <td>{{ $sellerCode->operator->name }}</td>

                <td>
                <a href="{{ route('wishSellerCode.edit', ['id'=>$sellerCode->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $sellerCode->id }}"
                   data-url="{{ route('wishSellerCode.destroy', ['id' => $sellerCode->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop