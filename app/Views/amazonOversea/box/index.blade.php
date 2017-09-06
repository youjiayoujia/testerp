@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>shipmentId</th>
    <th>箱号</th>
    <th>重量</th>
    <th>体积(cm3)</th>
    <th>物流方式</th>
    <th>追踪号</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $box)
        <tr>
            <td>{{ $box->id }}</td>
            <td>{{ $box->report ? $box->report->shipment_id : '' }}</td>
            <td>{{ $box->boxNum }}</td>
            <td>{{ $box->weight }}</td>
            <td>{{ $box->length.'*'.$box->width.'*'.$box->height }}</td>
            <td>{{ $box->logistics ? $box->logistics->code : '' }}</td>
            <td>{{ $box->tracking_no }}</td>
            <td>{{ $box->status ? '已发货' : '未发货' }}</td>
            <td>{{ $box->created_at }}</td>
            <td>{{ $box->updated_at }}</td>
            <td>
                <a href="{{ route('box.show', ['id'=>$box->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('box.edit', ['id'=>$box->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $box->id }}"
                   data-url="{{ route('box.destroy', ['id' => $box->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
@stop