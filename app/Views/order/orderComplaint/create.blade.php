@extends('common.form')
@section('formAction') {{ route('orderComplaint.store') }} @stop
@section('formBody')
<input type="hidden" name="create_user_id" value="1">
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="row">
        <div class="form-group col-lg-4">
                <strong>投诉来类型：</strong>
                <input type="text" class="from-control" name="complaint_type" value="" />
            </div>
            <div class="form-group col-lg-4">
                <strong>order_item_id：</strong>
                <input type="text" class="from-control" name="order_item_id" value=""/>
              
            </div>
            <div class="form-group col-lg-4">
                <strong>投诉email：</strong>
                <input type="text" class="from-control" name="complaint_email" value=""/>
            </div>
            
		</div>
         <div class="row">
         <div class="form-group col-lg-4">
                <strong>投诉来源国：</strong>
                <input type="text" class="from-control" name="complaint_country" value=""/>
            </div>
            <div class="form-group col-lg-4">
                <strong>投诉描述：</strong>
                <textarea name="question" >
              	</textarea>
            </div>
           
		</div>
</div>

@stop
 