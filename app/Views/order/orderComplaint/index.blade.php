@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" >order_item_ID</th>
    <th>投诉类型</th>
    <th>投诉Email</th>
    <th>投诉来源国</th>
    <th>投诉描述</th>
    <th>创建人</th>
    <th>创时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->order_item_id }}</td>
            <td>{{ $item->complaint_type }}</td>
            <td>{{ $item->complaint_email }}</td>
            <td>{{ $item->complaint_country }}</td>
            <td>{{ $item->question }}</td>
            <td>{{ $item->create_user_id }}</td>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>
                <a href="{{ route('orderComplaint.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('orderComplaint.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('orderComplaint.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
