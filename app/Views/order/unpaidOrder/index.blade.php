@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>买家ID/Email/订单号</th>
    <th>要求</th>
    <th class="sort" data-field="date">日期</th>
    <th>销售账号</th>
    <th>客服</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $unpaidOrder)
        <tr>
            <td>{{ $unpaidOrder->id }}</td>
            <td>{{ $unpaidOrder->ordernum }}</td>
            <td>{{ $unpaidOrder->remark . ' ' . $unpaidOrder->note }}</td>
            <td>{{ $unpaidOrder->date }}</td>
            <td>{{ $unpaidOrder->channel->name }}</td>
            <td>{{ $unpaidOrder->user ? $unpaidOrder->user->name : '' }}</td>
            <td>{{ $unpaidOrder->status_name }}</td>
            <td>{{ $unpaidOrder->updated_at }}</td>
            <td>{{ $unpaidOrder->created_at }}</td>
            <td>
                <a href="{{ route('unpaidOrder.show', ['id' => $unpaidOrder->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('unpaidOrder.edit', ['id' => $unpaidOrder->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $unpaidOrder->id }}"
                   data-url="{{ route('unpaidOrder.destroy', ['id' => $unpaidOrder->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $unpaidOrder->table }}" data-id="{{$unpaidOrder->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
