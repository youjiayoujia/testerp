@extends('common.form')
@section('formAction') {{ route('supplierLevel.store') }} @stop
@section('formBody')
<div class="row">
    <div class="form-group col-lg-2">
        <label for="name" class='control-label'>等级名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control"placeholder="等级名称" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="description" class='control-label'>描述</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" name="description" placeholder="描述" value="{{ old('description') }}">
    </div>
</div>
@stop
