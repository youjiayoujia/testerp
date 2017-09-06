@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>包装限制名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $wrapLimits)
        <tr>
            <td>{{ $wrapLimits->id }}</td>
            <td>{{ $wrapLimits->name }}</td>
            <td>{{ $wrapLimits->created_at }}</td>
            <td>{{ $wrapLimits->updated_at }}</td>
            <td>
                <a href="{{ route('wrapLimits.show', ['id'=>$wrapLimits->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('wrapLimits.edit', ['id'=>$wrapLimits->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $wrapLimits->id }}"
                   data-url="{{ route('wrapLimits.destroy', ['id' => $wrapLimits->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
