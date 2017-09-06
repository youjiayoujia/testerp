@extends('common.form')
@section('formAction') {{ route(request()->segment(1).'.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <div class='row'>

        <div class="form-group col-md-3">
            <label for="color">中文名：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="分类中文名" name='cn_name' value="{{ old('purchase_carriage') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">英文名：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="分类英文名" name='en_name' value="{{ old('purchase_day') }}">
        </div>
    </div>
@stop

@section('pageJs')
@stop