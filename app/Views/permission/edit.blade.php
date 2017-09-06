@extends('common.form')
@section('formAction') {{ route('permission.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="action_name" class='control-label'>权限</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="action_name" placeholder="权限" name='action_name' value="{{ $model->action_name }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="route" class='control-label'>route</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="route" placeholder="route" name='route' value="{{ $model->route }}">
        </div>
    </div>

    <div class="form-group col-lg-6">
            <label for="parent_id" class='control-label'>权限所属类</label>
                <select name="parent_id">
                    @foreach(config('permission.parent.name') as $key=>$name)
                        <option value="{{ $key }}" {{ $key == $model->parent_id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
        </div>
@stop