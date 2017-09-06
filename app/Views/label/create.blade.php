@extends('common.form')
@section('formAction') {{ route('label.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>标签名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="标签名" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="group_id" class='control-label'>组别</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="group_id" placeholder="组别" name='group_id' value="{{ old('group_id') }}">
        </div>
    </div>
@stop