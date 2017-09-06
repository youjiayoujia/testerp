@extends('common.table')
@section('tableToolButtons')@stop
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>
    <th class='sort' data-field='amount'>数量</th>
    <th>仓库</th>
    <th>库位</th>
    <th>hold类型</th>
    <th>hold来源</th>
    <th>备注</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockHold)
        <tr>
            <td>{{ $stockHold->id }}</td>
            <td>{{ $stockHold->stock ? $stockHold->stock->item ? $stockHold->stock->item->sku : '' : '' }}</td>
            <td>{{ $stockHold->quantity}}</td>
            <td>{{ $stockHold->stock ? $stockHold->stock->warehouse ? $stockHold->stock->warehouse->name : '' : '' }}</td>
            <td>{{ $stockHold->stock ? $stockHold->stock->position ? $stockHold->stock->position->name : '' : '' }}</td>
            <td>{{ $stockHold->type_name }}</td>
            <td>{{ $stockHold->relation_name }}</td>
            <td>{{ $stockHold->remark }} </td>
            <td>{{ $stockHold->created_at }}</td>
            <td>
                <a href="{{ route('stockHold.show', ['id'=>$stockHold->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
            </td>
        </tr>
    @endforeach
@stop
