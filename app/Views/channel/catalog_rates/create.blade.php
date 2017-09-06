@extends('common.form')
@section('formAction') {{ route('CatalogRatesChannel.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>产品分类渠道名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="产品分类渠道名称" name='name' value="{{ old('name') }}">
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