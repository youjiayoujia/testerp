<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-13
 * Time: 11:32
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>Ebay账号</th>
    <th>大PP</th>
    <th>小PP</th>
    <th>PP币种临界值</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $v)
        <tr class="">
            <td>{{$v->id}}</td>
            <td>{{$v->channelAccount->alias}}</td>
            <td>{{$v->bigPaypal->paypal_email_address}}</td>
            <td>{{$v->smallPaypal->paypal_email_address}}</td>
            <td>
            <?php
                if(!empty($v->currency)){
                    $currency = json_decode($v->currency);
                    foreach($currency as $key=>$cur){
                        echo $key.':'.$cur.'<br/>';
                    }
                }
                ?>
            </td>
            <td>
                <a href="{{ route('ebayAccountSet.edit', ['id'=>$v->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $v->id }}"
                   data-url="{{ route('ebayAccountSet.destroy', ['id' => $v->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>


            </td>
        </tr>
    @endforeach
@stop