@extends('common.form')
@section('formAction') {{ route('CatalogRatesChannel.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>渠道名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="渠道英文名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">
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