@extends('common.form')
@section('formAction'){{ route('productRequire.excelStore')}}@stop
@section('formBody')
    <div class='form-group'>
        <label>文件</label>
        <input type='file' name='excel'>
    </div>
@stop
