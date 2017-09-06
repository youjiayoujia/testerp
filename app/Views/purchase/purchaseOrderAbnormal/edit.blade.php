@extends('common.detail')
@section('detailBody') 
        <div class="panel-heading">单头</div>
        <div class="panel-body">
            <div class="form-group col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
                <strong>采购仓库</strong>:
                {{ $model->warehouse->name}}
            </div>
             <div class="form-group col-lg-4">
            	<strong>供应商信息</strong>:
                名：{{$model->supplier->name}}&nbsp;电话：{{$model->supplier->telephone}} &nbsp;地址：{{$model->supplier->province}}{{$model->supplier->city}}{{$model->supplier->address}}
                &nbsp;
                @if($model->supplier->type==1)
                	线上采购
                @else
                	线下采购
                @endif
            </div>
            <div class="form-group col-lg-4">
                <strong>订单成本</strong>:
                物流费{{ $model->total_postage}}+商品采购价格{{ $model->total_purchase_cost}}  总成本{{ $model->total_postage + $model->total_purchase_cost}}
            </div>
            <div class="form-group col-lg-4">
                <strong>采购单状态</strong>:
               @foreach(config('purchase.purchaseOrder.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div>   
         <div class="form-group col-lg-4">
            	<strong>取消采购单</strong>:
                	<a href="/purchaseOrderAbnormal/cancelOrder/{{$model->id}}" class="btn btn-info btn-xs"> 取消该采购</a>  
            </div>
         
        </div>

     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>采购条目ID</td> 
            <td>SKU*采购数量</td> 
            <td>样图</td>         
            <td>状态</td> 
            <td>操作</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
           
            <td>{{$purchaseItem->sku}}*{{$purchaseItem->purchase_num}}</td>
            <td>
            @if($purchaseItem->item->product->default_image>0) 
            <img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px">
             @else
             暂无图片
             @endif
            </td>  
            <td>
            @foreach(config('purchase.purchaseItem.active') as $key=>$v)
           	@if($purchaseItem->active==$key)
            {{$v}}
            @endif 
            @endforeach
            @if($purchaseItem->active ==1)
                @foreach(config('purchase.purchaseItem.active_status.1') as $k=>$v)
                    @if($purchaseItem->active_status==$k)
                        {{$v}}
                    @endif 
                @endforeach
                @elseif($purchaseItem->active ==2)
                @foreach(config('purchase.purchaseItem.active_status.2') as $k=>$v)
                    @if($purchaseItem->active_status==$k)
                        {{$v}}
                    @endif 
                @endforeach 
                @elseif($purchaseItem->active ==3)
                @foreach(config('purchase.purchaseItem.active_status.3') as $k=>$v)
                    @if($purchaseItem->active_status==$k)
                        {{$v}}
                    @endif 
                @endforeach 
                @elseif($purchaseItem->active ==4)
                @foreach(config('purchase.purchaseItem.active_status.4') as $k=>$v)
                    @if($purchaseItem->active_status==$k)
                        {{$v}}
                    @endif 
                @endforeach  
            @endif      
             </td>
              
			<td>
            @if($purchaseItem->active ==1)
            <a href="/purchaseItem/cancelThisItem/{{$purchaseItem->id}}" class="btn btn-info btn-xs"> 取消该条目</a>  
            @elseif($purchaseItem->active == 2)
            报等时间：{{$purchaseItem->wait_time}}&nbsp;报等备注：{{$purchaseItem->remark}}
            @elseif($purchaseItem->active >2)
             @foreach(config('purchase.purchaseItem.active') as $key=>$v)
             	@if($key ==$purchaseItem->active)
            	{{$v}}
            	@endif
             @endforeach
            
            @else
          	 正常
             @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
        </div>
    </div>
@stop