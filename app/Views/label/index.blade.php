@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>组别</th>
    <th class="sort" data-field="created_at">创建日期</th>
    <th class="sort" data-field="updated_at">更新日期</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $label)
        <tr>
            <td>{{ $label->id }}</td>
            <td>{{ $label->name }}</td>
            <td>{{ $label->group_id }}</td>
            <td>{{ $label->created_at }}</td>
            <td>{{ $label->updated_at }}</td>
            <td>
                <a href="{{ route('label.edit', ['id'=>$label->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $label->id }}"
                   data-url="{{ route('label.destroy', ['id' => $label->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                
            </td>
        </tr>
    @endforeach
@stop
