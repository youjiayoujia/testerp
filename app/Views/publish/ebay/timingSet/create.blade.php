<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-28
 * Time: 13:35
 */
?>

@extends('common.form')
@section('formAction') {{ route('ebayTiming.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">模板名称：</label>
        </div>
        <div class="form-group col-sm-4">
            <input class="form-control" type="text" name="name">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">账号：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="account_id"  id="account_id">
                <option value="">==请选择==</option>
                @foreach($account as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">站点：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="site"  id="site">
                <option value="">==请选择==</option>
                @foreach(config('ebaysite.site_name_id') as $name=>$id)
                    <option value="{{$id}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">仓库：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="warehouse">
                <option value="">==请选择==</option>
                @foreach(config('ebaysite.warehouse') as $key=>$name)
                    <option value="{{$key}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">站点时间：</label>
        </div>
        <div class="form-group col-sm-1">
            <input type="text" class="form-control datetime_select" placeholder="起始时间" name="start_time"/>
        </div>
        <div class="form-group col-sm-1">
            <input type="text" class="form-control datetime_select"   placeholder="结束时间" name="end_time"/>
        </div>
    </div>

@stop


@section('pageJs')
    <script type="text/javascript">
        $('.select_select0').select2();
        $('.datetime_select').datetimepicker({
            format:	'H:i'});
    </script>

@stop