@extends('common.detail')
@section('detailBody')

<div class="panel panel-default">
        <div class="panel-heading">单头</div>
        <div class="panel-body">
             <div class="form-group col-lg-4">
                <strong>采购仓库</strong>:
                {{ $model->warehouse?$model->warehouse->name:''}}
            </div>
            <div class="form-group col-lg-4">
                <strong>仓库地址</strong>:
                {{ $model->warehouse?$model->warehouse->province:''}}{{ $model->warehouse?$model->warehouse->city:''}}{{ $model->warehouse?$model->warehouse->address:''}}
            </div>
            
             <div class="form-group col-lg-4">
                <strong>采购单ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
            	<strong>供应商信息</strong>:
                名：{{$model->supplier?$model->supplier->name:''}}&nbsp;电话：{{$model->supplier?$model->supplier->telephone:''}} &nbsp;地址：{{$model->supplier?$model->supplier->province:''}}{{$model->supplier?$model->supplier->city:''}}{{$model->supplier?$model->supplier->address:''}}
                &nbsp;
                @if($model->supplier)
                    @if($model->supplier->type==1)
                	   线上采购
                    @else
                	   线下采购
                    @endif
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
                <strong>采购人</strong>:
                {{$model->assigner}}
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
          
            <div class="form-group col-lg-4">
                <strong>采购单审核状态</strong>:     
            	@if($model->examineStatus == 0)
                    未审核
                 @elseif($model->examineStatus == 1)
                 审核通过
                 @elseif($model->examineStatus == 2)
                 待复审
                 @else
                 审核不通过
                @endif
            </div>   
            <div class="form-group col-lg-4">
            	<strong>采购单结算状态</strong>:
                @if($model->close_status ==0)
                	未结算
                @else
                已结算
                @endif    
            </div>        
        </div>
		</div>
     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
        
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>{{$model->arrival_day}}
            <td>SKU</td> 
            <td>物品名称</td>
            <td>采购数量</td> 
            <td>已到货数量</td> 
            <td>入库数量</td>
            <td>不合格数量</td>
            <td>预计到达日期</td>
            <td>实际到货日期</td>
            <td>状态</td>
            <td>单价</td>
            <td>系统采购价格</td>
            <td>小计</td> 
            <td>入库金额</td>
            <td>审单备注</td>            
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)  
        <tr> 
            <td>{{$purchaseItem->sku}}</td>
            
            <td>{{$purchaseItem->item?$purchaseItem->item->c_name:''}}</td>
            <td>{{$purchaseItem->purchase_num}}</td>
            <td>{{$purchaseItem->arrival_num}}</td>   
            <td>
           {{$purchaseItem->storage_qty}}
            </td>
            <td>       	
            {{$purchaseItem->arrival_num - $purchaseItem->storage_qty}}
             </td>
            <td>
            {{$model->arrival_day}}
            </td>
            <td>
              {{$purchaseItem->arrival_time}}
 			</td>
            <td>{{config('purchase.purchaseItem.status')[$purchaseItem->status]}}</td>
            <td>
           {{$purchaseItem->purchase_cost}}
            </td>    
          
             <td>
            	 {{$purchaseItem->item?$purchaseItem->item->purchase_price:''}}
            </td> 
            <td>
           {{$purchaseItem->purchase_num * $purchaseItem->purchase_cost}}
            </td> 
			<td>
           {{$purchaseItem->storage_qty * $purchaseItem->purchase_cost}}
            </td>
            <td>
           {{$purchaseItem->remark}}
            </td>
        </tr>
        @endforeach
       <tr>
       <td colspan="2"><strong>合计：</strong></td>
       <td>{{$purchaseItemsNum}}</td>
       <td>{{$purchaseItemsArrivalNum}}</td>
       <td>{{$storage_qty_sum}}</td>
       <td>{{$purchaseItemsArrivalNum - $storage_qty_sum}}</td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td>{{$purchaseCost}} + YF{{$postage ? $postage : 0}}={{$purchaseCost + $postage}}</td>
       <td>{{$storageCost}} + YF{{$postage ? $postage : 0}}={{$storageCost + $postage}}</td>
       <td></td>
       </tr>
        <tr>
            <td>订单总金额：</td>
            <td colspan="13">{{$purchaseCost + $postage}}</td>
        </tr>
        <tr>
            <td>订单运费：</td>
            <td colspan="13">{{$storageCost + $postage}}</td>
        </tr>
        <tr>
            <td>采购员：</td>
            <td colspan="13">{{$model->assigner_name}}</td>
        </tr>
    </tbody>
    </table>
   
        </div>
    </div>
     <div class="panel panel-default">
        <div class="panel-heading">尾部</div>
        <div class="row">
            <div class="form-group col-lg-4">
                <strong>采购日期</strong>:
                 <input type="button" value="打印" onclick="window.print();"/> 
            </div>
            <div class="form-group col-lg-4">
                <strong>打印日期</strong>:
                <?php echo date('Y-m-d h:i:s',time());?>
            </div>
             <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                {{$model->assigner}}
            </div>
         </div>   
    </div>
@stop
