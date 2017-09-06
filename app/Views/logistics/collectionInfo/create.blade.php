@extends('common.form')
@section('formAction') {{ route('logisticsCollectionInfo.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="bank" class="control-label">收款银行</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="bank" placeholder="收款银行" name='bank' value="{{ old('bank') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="account" class="control-label">收款账户</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="account" placeholder="收款账户" name='account' value="{{ old('account') }}">
        </div>
    </div>
@stop