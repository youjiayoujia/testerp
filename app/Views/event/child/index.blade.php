@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>事件</th>
    <th>时间</th>   
    <th>操作人</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $eventChild)
        <tr>
            <td>{{ $eventChild->id }}</td>
            <td>{{ $eventChild->what }}</td>
            <td>{{ $eventChild->when }}</td>
            <td>{{ $eventChild->whoName ? $eventChild->whoName->name : '' }}</td>
            <td>
                <a href="{{ route('eventChild.show', ['id'=>$eventChild->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')@stop
