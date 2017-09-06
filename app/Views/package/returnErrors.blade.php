@extends('common.form')
@section('formBody')
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <th>追踪号</th>
        <th>错误原因</th>
        </thead>
        <tbody>
        @foreach($returnErrors as $error)
        <tr>
            <td>{{$error['id']}}</td>
            <td>{{$error['remark']}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('formButton')@stop