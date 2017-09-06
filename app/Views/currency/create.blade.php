@extends('common.form')
@section('formAction') {{ route('currency.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="code" class='control-label'>货币简称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="code" placeholder="货币简称" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>货币名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="货币名称" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-6'> 
            <label for='identify'>货币标识</label> 
            <input type='text' class="form-control" name="identify" placeholder="货币标识" value="{{ old('identify') }}">
        </div>
        <div class='form-group col-lg-6'> 
            <label for='rate'>货币汇率</label> 
            <input type='text' class="form-control" name="rate" placeholder="货币汇率" value="{{ old('rate') }}">
        </div>
    </div>
@stop