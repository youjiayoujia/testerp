@extends('layouts.default')
@section('body')
<div class='col-lg-4'>
    <table class='table table-bordered'>
        <tbody>
            <tr><td>收件公司:</td><td colspan='3'>{{$logistics}}</td></tr>
            <tr><td>渠道名称:</td><td colspan='3'></td></tr>
            <tr><td colspan='2'>渠道代码:</td><td colspan='2'>发货袋号:{{$number}}</td></tr>
            <tr><td colspan='2'>重量:</td><td colspan='2'>数量:</td></tr>
            <tr><td>备注:</td><td colspan='3'></td></tr>
            <tr><td colspan='2'>发件公司:</td><td colspan='2'>发货日期:</td></tr>
        </tbody>
    </table>
</div>
@stop