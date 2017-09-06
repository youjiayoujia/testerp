@extends('common.table')
@section('tableToolButtons')
		 <div class="btn-group">
         <a href="/purchaseItemList/excelReduction" class="btn btn-info" >
             批量导入采购价格
        </a>
        </div>
         <div class="btn-group">
         <a href="/purchaseItemList/postExcelReduction" class="btn btn-info" >
             批量导入物流单号
        </a>
        </div>
         <div class="btn-group">
        <a href="/purchaseItemList/reduction" class="btn btn-info" id="batchexamine">
             批量处理采购条目
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th>
    <th>Item_ID</th>
    <th>采购单ID</th>
    <th>成本审核</th>
    <th>产品图片</th>
    <th>供应商-采购链接</th>
    <th>采购去向</th>
    <th>库存</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>采购条目状态</th>
    <th>入库状态</th>
    <th>入库数量</th>
    <th>异常状态</th>
    <th>备注</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseList)
        <tr>
            <td>{{ $purchaseList->id }}</td>
            <td>{{ $purchaseList->sku}}</td>
            <td>{{ $purchaseList->purchase_order_id}}</td>
           <td>
           @foreach(config('purchase.purchaseItem.costExamineStatus') as $k=>$costExamineStatu)
            	@if($purchaseList->costExamineStatus == $k)
            	{{$costExamineStatu}}
                @endif
            @endforeach
           </td>
            <td><img src="{{ asset($purchaseList->item->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseList->supplier->name}}
            @if($purchaseList->supplier->type ==1)
            <a href="http://{{$purchaseList->supplier->url}}">{{$purchaseList->supplier->url}}</a>
            @else
            线下采购
            @endif
            </td>
            <td>{{ $purchaseList->warehouse->name}}</td>
            <td>{{ $purchaseList->all_quantity}}</td>
            <td>{{ $purchaseList->purchase_num}}/{{ $purchaseList->arrival_num}}/{{ $purchaseList->lack_num}}</td>
            @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseList->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach
                      
            <td>@foreach(config('purchase.purchaseItem.storageStatus') as $k=>$vo)
            	@if($purchaseList->storageStatus == $k)  
            	{{ $vo }}
                
                @endif
            @endforeach</td>
            <td>{{$purchaseList->storage_qty}}</td>
            <td> 
           @foreach(config('purchase.purchaseItem.active') as $k=>$vo)
            	@if($purchaseList->active == $k)  
            	{{ $vo }}
                @if($k >0)- @endif
                @endif
            @endforeach
            @if($purchaseList->active==1)
            @foreach(config('purchase.purchaseItem.active_status.1') as $key => $v)
                    @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==2)
            @foreach(config('purchase.purchaseItem.active_status.2') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==3)
            @foreach(config('purchase.purchaseItem.active_status.3') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==4)
            @foreach(config('purchase.purchaseItem.active_status.4') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @endif
            </td>
            <td>{{$purchaseList->remark}}</td>
            <td>
                <a href="{{ route('purchaseItemList.show', ['id'=>$purchaseList->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($purchaseList->status == 1)
                 <a href="/purchaseItemList/itemReductionUpdate/{{$purchaseList->id}}" class="btn btn-danger btn-xs">
                     还原该采购条目
                </a>
                @endif
            </td>
            
        </tr>
    @endforeach
@stop
 