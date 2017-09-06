@extends('common.form')
@section('formAction') {{ route('logisticsCodeFn') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="col-lg-4">
            <strong>当前物流方式</strong>: {{ $logistics->name }}
        </div>
        <div class="col-lg-4">
            <strong>当前物流方式简码</strong>: {{ $logistics->code }}
        </div>
        <br/>
        <br/>
        <div class="form-group col-lg-12">
            <label for="url" class="control-label">Select File (文件格式为.csv)</label>
            <input type="hidden" name="logistics_id" value="{{ $logistics->id }}">
            <input id="input-1" type="file" class="file" name="trackingnos">
        </div>
    </div>
@stop