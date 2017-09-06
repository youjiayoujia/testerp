@extends('common.form')
@section('formAction') {{ $action }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-3">
        <label>excel导入文件</label>
        <input type='file' name='excel'>
    </div>
    <a href='javascript:' class='btn btn-info download'>格式下载</a>
    <input type='hidden' class='type' data-type="{{isset($type) ? $type : ''}}">
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){
    $('.download').click(function(){
        if($('.type').data('type') == '4') {
            location.href="{{ route('package.downloadLogisticsTno')}}";
            return false;
        }
        if($('.type').data('type') == '3') {
            location.href="{{ route('package.downloadTrackingNo')}}";
            return false;
        }
        if($('.type').data('type')) {
            location.href="{{ route('package.downloadFee')}}";
        } else {
            location.href="{{ route('package.downloadType')}}";
        }
    });
});
</script>
