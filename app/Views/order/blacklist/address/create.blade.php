@extends('common.form')
@section('formAction') {{ route('blacklistAddress.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="address" class="control-label">地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="address" placeholder="地址" name='address' value="{{ old('address') }}">
        </div>
    </div>
@stop