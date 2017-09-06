@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockTaking.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='file' name='actual_stock'>
        </div>
    </div>
@stop