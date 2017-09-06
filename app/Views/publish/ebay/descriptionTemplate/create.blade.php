<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 14:10
 */
?>

@extends('common.form')
@section('formAction') {{ route('ebayDescription.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">模板名称：</label>
        </div>
        <div class="form-group col-sm-6">
                <input type="text" class="form-control" name="name">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right"></label>
        </div>
        <div class="form-group col-sm-6">
           <span class="text-danger">
            <?php
               echo '注意事项： 先将下面编辑框设置为HTMl代码，{'.'{tittle}} 表示模板标题放置的地方 {'.'{description}} 表示产品描述放置的地方 {'.'{picture}} 表示产品图片放置的地方';
               ?>
            </span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">站点：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="site">
                <option value="">==请选择==</option>
                @foreach(config('ebaysite.site_name_id') as $name=>$id)
                    <option value="{{$id}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">仓库：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="warehouse">
                <option value="">==请选择==</option>
                @foreach(config('ebaysite.warehouse') as $key=>$name)
                    <option value="{{$key}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="row">
        <label class="col-sm-1 control-label">描述详情：</label>

        <div class="col-sm-9">
                    <textarea id="description" name="description">

                    </textarea>

        </div>

    </div>

@stop



@section('pageJs')
    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
    <script type="text/javascript">
        $('.select_select0').select2();
        var content = UM.getEditor('description', {
            //initialFrameHeight: 500
        });
        content.setWidth("100%");
        $(".edui-body-container").css("width", "100%");
    </script>

@stop
