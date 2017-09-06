@extends('common.form')
@section('formAction') /purchaseItemList/reductionUpdate  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
      <div class="form-group col-lg-3">
        <label for="type">采购条目的ID：</label>(批量输入采购条目ID各之间用#隔开)
         <textarea name="purchaseItemIds" rows="4" cols="50" autofocus>
         </textarea>
    </div>
     <div class="form-group col-lg-3">
       <strong>批量操作：</strong>
         <select name="status">
         	<option value="0">还原</option>
            <option value="1">采购中</option>
            <option value="3">取消</option>
         </select>
    </div>
    </div>
@stop
 
 
 
 