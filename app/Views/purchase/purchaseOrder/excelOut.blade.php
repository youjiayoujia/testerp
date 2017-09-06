@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">采购单导出</div>
        <div class="panel-body">
           
            <div class="form-group col-lg-4">
            	<strong>导出所有采购单</strong>:    
                	<a href="/purchaseOrder/excelOrderOut/0" class="btn btn-info btn-xs"> 导出
                </a>       
            </div>  
            <div class="form-group col-lg-4">
            	<strong>导出三天未到货的采购单</strong>:    
                	<a href="/purchaseOrder/excelOrderOut/3" class="btn btn-info btn-xs"> 导出
                </a>       
            </div>           
        </div>
    </div>   
@stop
