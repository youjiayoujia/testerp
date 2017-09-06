@extends('common.form')
@section('formAction') {{ route('purchaseAbnormal.store') }} @stop
@section('formBody')
    <input type="hidden" name="user_id" value="1">
    <div class="row"> 
    <div class="form-group col-lg-4">
        <label class='control-label'>批量输入SKU:</label>（批量输入sku各之间用#隔开）
        <textarea name="sku" rows="4" cols="50" autofocus>
        </textarea>
         
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label' style="margin-top:0">选择异常种类</label>
        <select name="active" >
        @foreach(config('purchase.purchaseItem.active') as $key=>$active)
        <option value="{{$key}}">{{$active}}</option>
        @endforeach
        </select>
    </div>    
    </div> 
@stop
 
 