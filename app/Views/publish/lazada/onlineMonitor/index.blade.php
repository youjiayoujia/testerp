@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>账号</th>
    <th>sellerSku</th>
    <th>shopSku</th>
    <th>Lazada-状态</th>
    <th>在线库存</th>
    <th>普通价格</th>
    <th>销售价格</th>
    <th>商品中文名</th>
    <th>ERP-状态</th>
    <th>ERP-SKU</th>
    <th>ERP实际库存</th>
@stop
@section('tableBody') 
    @foreach($data as $productInfo)
        <tr>
            <td><input type="checkbox" name="single[]" data-id = "{{$productInfo->id}}" ></td>
            <td>{{$productInfo->account}}</td>
            <td>{{$productInfo->sellerSku}}</td>
            <td>{{$productInfo->shopSku}}</td>
            <td>{{$productInfo->status}}
                <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setSkuStatus{{$productInfo->id}}"
                    title="设置">
                    <span class="glyphicon glyphicon-link"></span> 
                </button>
            </td>
            <td>{{$productInfo->quantity}}                
                <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setQuantity{{$productInfo->id}}"
                    title="设置">
                    <span class="glyphicon glyphicon-link"></span> 
                </button>
            </td>
            <td>{{$productInfo->price}}
                <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setPrice{{$productInfo->id}}"
                    title="设置">
                    <span class="glyphicon glyphicon-link"></span> 
                </button>
            </td>
            <td>{{$productInfo->salePrice}}
                <button class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#setSalePrice{{$productInfo->id}}"
                    title="设置">
                    <span class="glyphicon glyphicon-link"></span> 
                </button>
            </td>
            <td>
                @if($productInfo->product)
                    {{$productInfo->product->c_name}}
                @endif
            </td>
            <td>
                @if($productInfo->product)
                    {{$productInfo->product->status}}
                @endif
            </td>
            <td>{{$productInfo->sku}}</td>
            <td>
                <?php
                    if($productInfo->item){
                        echo isset($stocks[$productInfo->item->id])? $stocks[$productInfo->item->id] : 0;
                    }else{
                        echo 0;
                    }                   
                ?>               
            </td>
        </tr>
        <div class="modal fade" id="setQuantity{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('lazada.setQuantity')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改在售数量</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sellerSku}}的在售数量</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <input type='text' class="form-control" placeholder="0~99999之间"  name="quantity" value="{{$productInfo->quantity}}">
                                    <input type="hidden" name="sellerSku" value="{{$productInfo->sellerSku}}">
                                    <input type="hidden" name="account" value="{{$productInfo->account}}">
                                  
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
                    <form action="{{route('lazada.setPrice')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改普通价格</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sellerSku}}的普通价格</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <input type='text' class="form-control" placeholder=""  name="price" value="{{$productInfo->price}}">
                                    <input type="hidden" name="sellerSku" value="{{$productInfo->sellerSku}}">
                                    <input type="hidden" name="account" value="{{$productInfo->account}}">
                                  
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
        <div class="modal fade" id="setSkuStatus{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('lazada.setSellerSkuStatus')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改sellerSku的在线状态</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sellerSku}}的在线状态</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>     
                                    <select name="skuStatus" class="form-control">
                                        <option value="active" <?php if($productInfo->status == 'active') echo "selected = 'selected'";?>>active</option>
                                        <option value="inactive" <?php if($productInfo->status == 'inactive') echo "selected = 'selected'";?>>inactive</option>
                                        <option value="deleted" <?php if($productInfo->status == 'deleted') echo "selected = 'selected'";?>>deleted</option>                                    
                                    </select> 
                                                                  
                                    <input type="hidden" name="sellerSku" value="{{$productInfo->sellerSku}}">
                                    <input type="hidden" name="account" value="{{$productInfo->account}}">
                                    <input type="hidden" name="status" value="{{$productInfo->status}}">
                                  
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
        <div class="modal fade" id="setSalePrice{{$productInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{route('lazada.setSalePrice')}}" method="POST">
                    {!! csrf_field() !!}
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="myModalLabel">修改sellerSku的销售价格</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="account" class='control-label'>修改 {{$productInfo->sellerSku}}的销售价格</label>
                                    <input type='text' class="form-control" placeholder=""  name="salePrice" value="{{$productInfo->salePrice}}">
                                    <input type="hidden" name="sellerSku" value="{{$productInfo->sellerSku}}">
                                    <input type="hidden" name="account" value="{{$productInfo->account}}"> 
                                    <input type="hidden" name="price" value="{{$productInfo->price}}"> 
                                    <input type="hidden" name="saleStartDate" value="{{$productInfo->saleStartDate}}"> 
                                    <input type="hidden" name="saleEndDate" value="{{$productInfo->saleEndDate}}">                                  
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
            <li><a href="javascript:" class='batchedit' data-name="changeStatus">修改状态</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changePrice">修改普通价格</a></li>             
            <li><a href="javascript:" class='batchedit' data-name="changeSalePrice">修改销售价格</a></li>
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
	var url = "{{ route('lazada.productBatchEdit') }}";
	window.location.href = url + "?ids=" + ids + "&param=" + param;
});


	

</script>
@stop