<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-05
 * Time: 10:21
 */
?>
@extends('common.form')
@section('formAction')  @stop
@section('formBody')

    <input class="hidden" value="{{$model->site_id}}" id="site_id">

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>退货政策</label>
            <label for="name" class='control-label'>最后更新时间：
                @if(isset($model->updated_at))
                    {{$model->updated_at}}
                @endif
            </label>
            <a class="btn btn-primary" onclick="updateInfo('returns',this)">更新</a>

        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>运输方式</label>
            <label for="name" class='control-label'>最后更新时间：
                @if(isset($shipping_last_time))
                    {{$shipping_last_time}}
                @endif

            </label>
            <a class="btn btn-primary" onclick="updateInfo('shipping',this)">更新</a>

        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-12">
            <label for="name" class='control-label'>分类信息</label>
            <label for="name" class='control-label'>最后更新时间：
                @if(isset($category_last_time))
                    {{$category_last_time}}
                @endif
            </label>

            <a class="btn btn-primary" onclick="updateInfo('category',this)">更新</a>


        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-12" id="is_use">
            <label for="name" class='control-label'>站点是否启用：</label>
            <input type="radio" name="is_use"  value="1" onclick="isUsed(1)" {{ Tool::isChecked('is_use', '1',$model ) }}>是
            <input type="radio" name="is_use"  value="0" onclick="isUsed(0)" {{ Tool::isChecked('is_use', '0',$model ) }}>否
        </div>
    </div>
@section('formButton')
@show
@stop
<script type="text/javascript">

    function updateInfo(type, e) {
        var mark = e;
        $(mark).html('更新中,请耐心等待');
        var site = $("#site_id").val();
        $(mark).removeAttr('onclick');
        $.ajax({
            url: "{{ route('ebayDetail.ajaxUpdate') }}",
            data: {
                site: site,
                type: type
            },
            dataType: 'json',
            type: 'get',
            success: function (result) {
                if (result == 1) {
                    alert('更新成功');
                } else {
                    alert('更新失败');
                }
                window.location.reload();
            }
        });
    }

    function isUsed(value){
        var site = $("#site_id").val();
        $.ajax({
            url: "{{ route('ebayDetail.ajaxIsUse') }}",
            data: {
                site: site,
                value: value
            },
            dataType: 'json',
            type: 'get',
            success: function (result) {
                alert(result);
            }
        });
    }

</script>