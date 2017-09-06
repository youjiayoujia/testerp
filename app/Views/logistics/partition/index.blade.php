@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>物流分区名</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $partition)
        <tr>
            <td>{{ $partition->id }}</td>
            <td>{{ $partition->name }}</td>
            <td>{{ $partition->created_at }}</td>
            <td>{{ $partition->updated_at }}</td>
            <td>
                <a href="{{ route('logisticsPartition.show', ['id'=>$partition->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsPartition.edit', ['id'=>$partition->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $partition->id }}"
                   data-url="{{ route('logisticsPartition.destroy', ['id' => $partition->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $partition->table }}" data-id="{{$partition->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
