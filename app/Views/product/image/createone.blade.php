@extends('common.form')
@section('formAction') {{ route('createImage') }} @stop
@section('formBody')
    Model:<input type="text" value="" name='model'>

@stop