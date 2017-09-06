@extends('common.form')
@section('formAction') /purchaseItemList/excelReductionUpdate  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
      <div class="form-group col-lg-3">
      <strong>excel导入范本</strong>
      <a href="/purchaseItemList/purchaseItemPriceExcel">导入采购价格范本</a>
    </div>
     <div class="form-group col-lg-3">
       <strong>上传excel表格：</strong>
         <input id="file-1" type="file" name="excel">
    </div>
    </div>
@stop
 
 
 
 