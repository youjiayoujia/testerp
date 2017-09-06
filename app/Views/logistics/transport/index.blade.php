@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>简称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $transport)
        <tr>
            <td>{{ $transport->id }}</td>
            <td>{{ $transport->name }}</td>
            <td>{{ $transport->code }}</td>
            <td>{{ $transport->updated_at }}</td>
            <td>{{ $transport->created_at }}</td>
            <td>
                <a href="{{ route('logisticsTransport.show', ['id'=>$transport->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsTransport.edit', ['id'=>$transport->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $transport->id }}"
                   data-url="{{ route('logisticsTransport.destroy', ['id' => $transport->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $transport->table }}" data-id="{{$transport->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
