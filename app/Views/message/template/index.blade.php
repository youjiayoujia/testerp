@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>一级类型</th>
    <th>类型</th>
    <th>名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $template)
        <tr>
            <td>{{ $template->id }}</td>
            <td>{{ $template->type->parent->name }}</td>
            <td>{{ $template->type->name }}</td>
            <td>{{ $template->name }}</td>
            <td>{{ $template->created_at }}</td>
            <td>{{ $template->updated_at }}</td>
            <td>
                <a href="{{ route('messageTemplate.show', ['id'=>$template->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('messageTemplate.edit', ['id'=>$template->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $template->id }}"
                   data-url="{{ route('messageTemplate.destroy', ['id' => $template->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $template->table }}" data-id="{{$template->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
