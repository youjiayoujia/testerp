@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>账号</th>
    <th>产品ID</th>
    <th>标题</th>
    <th>erp Sku</th>
    <th>smt Sku</th>
    <th>物品名称</th>
    <th>近30天销量</th>
    <th>刊登时间</th>
    <th>刊登人员</th>
    <th>状态</th>
    <th>平台状态</th>
    <th>价格</th>
    <th>订单利润率</th>
    <th>最低价</th>
    <th>折扣率</th>
    <th>有货/无货</th> 
    <th>操作</th>
@stop
@section('tableBody')    
      @foreach($data as $item)
      <tr>
        <th><input type='checkbox' name='single[]' class='single' data-productId="{{$item->productId}}"   value="<?php echo $item->productId . ',' .  $item->smtSkuCode;?>"></th>
        <th>{{$item->product->accounts ? $item->product->accounts->alias : ''}}</th>
        <th><a href="{{$item->product->product_url}}" target="_Blank">{{$item->productId}}</a></th>
        <th>{{$item->product->subject}}</th>
        <th>{{$item->skuCode}}</th>
        <th>{{$item->smtSkuCode}}</th>
        <th>{{$item->products ? $item->products->c_name : '' }}</th>
        <th>{{$item->product->quantitySold1}}</th>
        <th>{{$item->product->gmtCreate}}</th>
        <th>{{$item->product->userInfo ? $item->product->userInfo->name : ''}}</th>
        <th>
            <?php 
                $status = $item->productItem ? $item->productItem->status : '';
                switch($status){
                    case 'selling' :        echo '在售';break;
                    case 'sellWaiting':     echo '待售';break;
                    case 'cleaning':        echo '清库存中';break;
                    case 'stopping':        echo '停产';break;
                    case 'saleOutStopping': echo '卖完下架';break;
                    case 'unSellTemp':      echo '货源待定';break;
                    case 'trySale':         echo '试销(卖多少采多少)';break;
                    default:                echo ' ';break;
                }
            ?>
        </th>
        <th>{{$item->product->productStatusType}}</th>
        <th>{{$item->skuPrice}}
            <button class="btn btn-primary btn-xs"
                data-toggle="modal"
                data-target="#setSkuPrice{{$item->productId}}"
                title="设置">
            <span class="glyphicon glyphicon-link"></span> 
            </button>
        </th>
        <th>{{$item->profitRate}}</th>
        <th>{{$item->lowerPrice}}</th>
        <th>{{$item->discountRate}}</th>
        <th>{{$item->ipmSkuStock}}
            <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setSkuStock{{$item->productId}}"
                    title="设置">
                <span class="glyphicon glyphicon-link"></span> 
            </button>
        <th>
            <a onclick="operator('<?php echo $item->productId;  ?>' ,'online',this)" class="btn btn-danger btn-xs  <?php   if($item->product->productStatusType=='offline'){echo "hidden"; }      ?>">
                <span class="glyphicon glyphicon-pencil "></span> 下架
            </a>

            <a onclick="operator('<?php echo $item->productId;  ?>' ,'offline',this)"  class="btn btn-success btn-xs <?php   if($item->product->productStatusType=='onSelling'){echo "hidden"; }      ?>">
                <span class="glyphicon glyphicon-pencil  "></span> 上架
            </a>
         </th>
      </tr>
      <div class="modal fade" id="setSkuPrice{{$item->productId}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('smtMonitor.editSingleSkuPrice')}}" method="POST">
                {!! csrf_field() !!}
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">修改在线产品价格</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>修改SKU: {{$item->smtSkuCode}}  的价格</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        </div>
                        <div class="form-group col-lg-4">
                        <label for="account" class='control-label'>按</label>
                            <select name="type">
                                <option value="amount">金额</option>
                                <option value="realprice">固定价格</option>
                                <option value="percent">百分比</option>                                    
                            </select>                              
                            <label for="account" class='control-label'>增加</label>
                         </div>
                        <div class="form-group col-lg-4">
                            <input type='text' class="form-control"  name="skuPrice">
                            <input type="hidden" name="productId" value="{{$item->productId}}">
                            <input type="hidden" name="account_id" value="{{$item->product->token_id}}">
                            <input type="hidden" name="skuId" value="{{$item->sku_active_id}}">
                            <input type="hidden" name="smtSkuCode" value="{{$item->smtSkuCode}}">
                        </div>
                    </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
               </form>
            </div>
        </div>
      </div>      
               
      <div class="modal fade" id="setSkuStock{{$item->productId}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('smtMonitor.editSingleSkuStock')}}" method="POST">
                {!! csrf_field() !!}
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">修改在线产品可售库存</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="account" class='control-label'>修改SKU: {{$item->smtSkuCode}}  的在线可售库存</label>
                                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                <input type='text' class="form-control" placeholder="0~99999之间"  name="ipmSkuStock">
                                <input type="hidden" name="productId" value="{{$item->productId}}">
                                <input type="hidden" name="account_id" value="{{$item->product->token_id}}">
                                <input type="hidden" name="skuId" value="{{$item->sku_active_id}}">
                            </div>
                     </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
               </form>
            </div>
        </div>
      </div>       
      @endforeach     
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" onclick="setProductSkuPrice()">修改价格</a></li>
            <li><a href="javascript:" data-type="set_sku_stock_false" onclick="setProductSkuStockStatus(this)">批量调无货</a></li>
            <li><a href="javascript:" data-type="set_sku_stock_true" onclick="setProductSkuStockStatus(this)">批量调有货</a></li>
            <li><a href="javascript:" data-type="offline" onclick="changeProductStatus(this)">批量下架</a></li>
            <li><a href="javascript:" data-type="online"  onclick="changeProductStatus(this)">批量上架</a></li>
            <li><a href="javascript:" onclick="setProductSkuStock()">批量调可售库存</a></li>
        </ul>
    </div>
    <div class="modal fade" id="setSkuPrice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('smtMonitor.batchEditSkuPrice')}}" method="POST">
                {!! csrf_field() !!}
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">批量修改在线产品价格</h4>
                </div>
                <div class="modal-body">
                    <div class="row">                      
                        <div class="form-group col-lg-4">
                        <label for="account" class='control-label'>按</label>
                            <select name="type">
                                <option value="amount">金额</option>
                                <option value="realprice">固定价格</option>
                                <option value="percent">百分比</option>                                    
                            </select>                              
                            <label for="account" class='control-label'>增加</label>
                         </div>
                        <div class="form-group col-lg-4">
                            <input type='text' class="form-control"  name="skuPrice">                          
                            <input type="hidden" name="products">
                        </div>
                    </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
               </form>
            </div>
        </div>
      </div>      
    <div class="modal fade" id="setSkuStock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('smtMonitor.batchEditSkuStock')}}" method="POST">
                {!! csrf_field() !!}
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">批量修改在线产品可售库存</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <div class="form-group col-lg-12">                              
                                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                <input type='text' class="form-control" placeholder="0~99999之间" id="skuStock" name="impSkuStock">
                                <input type="hidden" name="products">                                
                            </div>
                     </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
               </form>
            </div>
        </div>
      </div>       
    <!-- 
    <div class="btn-group" role="group">
        <input class="form-control lr" id="lr" placeholder="价格" name="lr">
    </div>
    <div class="btn-group" role="group">
        <select class="form-control sx" name="sx" id="sx">
            <option value="null">价格筛选</option>
            <option value="equal">等于</option>
            <option value="less">小于</option>
            <option value="greater ">大于</option>
            <option value="between">区间</option>
        </select>
    </div>
    
      <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="manual_update">
               手动更新
            </a>
        </div>-->
@stop
@section('childJs')
<link href="{{ asset('plugins/layer/skin/layer.css')}}" type="text/css" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
	 $('.sx').change(function () {
         var lr = $('.lr').val();
         if (lr == '') {
             alert('请输入价格!');
             $('.sx').val('null');
         }else {
             var sx = $('.sx').val();
             if (sx != null) {
                 location.href = "{{ route('smtMonitor.index') }}?sx=" + sx +"&lr=" + lr;
             }
         }
     });
});
function operator(id,type,e){
	var msg;
    if (type == 'online') {
        msg = '亲，您真的想让商品：' + id + ' 下架吗？';
    } else if (type == 'offline') {
        msg = '亲，您真的想让商品：' + id + ' 上架吗？';
    }
	layer.confirm(msg,function(){
		 $.ajax({
		        url : "{{ route('smt.ajaxOperateOnlineProduct') }}",
		        data : {id:id,type:type},
		        dataType : 'json',
		        type : 'get',
		        success : function(result) {
			        if(typeof(result) == 'string'){
				        result = JSON.parse();
			        }
		            if(result.status==1){
		                if(type=='online'){
		                    $(e).next().removeClass('hidden');
		                    $(e).addClass('hidden');
		                }
		                if(type=='offline'){
		                    $(e).prev().removeClass('hidden');
		                    $(e).addClass('hidden');
		                }
		                
		                layer.alert(result.info);
		                parent.location.reload();
		            }else{
		            	layer.alert(result.info);		            	
		            }
		        }
		    });
	});   
}

$("#manual_update").click(function () {
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(' ');

	//var token = '<?php echo csrf_token(); ?>';
	if (!productIds){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}
	$.ajax({
        url: "{{ route('smtMonitor.manualUpdateProductInfo') }}",
        data: {productIds: productIds},
        dataType: 'json',
        type: 'post',
        //async: false,
        success: function (result) {
            console.log(result);
            //alert(result.Msg);
            //window.location.reload();
        }
    });
});



function changeProductStatus(obj){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).attr('data-productId');
	}).get().join(',');
	if (!productIds){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}

	var type = $(obj).data('type');
	$.ajax({
		url: "{{ route('smtMonitor.ajaxOperateOnlineProductStatus') }}",
        data: {productIds: productIds,type:type},
        dataType: 'json',
        type: 'post',
        beforeSend: function(){
        	layer.alert("正在批量下架，请稍等......！请不要刷新页面！！！");        	
        },
        success: function (result) {  
            $.each(result,function(index,value){
            	alert(value.Msg);
             });         	         
            window.location.reload();
        }
	});
}

function setProductSkuStock(){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(' ');
	
	if (!productIds){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}
	$('input[name="products"]').val(productIds);

	$('#setSkuStock').modal({
		keyboard: false
	});
}

function setProductSkuPrice(){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(' ');
	
	if (!productIds){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}
	$('input[name="products"]').val(productIds);

	$('#setSkuPrice').modal({
		keyboard: false,
	});
}

function setProductSkuStockStatus(obj){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');
	if (!productIds){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}

	var type = $(obj).data('type');
	$.ajax({
		url: "{{ route('smtMonitor.ajaxOperateProductSkuStockStatus') }}",
        data: {productIds: productIds,type:type},
        dataType: 'json',
        type: 'post',
        beforeSend: function(){
        	layer.alert("正在批量操作，请稍等......！请不要刷新页面！！！");        	
        },
        success: function (result) {  
            $.each(result,function(index,value){
            	alert(value.Msg);
             });         	         
            window.location.reload();
        }
	});
}
</script>
@stop