@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsLimits.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-6">
        <label for="name" class='control-label'>物流限制名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="物流限制名称" name='name' value="{{ old('name') }}">


        <label for="ico" class='control-label'>图标</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="图标" name='ico' value="{{ old('ico')}}">
    </div>
</div>
@stop