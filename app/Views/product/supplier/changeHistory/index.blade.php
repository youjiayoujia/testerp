@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>供货商</th>
    <th>原采购员</th>
    <th>变更采购员</th>
    <th>调整人</th>
    <th>调整时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->supplierName ? $model->supplierName->name : '' }}</td>
            <td>{{ $model->fromName ? $model->fromName->name : '' }}</td>
            <td>{{ $model->toName ? $model->toName->name : '' }}</td>
            <td>{{ $model->adjustByName ? $model->adjustByName->name : '' }}</td>
            <td>{{ $model->created_at }}</td>
            <td>
                <a href="{{ route('supplierChangeHistory.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
@stop