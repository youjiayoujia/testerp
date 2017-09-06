@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>账号</th>
    <th>ItemNumber</th>
    <th>Joom-sku</th>
    <th>Joom-状态</th>
    <th>物品中文名称</th>
    <th>erp状态</th>
    <th>erp-sku</th>
    <th>在线库存</th>
    <th>erp实际库存</th>
    <th>价格</th>
    <th>运费</th>
    <th>操作</th>
@stop
@section('tableBody') 
    @foreach($data as $productInfo)
        <tr>
            <td><input type="checkbox" name="single[]" data-id = "{{$productInfo->id}}" ></td>
            <td>li****ufei@moonarstore.com</td>
            <td>{{$productInfo->productID}}</td>
            <td>{{$productInfo->sku}}</td>
            <td><?php
                if($productInfo->enabled == 0){
                    echo "已下架";
                }else{
                    echo "未下架";
                } ?>
            </td>
            <td>@if($productInfo->product)
                    {{$productInfo->product->c_name}}
                @endif
            </td>
            <td>
                @if($productInfo->item)
                    {{$productInfo->item->status}}
                @endif
            </td>
            <td>{{$productInfo->erp_sku}}
            </td>
            <td>{{$productInfo->inventory}}
                <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setinventory{{$productInfo->id}}"
                    title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
            </td>
            <td><?php
                if($productInfo->item){
                    echo isset($stocks[$productInfo->item->id])? $stocks[$productInfo->item->id] : 0;
                }else{
                    echo 0;
                }
                ?>
            </td>
            <td>{{$productInfo->price}}
                <button class="btn btn-primary btn-xs"
                        data-toggle="modal"
                        data-target="#setPrice{{$productInfo->id}}"
                        title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
            </td>
            <td>{{$productInfo->shipping}}
                <button class="btn btn-primary btn-xs"
                        data-toggle="modal"
                        data-target="#setshipping{{$productInfo->id}}"
                        title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
            </td>
            <td>
                <?php
                if($productInfo->enabled == 0){?>
                <a onclick="operator('<?php echo $productInfo->sku;  ?>' ,'<?php echo $productInfo->enabled;?>',this)" class="btn btn-success btn-xs">
                <span class="glyphicon glyphicon-pencil "></span> 上架
                    </a>
                <?php
                }else{ ?>
                <a onclick="operator('<?php echo $productInfo->sku;  ?>' ,'<?php echo $productInfo->enabled;?>',this)" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-pencil  "></span> 下架
                    </a>
                    <?php
                } ?>
                </td>
        </tr>
        <div class="modal fade" id="setshipping{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('joomonline.setshipping')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改Joom运费</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sku}}Joom运费</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <input type='text' class="form-control" name="shipping" value="{{$productInfo->shipping}}">
                                    <input type="hidden" name="sku" value="{{$productInfo->sku}}">
                                  
                                </div>
                         </div>
                    </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary" id="setQuantity">提交</button>
                    </div>
                   </form>
                </div>
            </div>
        </div> 
        <div class="modal fade" id="setPrice{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('joomonline.setPrice')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改Joom价格</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sku}}的Joom价格</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <input type='text' class="form-control" placeholder=""  name="price" value="{{$productInfo->price}}">
                                    <input type="hidden" name="sku" value="{{$productInfo->sku}}">
                                  
                                </div>
                         </div>
                    </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary" id="setQuantity">提交</button>
                    </div>
                   </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="setinventory{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('joomonline.setSellerinventory')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改 {{$productInfo->sku}}的在线库存</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sku}}的在线库存</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small><br>
                                    <input type="text" style="height:30px;width:200px" name="Quantity" value="{{$productInfo->inventory}}">
                                    <input type="hidden" name="sku" value="{{$productInfo->sku}}">
                                  
                                </div>
                         </div>
                    </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary" id="setQuantity">提交</button>
                    </div>
                   </form>
                </div>
            </div>
        </div>
             
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="glyphicon glyphicon-filter"></i>
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='batchedit' data-name="changeQuantity">设置sku在线库存</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changeup">批量上架sku</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changedown">批量下架sku</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changePrice">批量修改价格</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changeshipping">批量修改运费</a></li>
        </ul>
    </div>
@stop
@section('childJs')
<link href="{{ asset('plugins/layer/skin/layer.css')}}" type="text/css" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type='text/javascript'>

$(".batchedit").click(function(){
	var ids = $('input[name="single[]"]:checked').map(function(){
		return $(this).attr('data-id');
	}).get().join(',');
	if(!ids){
		alert('请先勾选信息', 'alert-warning');
		return false;
	}
	var param = $(this).data("name");
    if(param == 'changeup' || param == 'changedown'){       //update Joom status
        if(param == 'changeup'){
            if(!confirm("确定要上架勾选的sku吗？")){
                return false;
            }
        }else if(param == 'changedown') {
            if (!confirm("确定要下架勾选的sku吗？")) {
                return false;
            }
        }
        var url = "{{ route('joomonline.batchUpdate') }}";
        window.location.href = url + "?product_ids=" + ids + "&operate=" + param;
    }else{
        var url = "{{ route('joomonline.productBatchEdit') }}";
        window.location.href = url + "?ids=" + ids + "&param=" + param;
      }
	});
function operator(sku,type,e){    //更改广告状态
    var msg;
    if (type == 0) {
        msg = '确定上架sku为：' + sku + ' 的商品吗？';
    } else if (type == 1) {
        msg = '确定下架sku为：' + sku + ' 的商品吗？';
    }
    layer.confirm(msg,function(){
        $.ajax({
            url : "{{ route('joomonline.setstatus') }}",
            data : {sku:sku,status:type},
            dataType : 'json',
            type : 'get',
            success : function(result) {
                if(typeof(result) == 'string'){
                    result = JSON.parse();
                }
                if(result.status==1){
                    layer.alert(result.info);
                    parent.location.reload();
                }else{
                    layer.alert(result.info);
                    parent.location.reload();
                }
            }
        });
    });
}

	

</script>
@stop