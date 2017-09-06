@extends('common.form')
@section('formAction')@stop
@section('formBody')
<div class='form-horizontal'>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control packageid' placeholder='包裹id'>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control trackingno' placeholder='追踪号'>
    </div>
    <div class='input-group-btn col-lg-2'>
        <button type='button' class='btn btn-info search'>查询</button>
    </div>
</div>
</div>
<div class='buf form-group'>

</div>
@stop
@section('formButton')@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.search').click(function(){
        packageid = $('.packageid').val();
        trackingno = $('.trackingno').val();
        if(packageid || trackingno) {
            $.get(
                "{{ route('package.getAllInfo')}}",
                {packageid:packageid, trackingno:trackingno},
                function(result){
                    $('.buf').html(result);
                }
            )
        }
    })
});
</script>
@stop