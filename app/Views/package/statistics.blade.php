@extends('common.form')
@section('formAction') {{ route('package.exportData') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label for='start_time'>开始时间</label>
            <input type='text' placeholder='xxxx-xx-xx xx:xx' name='start_time' class='form-control start_time datetimepicker_dark'>
        </div>
        <div class='form-group col-lg-3'>
            <label for='end_time'>结束时间时间</label>
            <input type='text' placeholder='xxxx-xx-xx xx:xx' name='end_time' class='form-control end_time datetimepicker_dark'>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">导出交接单</button>
@stop
@section('pageJs')
<script>
    $('.datetimepicker_dark').datetimepicker({theme:'dark'})
</script>
@stop