@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockCarryOver.createCarryOverResult') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label for='stockTime'>时间</label>
            <input type='text' placeholder='xxxx-xx' name='stockTime' id='notday' class='form-control stockTime'>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">生成月结记录</button>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('#notday').blur(function(){
        if($(this).val()) {
            var notday = /^(\d){4}(-|\/)(\d){2}$/gi;
            if(!notday.test($(this).val())) {
                alert('日期不规范');
                $(this).val('');
            }
        }
    });
});
</script>