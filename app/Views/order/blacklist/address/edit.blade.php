@extends('common.form')
@section('formAction') {{ route('blacklistAddress.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="address" class="control-label">地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="address" placeholder="地址" name='address' value="{{ old('address') ?  old('address') : $model->address }}">
        </div>
    </div>
@stop