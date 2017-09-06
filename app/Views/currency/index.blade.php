@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>货币简称</th>
    <th>货币名称</th>   
    <th>货币标识</th>
    <th>汇率</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $currency)
        <tr>
            <td>{{ $currency->id }}</td>
            <td>{{ $currency->code }}</td>
            <td>{{ $currency->name }}</td>
            <td>{{ $currency->identify }}</td>
            <td>{{ $currency->rate }}</td>
            <td>{{ $currency->created_at }}</td>
            <td>
                <a href="{{ route('currency.show', ['id'=>$currency->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('currency.edit', ['id'=>$currency->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $currency->id }}"
                   data-url="{{ route('currency.destroy', ['id' => $currency->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
