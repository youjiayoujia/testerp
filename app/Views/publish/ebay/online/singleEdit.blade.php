<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-10-05
 * Time: 10:22
 */
?>
<style>
    .pic-main, .pic-detail, .relate-list {
        padding: 5px;
        border: 1px solid #ccc;
    }

    .pic-main li, .pic-detail li, .relate-list li {
        margin: 5px;
        padding: 0px;
        border: 0px;
        width: 102px;
        text-align: right;
    }

    .pic-main li div, .pic-detail li div, .relate-list li div {
        width: 102px;
        height: 125px;
        border: 1px solid #fff;
    }

    .pic-main .placeHolder div, .pic-detail .placeHolder div, .relate-list .placeHolder div {
        width: 102px;
        height: 125px;
        background-color: white !important;
        border: dashed 1px gray !important;
    }


</style>
@extends('common.form')
@section('formAction')  {{ route('ebayOnline.singleUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <input type='hidden' value='{{$param}}' name="param">

    <div class="form-group">
        <label for="model">Ebay ItemID：<a target="_blank"
                                          href="http://www.ebay.com/itm/{{$model->item_id}}">{{$model->item_id}}</a></label>
    </div>
    <div class="row">

    </div>

    <?php
    switch ($param) {
    case 'changeSku':
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">SKU信息</div>
        <div class="panel-body">
            @if($model->listing_type=='Chinese')
                <div id="listing_type1" class="">
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">SKU:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" id="sku" name="sku" value="{{$model->sku}}" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">起拍价格:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" id="start_price" name="start_price" value="{{$model->start_price}}" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">数量:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="quantity" value="{{$model->quantity}}">
                        </div>
                    </div>
                </div>
            @endif

            @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==0)
                <div id="listing_type2" class=" ">
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">SKU:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" id="sku" name="sku" value="{{$model->sku}}" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">价格:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" id="start_price" name="start_price" value="{{$model->start_price}}" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">数量:</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="quantity" value="{{$model->quantity}}">
                        </div>
                    </div>

                </div>
            @endif
            @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==1)
                <div id="listing_type3" class=" ">
                    <?php
                    if(!empty($model->variation_specifics)){
                        $variation_specifics = json_decode($model->variation_specifics,true);
                        $variation=[];
                        $i=1;
                        foreach($variation_specifics as $key=>$value){
                            if($key=='UPC'||$key=='EAN'){
                                $variation[4]=$key;
                            }else{
                                $variation[$i]=$key;
                                $i++;
                            }

                        }
                    }
                    ?>
                    <div id="variation_sku">
                        <div class="row">
                            <div class="form-group col-sm-1">
                                <label class="text-center">SKU</label>
                            </div>
                            <div class="form-group col-sm-1">
                                <label class="text-center">价格</label>
                            </div>
                            <div class="form-group col-sm-1">
                                <label class="text-center">数量</label>
                            </div>

                            <div class="form-group col-sm-1">
                                <input type="text" value="@if(isset($variation[1])){{$variation[1]}}@endif" name="variation[]"
                                       class="form-control text-center variation_picture_main">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text"  value="@if(isset($variation[2])){{$variation[2]}}@endif" name="variation[]" class="form-control text-center ">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text"  value="@if(isset($variation[3])){{$variation[3]}}@endif" name="variation[]" class="form-control text-center">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text"  value="@if(isset($variation[4])){{$variation[4]}}@endif" name="variation[]" class="form-control text-center" >
                            </div>
                        </div>
                        @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==1)
                            @foreach($model->details as $key=> $sku)
                                <div class="row">
                                    <div class="form-group col-sm-1 "><input type="text" value="{{$sku->sku}}"  name="sku[]"  class="form-control text-center  sku-sku" ></div>
                                    <div class="form-group col-sm-1 do-change">
                                        <input type="text" value="{{$sku->start_price}}" name="start_price[]"  class="form-control text-center sku-price"  onblur="batchUpdatePrice(this)" style="background-color:#fa3658"></div>
                                    <div class="form-group col-sm-1 do-change">
                                        <input type="text" value="{{$sku->quantity}}"  name="quantity[]" class="form-control text-center sku-quantity " onblur="batchUpdateQuantity(this)" style="background-color:#fa3658"></div>
                                    <div class="form-group col-sm-1">
                                        <input type="text" value="@if(isset($variation[1])){{$variation_specifics[$variation[1]][$key]}}@endif"  name="variation0[]" class="form-control text-center variation_picture" onblur="variationPicture(this)"></div>
                                    <div class="form-group col-sm-1">
                                        <input type="text" value="@if(isset($variation[2])){{$variation_specifics[$variation[2]][$key]}}@endif"  name="variation1[]" class="form-control text-center"></div>
                                    <div class="form-group col-sm-1">
                                        <input type="text" value="@if(isset($variation[3])){{$variation_specifics[$variation[3]][$key]}}@endif" name="variation2[]" class="form-control text-center"></div>
                                    <div class="form-group col-sm-1">
                                        <input type="text" value="@if(isset($variation[4])){{$variation_specifics[$variation[4]][$key]}}@endif" name="variation3[]" class="form-control text-center"></div>
                                    <button type="button" class="btn btn-danger bt-right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="panel-footer">
                        <div class="create" id="addItem" onclick="addItem('')"><i
                                    class="glyphicon glyphicon-plus red"></i><strong>新增产品</strong></div>
                    </div>
                    <div id="variation_picture">
                        <?php  $variation_picture = json_decode($model->variation_picture,true); ?>
                        @if(!empty($variation_picture))
                            @foreach($variation_picture as $key=>$value)
                                @foreach($value as $k_v=>$v_k)
                                    <div class="row is-has-picture "  data-content="{{$k_v}}">
                                        <div class="form-group col-sm-2 text-left">
                                            <label onclick="setMulSkuPicture(this)">{{$k_v}}:</label>
                                        </div>
                                        <div class="form-group col-sm-2 ">

                                            <input type="text" class="hidden" name="variation_picture[{{$k_v}}]" value="{{$v_k}}">
                                            <img width="100px" height="100px" src="{{$v_k}}" onclick="deleteMulPicture(this)">
                                        </div></div>
                                @endforeach
                            @endforeach
                        @endif

                    </div>
                </div>
            @endif
        </div>

    </div>
    <?php
    break;

    case 'changeTitle':
    ?>
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="text-right">标题：</label></div>
        <div class="form-group col-sm-8">
            <input class="form-control" type="text" placeholder="标题" name="title" maxlength=80
                   value="{{$model->title}}"></div>
    </div>
    <div class="row ">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">子标题：</label>
        </div>
        <div class="form-group col-sm-6" name="sub_title">
            <input class="form-control" type="text" placeholder="副标题" maxlength=80 value="{{$model->sub_title}}">
        </div>
    </div>
    <?php
    break;
    case 'changeDescription':
    ?>
    <div class="row">
        <label class="col-sm-2 control-label">描述详情：</label>

        <div class="col-sm-9">
                    <textarea id="description" name="description">
                        {{htmlspecialchars_decode($model->description)}}
                    </textarea>

        </div>

    </div>
    <?php
    break;
    case 'changeShipping':
    ?>
    <?php
    $ship_name = [
            1 => '第一运输',
            2 => '第二运输'
    ];

    $shipping_details = json_decode($model->shipping_details, true);
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">国内运输</div>
        <div class="panel-body">

            @for($i=1;$i<3;$i++)
                <div id="{{'shipping'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 shipping"
                                    name="shipping[{{$i}}][ShippingService]">
                                <option value=""></option>
                                @foreach($shipping as $ship)
                                    @if(($ship->valid_for_selling_flow==1)&&($ship->international_service==2))
                                        <option value="{{$ship->shipping_service}}"
                                        @if(isset($shipping_details['Shipping'][$i]['ShippingService'])&&($shipping_details['Shipping'][$i]['ShippingService']==$ship->shipping_service))
                                                selected="selected"
                                                @endif
                                                >{{$ship->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control shipping_cost" type="text"
                                   name="shipping[{{$i}}][ShippingServiceCost]"
                            @if(isset($shipping_details['Shipping'][$i]['ShippingServiceCost']))
                                   value="{{$shipping_details['Shipping'][$i]['ShippingServiceCost']}}"
                                    @endif
                                    >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control shipping_addcost" type="text"
                                   name="shipping[{{$i}}][ShippingServiceAdditionalCost]"
                            @if(isset($shipping_details['Shipping'][$i]['ShippingServiceAdditionalCost']))
                                   value="{{$shipping_details['Shipping'][$i]['ShippingServiceAdditionalCost']}}"
                                    @endif >
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">国际运输</div>
        <div class="panel-body">
            @for($i=1;$i<3;$i++)
                <div id="{{'international'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 international"
                                    name="InternationalShipping[{{$i}}][ShippingService]">

                                <option value=""></option>
                                @foreach($shipping as $ship)
                                    @if(($ship->valid_for_selling_flow==1)&&($ship->international_service==1))
                                        <option value="{{$ship->shipping_service}}"
                                        @if(isset($shipping_details['InternationalShipping'][$i]['ShippingService'])&&($shipping_details['InternationalShipping'][$i]['ShippingService']==$ship->shipping_service))
                                                selected="selected"
                                                @endif
                                                >
                                            {{$ship->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control international_cost" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceCost]" @if(isset($shipping_details['InternationalShipping'][$i]['ShippingServiceCost']))
                                   value="{{$shipping_details['InternationalShipping'][$i]['ShippingServiceCost']}}" @endif >

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control international_addcost" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceAdditionalCost]"   @if(isset($shipping_details['InternationalShipping'][$i]['ShippingServiceAdditionalCost']))
                                   value="{{$shipping_details['InternationalShipping'][$i]['ShippingServiceAdditionalCost']}}"  @endif>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输国家：</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <select class="form-control select_select0 col-sm-1 international_ship"
                                    name="InternationalShipping[{{$i}}][ShipToLocation][]" multiple>
                                @foreach(config('ebaysite.ebay_country') as $key=> $v)
                                    <option value="{{$key}}"
                                    @if(isset($shipping_details['InternationalShipping'][$i]['ShipToLocation']))
                                        @if(in_array($v,$shipping_details['InternationalShipping'][$i]['ShipToLocation'])){{'selected="selected"'}}  @endif
                                            @endif
                                            >{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">不运输国家</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">不运输国家：</label>
                </div>
                <div class="form-group col-sm-8">
                    <select class="form-control select_select0 col-sm-1"
                            name="un_ship[]" id="un_ship" multiple>
                        @foreach(config('ebaysite.ebay_country') as $key=> $v)
                            <option value="{{$key}}" @if(in_array($key,$shipping_details['ExcludeShipToLocation'])){{'selected="selected"'}}  @endif  >{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>




    <?php
    break;
    case 'changePicture':
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">图片信息</div>
        <div class="panel-body">

            <div class="row form-group">
                <label class="col-sm-2 control-label">橱窗图片：</label>

                <div class="col-sm-10">
                    <div id="ebay_picture">
                        <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm from_local" lang="detail">从我的电脑选取</a> -->
                        <a href="javascript:void(0);" class="btn btn-success btn-sm"
                           onclick="add_pic_in_detail('add','1')">图片外链</a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm"
                           onclick="getSkuPicture()">获取SKU图片</a>
                        {{-- <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">图片目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">实拍目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">WISH目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">无水印目录上传</a>--}}
                        &nbsp;&nbsp;
                        <a class="btn btn-danger btn-xs  pic-del-all"><span
                                    class="glyphicon glyphicon-trash"></span>全部删除</a>
                        <b class="ajax-loading hide">图片上传中...</b>
                    </div>
                    <ul class="list-inline pic-detail">

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    break;
    case 'changeSpecifics':
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">物品状况与属性</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品状况：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control select_select0 col-sm-1" name="condition_id" id="condition_id">
                        @if(isset($condition)&&!empty($condition))
                            @foreach($condition as  $con)
                                <option value="{{$con['condition_id']}}" {{Tool::isSelected('condition_id', $con['condition_id'],$model)}}>{{$con['condition_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row hidden" id="condition_description">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品状况描述：</label>
                </div>
                <div class="form-group col-sm-5">
                    <textarea name="condition_description" class="form-control">
                        {{$model->condition_description}}
                    </textarea>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品属性：</label>
                </div>
                <div class=" col-sm-3">
                    <a class="btn btn-primary btn-sm dir_add" href="javascript: void(0);"
                       onclick="addUserSpecifics(1,1)">添加属性</a>
                </div>
            </div>

            <?php
            $specificsSelected = json_decode($model->item_specifics, true);
            ?>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class=""></label>
                </div>
                <div class="form-group col-sm-11" id="addSpecifics">
                    @if(isset($specifics)&&!empty($specifics))
                        @foreach($specifics as $key=> $spe)
                            <div class=" col-sm-6"><label class=" text-right col-sm-3">
                                    @if($spe['min_values']==1)
                                        *
                                    @endif
                                    {{$spe['name']}}</label>
                                <select name="item_specifics[{{$spe['name']}}]" class="select_select_tags col-sm-3">
                                    <option value=""></option>
                                    <?php
                                    $value = json_decode($spe['specific_values']);
                                    ?>
                                    @if(!empty($value)&&isset($value))
                                        @foreach($value as $v)
                                            <option value="{{$v}}"  @if(isset($specificsSelected[$spe['name']])&&$specificsSelected[$spe['name']]==$v){{'selected="selected"'}} @endif               >{{$v}}</option>
                                        @endforeach
                                    @endif
                                </select></div>
                        @endforeach
                    @endif

                    @if(isset($condition[0]['is_upc'])&&$condition[0]['is_upc']=='Required')

                        <div class=" col-sm-6"><label class=" text-right col-sm-3">*UPC</label>
                            <select name="item_specifics[UPC]" class="select_select_tags col-sm-3">
                                <option value=""></option>
                            </select></div>
                    @endif

                    @if(isset($condition[0]['is_ean'])&&$condition[0]['is_ean']=='Required')

                        <div class=" col-sm-6"><label class=" text-right col-sm-3 ">*EAN</label>
                            <select name="item_specifics[EAN]" class="select_select_tags col-sm-3">
                                <option value=""></option>
                            </select></div>
                    @endif

                    @if(isset($condition[0]['is_isbn'])&&$condition[0]['is_isbn']=='Required')

                        <div class=" col-sm-6"><label class=" text-right col-sm-3 ">*ISBN</label>
                            <select name="item_specifics[ISBN]" class="select_select_tags col-sm-3">
                                <option value=""></option>
                            </select></div>
                    @endif


                </div>


            </div>

        </div>
    </div>
    <?php
    break;
    default:
        echo 123;
        break;
    }
    ?>

@stop

@section('pageJs')
    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
    <script type="text/javascript">
        $('.select_select0').select2();
        $('.select_select_tags').select2({
            tags: true
        });

        if ($("#description").length > 0) {
            var content = UM.getEditor('description', {
                initialFrameHeight: 500
            });
            content.setWidth("100%");
            $(".edui-body-container").css("width", "80%");
        }


        $(".pic-detail").dragsort({
            dragSelector: "div",      //容器拖动手柄
            dragBetween: true,                   //
            dragEnd: function () {
            },                   //执行之后的回调函数
            placeHolderTemplate: "<li class='placeHolder'><div></div></li>"     //拖动列表的HTML部分
        });
        $(document).on('click', '.pic-del', function () {
            $(this).closest('li').remove();
        });
        $(document).on('click', '.bt-right', function () {
            $(this).parent().remove();

        });

        $("#condition_id").change(function () {
            var value = $("#condition_id").val();
            if (value != 1000) {
                if ($('#condition_description').hasClass("hidden")) {
                    $('#condition_description').removeClass("hidden");
                }
            } else {
                if (!$('#condition_description').hasClass("hidden")) {
                    $('#condition_description').addClass("hidden");
                }
            }

        });

        function add_pic_in_detail(type, value) {
            if (type == 'add') {
                var str = prompt("图片外链地址");
                if (!str) {
                    return false;
                }
            }
            if (type == 'auto') {
                var str = value;
            }
            var html = '<li>' +
                    '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + str + '">' +
                    '<input type="hidden" value="' + str + '" name="picture_details[]">' +
                    '<a class="pic-del" href="javascript: void(0);">删除</a>' +
                    '</div>' +
                    '</li>';
            $("#ebay_picture").next().append(html);
        }

        function addUserSpecifics(type, value) {
            if (type == 1) {
                var str = prompt("新增属性名称");
            } else {
                var str = value;
            }
            if (str) {
                var html = '<div class=" col-sm-6"><label onclick="deleteSpecifics(this)"class=" text-right col-sm-3">' + str + ':</label><select class="select_select_tags col-sm-3"   name="item_specifics[' + str + ']"></select></div>';
                $("#addSpecifics").append(html);
                $('.select_select_tags').select2({
                    tags: true
                });
            }
        }

        function addItem(sku) {
            var html = '<div class="row">' +
                    '<div class="form-group col-sm-1 ">' +
                    '<input type="text" value="' + sku + '"  name="sku[]"  class="form-control text-center  sku-sku" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1 do-change">' +
                    '<input type="text" value="" name="start_price[]" class="form-control text-center sku-price"  onblur="batchUpdatePrice(this)" style="background-color:#fa3658"></div>' +
                    '<div class="form-group col-sm-1 do-change">' +
                    '<input type="text" value=""  name="quantity[]" class="form-control text-center sku-quantity " onblur="batchUpdateQuantity(this)" style="background-color:#fa3658"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value=""  name="variation0[]" class="form-control text-center variation_picture" onblur="variationPicture(this)"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value=""  name="variation1[]" class="form-control text-center"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value="" name="variation2[]" class="form-control text-center"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value="Does not apply" name="variation3[]" class="form-control text-center"></div>' +
                    '<button type="button" class="btn btn-danger bt-right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button></div>';
            $("#variation_sku").append(html);
            if(sku!=''){
                mulAccountType1(sku);            }
        }


        function variationPicture(e) {
            $(".variation_picture").each(function () {
                var value = $(this).val();
                if (value != '') {
                    var is_has = false;
                    $(".is-has-picture").each(function () {
                        if ($(this).attr('data-content') == value) {
                            is_has = true;
                        }
                    });
                    if (!is_has) {
                        var html = ' <div class="row is-has-picture "  data-content="' + value + '"> ' +
                                '<div class="form-group col-sm-2 text-left">' +
                                '<label onclick="setMulSkuPicture(this)">' + value + ':</label>' +
                                '</div>' +
                                '<div class="form-group col-sm-2 ">' +
                                '<input type="text" class="hidden" name="variation_picture[' + value + ']" value="">' +
                                '</div></div>';
                        $("#variation_picture").append(html);
                    }
                }
            });
            $(".is-has-picture").each(function () {
                var mark = $(this);
                var value = $(this).attr('data-content');
                var is_has = false;
                $(".variation_picture").each(function () {
                    if (value == $(this).val()) {
                        is_has = true;
                    }
                });
                if (!is_has) {
                    mark.remove();
                }

            });
        }
        function setMulSkuPicture(e) {
            var str = prompt("请输入图片外链");
            if (str) {
                $(e).parent().next().children().eq(1).remove();
                var html = '<img width="100px" height="100px" src="' + str + '" onclick="deleteMulPicture(this)">';
                $(e).parent().next().children().eq(0).val(str);
                $(e).parent().next().append(html)

            }
        }
        function deleteMulPicture(e) {
            if (confirm('确定要删除图片吗？')) {
                $(e).prev().val('');
                $(e).remove();
            }
        }
        function batchUpdateQuantity(e) {
            var value = $(e).val();//background-color   #25fa69
            $(e).css("background-color", '#25fa69');
            $(".sku-quantity").each(function () {
                if ($(this).css("background-color") == 'rgb(250, 54, 88)') {
                    $(this).val(value);
                }
            });

        }
        function batchUpdatePrice(e) {
            var value = $(e).val();//background-color   #25fa69
            var sku =  $(e).parent().parent().children().children().eq(0).val();
            $(e).css("background-color", '#25fa69');
            var i =0;
            $(".sku-price").each(function () {
                if ($(this).css("background-color") == 'rgb(250, 54, 88)') {
                    $(this).val(value);
                }
                i++;
            });

        }
    </script>

@stop