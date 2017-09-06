@extends('common.form')
@section('formAction') {{ route('logisticsLimits.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>物流限制名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="物流限制名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">

            <label for="ico" class='control-label'>图标</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="图标" name='ico' value="{{ old('ico') ? old('ico') : $model->ico }}">
        </div>
    </div>
@stop