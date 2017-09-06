@extends('common.form')
@section('formAction') {{ route('box.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label>物流方式</label>
            <select name='logistics_id' class='form-control logistics'>
                <option value=""></option>
            @foreach($logisticses as $logistics)
                <option value="{{ $logistics->id }}" {{ $model->logistics_id == $logistics->id ? 'selected' : '' }}>{{ $logistics->code }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label>追踪号</label>
            <input type='text' name='tracking_no' class='form-control' value="{{ old('tracking_no') ? old('tracking_no') : $model->tracking_no }}">
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