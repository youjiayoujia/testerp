@extends('common.form')
@section('formAction')  {{ route('closePurchaseOrder.update', ['id' => $model->id]) }}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="update_userid" value="2"/>
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
            	<strong>结算时间</strong>:
               {{$model->updated_at}}
            </div>
            <div class="form-group col-lg-4">
                <strong>订单成本</strong>:
                物流费{{ $sumPostage}}+商品采购价格{{ $model->total_purchase_cost}}  总成本{{ $sumPostage + $model->total_purchase_cost}}
            </div>
            <div class="form-group col-lg-4">
                <strong>采购人</strong>:            
            		{{$model->assigner}}
            </div> 
            <div class="form-group col-lg-4">
                <strong>采购单结算方式</strong>:
          		@if($model->close_status ==0)
                <select name="close_status">
               		@foreach(config('product.product_supplier.pay_type') as $k=>$v)           	
            			<option value="{{$k}}" {{ $model->pay_type == $k ? 'selected' : '' }}>{{$v}}</option>
 					@endforeach
                    </select>
                @else
                <input name="close_status" type="hidden" value="1"/>
                已结算
                @endif
            </div> 
             <div class="form-group col-lg-4">
                <strong>采购单结算状态</strong>:
          		@if($model->close_status ==0)
                <select name="close_status">
               		@foreach(config('purchase.purchaseOrder.close_status') as $k=>$val)           	
            			<option value="{{$k}}">{{$val}}</option>
            		@endforeach
                </select>
                @else
                <input name="close_status" type="hidden" value="1"/>
                已结算
                @endif
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
            <strong>采购单运单号</strong>:
                @if(!$model->post_coding)
                暂无运单号
                @else
                {{$model->post_coding}}
                @endif
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运费</strong>:
             @if(!$model->post_coding)
                暂无运费上报
                @else
                {{$model->total_postage}}
                @endif  
            </div>  
		</div>
     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
        <div class="row">
         <div class="form-group col-lg-4">
                <strong>未入库条目</strong>:
            </div>
            </div>
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>采购条目ID</td> 
            <td>采购条目状态</td> 
            <td>SKU</td>       
            <td>采购价格</td>
            <td>采购数量</td>
            <td>总价</td>
            <td>成本审核状态</td>	
            <td>物流单号</td>
            <td>实际入库数量</td>
            <td>供应商sku</td>       
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            <td>
                @foreach(config('purchase.purchaseItem.status') as $key=>$v)
                    @if($purchaseItem->status == $key)
                        {{$v}}
                    @endif
                @endforeach
            </td>
            <td>{{$purchaseItem->sku}}</td>        
             <td>
             {{$purchaseItem->purchase_cost}}
 			</td>
            <td>{{$purchaseItem->purchase_num}}</td>
            <td>
            {{$purchaseItem->purchase_cost * $purchaseItem->purchase_num}}
            </td> 
             <td>
            @if($purchaseItem->costExamineStatus ==2)
            采购价格审核通过
            @elseif($purchaseItem->costExamineStatus ==1)
            采购价格审核不通过
            @else
            采购价格未审核
            @endif
            </td>
            <td>
            {{$purchaseItem->post_coding }}
            </td>       
            <td>
              {{$purchaseItem->storage_qty }}  
            </td>
            <td>{{$purchaseItem->item->supplier_sku}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
    
@stop