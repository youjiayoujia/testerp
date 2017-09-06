@extends('common.form')
@section('formAction') {{ route('wrapLimits.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>包装限制名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="包装限制名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
    </div>
@stop