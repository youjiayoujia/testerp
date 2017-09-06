@extends('common.form')
@section('formAction') {{ route('permission.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="action_name" class='control-label'>权限</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="action_name" placeholder="权限" name='action_name' value="{{ old('action_name') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="controller" class='control-label'>Controller</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control col-xs-2" id="controller" placeholder="controller" name='controller' value="{{ old('controller') }}">
        </div>

        <div class="form-group col-lg-6">
            <label for="action" class='control-label'>Action</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control col-xs-2" id="action" placeholder="action" name='action' value="{{ old('action') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="parent_id" class='control-label'>权限所属类</label>
                <select name="parent_id">
                    @foreach(config('permission.parent.name') as $key=>$name)
                        <option value="{{ $key }}">
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
        </div>
    </div>

@stop