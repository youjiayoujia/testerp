@extends('common.form')
@section('formAction') {{ route('inOut.exportResult') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label>sku</label>
            <input type='text' name='item_id' class='form-control'>
        </div>
        <div class='form-group col-lg-4'>
            <label for='start_time'>开始时间</label>
            <input type='text' placeholder='xxxx-xx-xx xx:xx' name='start_time' class='form-control start_time datetimepicker_dark'>
        </div>
        <div class='form-group col-lg-4'>
            <label for='end_time'>结束时间时间</label>
            <input type='text' placeholder='xxxx-xx-xx xx:xx' name='end_time' class='form-control end_time datetimepicker_dark'>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-8">
            <label for="size">类型</label>
            <div class='radio'>
            @foreach($types as $key => $type)
                <label>
                    <input type='checkbox' name='types[]' value="{{ $key }}"}}>{{$type}}
                </label>
            @endforeach
            </div>       
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.datetimepicker_dark').datetimepicker({theme:'dark'});
    });
</script>
@stop