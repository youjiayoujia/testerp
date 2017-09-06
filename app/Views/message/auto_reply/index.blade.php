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
    <th>启用状态</th>
    <th>规则名称</th>
    <th>渠道</th>
    <th>创建者</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->status}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->channel_name}}</td>
            <td>{{$item->user_name}}</td>
            <td>{{$item->created_at}}</td>
            <td>
                <a href="{{ route(request()->segment(1).'.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route(request()->segment(1).'.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route(request()->segment(1).'.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
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