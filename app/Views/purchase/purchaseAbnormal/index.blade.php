@extends('common.table')
@section('tableHeader')
    <th>ID</th>
    <th>sku*采购数量</th>
    <th>采购单ID</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>shop</th>
    <th>异常状态</th>
    <th>是否生成采购单</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseItem)
        <tr>
            <td>
            
            {{ $purchaseItem->id }}</td>
            <td>{{ $purchaseItem->sku}}*{{ $purchaseItem->purchase_num}}</td>
           
            <td>{{ $purchaseItem->purchase_order_id}}</td>
            <td> 
             @if($purchaseItem->item->product->default_image>0)
             <img src="{{$purchaseItem->item->product->image->src}}" height="50px"/>
            @else
           该图片不存在
            @endif
           </td>
            <td>{{ $purchaseItem->supplier->name}}</td>
            <td>{{ $purchaseItem->warehouse->name}}</td>
        
            
            <td>
            @foreach(config('purchase.purchaseItem.active') as $k=>$status)
            	@if($purchaseItem->active == $k)
            	{{ $status }}
                @endif
            @endforeach
             @if($purchaseItem->active == 1)
            @foreach(config('purchase.purchaseItem.active_status.1') as $key=>$v)
           	{{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 2)
            @foreach(config('purchase.purchaseItem.active_status.2') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 3)
            @foreach(config('purchase.purchaseItem.active_status.3') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 4)
            @foreach(config('purchase.purchaseItem.active_status.4') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @endif
            </td>
            	@if($purchaseItem->purchase_order_id)
            	<td>已生成采购单</td>
                @else
                <td>未生成采购单</td>
                @endif           
            <td>{{ $purchaseItem->created_at }}</td>
            <td>
                 
                <a href="{{ route('purchaseAbnormal.edit', ['id'=>$purchaseItem->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 处理异常
                </a>
                
            </td>
        </tr>
    @endforeach
  
@stop