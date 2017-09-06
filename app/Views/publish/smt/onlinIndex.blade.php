@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>图片</th>
    <th>账号</th>   
    <th>产品ID</th>
    <th>SKU</th>
    <th>标题</th>
    <th>近30天销售</th>
    <th>刊登人员</th>
    <th>刊登时间</th>
    <th>操作</th>
@stop
@section('tableBody')
     @foreach($data as $smtProductList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$smtProductList->productId}}"></td>
            <td>
                <?php
                    if(!empty($smtProductList->details->imageURLs)){
                        $imagesUrlArr = explode(';', $smtProductList->details->imageURLs);
                        $firstImageURL = array_shift($imagesUrlArr);
                    }else {
                         $firstImageURL = '';
                    }
                ?>
                  <a target="_blank" href="{{ $firstImageURL}}"><img style="width:50px;height:50px;" src="{{ $firstImageURL}}"></a>
            </td>
            <td>{{ $smtProductList->accounts ? $smtProductList->accounts->alias : ''}}</td>
            <td>{{ $smtProductList->productId }}</td>
            <td>
                <?php 
                    $skuCodeArr = array();                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                        echo $productSkuItem->skuCode.'<br/>';                       
                    }                                             
                ?>            
            </td>
            <td>{{ $smtProductList->subject }}</td>    
            <td>{{ $smtProductList->quantitySold1 }}</td>       
            <td>{{ $smtProductList->userInfo ? $smtProductList->userInfo->name : ''}}</td>
            <td>{{ $smtProductList->gmtCreate}}</td>
            <td>   
                  
                    <a href="javascript:void(0)"
                       class="btn btn-warning btn-xs" id="editOnline" data-id = "{{$smtProductList->productId}}">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑在线信息
                    </a>
                     @if( $smtProductList->productStatusType == 'onSelling' || $smtProductList->productStatusType == 'offline')
                    <a onclick="operator('<?php echo $smtProductList->productId;  ?>' ,'online',this)" class="btn btn-danger btn-xs  <?php   if($smtProductList->productStatusType=='offline'){echo "hidden"; }      ?>">
                        <span class="glyphicon glyphicon-pencil "></span> 下架
                    </a>

                    <a onclick="operator('<?php echo $smtProductList->productId;  ?>' ,'offline',this)"  class="btn btn-success btn-xs <?php   if($smtProductList->productStatusType=='onSelling'){echo "hidden"; }      ?>">
                        <span class="glyphicon glyphicon-pencil  "></span> 上架
                    </a>
                    @endif
                                    
            </td>
        </tr>
     @endforeach
@stop
@section('tableToolButtons')
         <div class="btn-group">
            <a class="btn btn-success export" href="javascript:void(0)" id="batch_copy">
              另存为草稿
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.index')}}">
            查看草稿列表
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.create') }}">
              批量修改
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:void(0)"  data-toggle="modal"
                    data-target="#myPriceModalSelect">
              同步指定帐号广告
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:void(0)"  id="coyp_account">COPY账号在线广告</a>              
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:void(0)"  data-toggle="modal" data-target="#modifyBand">
             批量修改广告品牌属性
            </a>
        </div>
    <div class="modal fade" id="modifyBand"    tabindex="-1" role="dialog"   aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="text-left modal-title" >批量修改广告品牌属性</h4>
    			</div>
    			<div class="modal-body">
    			     
    			      <form class="form-horizontal" id="form-band" action="{{ route('smtProduct.batchModifyBand') }}" method="POST">
    			      {!! csrf_field() !!}		
    			     <!-- <form class="form-horizontal" id="form-band" action="{{ route('smtProduct.batchModifyBand') }}" method="POST"> -->				
            			<div class="form-group">
    						<label class="col-sm-2">账号:</label>
    						<div class="col-sm-4">
    							<select  id="selectaccount" class="form-control" name="token_id">
    								<option value="">---请选择---</option>
    								@foreach($accountList as $account)
            						    <option value="{{ $account->id }}">{{ $account->account}}</option>'
            						@endforeach
    							</select>
    						</div>
    					</div>
    					<!-- 
    					<div class="form-group">
    					    <label class="col-sm-2">类目属性:</label>
    					    <div class="col-sm-4">
        					    <select  id="selectcategory" class="form-control">
        					       <option value="">---请选择类目---</option>
        					       @foreach($categoryList as $category)
        					            <option value="{{ $category->category_id}}">{{ $category->category_name}}</option>'
        					       @endforeach
    					       </select>
    					   </div>
    					</div>
    					-->
    					<div class="form-group">
    						<label class="col-sm-2">产品分组</label>
    						<div class="col-sm-4">
    							<select  id="groupId3"  class="form-control" name="group_id">
    								<option value="">=所有分组=</option>
    							</select>
    
    						</div>
    					</div>
                        <div class="form-group">
    						<label class="col-sm-2">品牌</label>
    						<div class="col-sm-4">
    							<select  id="band_id"  class="form-control" name="band_id">
    								<option value="">=请选择品牌=</option>
    								<option value="201563181">Favolook </option>
    								<option value="201802818">Faddare  </option>
    								<option value="201569869">Moonar   </option>
    								<option value="201606963">Woopower </option>
    								<option value="201606953">RV77     </option>
    							</select>
    
    						</div>
    					</div>
            
    					<div class="modal-footer">
    					     <button type="submit" class="btn btn-primary" id='modify'>提交</button> 
    					     <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>                                
    						<!--<a href="#"   class="btn btn-primary " id="batchModifyBand">确定</a>-->
    						<!--<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>-->
    					</div>
            	   </form>
    			</div>
    		</div>
    	</div>
    </div>     
   <div class="modal fade" id="myPriceModalSelect"    tabindex="-1" role="dialog"   aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="text-left modal-title" >同步指定账号广告</h4>
			</div>
			<div class="modal-body">
			     <form class="form-horizontal">				
        			<div class="form-group">
						<label class="col-sm-2">账号:</label>
						<div class="col-sm-4">
							<select  id="selectaccount1" class="form-control">
								<option value="">---请选择---</option>
								@foreach($accountList as $account)
        						    <option value="{{ $account->id }}">{{ $account->account}}</option>'
        						@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">产品分组</label>
						<div class="col-sm-4">
							<select  id="groupId4"  class="form-control">
								<option value="">=所有分组=</option>
							</select>

						</div>
					</div>


					<div class="modal-footer">
						<a href="#"   class="btn btn-primary " id="confirm">确定</a>
						<!--<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>-->
					</div>
        	   </form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="coypaccountModalSelect"    tabindex="-1" role="dialog"   aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" >复制信息</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal"  >                
    				<div class="form-group">
    					<label class="col-sm-2">(From)账号</label>
    					<div class="col-sm-4">
    						<select  id="selectaccountfrom" class="form-control">
    							<option value="">---请选择---</option>
    							@foreach($accountList as $account)
        						    <option value="{{ $account->id }}">{{ $account->account}}</option>'
        						@endforeach
    						</select>
    					</div>
    				</div>
    
    				<div class="form-group">
    					<label class="col-sm-2">产品分组</label>
    					<div class="col-sm-4">
    						<select  id="groupId1" class="form-control">
    							<option value="">=所有分组=</option>
    						</select>
    
    					</div>
    				</div>
    
    				<div class="form-group">
    					<label class="col-sm-2">选择分类</label>
    					<div class="col-sm-1">
    						<input type="checkbox" id="needcategoryone"  name="needcategoryone">
    
    					</div>
    				</div>
    
    				<div  class="needcategory hidden">
    					<div class="form-group">
    						<label class="col-sm-2">搜索类目</label>
    						<div class="col-sm-4">
    							<input type="text"  size="15" id="searchcategoryinfo" class="form-control">
    							
    							
    						</div>    						
    						<div class="col-sm-2">
    							<a href="#" class="btn btn-primary btn-xs" id="searchcategory" style="float: left"> 搜索</a>
    						</div>
    					</div>
    
    					<div class="form-group">
    						<label class="col-sm-2">确认类目</label>
    						<div class="col-sm-4">
    							<select id="checkecategory" class="form-control">
    							</select>
    						</div>
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-2">(To)账号</label>
    					<div class="col-sm-4">
    						<select  id="selectaccountto" class="form-control">
    							<option value="">---请选择---</option>
    							@foreach($accountList as $account)
        						    <option value="{{ $account->id }}">{{ $account->account}}</option>'
        						@endforeach
    						</select>
    					</div>
    				</div>
    
    				<div class="form-group">
    					<label class="col-sm-2">产品分组</label>
    					<div class="col-sm-4">
    						<select  id="groupId2" class="form-control">
    							<option value="">=所有分组=</option>
    						</select>						
    					</div>
    					<div class="col-sm-4">
    					  <span style="color:red">请指定产品分组，否则新草稿分组为空</span>
    					</div>
    				</div>
    
    
    				<div class="modal-footer">
    					<a href="#"   class="btn btn-primary " id="copycheck">确定</a>
    					<!--<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>-->
    				</div>
    				<!--<button type="submit" class="btn btn-primary">提交</button>-->
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModalSelect"    tabindex="-1" role="dialog"   aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="text-left modal-title" >速卖通刊登--产品复制</h4>
    			</div>
    			<div class="modal-body">
    				<form class="form-horizontal" onsubmit="return false;">   				    
    					<div class="form-group">    						
    						<div class="col-sm-2">
    						全选:	<input type="checkbox" id="checkAll"/>
    						</div>
    							<div class="col-sm-12">
    							<?php  							
    						  
    							
    							foreach($accountList as $account){ 
    							    echo '<div class ="col-sm-3">';
    							    echo $account->account.':'.'<input type="checkbox"  name="account"  value="'.$account->id.'" >';
    							    echo '</div>';
    							}
    							?>
                                <input type="hidden" id="productIds"/>
    						</div>
    					</div>   
    
    					<div class="modal-footer">
    						<a href="#"   class="btn btn-primary " id="productCopy">确定</a>
    						<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>
    				    </div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>
@stop
@section('childJs')
<link href="{{ asset('plugins/layer/skin/layer.css')}}" type="text/css" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $('.select_all').click(function () {
        if ($(this).prop('checked') == true) {
            $('.single').prop('checked', true);
        } else {
            $('.single').prop('checked', false);
        }
    });
})

$(document).on('click', '#batch_post', function(){
		var productIds = $('input[name="single[]"]:checked').map(function(){
			return $(this).val();
		}).get().join(',');
		if (!productIds){
			alert('请先选择行', 'alert-warning');
			return false;
		}

		if (!confirm('确定要批量发布吗，发布过程中不能操作?')){
			return false;
		}
	
		 $.ajax({
			url: "{{route('smt.batchPost')}}",
			data: 'productIds='+productIds,
			type: 'post',
			dataType: 'json',
			async: true,
			success:function(data){				
				/*if(typeof(data) == "object"){
					data = JSON.stringify(data);					
				}*/
				
				$.each(data,function(name, value){
					if(value.status){
						alert(value.info);
					}else{
						alert(value.info);
					}
				});
				
			}
		});	 

	});

//批量删除
$(document).on('click', '#batch_del', function(){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');

	if (!productIds){
		alert('请先选择行', 'alert-warning');
		return false;
	}

	if (!confirm('确定要删除吗？')){
		return false;
	}

	$.ajax({
		url: "{{route('smt.batchDel')}}",
		data: 'productIds='+productIds,
		type: 'post',
		dataType: 'json',
		async: true,
		success:function(data){
			if(typeof(data) == "string"){
				data = JSON.prase(data);
			}
			if(data.status){
				alert(data.info);
			}
		}
	});	 	
});

function operator(id,type,e){
	layer.confirm("您确定要进行此操作？",function(){
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

$(document).on('click','#editOnline',function(){
	var product_id = $(this).data('id');
	if(confirm('是否要先同步广告，本次同步不计算利润率')){
		$.ajax({
			url: "{{route('smtProduct.synchronizationProduct')}}",
			data: 'product_id='+product_id,
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function(){
				showtips('产品:'+product_id+'同步中...', 'alert-success');
			},
			success: function(data){
				if (data.status){
					showtips('同步成功', 'alert-success');
					targetBtn.trigger('click');
				}else {
					showtips('同步失败'+data.info);
					targetBtn.trigger('click');
				}
			}
		});
	}else{		
		var url = "{{route('smt.editOnlineProduct')}}";
		window.location.href = url + '?id=' + product_id;
	}	
	
})


$(document).on('click', '#batch_copy', function(event) {
		event.preventDefault();
		/* Act on the event */
		var productIds = $('input[name="single[]"]:checked').map(function() {
			return $(this).val();
		}).get().join(',');
		if (productIds == ''){
			alert('请先选择数据');
			return false;
		}
		if (confirm('确定要将选中的广告另存为草稿吗？')) {
			$('#productIds').val(productIds);
			$('#myModalSelect').modal({backdrop: 'static', keyboard: false,toggle:true});
		}
})

$("#productCopy").click(function(){	
	var productIds = $('#productIds').val();
	var accounttext = $("input[name='account']:checked").map(function(){return $(this).val()}).get().join(',');
	/*$('input[name="account"]:checked').each(function() {
		accounttext += ","+$(this).val();
	});*/

	if(!accounttext){
		showtips('请先选择账号!', 'alert-warning');
	}

	$.ajax({
		url: '{{route('smtProduct.copyToDraft')}}',
		data: 'productIds='+productIds+'&tokenIds='+accounttext,
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$('#batch_copy').addClass('productCopy');
		},
		success: function(data){
			var str='';
			if (data.data){
				$.each(data.data, function(index, el){
					str += el+';';
				});
			}
			if (data.status) { //成功
				showxbtips(data.info+str);
				$("#myModalSelect").modal('hide');
			}else {
				showxbtips(data.info+str, 'alert-warning');
			}
		},
		complete: function(){
			$('#productCopy').removeClass('disabled');
		}
	})
	
});

$(document).on('click', '#batch_copy1', function(event) {
		event.preventDefault();
		/* Act on the event */
		var productIds = $('input[name="single[]"]:checked').map(function() {
			return $(this).val();
		}).get().join(',');
		if (productIds == ''){
			alert('请先选择数据');
			return false;
		}
		
		if (confirm('确定要将选中的广告另存为草稿吗？')) {
			//弹出层选择账号
			$.layer({
				type   : 2,
				shade  : [0.8 , '' , true],
				title  : ['选择账号',true],
				iframe : {src : '{{route('smtProduct.showAccountToCopyProduct')}}'},
				area   : ['900px' , '550px'],
				success : function(){
					layer.shift('top', 400)
				},
				btns : 2,
				btn : ['确定', '取消'],
				yes : function(index){ //确定按钮的操作
					var account_list = layer.getChildFrame('.account_list :checked', index).map(function(){
						return $(this).val();
					}).get().join(',');
					console.log(account_list);
					if (account_list != ''){

						$.ajax({
							url: '{{route('smtProduct.copyToDraft')}}',
							data: 'productIds='+productIds+'&tokenIds='+account_list,
							type: 'POST',
							dataType: 'json',
							beforeSend: function(){
								$('#batch_copy').addClass('disabled');
							},
							success: function(data){
								var str='';
								if (data.data){
									$.each(data.data, function(index, el){
										str += el+';';
									});
								}
								if (data.status) { //成功
									showxbtips(data.info+str);
								}else {
									showxbtips(data.info+str, 'alert-warning');
								}
							},
							complete: function(){
								$('#batch_copy').removeClass('disabled');
							}
						});
					}else {
						showtips('请先选择账号', 'alert-warning');
					}
					layer.close(index);
				},
				no: function(index){
					layer.close(index);
				}
			});
		}else{
			return false;
		}
	});

$(document).on('change', '#selectaccount', function(){
	var token_id = $(this).val();
	$('#groupId3').empty();
	if (token_id == ''){ //账号为空，隐藏分组信息
		return false;
	}else {
		//异步获取账号信息
		$.ajax({
			url: "{{ route('smtProduct.showAccountProductGroup') }}",
			data: 'token_id='+token_id,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				if (data.status){
					$('#groupId3').append(data.data);
				}
			}
		});
	}
});
$(document).on('change', '#selectaccount1', function(){
	var token_id = $(this).val();
	$('#groupId3').empty();
	if (token_id == ''){ //账号为空，隐藏分组信息
		return false;
	}else {
		//异步获取账号信息
		$.ajax({
			url: "{{ route('smtProduct.showAccountProductGroup') }}",
			data: 'token_id='+token_id,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				if (data.status){
					$('#groupId4').append(data.data);
				}
			}
		});
	}
});

$('#confirm').click(function(){
	var token_id =$("#selectaccount1").val();
	var groupId3 = $("#groupId4").val();
	if(groupId3=='none')
	{
		alert('无法同步该分组');
		return false;
	}
	$.ajax({
		url: "{{ route('smtProduct.SynchronousDataByAccount') }}",
		data: 'token_id='+token_id+'&groupId3='+groupId3,
		type: 'POST',
		dataType: 'JSON',
		beforeSend: function(){
			 ii = layer.load('更新中。。。');
		},
		success: function(data){
			layer.close(ii);
			$('#myPriceModalSelect').modal('toggle');
			alert(data.info)
		}
	});
});

$('#coyp_account').click(function(){
	$('#coypaccountModalSelect').modal('toggle');
	$("#searchcategoryinfo").val("");

	$("#needcategoryone").removeProp('checked');
	$("#needcategoryone").prop('checked',false);
});

$(document).on('change', '#selectaccountfrom', function(){
		var token_id = $(this).val();
		$('#groupId1').empty();
		if (token_id == ''){ //账号为空，隐藏分组信息
			return false;
		}else {
			//异步获取账号信息
			$.ajax({
				url: "{{ route('smtProduct.showAccountProductGroup') }}",
				data: 'token_id='+token_id,
				type: 'POST',
				dataType: 'JSON',
				success: function(data){
					if (data.status){
						$('#groupId1').append(data.data);
					}
				}
			});
		}
});

$(document).on('change', '#selectaccountto', function(){
	var token_id = $(this).val();
	$('#groupId2').empty();
	if (token_id == ''){ //账号为空，隐藏分组信息
		return false;
	}else {
		//异步获取账号信息
		$.ajax({
			url: "{{ route('smtProduct.showAccountProductGroup') }}",
			data: 'token_id='+token_id,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				if (data.status){
					$('#groupId2').append(data.data);
				}
			}
		});
	}
});

$(document).on('change', '#selectcategory', function(){
	var token_id = $('#selectaccount').val();
	var category_id = $(this).val();
	if(!token_id){
		alert('请先选择帐号');
		return false;
	}else{
		$.ajax({
			url: "{{ route('smtProduct.getCategoryAttributesById') }}",
			data: 'token_id='+token_id+'&category_id='+category_id,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				if (data.status){
					$('#groupId2').append(data.data);
				}
			}
		});	
	}		
});

$('#needcategoryone').click(function(){
	if($('#needcategoryone').is(":checked"))
	{
		$(".needcategory").removeClass("hidden");
	}
	else
	{
		$(".needcategory").addClass("hidden");
	}
})

$('#copycheck').click(function(){
	var token_id_from = $('#selectaccountfrom').val();
	var token_id_to = $('#selectaccountto').val();
	var groupId1 = $('#groupId1').val();
	var groupId2 = $('#groupId2').val();
	var	checkecategory='';
	if($('#needcategoryone').is(":checked")){
		var	 checkecategory = $("#checkecategory").val();
	}

	if(token_id_from==''||token_id_to==''||(token_id_from==token_id_to)){
		alert("请选择账号,两个账号不能相同");
		return false;
	}

	$.ajax({
		url: "{{ route('smtProduct.copyAllAccountNew') }}",
		data: 'token_id_from='+token_id_from+'&token_id_to='+token_id_to+'&groupId1='+groupId1+'&groupId2='+groupId2+'&checkecategory='+checkecategory,
		type: 'POST',
		dataType: 'JSON',
		beforeSend: function(){
			ii = layer.load('更新中。。。');
		},
		success: function(data){
			layer.close(ii);
			if(data.status){
				alert("复制完成");
			}else{
				alert(data.data);
			}
			$('#coypaccountModalSelect').modal('toggle');
		}
	});
})
	
$("#searchcategory").click(function(){
	var searchcategoryinfo = $("#searchcategoryinfo").val();
	if (searchcategoryinfo == ""){
		alert("请填写分类信息");
		return false;
	}
	$.ajax({
		url: "{{ route('smtProduct.getCategoryInfo') }}",
		data: 'searchcategoryinfo='+searchcategoryinfo,
		type: 'POST',
		dataType: 'JSON',

		success:function(data){
			$('#checkecategory').empty();
			$('#checkecategory').append(data.info);
		}
	})
})

$("#batchModifyBand").click(function(){
	var band_id = $("#band_id").val();
	var group_id = $("#groupId3").val();
	var token_id = $("#selectaccount").val();
	if(!band_id){
		alert("请选择品牌！");
		return false;
	}
	if(!group_id){
		alert("请选择分组！");
		return false;
	}
	$.ajax({
		url: "{{ route('smtProduct.batchModifyBand') }}",
		data: {'token_id':token_id,'group_id':group_id,'band_id':band_id},
		type: "POST",
		dataType:"JSON",
		beforeSend: function(){
			 ii = layer.load('更新中。。。');
		},
		success:function(data){
			alert(data.info);
			$('#modifyBand').modal('hide');
		}
	})
})

$('#modify').click(function () {
	$('#modify').addClass('disabled');
})

$(document).on('change', '#select2-mixedSearchFieldsfilterSelectstoken_id-rs-container', function(){
	var token_id = $(this).val();
	$.ajax({
		url: "{{ route('smtProduct.showAccountProductGroup') }}",
		data: 'token_id='+token_id,
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			if (data.status){
				$('#select2-mixedSearchFieldsfilterSelectsgroupId-em-container').append(data.data);
			}
		}
	});	
})

 $('#checkAll').click(function(){
            this.checked ? $("[name='account']").prop('checked', true) : $("[name='account']").prop('checked', false);
        });
$('#quanxuanzaccount').click(function(){
	$("[name='account']").each(function(){

		if($(this).is(':checked')) {
			$(this).removeProp('checked');
			$(this).prop('checked',false);
		}
		else
		{
			$(this).prop("checked",true);//全选
		}
	})
})

</script>
@stop