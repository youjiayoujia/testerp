@extends('common.table')
@section('tableToolButtons')
@stop
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>账号</th>
    <th>标题</th>
    <th>用户昵称</th>
    <th>用户ID</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>消息ID</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $reply)
        <tr>
            <td>{{ $reply->id }}</td>
            <td>{{ $reply->message->account->alias }}</td>
            <td>{{ str_limit($reply->title, 60) }}</td>
            <td>{{ $reply->to }}</td>
            <td>{{ $reply->to_email }}</td>
            <td>{{ $reply->status }}</td>
            <td>{{ $reply->created_at }}</td>
            <td>{{ $reply->message_id }}</td>
            <td>
                <a href="{{ route('messageReply.edit', ['id'=>$reply->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
