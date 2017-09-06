<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-21
 * Time: 13:45
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
@section('formAction') {{ route('wish.editOnlineProductStore') }} @stop
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
    <div class="panel panel-default">
        <div class="panel-heading">账号选择</div>
        <div class="panel-body">
            <?php
            //  $account = array('A-FM', 'A-AN', 'A-ME', 'A-SM', 'D-SM', 'H-XH', 'H-LE', 'H-RE', 'A-OY', 'H-TE', 'J-RY', 'I-LT', 'J-MT', 'J-M5', 'M-SP', '226win', '62gbs');

            foreach ($account as $key => $a):?>
            @if($key == $account_id)
                <div class="col-lg-2">
                    <input type="checkbox" value="<?php echo $key;?>"
                           name="choose_account[]" <?php  if (isset($account_id) && $key == $account_id) {
                        echo 'checked="checked"';
                    } ?>
                           disabled datatype="*" nullmsg="账号不能为空" class="choose_account "/> <?php echo $a;?>
                </div>
            @endif
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
                    <input type="text" datatype="*" nullmsg="parent_sku不能为空" class="form-control" name="parent_sku" readonly
                           id="parent_sku" value="<?php if (isset($parent_sku)) echo $parent_sku; ?>"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">发货时间(shipping_time)：</label>
                </div>

                <div class="form-group col-sm-8">
                    <input type="text" class="form-control" datatype="*" nullmsg="发货时间不能为空" name="shipping_time"
                           id="shipping_time" value="<?php if (isset($shipping_time)) echo $shipping_time; ?>"/>
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
        <div class="panel-body" id="itemDiv">
            <div class='row'>
                <div class="form-group col-sm-2"></div>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="image" class='control-label'>图片</label>

                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>数量</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label'>单价</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label'>颜色</label>

                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label'>尺寸</label>

                </div>
            </div>

            @if(isset($details))
                @foreach($details as $detail)

                    <div class="row">
                        <div class="form-group col-sm-2"></div>
                        <div class="form-group col-sm-2">
                            <input type="text" class="form-control sku" placeholder="sku" name="arr[sku][]" READONLY
                                   value="{{$detail->sku}}">
                        </div>
                        <div class="form-group col-sm-2 image">
                            <div class="form-group col-sm-2">
                                @if(!empty($detail->main_image))
                                    <img class="is_del" width="50px" height="50px" src="{{$detail->main_image}}">
                                @endif
                                <input type="text" class="hidden" value="{{$detail->main_image}}"
                                       name="arr[main_image][]">

                            </div>
                            @if(empty($detail->main_image))
                                <a class="btn btn-success " title="设置图片" onclick="add_pic(this)"
                                   href="javascript:void(0);">
                                    <span class="glyphicon glyphicon-picture"></span>
                                </a>
                            @endif
                        </div>

                        <div class="form-group col-sm-1">
                            <input type="text" class="form-control " placeholder="数量" name="arr[quantity][]"
                                   value="{{$detail->inventory}}">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" class="form-control " placeholder="单价" name="arr[price][]"
                                   value="{{$detail->price}}">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" class="form-control " placeholder="颜色" name="arr[color][]"
                                   value="{{$detail->color}}">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" class="form-control " placeholder="尺码" name="arr[size][]"
                                   value="{{$detail->size}}">
                        </div>
                        @if($detail->enabled==1)
                            <button type="button" class="btn btn-warning" title="不启用该SKU" onclick="operatSku(this,2)"><i
                                        class="glyphicon glyphicon-remove"></i></button>
                            <button type="button" class="btn btn-success " title="更新该SKU" onclick="operatSku(this,3)"><i
                                        class="glyphicon glyphicon-repeat"></i></button>

                        @else
                            <button type="button" class="btn btn-success" title="启用该SKU" onclick="operatSku(this,1)"><i
                                        class="glyphicon glyphicon-ok"></i></button>
                        @endif

                    </div>
                @endforeach

            @endif

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
                            <input type="text" id="shipping" value="{{$shipping}}" class="form-control"
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
                    <input type="text" class="form-control" name="msrp" id="msrp" placeholder="建议零售价"
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
                                @if(!empty($image))
                                <li>
                                    <div><img src="{{$image}}" width="100" height="100" style="border: 0px;"><input type="hidden" name="extra_images[]" value="{{$image}}"/>
                                        <a href="javascript: void(0);" class="pic_del">删除</a></div>
                                </li>
                                @endif
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
        </div>
    </div>
    <input type="hidden" name="action" id="action" value=""/>
    <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" id="id"/>
@stop

@section('formButton')
    <div class="text-center">
        <button type="submit" name="editOnlineProduct" class="btn btn-success submit_btn ">修改线上信息</button>
    </div>

@show{{-- 表单按钮 --}}



@section('pageJs')

    {{--<script src="{{ asset('plugins/UEditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('plugins/UEditor/ueditor.all.js') }}"></script>--}}
    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
    <script type='text/javascript'>


        var content = UM.getEditor('content', {
            initialFrameHeight: 500,
            initialFrameWidth:1200
        });
        content.setWidth("100%");
        $(".edui-body-container").css("width", "98%");
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
            var html = '<div class="row">' +
                    '<div class="form-group col-sm-2"></div>' +
                    '<div class="form-group col-sm-2">' +
                    '<input type="text" class="form-control sku" placeholder="sku" name="arr[sku][]">' +
                    '</div>' +
                    '<div class="form-group col-sm-2 image">' + '<div class="form-group col-sm-2">' +
                    '<input type="text" class="hidden" value="" name="arr[main_image][]">' +
                    '</div>' +
                    '<a class="btn btn-success " title="设置图片"  onclick="add_pic(this)" href="javascript:void(0);">' +
                    '<span class="glyphicon glyphicon-picture"></span>' +
                    '</a>' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control "  placeholder="数量" name="arr[quantity][]" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control "  placeholder="单价" name="arr[price][]" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control "  placeholder="颜色" name="arr[color][]" >' +
                    '</div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" class="form-control "  placeholder="尺码" name="arr[size][]" >' +
                    '</div>' +
                    '<button type="button" class="btn btn-success " title="上架该SKU" onclick="operatSku(this,4)"><i class="glyphicon glyphicon-arrow-up"></i></button>' +
                    '<button type="button" class="btn btn-danger bt_right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button>' +
                    '</div>';
            $('#itemDiv').append(html);
        });





        $(document).on('click', '.bt_right', function () {
            $(this).parent().remove();

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

            }
        });


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
        /*
           1 上架
           2  下架
           3  更新线上信息
         */
        function operatSku(e, type) {
            var mark = e;
            var type =type;
            var sku = '';
            var inventory = '';
            var price = '';
            var size = '';
            var color = '';
            var main_image = '';
            var i=1;
            var account_id='';

            $("input[name='choose_account[]']:checkbox").each(function () {
                if ($(this).is(':checked')) {
                     account_id = $(this).val();

                }
            });
            $(mark).parent().find(":text").each(function () {
                if(i==1){ sku=$(this).val();}
                if(i==2){ main_image=$(this).val();}
                if(i==3){ inventory=$(this).val();}
                if(i==4){ price=$(this).val();}
                if(i==5){ color=$(this).val();}
                if(i==6){ size=$(this).val();}
                i++;
            });

            var  id=$("#id").val();
            var msrp = $("#msrp").val();
            var shipping_time = $("#shipping_time").val();
            var shipping = $("#shipping").val();
            var parent_sku =$("#parent_sku").val();
            $.ajax({
                url : "{{ route('wish.ajaxEditOnlineProduct') }}",
                data : {sku : sku,inventory:inventory,price:price,shipping:shipping,size:size,color:color,msrp:msrp,shipping_time:shipping_time,main_image:main_image,id:id,type:type,account_id:account_id,parent_sku:parent_sku},
                dataType : 'json',
                type : 'get',
                success : function(result) {
                    if(result.status==1){
                        if(result.data==1){
                            $(mark).parent().append('<button type="button" class="btn btn-warning" title="不启用该SKU" onclick="operatSku(this,2)"><i class="glyphicon glyphicon-remove"></i></button> <button type="button" class="btn btn-success " title="更新该SKU" onclick="operatSku(this,3)"><i class="glyphicon glyphicon-repeat"></i></button>');
                            $(mark).remove();


                        }
                        if(result.data==2){
                            $(mark).parent().append('<button type="button" class="btn btn-success" title="启用该SKU" onclick="operatSku(this,1)"><i class="glyphicon glyphicon-ok"></i></button>');
                            $(mark).next().remove();
                            $(mark).remove();
                        }

                        if(result.data==3){

                        }
                        if(result.data==4){
                            $(mark).parent().append('<button type="button" class="btn btn-warning" title="不启用该SKU" onclick="operatSku(this,2)"><i class="glyphicon glyphicon-remove"></i></button> <button type="button" class="btn btn-success " title="更新该SKU" onclick="operatSku(this,3)"><i class="glyphicon glyphicon-repeat"></i></button>');
                            $(mark).next().remove();
                            $(mark).remove();

                        }
                        alert(result.info);

                    }else{
                        alert(result.info);
                    }
                }
            });

        }
    </script>
@stop
