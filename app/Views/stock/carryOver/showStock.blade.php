@extends('common.form')
@section('formAction') {{ route('stockCarryOver.showStockView') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label for='stockTime'>开始时间</label>
            <input type='text' placeholder='xxxx-xx-xx xx:xx 24进制' id='datetimepicker_dark' name='stockTime' class='form-control'>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">查看</button>
@stop
@section('pageJs')
<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.css') }}">
<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
<script>
    $('#datetimepicker_dark').datetimepicker({theme:'dark'})
</script>
@stop