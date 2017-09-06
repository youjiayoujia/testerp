@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>收货包装名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $recieveWraps)
        <tr>
            <td>{{ $recieveWraps->id }}</td>
            <td>{{ $recieveWraps->name }}</td>
            <td>{{ $recieveWraps->created_at }}</td>
            <td>{{ $recieveWraps->updated_at }}</td>
            <td>
                <a href="{{ route('recieveWraps.show', ['id'=>$recieveWraps->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('recieveWraps.edit', ['id'=>$recieveWraps->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $recieveWraps->id }}"
                   data-url="{{ route('recieveWraps.destroy', ['id' => $recieveWraps->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
