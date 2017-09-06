@extends('common.form')
@section('formAction') {{ route('pickList.processBase') }} @stop
@section('formBody')
    <div class='row'>
        <input type='hidden' name='flag' value="{{ $content }}">
        <div class='form-group col-lg-2'>
        	@if($content != 'forceOut')
            	<label>拣货单号:</label>
            	<input type='text' name='picknum' class='form-control mixed' placeholder="picknum">
            @else
                <label>包裹号(追踪号):</label>
            	<input type='text' name='picknum' class='form-control mixed' placeholder="package_id | tracking_no">
            @endif
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success submit">提交</button>
    <button type="reset" class="btn btn-default">取消</button>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.mixed').focus();

	$(document).on('keypress', function(event){
		if(event.keyCode == '13') {
			if($('.mixed').val()) {
				$('.submit').click();
			}
			return false;
		}
	})
})
</script>
@stop