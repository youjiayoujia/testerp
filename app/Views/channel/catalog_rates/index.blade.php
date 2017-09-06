@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $channel)
        <tr>
            <td>{{ $channel->id }}</td>
            <td>{{ $channel->name }}</td>

            <td>{{ $channel->created_at }}</td>
            <td>{{ $channel->updated_at }}</td>
            <td>
                <a href="{{ route('CatalogRatesChannel.show', ['id'=>$channel->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('CatalogRatesChannel.edit', ['id'=>$channel->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $channel->id }}"
                   data-url="{{ route('CatalogRatesChannel.destroy', ['id' => $channel->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>

                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $channel->table }}" data-id="{{$channel->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop