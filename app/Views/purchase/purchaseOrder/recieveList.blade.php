<script type="text/javascript" src="../../js/jquery.min.js"></script>
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
<br><br><br>
<!-- Table goes in the document BODY -->
<table class="gridtable">
	<tr>
		<th colspan="3">查看采购单详情：采购单号<span>{{$id}}</span></th>
	</tr>
	<tr>
		<td>下单时间</td>
		<td>{{$purchase_order->created_at}}</td>
	</tr>
	<tr>
		<td>付款方式</td>
		<td>{{config('purchase.purchaseOrder.pay_type')[$purchase_order->pay_type]}}</td>
	</tr>
	<tr>
		<td>付款凭证</td>
		<td>{{$purchase_order->is_certificate?'不需要':'需要'}}</td>
	</tr>
	<tr>
		<td>入库仓库</td>
		<td>{{$purchase_order->warehouse?$purchase_order->warehouse->name:''}}</td>
	</tr>
	<tr>
		<td>订单详情</td>
		<td>
			<table class="gridtable">
			<tr>
				<th>图片预览</th>
				<th>SKU</th>
				<th>名称</th>
				<th>注意事项</th>
				<th>采购数量</th>
				<th>已到货</th>
				<th>实到</th>
				<th>打印条码</th>
			</tr>
			@foreach($purchase_order->purchaseItem as $keys=>$item)
				<tr>
					<td><img src="{{ asset($item->productItem->product->dimage) }}" width="100px"></td>
					<td>{{$item->sku}}</td>
					<td>{{$item->item->name}}</td>
					<td></td>
					<td>{{$item->purchase_num}}</td>
					<td>{{$item->arrival_num}}</td>
					<td><input type="text" value="0" id="arrivenum_{{$item->id}}" name="arrivenum_{{$item->id}}"></td>
					<td><button class="printpo" value="{{$item->id}}">打印</button></td>
				</tr>
			@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td>订单运费</td>
		<td>RMB {{$purchase_order->total_postage}} 元人民币</td>
	</tr>
	<tr>
		<td>订单总金额</td>
		<td>RMB {{$total_price+$purchase_order->total_postage}} 元人民币</td>
	</tr>
	<tr>
		<td>订单备注</td>
		<td>{{$purchase_order->remark}}</td>
	</tr>
	<tr>
		<td>订单采购员</td>
		<td>{{$purchase_order->purchaseUser?$purchase_order->purchaseUser->name:''}}</td>
	</tr>
	<tr>
		<td>收货记录</td>
		<td>
			<table class="gridtable">
				<tr>
					<td>序号</td>
					<td>编码</td>
					<td>到货</td>
					<td>时间</td>
					<td>优品</td>
					<td>误差</td>
					<td>质检</td>
					<td>跟踪</td>
				</tr>
			<?php $i=0; ?>
			@foreach($purchase_order->purchaseItem as $item)
				@foreach($item->arrivalLog as $log)
					<tr>
						<td>{{$i+1}}</td>
						<td>{{$log->sku}}</td>
						<td>{{$log->arrival_num}}</td>
						<td>{{$log->created_at}}</td>
						<td>{{$log->good_num}}</td>
						<td>{{$log->bad_num}}</td>
						<td>{{$log->quality_time}}</td>
						<td></td>
					</tr>
					<?php $i++; ?>
				@endforeach
			@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<button class="modify">修改</button>
			<button class="modify" value="all">设置全部到货</button>
			<button class="back" onclick="back()">返回</button>
		</td>
	</tr>
</table>


<script type='text/javascript'>
	function back(){
		window.location.href="recieve";
	}

 	$(document).on('click','.printpo',function(){
        var purchase_item_id = $(this).val();
        var url = "{{route('printpo')}}"
        window.open(url+"?id="+purchase_item_id);
        
    });

	$(document).on('click','.modify',function(){
        var data = "";
        var all = $(this).val();
        var p_id = "{{$id}}";
        var url = "{{ route('updateArriveNum') }}";
        if(all==''){
            $("input[name^='arrivenum_']").each(function(){
                id = $(this).attr("name");
                id = id.substr(10);
                if($("#arrivenum_"+id).val()>0){
                    data += id+":"+$(this).val()+",";
                }
　　　　    });
            
        }
		window.location.href=url+"?data="+data+"&p_id="+p_id;
		/*return;
        $.ajax({
            url:"{{ route('updateArriveNum') }}",
            data:{data:data,p_id:$("#ajaxp_id").val()},
            dataType:'json',
            type:'get',
            async:true,
            success:function(result){
                javascript:document.getElementById("p_id").focus();
                var e = jQuery.Event("keydown");//模拟一个键盘事件
                e.keyCode =13;//keyCode=13是回车
                $("#p_id").trigger(e);
                javascript:document.getElementById("p_id").focus();
                javascript:document.getElementById('ajaxp_id').value = result;
            }
        });   */                      
	});
</script>



