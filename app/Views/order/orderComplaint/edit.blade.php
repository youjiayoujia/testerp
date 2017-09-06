@extends('common.form')
@section('formAction') {{ route('orderComplaint.update', ['id' => $model->id]) }} @stop
@section('formBody')
<input type="hidden" name="_method" value="PUT"/>
   <input type="hidden" name="update_user_id" value="2">
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="row">
        <div class="form-group col-lg-4">
                <strong>投诉来类型：</strong>
                <input type="text" class="from-control" name="complaint_type" value="{{$model->complaint_type}}" />
            </div>
            <div class="form-group col-lg-4">
                <strong>order_item_id：</strong>
                <input type="text" class="from-control" name="order_item_id" value="{{$model->order_item_id}}"/>
              
            </div>
            <div class="form-group col-lg-4">
                <strong>投诉email：</strong>
                <input type="text" class="from-control" name="complaint_email" value="{{$model->complaint_email}}"/>
            </div>
            
		</div>
         <div class="row">
         <div class="form-group col-lg-4">
                <strong>投诉来源国：</strong>
                <input type="text" class="from-control" name="complaint_country" value="{{$model->complaint_country}}"/>
            </div>
            <div class="form-group col-lg-4">
                <strong>投诉描述：</strong>
                <textarea name="question" >
                {{$model->question}}
              	</textarea>
            </div>
           
		</div>
</div>
@stop