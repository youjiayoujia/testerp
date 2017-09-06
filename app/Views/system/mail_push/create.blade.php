@extends('common.form')
@section('formAction') {{ route(request()->segment(1).'.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-md-6">
            <label for="color">变量代码：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="分类中文名" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="color">变量名称：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="分类英文名" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">变量描述：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <textarea class="form-control" name='description' >{{ old('description') }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">变量值：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <textarea class="form-control" name='value' >{{ old('value') }}</textarea>
        </div>
    </div>
@stop

@section('pageJs')
@stop