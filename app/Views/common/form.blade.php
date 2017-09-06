@extends('layouts.default')
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading">@section('formTitle') {{ $metas['title'] }} @show{{-- 表单标题 --}}</div>
        <div class="panel-body">
            <form method="POST" action="@section('formAction')@show{{-- 表单提交地址 --}}" @section('formAttributes')@show{{-- 表单其它属性 --}}  enctype="multipart/form-data" class='thefatherform'>
                {!! csrf_field() !!}
                @section('formBody')@show{{-- 表单内容 --}}
                @section('inputRefer'){{--pageRefer--}}
                    @if(!empty($refer_url))
                        <input type="hidden" name="refer_url" value="{{$refer_url}}">
                    @endif
                @show
                @section('formButton')
                    <button type="submit" class="btn btn-success">提交</button>
                    <button type="button" class="btn btn-default" onclick="return history.go(-1);">取消</button>
                @show{{-- 表单按钮 --}}
            </form>
        </div>
    </div>
@stop