<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-08
 * Time: 15:58
 */
?>

<style>
    .row-border {
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 3px 4px 3px rgba(238, 238, 238, 1);
        margin-bottom: 10px;
    }

    .proh {
        width: 100%;
        height: 30px;
    }

    .hideaccordion, .showaccordion {
        float: left;
        height: 18px;
        line-height: 18px;
        position: relative;
        padding: 6px;
    }

    .hideaccordion h1, .showaccordion h1 {
        font-size: 14px;
        font-weight: bold;
        color: #444;
    }

    .hideaccordion h1 i {
        cursor: pointer;
    }

    .probody {
        width: 90%;
        height: 90%;
        padding: 10px;
    }

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

    #Validform_msg {
        color: #7d8289;
        font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif;
        width: 280px;
        background: #fff;
        position: absolute;
        top: 0px;
        right: 50px;
        z-index: 99999;
        display: none;
        filter: progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#999999');
        -webkit-box-shadow: 2px 2px 3px #aaa;
        -moz-box-shadow: 2px 2px 3px #aaa;
    }

    #Validform_msg .iframe {
        position: absolute;
        left: 0px;
        top: -1px;
        z-index: -1;
    }

    #Validform_msg .Validform_title {
        line-height: 25px;
        height: 25px;
        text-align: left;
        font-weight: bold;
        padding: 0 8px;
        color: #fff;
        position: relative;
        background-color: #000;
    }

    #Validform_msg a.Validform_close:link, #Validform_msg a.Validform_close:visited {
        line-height: 22px;
        position: absolute;
        right: 8px;
        top: 0px;
        color: #fff;
        text-decoration: none;
    }

    #Validform_msg a.Validform_close:hover {
        color: #cc0;
    }

    #Validform_msg .Validform_info {
        padding: 8px;
        border: 1px solid #000;
        border-top: none;
        text-align: left;
    }

    /***拖拽样式***/
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
@section('formAction') {{ route('wish.store') }} @stop
@section('formAttributes') {{ "class=validate_form" }} @stop
@section('formBody')
    <?php
    if (isset($model)) {
        $account_id = $model->account_id;
        $product_name = $model->product_name;
        $tags = $model->tags;
        $parent_sku = $model->parent_sku;
        $shipping_time = $model->details->first()->shipping_time;
        $brand = $model->brand;
        $landing_page_url = $model->landing_page_url;
        $upc = $model->upc;
        $details = $model->details;
        $shipping = $model->details->first()->shipping;
        $msrp = $model->details->first()->msrp;
        $id = $model->id;
        $extra_images = $model->extra_images;
        $extra_images = explode('|', $extra_images);
        $product_description = $model->product_description;

    }


    ?>



    <div class="modal fade " id="withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">生成SKU</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <span class="text-center" >颜色和尺寸用英文逗号分隔 例如 颜色:red,yellow,black</span></div>
                        </div>
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label  class="label-right"> 母SKU:</label>
                        </div>
                        <div class="form-group col-sm-10">
                            <input class="form-control" id="generate_sku_parent">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label  class="right"> 颜色:</label>
                        </div>
                        <div class="form-group col-sm-10">
                            <input class="form-control" id="generate_sku_color">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label for="subject" class="right"> 尺寸:</label>
                        </div>
                        <div class="form-group col-sm-10">
                            <input class="form-control" id="generate_sku_size" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-info creatsku" >确定</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">账号选择</div>
        <div class="panel-body">
            <?php
            //  $account = array('A-FM', 'A-AN', 'A-ME', 'A-SM', 'D-SM', 'H-XH', 'H-LE', 'H-RE', 'A-OY', 'H-TE', 'J-RY', 'I-LT', 'J-MT', 'J-M5', 'M-SP', '226win', '62gbs');

            foreach ($account as $key => $a):?>
            <div class="col-lg-2">
                <input type="checkbox" value="<?php echo $key;?>"
                       name="choose_account[]" <?php  if (isset($account_id) && $key == $account_id) {
                    echo 'checked="checked"';
                } ?>
                       datatype="*" nullmsg="账号不能为空" class="choose_account "/> <?php echo $a;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">多账号标题</div>
        <div class="panel-body">
            <div id="account_tittle">
                @if(isset($product_name))
                    <div class="row" id="{{'account_tittle_'.$account_id}}">
                        <div class="form-group col-sm-2">
                            <label for="subject" class="right">{{$account[$account_id]}} 标题：</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <textarea name="account_tittle[{{$account_id}}][tittle]"
                                      class="form-control">{{$product_name}}</textarea>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">多账号Tags</div>
        <div class="panel-body">
            <div id="account_tags">

                @if(isset($tags))
                    <div class="row" id="{{'account_tags_'.$account_id}}">
                        <div class="form-group col-sm-2">
                            <label for="subject" class="right">{{$account[$account_id]}} Tags：</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <textarea name="account_tags[{{$account_id}}][tags]"
                                      class="form-control">{{$tags}}</textarea>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">

            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">parent_sku：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" datatype="*" nullmsg="parent_sku不能为空" class="form-control" name="parent_sku"
                           id="parent_sku" value="<?php if (isset($parent_sku)) echo $parent_sku; ?>"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">发货时间(shipping_time)：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" datatype="*" nullmsg="发货时间不能为空" name="shipping_time"
                           id="shipping_time" value="<?php if (isset($shipping_time)){ echo $shipping_time; }else{ echo '20-45';}  ?>"/>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">制造商(brand)：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" name="brand" id="brand"
                           value="<?php if (isset($brand)) echo $brand; ?>"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">UPC：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" name="upc" id="upc"
                           value="<?php if (isset($upc)) echo $upc; ?>"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">产品查询(landing_page_url)：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" name="landing_page_url" id="landing_page_url"
                           value="<?php if (isset($landing_page_url)) echo $landing_page_url; ?>"/>
                </div>
            </div>


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">SKU信息</div>

        <div class="panel-body" >
            <div class='row'>
                <div class="form-group col-sm-2">
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" placeholder="ERPSKU" id="erp_sku">
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" placeholder="前缀"  datatype="*" nullmsg="请输入前缀"  name="sku_perfix"  value="@if(isset($model->sku_perfix)){{$model->sku_perfix}}@endif">
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" placeholder="数量" id="sku_num">
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" placeholder="价格" id="sku_price">
                </div>
                <div class="form-group col-sm-2">
                    <a class="btn btn-info" onclick="add_sku_info()">ERPSKU</a>
                    <a class="btn btn-primary withdraw" data-toggle="modal"  data-target="#withdraw">随机SKU</a>
                </div>
            </div>

            <div  id="itemDiv">
            <div class='row' id="sku_detail">
                <div class="form-group col-sm-2">
                </div>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label' >sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="image" class='control-label'>图片</label>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label' onclick="batch_setting('quantity')">数量</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label' onclick="batch_setting('price')">单价</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label' onclick="batch_setting('color')">颜色</label>

                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label' onclick="batch_setting('size')">尺寸</label>

                </div>
            </div>

            @if(isset($details))
                @foreach($details as $detail)
                    <div class="need-del">
                        <div class="row">
                            <div class="form-group col-sm-2"></div>
                            <div class="form-group col-sm-2">
                                <input type="text" class="form-control prefix" placeholder="sku" name="arr[sku][]"
                                       value="{{Tool::getErpSkuBySku($detail->sku,$model->channelAccount->wish_sku_resolve) }}">
                            </div>
                            <div class="form-group col-sm-2 image">
                                <div class="form-group col-sm-2">
                                    @if(!empty($detail->main_image))
                                        <img class="is_del" width="50px" height="50px" src="{{$detail->main_image}}">
                                    @endif
                                    <input type="hidden" value="{{$detail->main_image}}" name="arr[main_image][]">

                                </div>
                                @if(empty($detail->main_image))
                                    <a class="btn btn-success " title="设置图片" onclick="add_pic(this)"
                                       href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-picture"></span>
                                    </a>
                                @endif
                            </div>

                            <div class="form-group col-sm-1">
                                <input type="text" class="form-control  quantity" placeholder="数量"
                                       name="arr[quantity][]"
                                       value="{{$detail->inventory}}">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text" class="form-control  price" placeholder="单价" name="arr[price][]"
                                       value="{{$detail->price}}">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text" class="form-control  color" placeholder="颜色" name="arr[color][]"
                                       value="{{$detail->color}}">
                            </div>
                            <div class="form-group col-sm-1">
                                <input type="text" class="form-control  size" placeholder="尺码" name="arr[size][]"
                                       value="{{$detail->size}}">
                            </div>
                            <button type="button" class="btn btn-danger bt_right" title="删除该SKU"><i
                                        class="glyphicon glyphicon-trash"></i></button>
                            <button type="button" class="btn " onclick="up_row(this)" title="上移"><i
                                        class="glyphicon glyphicon-arrow-up"></i></button>
                            <button type="button" class="btn " onclick="down_row(this)" title="下移"><i
                                        class="glyphicon glyphicon-arrow-down"></i></button>
                            <button type="button" class="btn btn-success zhankai " title="设置多账号价格"
                                    onclick="add_account_price(this)"><i class="glyphicon glyphicon-plus"></i></button>
                        </div>

                        <div class="form-group account_info hidden">
                            <div class="row ' + single_account_value + '">
                                <div class="form-group col-sm-2 text-right">
                                    <label class="text-right">{{$account[$account_id]}}</label>
                                </div>
                                <div class="form-group col-sm-8">
                                    <input type="text" class="form-control single_price"
                                           name="account_price[{{$account_id}}][]" placeholder="产品价格">
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            @endif
            </div>
        </div>
        <div class="panel-footer">
            <div class="create" id="addItem"><i class="glyphicon glyphicon-plus"></i><strong>新增产品</strong></div>
        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">多账号运费</div>
        <div class="panel-body">
            <div id="account_shipping">
                @if(isset($shipping))

                    <div class="row" id="{{'account_shipping_'.$account_id}}">
                        <div class="form-group col-sm-2">
                            <label for="subject" class="right"> {{$account[$account_id]}}运费(shipping)：</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <input type="text" value="{{$shipping}}" class="form-control"
                                   name="account_shpping[{{$account_id}}][shipping]" placeholder="运费价格">
                        </div>
                    </div>
                @endif


            </div>

        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">其他信息</div>
        <div class="panel-body">

            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">零售价(msrp)：</label>
                </div>
                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" name="msrp" id="msrp" placeholder="建议零售价" onblur="changeMsrp(this)"
                           value="<?php if (isset($msrp)) echo $msrp; ?>">
                </div>
            </div>


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">图片信息</div>
        <div class="panel-body">

            <div class="form-group clearfix">
                <label class="col-sm-2 control-label">描述图片：</label>

                <div class="col-sm-10">
                    <div>
                        <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm from_local" lang="detail">从我的电脑选取</a> -->
                        <a href="javascript:void(0);" class="btn btn-success btn-sm image_url"
                           onclick="add_pic_in_detail(this)">图片外链</a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm image_url"
                           onclick="get_sku_pic(this)">获取图片</a>
                        {{-- <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">图片目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">实拍目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">WISH目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">无水印目录上传</a>--}}
                        &nbsp;&nbsp;
                        <a class="btn btn-danger btn-xs delete_item pic_del_all"><span
                                    class="glyphicon glyphicon-trash"></span>全部删除</a>
                        <b class="ajax-loading hide">图片上传中...</b>
                    </div>
                    <ul class="list-inline pic-detail">

                        @if(!empty($extra_images))
                            @foreach($extra_images as $image)
                                <li>
                                    <div><img src="{{$image}}" width="100" height="100" style="border: 0px;"><input
                                                type="hidden" name="extra_images[]" value="{{$image}}"/><a
                                                href="javascript: void(0);" class="pic_del">删除</a></div>
                                </li>
                            @endforeach
                        @endif


                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">描述信息</div>
        <div class="panel-body">
            <div class="form-group clearfix">
                <label class="col-sm-2 control-label">描述信息：</label>

                <div class="col-sm-10">
                    <textarea id="content" name="content">
                        <?php  if (isset($product_description)) echo $product_description; ?>
                    </textarea>

                </div>

            </div>

            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">检查重复刊登：</label>
                </div>
                <div class="form-group col-sm-8">
                    <input type="checkbox" checked name="check_repeat" value="check_repeat">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="action" id="action" value=""/>
    <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" id="id"/>
@stop

@section('formButton')
    <div class="text-center">
        <button type="submit" name="save" class="btn btn-success submit_btn ">保存为草稿</button>
        <button type="submit" name="editAndPost" class="btn btn-primary  submit_btn ">保存并且发布</button>
    </div>

@show{{-- 表单按钮 --}}



@section('pageJs')

    {{-- <script src="{{ asset('plugins/UEditor/umeditor.config.js') }}"></script>
     <script src="{{ asset('plugins/UEditor/umeditor.js') }}"></script>--}}
    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
    <script type='text/javascript'>

        var content = UM.getEditor('content', {
            initialFrameHeight: 500
        });
        content.setWidth("100%");
        $(".edui-body-container").css("width", "98%");
        /*   var ue = UE.getEditor('container', {
         initialFrameHeight: 500
         });*/
        /* ue.ready(function() {
         ue.setContent('<p>hello!</p>'); //ture 追加内容
         });*/

        $(".pic-detail").dragsort({
            dragSelector: "div",      //容器拖动手柄
            dragBetween: true,                   //
            dragEnd: function () {
            },                   //执行之后的回调函数
            placeHolderTemplate: "<li class='placeHolder'><div></div></li>"     //拖动列表的HTML部分
        });


        $('#addItem').click(function () {
            addItem('','','','','','');
        });


        $(".choose_account").click(function () {               //checkBox点击事件
            var value = $(this).val();
            var text_name = $(this).parent().text();
            var id_name = 'account_tittle_' + value;
            var id_tags = 'account_tags_' + value;
            var id_shipping = 'account_shipping_' + value;
            var tittle_text = $("#account_tittle").children().first().children().eq(1).children().eq(0).val();
            if (typeof(tittle_text) == 'undefined') {
                tittle_text = '';
            }
            var tags_text = $("#account_tags").children().first().children().eq(1).children().eq(0).val();
            if (typeof(tags_text) == 'undefined') {
                tags_text = '';
            }
            var shipping_text = $("#account_shipping").children().first().children().eq(1).children().eq(0).val();
            if (typeof(shipping_text) == 'undefined') {
                shipping_text = '';
            }
            if ($(this).is(':checked')) { // 选中
                var add_tittle = '<div  class="row" id="' + id_name + '">' +
                        '<div class="form-group col-sm-2">' +
                        '<label for="subject" class="right">' + text_name + ' 标题：</label>' +
                        '</div>' +
                        '<div class="form-group col-sm-8">' +
                        '<textarea  datatype="*" nullmsg="标题不能为空"  name="account_tittle[' + value + '][tittle]"  class="form-control" placeholder="名称" >' + tittle_text +
                        '</textarea>' +
                        '</div>' +
                        '</div>';
                $('#account_tittle').append(add_tittle);

                var add_tags_html = '<div class="row" id="' + id_tags + '">' +
                        '<div class="form-group col-sm-2">' +
                        '<label for="subject" class="right">' + text_name + ' Tags：</label>' +
                        '</div>' +
                        '<div class="form-group col-sm-8">' +
                        '<textarea  datatype="*" nullmsg="Tags不能为空" name="account_tags[' + value + '][tags]"  class="form-control" placeholder="Tags不超过10个">' + tags_text +
                        '</textarea>' +
                        '</div>' +
                        '</div>';
                $('#account_tags').append(add_tags_html);

                var add_shipping_html = ' <div class="row" id="' + id_shipping + '">' +
                        '<div class="form-group col-sm-2">' +
                        '<label for="subject" class="right">' + text_name + '运费(shipping)：</label>' +
                        '</div>' +
                        '<div class="form-group col-sm-8">' +
                        '<input type="text"  datatype="*" nullmsg="运费不能为空" class="form-control" name="account_shpping[' + value + '][shipping]"  placeholder="运费价格"  value=' + shipping_text + '>' +
                        '</div>' +
                        '</div>';
                $('#account_shipping').append(add_shipping_html);
                var account_info_html = '';
                var single_account_value = 'single_account_value_' + value;
                var info = '<div class="row ' + single_account_value + '">' +
                        '<div class="form-group col-sm-2 text-right">' +
                        '<label  class="text-right">' + text_name + '：</label>' +
                        '</div>' +
                        '<div class="form-group col-sm-8">' +
                        '<input type="text" class="form-control single_price"  name="account_price[' + value + '][]"  placeholder="产品价格" value="">' +
                        '</div></div>';
                $(".account_info").each(function () {
                    $(this).append(info);
                });
                //account_info
            } else { //取消
                $("#account_tittle_" + value).remove();
                $("#account_tags_" + value).remove();
                $("#account_shipping_" + value).remove();
                $(" .single_account_value_" + value).remove();
            }
        });


        $(document).on('click', '.bt_right', function () {
         //   $(this).parent().next().remove();
           // $(this).parent().remove();
            $(this).parent().parent().remove();

        });

        $(document).on('click', '.pic_del', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('click', '.pic_del_all', function () {
            if (confirm('确认删除全部图片吗？')) {
                $(this).closest('.form-group').find('ul').empty();
            }
        });

        $(document).on('click', '.is_del', function () {
            if (confirm('确认删除图片吗？')) {
                $(this).next().val('');
                var html = '<a class="btn btn-success " title="设置图片" href="javascript:void(0);" onclick="add_pic(this)" ><span class="glyphicon glyphicon-picture"></span> </a>';
                $(this).parent().parent().append(html);
                $(this).remove();

            }
        });
        $(".creatsku").click(function(){
            var generate_sku_parent = $("#generate_sku_parent").val();
            var generate_sku_color = $("#generate_sku_color").val();
            var generate_sku_size = $("#generate_sku_size").val();
            var sku_price = $("#sku_price").val();
            var sku_num = $("#sku_num").val();
            $.ajax({
                url: "{{ route('wish.ajaxGenerateSku') }}",
                data: {
                    generate_sku_parent: generate_sku_parent,
                    generate_sku_color:generate_sku_color,
                    generate_sku_size:generate_sku_size
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    $("#withdraw").modal('toggle');
                    $(".need-del").remove();
                    $("#generate_sku_parent").empty();
                    $("#generate_sku_color").empty();
                    $("#generate_sku_size").empty();
                    for(var i=0;i<result.data.length;i++){
                        addItem(result.data[i].sku,'',sku_num,sku_price,result.data[i].color,result.data[i].size);
                    }
                    }
                })
        });

        $('.submit_btn').click(function () {
            var name = $(this).attr('name');
            $("#action").val(name);

        });
        $('.validate_form').Validform({
            btnSubmit: '.submit_btn',
            btnReset: '.btn-reset',
            ignoreHidden: true,
            ajaxPost: true,
            callback: function (data) { //返回数据
                if (data.status) {
                    if (data.data) {
                        $('#id').val(data.data);
                    }
                }
            }
        });

        function add_sku_info() {
            var str = $("#erp_sku").val();
            if (str == '') {
                return false;
            }
            var sku_price = $("#sku_price").val();
            var sku_num = $("#sku_num").val();
            $.ajax({
                url: "{{ route('wish.ajaxGetInfo') }}",
                data: {
                    sku: str
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result.status) {
                        $(".need-del").remove();
                        $(".pic-detail").empty();
                        for (var i = 0; i < result.data.sku.length; i++) {
                            if (typeof(result.data.pic[i]) == 'undefined') {
                                addItem(result.data.sku[i], '',  sku_num,sku_price, '', '')
                            } else {
                                addItem(result.data.sku[i], result.data.pic[i], sku_num, sku_price, '', '')
                            }
                        }
                        for (var i = 0; i < result.data.product_sku_pic.length; i++) {
                            var html = '<li>' +
                                    '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + result.data.product_sku_pic[i] + '">' +
                                    '<input type="hidden" value="' + result.data.product_sku_pic[i] + '" name="extra_images[]">' +
                                    '<a class="pic_del" href="javascript: void(0);">删除</a>' +
                                    '</div>' +
                                    '</li>';
                            $(".pic-detail").append(html);
                        }
                        content.setContent( result.data.description);

                    }
                }
            })

        }

        function addItem(sku,pic,num,pirce,color,size){
            var html = '<div class="need-del"><div class="row">' +
                    '<div class="form-group col-sm-2"></div>' +
                    '<div class="form-group col-sm-2">' +
                    '<input type="text" class="form-control prefix" placeholder="sku" name="arr[sku][]"  value="'+sku+'">' +
                    '</div>';
            if(pic==''){
                html =html+ '<div class="form-group col-sm-2 image">' + '<div class="form-group col-sm-2">' +
                '<input type="hidden" value="" name="arr[main_image][]">' +
                '</div>' +
                '<a class="btn btn-success " title="设置图片"  onclick="add_pic(this)" href="javascript:void(0);">' +
                '<span class="glyphicon glyphicon-picture"></span>' +
                '</a>' +
                '</div>';
            }else{
               html = html+'<div class="form-group col-sm-2 image"> <div class="form-group col-sm-2">' +
               '<img class="is_del" width="50px" height="50px" src="'+pic+'">' +
               '<input type="hidden" name="arr[main_image][]" value="'+pic+'">' +
               '</div>' +
               '</div>';
            }
            html =html+'<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control  quantity"  placeholder="数量" name="arr[quantity][]" value="'+num+'" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control  price"  placeholder="单价" name="arr[price][]" value="'+pirce+'" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control  color"  placeholder="颜色" name="arr[color][]" value="'+color+'" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control  size"  placeholder="尺码" name="arr[size][]"  value="'+size+'">' +
                    '</div>' +
                    '<button type="button" class="btn btn-danger bt_right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button>' +
                    '<button type="button" class="btn " onclick="up_row(this)"  title="上移"><i class="glyphicon glyphicon-arrow-up"></i></button>' +
                    '<button type="button" class="btn " onclick="down_row(this)"  title="下移"><i class="glyphicon glyphicon-arrow-down"></i></button>' +
                    '<button type="button" class="btn btn-success zhankai " title="设置多账号价格" onclick="add_account_price(this)"><i class="glyphicon glyphicon-plus"></i></button>' +
                    '</div>';


            var account_info = ' <div class="form-group account_info hidden">';
            $("input[name='choose_account[]']:checkbox").each(function () {
                var value = $(this).val();
                var text_name = $(this).parent().text();
                var id_name = 'account_tittle_' + value;
                if ($(this).is(':checked')) {
                    var single_account_value = 'single_account_value_' + value;
                    var info = '<div class="row ' + single_account_value + '">' +
                            '<div class="form-group col-sm-2 text-right">' +
                            '<label  class="text-right">' + text_name + '：</label>' +
                            '</div>' +
                            '<div class="form-group col-sm-8">' +
                            '<input type="text" class="form-control single_price"   name="account_price[' + value + '][]"  placeholder="产品价格" >' +
                            '</div></div>';
                    account_info = account_info + info;
                }
            });
            account_info = account_info + '</div></div>';
            $('#itemDiv').append(html + account_info);
        }

        function add_pic(e) {
            var mark = e;
            var str = prompt("图片外链地址");
            if (str) {
                var html = '<img class="is_del" src="' + str + '"   width="50px" height="50px" />';
                $(mark).prev().children().eq(0).val(str);
                $(mark).prev().prepend(html);
                $(mark).remove();
            }
        }

        function add_pic_in_detail(e) {
                var mark = e;
                var str = prompt("图片外链地址");
                if (str) {
                    var html = '<li>' +
                            '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + str + '">' +
                            '<input type="hidden" value="' + str + '" name="extra_images[]">' +
                            '<a class="pic_del" href="javascript: void(0);">删除</a>' +
                            '</div>' +
                            '</li>';
                    $(mark).parent().next().append(html);
                }
        }

        function get_sku_pic(e){
            var str = prompt("输入SKU");
            if (str) {
                $.ajax({
                    url: "{{ route('wish.ajaxGetSkuPicture') }}",
                    data: {
                        sku: str
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        $.each(result.data, function(index, el){
                            var html = '<li>' +
                                    '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + el + '">' +
                                    '<input type="hidden" value="' + el + '" name="extra_images[]">' +
                                    '<a class="pic_del" href="javascript: void(0);">删除</a>' +
                                    '</div>' +
                                    '</li>';
                            $(".pic-detail").append(html);
                        });
                    }
                })
            }
        }

        function add_account_price(e) {
            var mark = e;
            if ($(mark).hasClass("zhankai")) {
                /* $(mark).parent().next().find(".single_price").each(function () {

                 //alert($(this).val());
                 });*/
                $(mark).removeClass("zhankai");
                $(mark).parent().next().removeClass("hidden");

            } else {
                $(mark).parent().next().addClass("hidden");
                $(mark).addClass("zhankai");

            }
        }


        function up_row(e) {
            var objParentTR = $(e).parent().parent();
            var prevTR = objParentTR.prev();
            var id = prevTR.attr('id');
            if (prevTR.length > 0 && id != 'sku_detail') {
                prevTR.insertAfter(objParentTR);
            }
        }

        function down_row(e) {
            var objParentTR = $(e).parent().parent();
            var nextTR = objParentTR.next();
            if (nextTR.length > 0) {
                nextTR.insertBefore(objParentTR);
            }
        }

        function batch_setting(key) {
            var str = prompt("请输入" + key + "(前缀不需要*)");
            if (str) {
                $("." + key).each(function () {
                    var old_val = $(this).val();
                    if (key == 'prefix') {
                        old_val = old_val.split("*");
                        if (old_val[1]) {
                            $(this).val(str + '*' + old_val[1]);

                        } else {
                            $(this).val(str + '*' + old_val[0]);
                        }
                    } else {
                        $(this).val(str);
                    }

                })
            }
        }

        function changeMsrp(e){
            var value = $(e).val();
            $("#itemDiv").children().last().children().find("input[name='arr[price][]']").val(value);
        }
    </script>
@stop




