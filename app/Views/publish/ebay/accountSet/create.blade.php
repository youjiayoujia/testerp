<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 10:48
 */
 ?>
@extends('common.form')
@section('formAction') {{ route('ebayAccountSet.store') }} @stop
@section('formBody')
     <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">账号：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="account_id">
                <option value="">==请选择==</option>
                @foreach($account as $key=> $a)
                <option value="{{$key}}">{{$a}}</option>
                @endforeach
            </select>
        </div>
    </div>

    @foreach(config('ebaysite.currency') as $key=>$currency)
        <div class="row">
            <div class="form-group col-sm-1">
                <label for="subject" class="right">{{$currency}}：</label>
            </div>
            <div class="form-group col-sm-2">
                <input type="text" class="form-control" name="currency[{{$currency}}]">
            </div>
        </div>
    @endforeach

     <div class="row">
         <div class="form-group col-sm-1">
             <label for="subject" class="right">大PP：</label>
         </div>
         <div class="form-group col-sm-6">
             <select class="select_select0 col-sm-4" name="big_paypal">
                 <option value="">==请选择==</option>
                 @foreach($paypal as $key=> $p)
                     <option value="{{$key}}">{{$p}}</option>
                 @endforeach
             </select>
         </div>
     </div>


     <div class="row">
         <div class="form-group col-sm-1">
             <label for="subject" class="right">小PP：</label>
         </div>
         <div class="form-group col-sm-6">
             <select class="select_select0 col-sm-4" name="small_paypal">
                 <option value="">==请选择==</option>
                 @foreach($paypal as $key=> $p)
                     <option value="{{$key}}">{{$p}}</option>
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
