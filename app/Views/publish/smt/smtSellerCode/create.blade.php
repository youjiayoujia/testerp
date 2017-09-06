<?php
/**
 * Created by PhpStorm.
 * User: guoou
 * Date: 2016-08-18
 * Time: 14:39
 */
?>
@extends('common.form')
@section('formAction') {{ route('smtSellerCode.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>销售代码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="seller_code"  name='sale_code' value="{{ old('sale_code') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>对应人员</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="user_id" id="user_id" class="form-control">
            <option value="">==请选择==</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach

            </select>

        </div>
    </div>

@stop