@extends('common.form')
@section('formAction') {{ route('logisticsCatalog.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">分类名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="分类名称" name='name' value="{{ old('name') }}">
        </div>
    </div>
@stop