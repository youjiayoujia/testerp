@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>地址</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $address)
        <tr>
            <td>{{ $address->id }}</td>
            <td>{{ $address->address }}</td>
            <td>{{ $address->updated_at }}</td>
            <td>{{ $address->created_at }}</td>
            <td>
                <a href="{{ route('blacklistAddress.show', ['id'=>$address->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('blacklistAddress.edit', ['id'=>$address->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $address->id }}"
                   data-url="{{ route('blacklistAddress.destroy', ['id' => $address->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $address->table }}" data-id="{{$address->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
