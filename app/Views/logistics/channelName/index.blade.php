@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>渠道</th>
    <th>名字</th>
    <th>回传编码</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $channelName)
        <tr>
            <td>{{ $channelName->id }}</td>
            <td>{{ $channelName->channel ? $channelName->channel->name : ''}}</td>
            <td>{{ $channelName->name }}</td>
            <td>{{ $channelName->logistics_key }}</td>
            <td>{{ $channelName->created_at }}</td>
            <td>{{ $channelName->updated_at }}</td>
            <td>
                <a href="{{ route('logisticsChannelName.show', ['id'=>$channelName->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsChannelName.edit', ['id'=>$channelName->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $channelName->id }}"
                   data-url="{{ route('logisticsChannelName.destroy', ['id' => $channelName->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $channelName->table }}" data-id="{{$channelName->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
