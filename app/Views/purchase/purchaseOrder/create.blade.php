@extends('common.form')
@section('formAction') {{ route('purchaseOrder.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')

<div class="panel panel-default">
	<div class="panel-heading">采购单信息 :</div>
		<div class="panel-body">
			<div class='row'>
				<div class="form-group col-md-3">
		            <label for="size">订单类型</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
		            <select  class="form-control" name="type">
		                @foreach(config('purchase.purchaseOrder.type') as $key=>$v)
			                <option value="{{$key}}">{{$v}}</option>
		                @endforeach
		            </select>
			    </div>
			    <div class="form-group col-md-3">
		            <label for="size">付款方式</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
		            <select  class="form-control" name="purchase_type">
		                @foreach(config('purchase.purchaseOrder.pay_type') as $key=>$paytype)
		                	<option value="{{ $key }}">{{$paytype}}</option>
		                @endforeach
		            </select>
			    </div>
			    <div class="form-group col-md-3">
			        <label for="size">供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
			        <select class='form-control supplier' id="supplier_id" name="supplier_id"></select>
			    </div>
			   
			    <div class="form-group col-md-3">
			            <label for="size">仓库</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
			            <select  class="form-control" name="warehouse_id">
			                @foreach($warehouses as $warehouse)
			                    <option value="{{ $warehouse->id }}">{{$warehouse->name}}</option>
			                @endforeach
			            </select>
			    </div>
			    <!-- <div class="form-group col-md-3">
			        <label for="size">辅供应商货号</label>
			        <input class="form-control" id="second_supplier_sku" placeholder="辅供应商货号" name='second_supplier_sku' value="{{ old('second_supplier_sku') }}">
			    </div> -->
			    
			</div>

			<div class='row'>
			    <div class="form-group col-md-3">
			 		<label for="size">物流方式</label><small class="text-danger glyphicon glyphicon-asterisk"></small>			 
			    	<select  class="form-control" name="carriage_type">
			    		@foreach(config('purchase.purchaseOrder.carriage_type') as $key=>$v)
			                <option value="{{$key}}">{{$v}}</option>
		                @endforeach
		            </select>
			    </div>
			    <div class="form-group col-md-3">
			        <label for="size">运费</label>
			        <input class="form-control" id="second_supplier_sku" placeholder="运费" name='total_postage' value="">
			    </div>
			   <div class="form-group col-md-3">
			        <label for="size">外部单号</label>
			        <input class="form-control" id="second_supplier_sku" placeholder="外部单号" name='post_coding' value="">
			    </div>

			    <div class="form-group col-md-3">
			        <label for="size">付款凭证</label>
			         <select  class="form-control" name="is_certificate">
						 <option value="0">不需要</option>
						 <option value="1">需要</option>
		            </select>
			    </div>
			    
			    <div class="form-group col-md-3">
			        <label for="size">备注</label>
			        <input class="form-control" id="remark" placeholder="备注" name='remark' value="">
			    </div>
			</div>
		</div>
</div>


	<div class="panel panel-default">
		<div class="panel-heading">采购物品信息 :</div>
		<div class="panel-body">
			<div class='row  purchase_num'>
			    <div class="form-group col-md-3">
			        <label for="size">SKU</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
			        <select class='form-control sku' name="item[0][sku]"></select>
			    </div>
			    
			    <div class="form-group col-md-3">
			        <label for="size">数量</label>
			        <input class="form-control"  placeholder="数量" name='item[0][purchase_num]' value="">
			    </div>
			    <div class="form-group col-md-3">
			        <label for="size">单价</label>
			        <input class="form-control"  placeholder="单价" name='item[0][purchase_cost]' value="" title='0'>
			    </div>
				
			</div>
			<div class="panel-footer">
            	<div class="create" id="additem"><i class="glyphicon glyphicon-plus"></i></div>
        	</div>
        </div>
		</div>
	</div>
    
@stop

@section('pageJs')
	<script type="text/javascript">
		$('.sku').select2({
	        ajax: {
	            url: "{{ route('purchaseAjaxSku') }}",
	            dataType: 'json',
	            delay: 250,
	            data: function (params) {
	              return {
	                user:params.term,
	                supplier_id: $("#supplier_id").val(),
	              };
	            },
	            results: function(data, page) {
	                
	            }
	        },
		});

		$('.supplier').select2({
	        ajax: {
	            url: "{{ route('ajaxSupplier') }}",
	            dataType: 'json',
	            delay: 250,
	            data: function (params) {
	              return {
	                supplier:params.term,
	              };
	            },
	            results: function(data, page) {
	                
	            }
	        },
    	});
    	{{-- 添加采购物品  --}}
        $(document).on('click', '#additem', function () {
            var num = $("input[name^='item[']:last").attr('title');
            num = parseInt(num);
            num = num + 1;

            $(".purchase_num").last().after('<div class="row  purchase_num"><div class="form-group col-md-3"><label for="size">SKU</label><small class="text-danger glyphicon glyphicon-asterisk"></small><select class="form-control sku" name="item['+num+'][sku]"></select></div><div class="form-group col-md-3"><label for="size">数量</label><input class="form-control"  placeholder="数量" name="item['+num+'][purchase_num]" value=""></div><div class="form-group col-md-3"><label for="size">单价</label><input class="form-control"  placeholder="单价" name="item['+num+'][purchase_cost]" value="" title="'+num+'"></div><button type="button" class="btn btn-danger bt_right"><i class="glyphicon glyphicon-trash"></i></button></div>');
        	$('.sku').select2({
		        ajax: {
		            url: "{{ route('purchaseAjaxSku') }}",
		            dataType: 'json',
		            delay: 250,
		            data: function (params) {
		              return {
		                user:params.term,
		                supplier_id: $("#supplier_id").val(),
		              };
		            },
		            results: function(data, page) {
		                
		            }
		        }
			});
       });

		$(document).on('click', '.bt_right', function () {
			$(this).parent().remove();
			current--;
			if(current < 1) {
				$('.sub').prop('disabled', true);
				alert('请输入sku');
			}
		});
	</script>
@stop
<style type="text/css">
    .bt_right{
        margin-top: 22px;
    }
</style>