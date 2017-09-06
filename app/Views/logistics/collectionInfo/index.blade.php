@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>收款银行</th>
    <th>收款账户</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $collectionInfo)
        <tr>
            <td>{{ $collectionInfo->id }}</td>
            <td>{{ $collectionInfo->bank }}</td>
            <td>{{ $collectionInfo->account }}</td>
            <td>{{ $collectionInfo->updated_at }}</td>
            <td>{{ $collectionInfo->created_at }}</td>
            <td>
                <a href="{{ route('logisticsCollectionInfo.show', ['id'=>$collectionInfo->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsCollectionInfo.edit', ['id'=>$collectionInfo->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $collectionInfo->id }}"
                   data-url="{{ route('logisticsCollectionInfo.destroy', ['id' => $collectionInfo->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $collectionInfo->table }}" data-id="{{$collectionInfo->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
