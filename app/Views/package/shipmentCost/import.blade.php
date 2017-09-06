@extends('common.form')
@section('formAction') {{ route('shipmentCost.importProcess') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-6">
        <label for="name" class='control-label'>文件导入</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
       	<input type='file' name='import'>
    </div>
</div>
@stop