@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>smt销售代码</th>
    <th>对应人员</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $sellerCode)
        <tr>
            <td>{{$sellerCode->id}}</td>
            <td>{{$sellerCode->sale_code}}</td>
            <td>{{$sellerCode->User->name}}</td>

                <td>
                <a href="{{ route('smtSellerCode.edit', ['id'=>$sellerCode->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $sellerCode->id }}"
                   data-url="{{ route('smtSellerCode.destroy', ['id' => $sellerCode->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop