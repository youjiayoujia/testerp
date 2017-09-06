@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>账号</th>
    <th>是否启用</th>
    <th class="sort" data-field="created_at">创建日期</th>
    <th class="sort" data-field="updated_at">更新日期</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->is_available?'启用':'禁用'}}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>
                <a href="{{ route('user.edit', ['id'=>$user->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $user->id }}"
                   data-url="{{ route('user.destroy', ['id' => $user->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                
            </td>
        </tr>
    @endforeach
@stop
