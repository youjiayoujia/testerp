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
@section('formAction')  {{ route('ebayOnline.batchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <input type='hidden' value='{{$param}}' name="param">

    <div class="form-group">
        <label for="model">Ebay ItemID：
            @foreach($data as $v)
                <a target="_blank" href="http://www.ebay.com/itm/{{$v->item_id}}">{{$v->item_id}}</a>
            @endforeach
        </label>
    </div>
    <div class="row">

    </div>

    <?php
    switch ($param) {

    case 'changeTitle':
    ?>
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="text-right">标题：</label></div>
        <div class="form-group col-sm-8">
            <input class="form-control" type="text" placeholder="标题" name="title" maxlength=80></div>
    </div>
    <div class="row ">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">子标题：</label>
        </div>
        <div class="form-group col-sm-6" name="sub_title">
            <input class="form-control" type="text" placeholder="副标题"  name="sub_title" maxlength=80 >
        </div>
    </div>
    <?php
    break;
    case 'changeDescription':
    ?>
    <div class="row form-group">
        <label class="col-sm-2 control-label">描述图片：</label>

        <div class="col-sm-10">
            <div id="description_picture">
                <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm from_local" lang="detail">从我的电脑选取</a> -->
                <a href="javascript:void(0);" class="btn btn-success btn-sm"
                   onclick="add_pic_in_description('add','1')">图片外链</a>
                <a class="btn btn-danger btn-xs  pic-del-all"><span
                            class="glyphicon glyphicon-trash"></span>全部删除</a>
                <b class="ajax-loading hide">图片上传中...</b>
            </div>
            <ul class="list-inline pic-detail">

            </ul>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2">
            <label for="subject" class="right">描述模板：</label>
            {{-- <a href="javascript:void(0);" class="btn btn-success btn-sm"
                onclick="previewDescription()">预览</a>--}}
        </div>
        <div class="form-group col-sm-1">
            <select class="form-control col-sm-1 select_select0" name="description_id" >
                <option value="">==请选择==</option>
                @foreach($description as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach

            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2">
            <label for="subject" class="text-right">描述标题：</label></div>
        <div class="form-group col-sm-8">
            <input class="form-control" type="text" placeholder="不填写 将自动使用广告标题" name="description_title" ></div>
    </div>

    <div class="row">
        <label class="col-sm-2 control-label">描述详情：</label>

        <div class="col-sm-9">
                    <textarea id="description" name="description">

                    </textarea>

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
    case 'endItem':
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

        function add_pic_in_description(type, value) {
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
                    '<input type="hidden" value="' + str + '" name="description_picture[]">' +
                    '<a class="pic-del" href="javascript: void(0);">删除</a>' +
                    '</div>' +
                    '</li>';
            $("#description_picture").next().append(html);
        }
    </script>

@stop