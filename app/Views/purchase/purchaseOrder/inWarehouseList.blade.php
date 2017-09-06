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
		<th colspan="3">查看采购单详情：采购单号<span id="ajaxp_id" value="{{$id}}">{{$id}}</span></th>
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
					<td>选择</td>
					<td>图片</td>
					<td>物品sku</td>
					<td>物品名称</td>
					<td>注意事项</td>
					<td>到货</td>
					<td>优品</td>
					<td>复审</td>
				</tr>
			<?php $i=0; ?>
			@foreach($purchase_order->purchaseItem as $item)
				@foreach($item->arrivalLog as $log)
					<tr>
						<td><input type="checkbox" {{$log->good_num?'disabled':''}}></td>
						<td><img src="{{ asset($item->productItem->product->dimage) }}" width="100px"></td>
						<td>{{$item->sku}}</td>
						<td>{{$item->item->name}}<br><span style="color:gray">到货时间：{{$log->created_at}}</span><br><span style="color:gray">质检时间：{{$log->quality_time}}</span></td>
						<td></td>
						<td><input type="text" name="arrival_num_{{$log->id}}" id="arrival_num_{{$log->id}}" value="{{$log->arrival_num}}"></td>
						<td><input type="text" {{$log->good_num?'disabled':''}} name="goodnum_{{$log->id}}" id="goodnum_{{$log->id}}" value="{{$log->good_num}}"></td>
						<td><input type="text" {{$log->good_num?'disabled':''}} name="badnum_{{$log->id}}" id="badnum_{{$log->id}}" value="{{$log->bad_num}}"></td>
					</tr>
					<?php $i++; ?>
				@endforeach
			@endforeach
			</table>
		</td>
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
		<td colspan="2">
			<button class="modify">修改</button>
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
        var p_id = "{{$id}}";
        var url = "{{ route('updateArriveLog') }}";
        var flag = 0;
        var num_contorl = 0;
		$("input[name^='goodnum_']").each(function(){
			id = $(this).attr("name");
            if($(this).val()!=0&&($(this).attr("disabled")!='disabled')){
                id = id.substr(8);
                if($("#goodnum_"+id).val()!=$("#badnum_"+id).val()){
					flag = 1;
				}
				if(parseInt($("#arrival_num_"+id).val())<parseInt($("#goodnum_"+id).val())){
					num_contorl = 1;
				}
                data += id+":"+$(this).val();
                data +=":"+$('#badnum_'+id).val()+",";
            }   
　　　　});
		if(data==''){
			alert('该采购单已经全部入库');location.href=history.go(-1);return;
		}
		if(flag==1){
			alert('两次输入数量不一致');return;
		}
		if(num_contorl==1){
			alert('优品数量大于到货数量');return;
		}
		
		window.location.href=url+"?data="+data+"&p_id="+p_id;                    
	});
</script>

