@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>3宝SKU</th>
    <th>cn_name</th>
    <th>hs_code</th>
    <th>unit</th>
    <th>规格型号</th>
    <th>status</th>
    <th class='sort' data-field='handle_time'>处理时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $bao3)
        <tr>
            <td>{{ $bao3->id }}</td>     
            <td>{{ $bao3->product ? $bao3->product->model : '' }}</td>
            <td>{{ $bao3->cn_name }}</td>
            <td>{{ $bao3->hs_code }}</td>
            <td>{{ $bao3->unit }}</td>
            <td>{{ $bao3->f_model }}</td>
            <td>{{ $bao3->status }}</td>
            <td>{{ $bao3->created_at }}</td>
            <td>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $bao3->id }}"
                   data-url="{{ route('customsClearance.destroy', ['id' => $bao3->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')@stop