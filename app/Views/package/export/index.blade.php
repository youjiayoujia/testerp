@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>模板名</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $exportPackage)
        <tr>
            <td>{{ $exportPackage->id }}</td>
            <td>{{ $exportPackage->name }}</td>
            <td>{{ $exportPackage->created_at }}</td>
            <td>
                <a href="{{ route('exportPackage.show', ['id'=>$exportPackage->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('exportPackage.edit', ['id'=>$exportPackage->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $exportPackage->id }}"
                   data-url="{{ route('exportPackage.destroy', ['id' => $exportPackage->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
