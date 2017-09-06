@extends('common.form')
@section('formAction') {{ route('pickList.statisticsProcess') }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="start_time" class='control-label'>起始时间</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control expected_date" placeholder="起始时间" name='start_time' value="{{ old('start_time') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="end_time" class='control-label'>结束时间</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control expected_date" placeholder="结束时间" name='end_time' value="{{ old('end_time') }}">
        </div>
    </div>
@stop

@section('pageJs')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script type='text/javascript'>
    $(document).ready(function(){
        $('.expected_date').cxCalendar();
    });
</script>
@stop