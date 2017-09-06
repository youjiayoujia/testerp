@extends('common.form')
@section('formAction') /purchaseOrder/createItem/{{$purchase_order_id}}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="userid" value="2"/>
        <div class="row">
         <div class="form-group col-lg-4">
                <strong>sku:</strong>
                <input type="sku" class="form-control" name="sku" value="">
            </div>
            <div class="form-group col-lg-4">
                <strong>数量</strong>:
                <input type="sku" class="form-control" name="purchase_num" value=""> 
            </div>
            <div class="form-group col-lg-4">
                <strong>单价</strong>:
                <input type="sku" class="form-control" name="purchase_cost" value=""> 
            </div>  
            </div>                 
      </div>
 
@stop