@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">编号</th>
    <th>物流分类名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $catalog)
        <tr>
            <td>{{ $catalog->id }}</td>
            <td>{{ $catalog->name }}</td>
            <td>{{ $catalog->updated_at }}</td>
            <td>{{ $catalog->created_at }}</td>
            <td>
                <a href="{{ route('logisticsCatalog.show', ['id'=>$catalog->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsCatalog.edit', ['id'=>$catalog->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $catalog->id }}"
                   data-url="{{ route('logisticsCatalog.destroy', ['id' => $catalog->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $catalog->table }}" data-id="{{$catalog->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
