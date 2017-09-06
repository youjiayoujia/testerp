@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">产品ID</th>
    <th>图片</th>
    <th>账号</th>   
    <th>SKU</th>
    <th>单价</th> 
    <th>状态</th> 
    <th>标题</th>
    <th>关键词</th>
    <th>操作</th>
@stop
@section('tableBody')
     @foreach($data as $smtProductList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$smtProductList->productId}}"></td>
            <td>{{ $smtProductList->productId }}</td>
            <td>
                <?php
                    if(!empty($smtProductList->details->imageURLs)){
                        $imagesUrlArr = explode(';', $smtProductList->details->imageURLs);
                        $firstImageURL = array_shift($imagesUrlArr);
                    }
                ?>
                 @if(!empty($firstImageURL))
                  <a target="_blank" href="{{ $firstImageURL}}"><img style="width:50px;height:50px;" src="{{ $firstImageURL}}"></a>
                 @endif
            </td>
            <td>{{ $smtProductList->accounts ? $smtProductList->accounts->alias : ''}}</td>
            <td>
                <?php 
                    $skuCodeArr = array();                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                        $skuCodeArr[] = $productSkuItem->skuCode;                       
                    }  
                    echo implode(',', $skuCodeArr);
                ?>            
            </td>
            <td>{{ $smtProductList->productPrice}}</td>
            <td>{{ $smtProductList->productStatusType == 'waitPost' ? '待发布' : '草稿'}}</td>
            <td>{{ $smtProductList->subject }}</td>
      
            
            <td>{{ $smtProductList->details ? $smtProductList->details->keyword : ''}} </td>         
            <td>                                      
               <a href="{{ route('smt.edit', ['id'=>$smtProductList->productId]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
               </a>
               <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $smtProductList->productId }}"
                   data-url="{{ route('smt.destroy', ['id' => $smtProductList->productId]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除                    
               </a>                                        
            </td>
        </tr>
     @endforeach
     <!-- 
     <form name="batchModify" action="{{route('smtProduct.batchModifyProduct',['_token'=>csrf_token()])}}" method="post" target="_blank" onsubmit="openNewSpecifiedWindow('newWindow2')">
		<input type="hidden" name="operateProductIds" value="" id="operateProductIds"/>
		<input type="hidden" name="from" value="draft"/>
	</form>
	-->
@stop
@section('tableToolButtons')
    @if($type == 'waitPost')
        <div class="btn-group">
            <a class="btn btn-success export" href="{{route('smt.index')}}">
                查看草稿列表
            </a>
        </div>                           
     @else
        <div class="btn-group">
            <a class="btn btn-success export" href="{{route('smt.waitPost')}}">
                查看待发布产品列表
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_wait">
             批量保存为待发布
            </a>
        </div>
        
        
     @endif      
        <div class="btn-group">
                <a class="btn btn-success export" href="javascript:" id="batch_modify">
                    批量修改
                </a>
           </div>
     
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_del">
                批量删除
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_post">
                批量发布
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.create') }}">
               新增
            </a>
        </div>
      <!-- 
      <style>
        .red{
        	color:red;
        }
      </style> 
    <div class="modal fade" id="myModalSelect"    tabindex="-1" role="dialog"   aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="text-left modal-title" >批量生产草稿</h4>
    			</div>
    			<div class="modal-body">
    				<form class="form-horizontal" onsubmit="return false;">
    					<div class="form-group">
    						<div class="col-sm-3">
    							<span>SKU:</span> <input type="text" id="skuname" readonly/>
    							<input type="text" id="draft_productid" class="hidden"/>
    						</div>
    
    						<div class="col-sm-4">
    							<span>生成空标题草稿:</span><input type="checkbox" id="empty_biaoti" />
    						</div>
    						 </div>
    					<div class="form-group">
    						<div class="col-sm-5">
    							<span class="red">必填词汇</span><input type="checkbox" id="mustword" /><br/>
    							<div  id ='mustkeyword'>
    							</div>
    							业务新增必填词汇(多个词汇用,隔开):<input id="addmustword" type="text"/>
    							</div>
    
    
    						<div class="col-sm-5">
    							<span class="red"> 选填填词汇</span><input type="checkbox" id="optionword"/><br/>
    							<div  id ='optionkeyword'>
    								</div>
    							业务新增选填词汇(多个词汇用,隔开):<input id="addoptionword" type="text"/>
    						</div>
    					</div>
    
    					<div class="form-group">
    						<div class="col-sm-3">
    							<span class="red">前缀(不带*)</span> <input id="perfectnum" type="text"/>
    						</div>
    					</div>
    
    					<div class="form-group">
    						
    						<div class="col-sm-2">
    						全选:	<input type="checkbox" id="quanxuanzaccount"/>
    						</div>
    							<div class="col-sm-12">
    							<?php
    							/*
    							$newaccont = array();
    							$newaccont = $token;
    							ksort($newaccont);    
    							
    							foreach($newaccont as $key=>$new){ 
    							    echo '<div class ="col-sm-3">';
    							    echo $key.':'.'<input type="checkbox"  name="account"  value="'.$new.'" >';
    							    echo '</div>';
    							}*/
    							?>
    
    						</div>
    					</div>   
    
    					<div class="modal-footer">
    						<a href="#"   class="btn btn-primary " id="accountcheck">确定</a>
    						<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>
    				    </div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>
    -->
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

$("#batch_wait").on('click',function(){
	var product_ids = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');

	if (!product_ids){
		alert('请先选择行', 'alert-warning');
		return false;
	}

	if (confirm('确定要批量保存为待发布?')){
		$.ajax({
			url: "{{route('smt.changeStatusToWait')}}",
			data: {product_ids : product_ids},
			dataType : 'json',
			type : 'get',
			success : function(result){
				window.location.reload();
			}
		})
	}

	
	
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
				var str = '';
				$.each(data,function(name, value){
					if(value.status){
						str = str + value.info + " ";
					}else{
						str =str + value.info + "  ";
					}
				});
				layer.alert(str);
				window.location.reload();
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
		success:function(result){
			alert(result.msg);
			window.location.reload();
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

//批量修改
$('#batch_modify').on('click', function(e){
	var productIds = $('input[name="single[]"]:checked').map(function() {
		return $(this).val();
	}).get().join(',');
	if (productIds == ''){
		layer.msg('请先选择产品');
		return false;
	}

	var url = "{{route('smtProduct.batchModifyProduct')}}";
    window.location.href = url + '?ids=' + productIds + '&type=' + "{{$type}}";
	//赋值下 --选择的产品就是需要批量修改的
	
});

function openNewSpecifiedWindow( windowName )
{
	window.open('',windowName,'width=700,height=400,menubar=no,scrollbars=no');
}

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

//批量生成标题
$('#production_praft').on('click', function(e){
	var i=0;
	var productIds ='';
	$('#skuname').val('');
	$('#addmustword').val('');
	$('#addoptionword').val('');
	$('#perfectnum').val('');

	$('input[name="single[]"]:checked').map(function() {
		 if(i>0){		 
			 return false;
		 }
		productIds =  $(this).val();		
		var SKU = $(this).parent().parent().children().eq(4).text();
		var draft_productid  = $(this).parent().parent().parent().children().eq(1).text();
		 if (productIds == ''){
			 layer.msg('请先选择产品');
			 return false;
		 }

		$.ajax({
			url: '',
			data: 'SKU='+SKU,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){

				$('#mustkeyword').empty().append(data.data[0]);
				$('#optionkeyword').empty().append(data.data[1]);
				$('#skuname').val(data.data[2])
				$('#draft_productid').val(draft_productid);
			}
		});

		$("[name='account']").each(function(){

			if($(this).is(':checked')) {
				$(this).removeProp('checked');
				$(this).prop('checked',false);
			}
		})

		if(	$('#mustword').is(':checked'))
		{
			$('#mustword').removeProp('checked');
			$('#mustword').prop('checked',false);
		}
		if($('#optionword').is(':checked'))
		{
			$('#optionword').removeProp('checked');
			$('#optionword').prop('checked',false);
		}
		if($('#accountcheck').is(':checked'))
		{
			$('#accountcheck').removeProp('checked');
			$('#accountcheck').prop('checked',false);
		}
		$('#myModalSelect').modal({backdrop: 'static', keyboard: false,toggle:true});
		 i++;
	})
});
	
//批量生产草稿
$('#accountcheck').click(function(){
		var accounttext="";
		$('input[name="account"]:checked').each(function() {
			accounttext += ","+$(this).val();
		});
		if(accounttext=='')
		{
			alert('请选择账号');
			return false;
		}

		var sku = $('#skuname').val();
		var addmustword = $('#addmustword').val();
		var addoptionword = $('#addoptionword').val();
		var productid = $('#draft_productid').val();
		var perfectnum = $('#perfectnum').val();
		var mustword="";
		$('input[name="mustword"]:checked').each(function(){
			 mustword= mustword+','+$(this).val();

		});

		var optionword="";
		$('input[name="optionword"]:checked').each(function(){
			 optionword= optionword+','+$(this).val();
		});


		if($('#empty_biaoti').is(':checked')) {
			var empty_biaoti = 'yes';
		}else{
			var empty_biaoti = 'no';
		}

		$.ajax({
			url: "{{ route('smt.batchCreateDraft') }}",
			data: 'sku='+sku+'&addmustword='+addmustword+'&addoptionword='+addoptionword+'&productid='+productid+'&perfectnum='+perfectnum+'&mustword='+mustword+'&optionword='+optionword+'&empty_biaoti='+empty_biaoti+'&accounttext='+accounttext,
			type: 'POST',
			dataType: 'JSON',
			success: function(data){

			alert(data.info);
			$('#myModalSelect').modal('toggle');
				window.location.reload();
			}
		})
	})

</script>
@stop