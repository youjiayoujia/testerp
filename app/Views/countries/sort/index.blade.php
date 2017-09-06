@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>地区名</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $sort)
        <tr>
            <td>{{ $sort->id }}</td>
            <td>{{ $sort->name }}</td>
            <td>{{ $sort->created_at }}</td>
            <td>
                <a href="{{ route('countriesSort.show', ['id'=>$sort->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('countriesSort.edit', ['id'=>$sort->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $sort->id }}"
                   data-url="{{ route('countriesSort.destroy', ['id' => $sort->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
