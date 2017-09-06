@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
<div class="panel-heading">三宝产品操作</div>
<div class="panel-body">
    <div class='row'>
    <form method="POST" action="{{ route('customsClearance.uploadProduct') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>批量上传三宝产品:</label>
        </div>
        <div class="row form-group col-lg-2">
            <input type='file' name='excel'>
        </div>
        <div class="form-group col-lg-5">
            <button type='submit' class='btn btn-info' value='submit'>submit</button>
            <a href='javascript:' class='downloadUploadProduct'>格式下载</a>
            <font>( CSV字段名称: model,hs_code,unit,f_model )</font>
        </div>
    </form>
    </div>
    <div class='row'>
    <form method="POST" action="{{ route('customsClearance.updateProduct') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>批量更新三宝产品(传basic):</label>
        </div>
        <div class="row form-group col-lg-2 ">
            <input type='file' name='excel'>
        </div>
        <div class="form-group col-lg-5">
            <button type='submit' class='btn btn-info' value='submit'>submit</button>
            <a href='javascript:' class='downloadUpdateProduct'>格式下载</a>
            <font>(CSV字段名称: model,hs_code,unit,f_model,status)</font>
        </div>
    </form>
    </div>
    <div class='row'>
    <form method="POST" action="{{ route('customsClearance.updateNumber') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>批量更新国家number:</label>
        </div>
        <div class="row form-group col-lg-2 ">
            <input type='file' name='excel'>
        </div>
        <div class="form-group col-lg-5">
            <button type='submit' class='btn btn-info' value='submit'>submit</button>
            <a href='javascript:' class='downloadNumber'>格式下载</a>
            <font>( CSV字段名称: code,number )</font>
        </div>
    </form>
    </div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading">三宝Package操作</div>
<div class="panel-body">
    <div class='row'>
    <form method="POST" action="{{ route('customsClearance.updateNanjing') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>批量csv更新"发往南京"状态(1为已发往,可设置0或1):</label>
        </div>
        <div class="row form-group col-lg-2">
            <input type='file' name='excel'>
        </div>
        <div class="form-group col-lg-5">
            <button type='submit' class='btn btn-info' value='submit'>submit</button>
            <a href='javascript:' class='downloadToNanjing'>格式下载</a>
            <font> ( CSV字段名称: package_id,is_tonanjing )</font>
        </div>
    </form>
    </div>
    <div class='row'>
    <form method="POST" action="{{ route('customsClearance.updateOver') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>批量csv更新"海关审结"状态(1为已审结,可设置0或1):</label>
        </div>
        <div class="row form-group col-lg-2 ">
            <input type='file' name='excel'>
        </div>
        <div class="form-group col-lg-5">
            <button type='submit' class='btn btn-info' value='submit'>submit</button>
            <a href='javascript:' class='downloadOver'>格式下载</a>
            <font> ( CSV字段名称: package_id,is_over )</font>
        </div>
    </form>
    </div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-body">
    <div class='row'>
        <div class='col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportProductZY') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>K通关订单导出(中邮):</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportProductEUB') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>K通关订单导出(EUB):</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportProductFed') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>K通关订单导出(FedEx):</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportProduct') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>K通关产品导出:</p>
                <p>输入Model: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
    </div>
    <div class='row'>
        <div class='col-lg-5'>
            <a href='javascript:' class='btn btn-info exportFailModel' value='导出未备案的Model'>导出未备案的Model</a>
            <a href='javascript:' class='btn btn-info exportFailItem' value='导出Item在架但Item3bao（不存在|hscode空|model不全）的产品'>导出Item在架但Item3bao（不存在|hscode空|f_model不全）的产品</a>
        </div>
    </div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading">收寄</div>
<div class="panel-body">
    <div class='row'>
        <div class='col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportNXB') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>邮政小包收寄导出:</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportEUB') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>E邮宝收寄导出:</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <form method="POST" action="{{ route('customsClearance.exportEUBWeight') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p>E邮宝重量导出:</p>
                <p>输入Package_id: </p>
                <textarea class='form-control' name='model'></textarea>
                <p><button type='submit' class='btn btn-info' value='submit'>submit</button></p>
            </form>
        </div>
        <div class='col-lg-offset-1 col-lg-2'>
            <p>NXB => 邮政小包</p>
            <p>EUB => E邮宝</p>
            <p>pick up => 收寄</p>
        </div>
    </div>
</div>
</div>
<script type='text/javascript'>
$(document).ready(function(){
    $('.downloadUploadProduct').click(function(){
        location.href="{{ route('customsClearance.downloadUploadProduct')}}";
    });

    $('.downloadUpdateProduct').click(function(){
        location.href="{{ route('customsClearance.downloadUpdateProduct')}}";
    });

    $('.downloadNumber').click(function(){
        location.href="{{ route('customsClearance.downloadNumber')}}";
    });

    $('.downloadToNanjing').click(function(){
        location.href="{{ route('customsClearance.downloadToNanjing')}}";
    });

    $('.downloadOver').click(function(){
        location.href="{{ route('customsClearance.downloadOver')}}";
    });

    $('.exportFailModel').click(function(){
        location.href="{{ route('customsClearance.exportFailModel')}}";
    });

    $('.exportFailItem').click(function(){
        location.href="{{ route('customsClearance.exportFailItem')}}";
    });
});
</script>
@stop
