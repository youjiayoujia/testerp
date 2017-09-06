@extends('common.table')
@section('tableHeader')
    <th>批次号</th>
    <th>挂号码</th>
    <th>包裹id</th>
    <th>包裹类型</th>
    <th>发货时间</th>
    <th>物流方式</th>
    <th>国家简称</th>
    <th>目的地</th>
    <th>计费重量</th>
    <th>理论重量</th>
    <th>重量差异百分比</th>
    <th>计费运费(元)</th>
    <th>理论运费(元)</th>
    <th>渠道名称</th>
    <th>导入时间</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{ $item->parent ? $item->parent->shipmentCostNum : '' }}</td>
            <td>{{ $item->hang_number }}</td>
            <td>{{ $item->package_id }}</td>
            <td>{{ $item->type == 'SINGLE' ? '单单' : ($item->type == 'MULTI' ? '多多' : '单多') }}</td>
            <td>{{ $item->shipped_at }}</td>
            <td>{{ $item->logistics ? $item->logistics->code : '' }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->destination }}</td>
            <td>{{ $item->all_weight }}</td>
            <td>{{ $item->theory_weight }}</td>
            <td>{{ round(($item->all_weight - $item->theory_weight)/($item->theory_weight ? $item->theory_weight : 1)*100, 2).'%' }}</td>
            <td>{{ $item->all_cost }}</td>
            <td>{{ $item->theory_cost }}</td>
            <td>{{ $item->channel_name }}</td>
            <td>{{ $item->created_at }}</td>
        </tr>   
    @endforeach
@stop
@section('tableToolButtons')
@stop