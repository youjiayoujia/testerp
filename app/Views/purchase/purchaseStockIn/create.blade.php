@extends('common.form')
@section('formAction') /purchaseStockIn/updateStorage @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formTitle') 
    <div class="panel-heading">
        <a href="{{route('purchaseStockIn.create')}}" class="btn btn-info btn-xs"> 单件入库</a> /
        <a href="/manyStockIn" class="btn btn-info btn-xs"> 多件入库</a>/
        <a href="{{route('purchaseStockIn.index')}}" class="btn btn-info btn-xs"> 已入库列表</a> 
    </div>
@stop
@section('formBody')
<input type="hidden" name="storageInType" onClick="checkType()" value="1">
    
       <div class="row">
            <div class="form-group col-lg-4">
                <strong>输入SKU</strong>: 单件入库
                <input type="text"class="form-control" name="sku" value="">
            </div>
		</div>
    
@stop
