@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th> 
    <th>采购单状态</th> 
    <th>采购单审核状态</th>
   	<th>供应商</th>
    <th>采购去向</th>
    <th>结算状态</th>
    <th>采购总金额+物流费</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
       		
            <td>{{ $purchaseOrder->id }}</td> 
            <td>@foreach(config('purchase.purchaseOrder.status') as $k=>$statu)
            	@if($purchaseOrder->status == $k)
            	{{ $statu }}
                @endif
            @endforeach </td>
           <td> @foreach(config('purchase.purchaseOrder.examineStatus') as $k=>$statu)
            	@if($purchaseOrder->examineStatus == $k)
            	{{ $statu }}
                @endif
            @endforeach   </td>  
    		<td>
            @if($purchaseOrder->supplier_id >0)
            	{{ $purchaseOrder->supplier->name}}
            @endif
            </td>
            <td>{{ $purchaseOrder->warehouse->name}}</td>
             @foreach(config('purchase.purchaseOrder.close_status') as $k=>$close_statu)
            	@if($purchaseOrder->close_status == $k)
            	<td>{{ $close_statu}}</td>
                @endif
            @endforeach
                    
            <td>{{ $purchaseOrder->total_purchase_cost}}+{{ $purchaseOrder->sumPostage}}</td>
            <td>
                <a href="{{ route('closePurchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil">@if($purchaseOrder->close_status == 0) 结算 @else 查看 @endif
                </a>
            </td>
        </tr>
    @endforeach


@stop