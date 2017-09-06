@extends('common.table')
@section('tableToolButtons')
 
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th>ID</th>
    <th>运单号</th>
    <th>运单状态</th>
    <th>关联采购单</th>
    <th>扫描人</th>
    <th>扫描时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $_result)
        <tr>
            <td>{{$_result->id}}</td>
            <td>{{$_result->post_coding}}</td>
            <td>{{$_result->purchase_order_id?'已关联':'未关联'}}</td>
            <td><a target="_blank" href="{{ route('purchaseOrder.printInWarehouseOrder', ['id'=>$_result->purchase_order_id]) }}">{{$_result->purchase_order_id}}</a></td>
            <td>{{$_result->user?$_result->user->name:''}}</td>
            <td>{{$_result->updated_at}}</td>
            <td>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item" data-url="{{ route('purchaseList.destroy', ['id' => $_result->id]) }}" data-id="{{$_result->id}}">
                    <span class="glyphicon glyphicon-trash"></span> 删除关联
                </a>
            </td>
        </tr>
    @endforeach
@stop
   

 