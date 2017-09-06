@extends('common.form')
@section('formAction') {{ route('logisticsTransport.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="code" class="control-label">简称</label>
            <input class="form-control" id="code" placeholder="简称" name='code' value="{{ old('code') }}">
        </div>
    </div>
@stop