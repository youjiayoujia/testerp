@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>API类型</th>
    <th>描述</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $channel)
        <tr>
            <td>{{ $channel->id }}</td>
            <td>{{ $channel->name }}</td>
            <td>{{ $channel->drive }}</td>
            <td>{{ $channel->brief }}</td>
            <td>{{ $channel->created_at }}</td>
            <td>{{ $channel->updated_at }}</td>
            <td>
                <a href="{{ route('channel.show', ['id'=>$channel->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('channel.edit', ['id'=>$channel->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $channel->id }}"
                   data-url="{{ route('channel.destroy', ['id' => $channel->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
