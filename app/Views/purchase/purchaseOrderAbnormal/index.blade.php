@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th>ID</th> 
    <th>采购单状态</th> 
    <th>采购单审核状态</th>
   	<th>供应商</th>
    <th>采购去向</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
            <td>{{ $purchaseOrder->id }}</td> 
           <td> @foreach(config('purchase.purchaseOrder.status') as $k=>$status)
            	@if($purchaseOrder->status == $k)
            	{{ $status }}
                @endif
            @endforeach </td>
           <td> @foreach(config('purchase.purchaseOrder.examineStatus') as $k=>$statu)
            	@if($purchaseOrder->examineStatus == $k)
            	{{ $statu }}
                @endif
            @endforeach</td>  
    		<td>
            @if($purchaseOrder->supplier_id >0)
            	{{ $purchaseOrder->supplier->name}}
            @endif
            </td>
            <td>{{ $purchaseOrder->warehouse->name}}</td>               
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
                @if($purchaseOrder->examineStatus == 2)
                <a href="{{ route('purchaseOrderAbnormal.edit', ['id'=>$purchaseOrder->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>处理
                </a>
                @endif       
               
            </td>
        </tr>
    @endforeach
@stop