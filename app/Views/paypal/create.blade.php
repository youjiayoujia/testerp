<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 13:58
 */
?>



@extends('common.form')
@section('formAction') {{ route('paypal.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>paypalEmail地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="paypal_email_address"  name='paypal_email_address' value="{{ old('paypal_email_address') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>paypalAPI账号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="paypal_account"  name='paypal_account' value="{{ old('paypal_account') }}">
        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>paypalAPI密码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="paypal_password"  name='paypal_password' value="{{ old('paypal_password') }}">
        </div>
    </div>

   <div class="row">
           <div class="form-group col-lg-12">
               <label for="brief" class='control-label'>paypalAPI口令</label>
               <textarea class="form-control" rows="3" name="paypal_token">{{ old('paypal_token') }}</textarea>
           </div>
       </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>paypal是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1" {{ Tool::isChecked('is_enable', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_enable" value="2" {{ Tool::isChecked('is_enable', '2') }}>否
                </label>
            </div>
        </div>
    </div>


@stop