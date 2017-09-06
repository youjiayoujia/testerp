<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-06-08
 * Time: 14:56
 */
?>
@extends('common.table')

@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuanOrder()">全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>主图</th>
    <th>账号</th>
    <th>产品ID</th>
    <th>Wish-SKU</th>
    <th>Wish-状态</th>
    <th>物品中文名称</th>
    <th>ERP-状态</th>
    <th>ERP-SKU</th>
    <th class="sort" data-field="inventory">在线库存</th>
    <th>实际库存</th>
    <th>价格</th>
    <th class="sort" data-field="shipping">运费</th>
    <th >售出个数</th>
    <th>操作</th>
@stop
@section('tableBody')
    <?php
        $numID = 0;
    ?>
    @foreach($data as $detail)

            <?php
                $numID++;
            ?>
        <tr>
            <td>
                <input type="checkbox" name="tribute_id" value="{{$detail->id}}" autocomplete="off" >
            </td>
            <td>{{  $detail->id }}</td>
            <td>
                <?php
                if (!empty($detail->main_image)) {
                    $picArr = explode('|', $detail->main_image);
                    $onePic = !empty($picArr) ? $picArr[0] : "";
                } else {
                    $onePic = '';
                }
                ?>
                @if(!empty($onePic))
                    <a target="_blank" href="{{$onePic}}"><img style="width:50px;height:50px;" src="{{$onePic}}"></a>
                @endif
            </td>
            <td>
                @if(isset($detail->channelAccount->account))
                {{  $detail->channelAccount->account }}
                @endif
            </td>
            <td>{{  $detail->productID }}</td>
            <td>{{  $detail->sku }}
            </td>
            <td>@if($detail->enabled == 1)
                上架
                @else
                下架
                @endif
            </td>
            <td>{{  @$detail->details->c_name }}</td>
            <td >{{  @$detail->details->status }}</td>
            <td >{{  $detail->erp_sku }}</td>
            <td style="text-align:center;">
                <span class="modifyValue">{{$detail->inventory}}</span>
                <br/><button class="btn btn-primary btn-xs"
                         data-toggle="modal"
                         data-target="#setSkuNumber{{$detail->id}}"
                         title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
                <div class="modal fade" id="setSkuNumber{{$detail->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                     <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="" method="POST">
                                {!! csrf_field() !!}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-left" id="myModalLabel{{$numID}}">修改SKU在线库存</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="account" class='control-label'>SKU:{{ $detail->sku}}</label>
                                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                            <input type='text' class="form-control" placeholder="0~99999之间" name="SkuStock">
                                            <input type="hidden" name="productId" value="{{$detail->productID}}">
                                            <input type="hidden" name="account_id" value="{{$detail->account_id}}">
                                            <input type="hidden" name="skuId" value="{{$detail->id}}">
                                            <input type="hidden" name="sku" value="{{$detail->sku}}">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" class="btn btn-primary" onclick="operatSku(this,'stock')" data-dismiss="modal">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
            <td>实库存</td>
            <td style="text-align:center;">
                <span class="modifyValue">{{$detail->price}}</span>
                <br/><button class="btn btn-primary btn-xs"
                             data-toggle="modal"
                             data-target="#setSkuPrice{{$detail->id}}"
                             title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
                <div class="modal fade" id="setSkuPrice{{$detail->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="" method="POST">
                                {!! csrf_field() !!}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-left" id="myModalLabel">修改SKU价格</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="account" class='control-label'>SKU:{{ $detail->sku}}</label>
                                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                            <input type='text' class="form-control" placeholder="0~99999之间" name="Price">
                                            <input type="hidden" name="productId" value="{{$detail->productID}}">
                                            <input type="hidden" name="account_id" value="{{$detail->account_id}}">
                                            <input type="hidden" name="skuId" value="{{$detail->skuId}}">
                                            <input type="hidden" name="sku" value="{{$detail->sku}}">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" class="btn btn-primary" onclick="operatSku(this,'Price')" data-dismiss="modal">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div></td>
            <td style="text-align:center;">
                <span class="modifyValue">{{$detail->shipping}}</span>
                <br/><button class="btn btn-primary btn-xs"
                             data-toggle="modal"
                             data-target="#setSkuFreight{{$detail->id}}"
                             title="设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
                <div class="modal fade" id="setSkuFreight{{$detail->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="" method="POST">
                                {!! csrf_field() !!}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-left" id="myModalLabel">修改SKU运费</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="account" class='control-label'>SKU:{{ $detail->sku}}</label>
                                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                            <input type='text' class="form-control" placeholder="0~99999之间" name="Freigh">
                                            <input type="hidden" name="productId" value="{{$detail->productID}}">
                                            <input type="hidden" name="account_id" value="{{$detail->account_id}}">
                                            <input type="hidden" name="skuId" value="{{$detail->skuId}}">
                                            <input type="hidden" name="sku" value="{{$detail->sku}}">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" class="btn btn-primary" onclick="operatSku(this,'Freigh')" data-dismiss="modal">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
            <td>{{  @$detail->belongs_product->number_sold }}</td>

            <td>
                <input type="hidden" name="productId" value="{{$detail->productID}}">
                <input type="hidden" name="account_id" value="{{$detail->account_id}}">
                <input type="hidden" name="skuId" value="{{$detail->skuId}}">
                <input type="hidden" name="sku" value="{{$detail->sku}}">
                        <a onclick="operatSku(this ,'disable')" class="btn btn-danger btn-xs  <?php   if($detail->status==1){echo "hidden"; }      ?>">
                            <span class="glyphicon glyphicon-pencil "></span> 下架
                        </a>

                        <a onclick="operatSku(this ,'enable')"  class="btn btn-success btn-xs <?php   if($detail->status==0){echo "hidden"; }      ?>">
                            <span class="glyphicon glyphicon-pencil  "></span> 上架
                        </a>

            </td>
        </tr>

    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="BatchOperation" data-status="Price" data-name="修改价格" data-toggle="modal"
                   data-target="#BatchOperation">修改价格</a></li>
            <li><a href="javascript:" class="BatchOperation" data-status="stock" data-name="设置Item数量" data-toggle="modal"
                   data-target="#BatchOperation">设置Item数量</a></li>
            <li><a href="javascript:" class="BatchOperation" data-status="Freigh" data-name="修改运费" data-toggle="modal"
                   data-target="#BatchOperation">修改运费</a></li>
            <li><a href="javascript:" class="BatchOperation" data-status="disable" data-name="下架产品" data-toggle="modal"
                   data-target="#BatchOperation">下架广告</a></li>
            <li><a href="javascript:" class="BatchOperation" data-status="enable" data-name="上架产品" data-toggle="modal"
                   data-target="#BatchOperation">上架广告</a></li>
        </ul>
    </div>
    <div class="modal fade" id="BatchOperation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('wishQuantity.BatchOperation') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-left" id="BOmyModalLabel">批量修改SKU在线价格</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                {{--<label for="account" class='control-label obhidden' style="float:left;">SKU:{{ $detail->sku}}</label>--}}
                                <small class="text-danger glyphicon glyphicon-asterisk obhidden"></small>
                                <input type='text' class="form-control obhidden" placeholder="0~99999之间" name="ModifyDate" >
                                <input type="hidden" name="BoAction" value="" id="BoAction">
                                <input type="hidden" name="IdStr" value="" id="IdStr">
                                <input type="hidden" name="preurl" value="{{ $preurl }}" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary"  >确认</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('childJs')
<script type="text/javascript">
    function operatSku(e,type){
        var mark = e;
        var url = "{{ route('wishQuantity.ajaxModifySku')}}";
        var data = "";
        var productId = $(mark).parent().parent().find("input[name='productId']:hidden").val();
        var account_id = $(mark).parent().parent().find("input[name='account_id']:hidden").val();
        var sku = $(mark).parent().parent().find("input[name='sku']:hidden").val();
        var skuId = $(mark).parent().parent().find("input[name='skuId']:hidden").val();
        var SucObject = $(mark).parent().parent().parent().parent().parent().parent().find(".modifyValue");
        var ableObj = $(mark).attr('class');
        switch(type){
            case 'stock':
                    //修改数量
                var SkuStock = $(mark).parent().parent().find("input[name='SkuStock']:text").val();
                data = {SkuStock:SkuStock,productId:productId,account_id:account_id,sku:sku,skuId:skuId,type:type};
                break;
            case 'Price':
                //修改价格
                var Price = $(mark).parent().parent().find("input[name='Price']:text").val();
                data = {Price:Price,productId:productId,account_id:account_id,sku:sku,skuId:skuId,type:type};
                break;
            case 'Freigh':
                //修改运费
                var Freigh = $(mark).parent().parent().find("input[name='Freigh']:text").val();
                data = {Freigh:Freigh,productId:productId,account_id:account_id,sku:sku,skuId:skuId,type:type};
                break;
            case 'disable':
                //下架
                data = {productId:productId,account_id:account_id,sku:sku,skuId:skuId,type:type};
                break;
            case 'enable':
                //上架架
                data = {productId:productId,account_id:account_id,sku:sku,skuId:skuId,type:type};
                break;
            default:
                return false;
                break;
        }

        $.ajax({
            url : url,
            data : data,
            dataType : 'json',
            type : 'post',
            success : function(result){
                if(result.status==1){
                    alert('操作成功');
                    switch(type){
                        case 'stock':
                            //修改数量
                            SucObject.html(SkuStock);
                            break;
                        case 'Price':
                            //修改价格
                            SucObject.html(Price);
                            break;
                        case 'Freigh':
                            //修改运费
                            SucObject.html(Freigh);
                            break;
                        case 'disable':
                            //下架
                            $(mark).addClass('hidden');
                            $(mark).next().removeClass('hidden');
                            break;
                        case 'enable':
                            //上架架
                            $(mark).addClass('hidden');
                            $(mark).prev().removeClass('hidden');
                            break;
                        default:
                            return false;
                            break;
                    }
                }else{
                    alert('操作失败:'+result.info);
                }
            },
            error : function(){
                alert('操作失败有错误存在!!');
            }
        });
    }
    //批量操作
    $('.BatchOperation').click(function (){
        var type = $(this).attr('data-status');
        var coll = document.getElementsByName("tribute_id");
        var length=$('input:checkbox[name=tribute_id]:checked').length;
        if(length < 1){
            alert('请选择产品');
            return false;
        }
        var idArray = new Array();

        $('input:checkbox[name=tribute_id]:checked').each(function(){
            idArray.push($(this).val());
        });
        //所有选择的产品ID
        var idStr = idArray.join(',');
        $('#BoAction').val(type);
        $('#IdStr').val(idStr);
        var hashidden = $('.obhidden').hasClass('hidden');
        switch(type){
            case 'Price':
                //修改数量
                $('#BOmyModalLabel').html('批量修改在线价格');
                if(hashidden){
                    $('.obhidden').removeClass('hidden');
                }
                break;
            case 'stock':
                //修改数量
                $('#BOmyModalLabel').html('批量修改在线数量');
                    if(hashidden){
                        $('.obhidden').removeClass('hidden');
                    }
                break;
            case 'Freigh':
                //修改运费
                $('#BOmyModalLabel').html('批量修改在线运费');
                if(hashidden){
                    $('.obhidden').removeClass('hidden');
                }
                break;
            case 'disable':
                //下架
                $('#BOmyModalLabel').html('批量修改下架产品');
                if(!hashidden){
                    $('.obhidden').addClass('hidden');
                }

                break;
            case 'enable':
                //上架架
                $('#BOmyModalLabel').html('批量修改上架产品');
                if(!hashidden){
                    $('.obhidden').addClass('hidden');
                }
                $('#surebo').html('确认');
                break;
        }
    })
    function operator(id,type,e){
        var mark = e;

        $.ajax({
            url : "{{ route('wish.ajaxOperateOnlineProduct') }}",
            data : {id : id,type:type},
            dataType : 'json',
            type : 'get',
            success : function(result) {
                if(result.status==1){
                    if(type=='disable'){
                        $(e).next().removeClass('hidden');
                        $(e).addClass('hidden')
                    }
                    if(type=='enable'){
                        $(e).prev().removeClass('hidden');
                        $(e).addClass('hidden')
                    }
                    alert(result.info);
                }else{
                    alert(result.info);
                }
            }
        });
    }
    //全选订单
    function quanxuanOrder()
    {
        var collid = document.getElementById("checkall");
        var coll = document.getElementsByName("tribute_id");
        if (collid.checked){
            for(var i = 0; i < coll.length; i++)
                coll[i].checked = true;
        }else{
            for(var i = 0; i < coll.length; i++)
                coll[i].checked = false;
        }
    }
</script>
@stop
