@extends('common.form')
@section('formAction') {{ route('label.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>Label</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="用户姓名" name='name' value="{{ $model->name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="group_id" class='control-label'>组别</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="group_id" placeholder="用户邮箱" name='group_id' value="{{ $model->group_id }}">
        </div>
    </div>
@stop