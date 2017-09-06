@extends('common.table')
@section('tableHeader')
    <th>批次号</th>
    <th>挂号码</th>
    <th>渠道名称</th>
    <th>异常描述</th>
    <th>导入时间</th>
@stop
@section('tableBody')
    @foreach($data->filter(function($single) use ($id){return $single->parent_id == $id;}) as $item)
        <tr>
            <td>{{ $item->parent ? $item->parent->shipmentCostNum : '' }}</td>
            <td>{{ $item->hang_num }}</td>
            <td>{{ $item->channel_name }}</td>
            <td>{{ $item->remark }}</td>
            <td>{{ $item->created_at }}</td>
        </tr>   
    @endforeach
@stop
@section('tableToolButtons')
@stop
