@extends('common.form')
@section('formAction') {{ route(request()->segment(1).'.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-md-6">
            <label for="color">变量代码：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="分类中文名" name='code' value="{{$model->code }}">
        </div>
        <div class="form-group col-md-6">
            <label for="color">变量名称：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="变量名称" name='name' value="{{ $model->name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">变量描述：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <textarea class="form-control" name='description' >{{ $model->description}}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">变量值：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <textarea class="form-control" name='value' >{{ $model->value }}</textarea>
        </div>
    </div>

@stop

@section('pageJs')

@stop