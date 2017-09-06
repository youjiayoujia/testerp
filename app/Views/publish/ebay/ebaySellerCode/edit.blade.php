<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-23
 * Time: 17:55
 */
?>

@extends('common.form')
@section('formAction') {{ route('ebaySellerCode.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>销售代码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="seller_code"  name='seller_code' value="{{ old('seller_code') ? old('seller_code') : $model->seller_code }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>对应人员</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="user_id" id="user_id" class="form-control select_select0">
                <option value="">==请选择==</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}"   {{ Tool::isSelected('user_id', $user->id,$model) }} >{{$user->name}}</option>
                @endforeach

            </select>

        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $('.select_select0').select2();

    </script>

@stop