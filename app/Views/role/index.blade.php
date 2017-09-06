@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>角色名</th>
    <th>角色</th>
    <th class="sort" data-field="created_at">创建日期</th>
    <th class="sort" data-field="updated_at">更新日期</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->role_name }}</td>
            <td>{{ $role->role }}</td>
            <td>{{ $role->created_at }}</td>
            <td>{{ $role->updated_at }}</td>
            <td>
                <a href="{{ route('role.edit', ['id'=>$role->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $role->id }}"
                   data-url="{{ route('role.destroy', ['id' => $role->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                
            </td>
        </tr>
    @endforeach
@stop
