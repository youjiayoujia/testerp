@extends('common.table')
@section('tableToolButtons')@stop
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="order_id">订单ID</th>
    <th>sku</th>
    <th class="sort" data-field="quantity">数量</th>
    <th class="sort" data-field="price">金额</th>
    <th class="sort" data-field="status">订单状态</th>
    <th class="sort" data-field="ship_status">发货状态</th>
    <th>是否赠品</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->order_id }}</td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->status }}</td>
            <td>{{ $item->ship_status }}</td>
            <td>{{ $item->is_gift }}</td>
            <td>{{ $item->remark }}</td>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>
                <a href="{{ route('orderItem.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('orderItem.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('orderItem.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
