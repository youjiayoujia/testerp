@extends('common.table')
@section('tableToolButtons')

    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th class="sort">ID</th>
    <th>变量代码</th>
    <th>变量名</th>
    <th>变量描述</th>
    <th>变量值</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->code}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->description}}</td>
            <td>{{$item->value}}</td>
            <td>{{$item->created_at}}</td>
            <td>
                <a href="{{ route(request()->segment(1).'.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route(request()->segment(1).'.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs index-a-edit">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route(request()->segment(1).'.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $item->table }}" data-id="{{$item->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach

@stop
@section('childJs')

@stop