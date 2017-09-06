@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">单头</div>
        <div class="row">
        <div class="form-group col-lg-4">
        <strong>标题</strong>：无锡择尚采购单
        </div>
            <div class="form-group col-lg-4">
                <strong>仓库</strong>:
                <select id="checkWarehouse" name="checkWarehouse" onChange="checkWarehouse(this.id)">
                <option value="0" selected>请选择仓库</option>
                	@foreach($warehouses as $key=>$v)
                    	<option value="{{$v->warehouse_id}}">{{$v->warehouse->name}}</option>
                    @endforeach
                </select>
            </div>
             <div class="form-group col-lg-4" >
                <strong>仓库信息</strong>:
                 <span id="warehouseAddress"></span>
            </div>
         </div>   
    </div>
     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div  id="purchaseItemList">
          
            
         </div>   
    </div>
        <div class="panel panel-default">
        <div class="panel-heading">尾部</div>
        <div class="row">
            <div class="form-group col-lg-4">
                <strong>采购日期</strong>:
                
            </div>
            <div class="form-group col-lg-4">
                <strong>打印日期</strong>:
                <?php echo date('Y-m-d h:i:s',time());?>
            </div>
             <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                {{$assigner}}
            </div>
         </div>   
    </div>
    
 <script type="text/javascript">
 	function checkWarehouse(x){
		var  warehouseId=$("#checkWarehouse").val();
		$.ajax({
                    url:'/checkWarehouse/address',
                    data:{warehouseId:warehouseId},
                    dataType:'html',
                    type:'get',
                    success:function(result){
                    if(result!=0){ 
					$("#warehouseAddress").html(result);
					}
                    }                    
                })
		$.ajax({
                    url:'/checkWarehouse',
                    data:{warehouseId:warehouseId},
                    dataType:'html',
                    type:'get',
                    success:function(result){
                       if(result==0){
						   $("#purchaseItemList").html('');
						   }else{
							   $("#purchaseItemList").html(result);
							   }
                    }                    
                })
		}
 </script>
    
@stop
