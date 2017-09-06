@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>时间</th>
    <th>调整人</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $overseaStockAdjustment)
        <tr>
            <td>{{ $overseaStockAdjustment->id }}</td>
            <td>{{ $overseaStockAdjustment->date }}</td>
            <td>{{ $overseaStockAdjustment->adjustBy ? $overseaStockAdjustment->adjustBy->name : ''}}</td>
            <td>{{ $overseaStockAdjustment->created_at }}</td>
            <td>
                <a href="{{ route('overseaStockAdjustment.show', ['id'=>$overseaStockAdjustment->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $overseaStockAdjustment->id }}"
                   data-url="{{ route('overseaStockAdjustment.destroy', ['id' => $overseaStockAdjustment->id]) }}" title='删除'>
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')@stop
