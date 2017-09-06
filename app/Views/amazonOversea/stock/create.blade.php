@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('wrapLimits.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-6">
        <label for="name" class='control-label'>包装限制名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="包装限制名称" name='name' value="{{ old('name') }}">
    </div>
</div>
@stop