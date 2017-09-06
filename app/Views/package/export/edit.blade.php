@extends('common.form')
@section('formAction') {{ route('exportPackage.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>模板名</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="模板名" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">字段列表</div>
        <div class="panel-body">
            @foreach($fields as $key => $field)
            <div class="row">
                <div class='form-grou col-lg-2'>
                    <input type='checkbox' name='fieldNames[]' value="{{ $key }}" {{ $model->inFields($key) ? 'checked' : ''}}>{{ $field }}
                </div>
                <div class='form-group col-lg-2'>
                    <input type='text' class="form-control col-lg-2" name='{{$key}},name' value="{{ old('$key'+',name') ? old('$key'+',name') : ($model->inFields($key) ? $model->inFields($key)->defaultName : '') }}" placeholder='默认字段名，可不填'>
                </div>
                <div class='form-group col-lg-2'>
                    <input type='text' class="form-control col-lg-2" name='{{$key}},level' value="{{ old('$key'+',level') ? old('$key'+',level') : ($model->inFields($key) ? $model->inFields($key)->level : '') }}" placeholder='字母或数字用来排序'>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">其他字段</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <label>字段名</label>
                </div>
                <div class='form-group col-sm-2'>
                    <label>字段值</label>
                </div>
                <div class='form-group col-sm-2'>
                    <label>字段排序</label>
                </div>
            </div>
            @foreach($extras as $key => $extra)
            <div class='row'>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control" placeholder="字段名" name='arr[fieldName][{{$key}}]' value="{{ $extra->fieldName }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control" placeholder="字段值" name='arr[fieldValue][{{$key}}]' value="{{ $extra->fieldValue }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control" placeholder="字段排序值" name='arr[fieldLevel][{{$key}}]' value="{{ $extra->fieldLevel }}">
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
            @endforeach
        </div>
        <div class="panel-footer create_form">
            <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    current = 1;
    $(document).on('click', '.create_form', function(){
        $.ajax({
            url:"{{ route('exportPackage.extraField') }}",
            data:{current:current},
            dataType:'html',
            type:'get',
            success:function(result) {
                $('.add_row').children('div:last').after(result);
            }
        });
        current++;
    });

    $(document).on('click', '.bt_right', function(){
        $(this).parent().remove();
    });
})
</script>
@stop