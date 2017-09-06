@extends('common.form')
@section('formAction') {{ route('channel.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>渠道名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>API类型</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="driver">
                @foreach($drivers as $driver)
                    <option value="{{ $driver }}" {{ Tool::isSelected('driver', $driver) }}>{{ $driver }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="brief" class='control-label'>描述</label>
            <textarea class="form-control" rows="3" name="brief">{{ old('brief') }}</textarea>
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.flat_rate').change(function(){
        if($(this).val() == 'catalog') {
            $('.flat_rate_value').val('');
            $('.flat_rate_value').prop('disabled', true);
        } else {
            $('.flat_rate_value').prop('disabled', false);
        }
    });

    $('.rate').change(function(){
        if($(this).val() == 'catalog') {
            $('.rate_value').val('');
            $('.rate_value').prop('disabled', true);
        } else {
            $('.rate_value').prop('disabled', false);
        }
    });
});
</script>
@stop