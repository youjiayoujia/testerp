@extends('common.form')
@section('formAction') {{ route('allotment.getLogistics', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label> 
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ old('allotment_id') ? old('allotment_id') : $model->allotment_id }}" readonly>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="type" class='control-label'>物流方式</label>
            <input type='text' name='type' class='form-control' placeholder="物流方式">
        </div>
        <div class="form-group col-lg-4">
            <label for="code" class='control-label'>物流号</label>
            <input type='text' name='code' class='form-control' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="fee" class='control-label'>物流费</label>
            <input type='text' name='fee' class='form-control' value="{{ old('fee') }}">
        </div>
    </div> 
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.logistics').select2();
})
</script>
@stop