@extends('common.form')
@section('formAction') {{ route('logisticsTransport.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="code" class="control-label">简称</label>
            <input class="form-control" id="code" placeholder="简称" name='code' value="{{ old('code') ?  old('code') : $model->code }}">
        </div>
    </div>
@stop