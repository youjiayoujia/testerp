@extends('common.form')
@section('formAction') {{ route('exportPackage.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>模板名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" placeholder="模板名" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">字段列表</div>
        <div class="panel-body">
            @foreach($fields as $key => $field)
                <div class="row">
                    <div class='form-grou col-lg-2'>
                        <input type='checkbox' name='fieldNames[]' value="{{ $key }}">{{ $field }}
                    </div>
                    <div class='form-group col-lg-2'>
                        <input type='text' class="form-control col-lg-2" placeholder="默认字段名,可不填" name='{{$key}},name' value="{{ old('$key'+',name') }}">
                    </div>
                    <div class='form-group col-lg-2'>
                        <input type='text' class="form-control col-lg-2" placeholder="字母或数字用来排序" name='{{$key}},level' value="{{ old('$key'+',level') }}">
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
        </div>
        <div class="panel-footer create_form">
            <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
@stop

@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            current = 1;
            $(document).on('click', '.create_form', function () {
                $.ajax({
                    url: "{{ route('exportPackage.extraField') }}",
                    data: {current: current},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $('.add_row').children('div:last').after(result);
                    }
                });
                current++;
            });

            $(document).on('click', '.bt_right', function () {
                $(this).parent().remove();
            });
        })
    </script>
@stop