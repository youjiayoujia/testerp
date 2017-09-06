@extends('common.form')
@section('formAction') {{ route('user.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="用户姓名" name='name' value="{{ $model->name }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>是否启用</label>
            <div>
                <input type='radio' name='is_available' value='0' {{$model->is_available ==0?'checked ':''}}>禁用
                <input type='radio' name='is_available' value='1' {{$model->is_available ==1?'checked ':''}}>启用
            </div>                   
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="email" class='control-label'>邮箱</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="email" placeholder="用户邮箱" name='email' value="{{ $model->email }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="password" class='control-label'>密码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='password' class="form-control" id="password" placeholder="用户密码" name='password' value="{{ $model->password }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="password" class='control-label'>所属仓库(可不选)</label>
            <select class='form-control' name='warehouse_id'>
                <option value=''></option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $model->warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="role_name" class='control-label'>选择用户对应的角色</label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            @foreach($roles as $role)
                <label class="checkbox-inline">
                    <input type="checkbox" id="role" value="{{$role->id}}" name="user_role[]" {{ in_array($role->id, $select_role)? 'checked' : '' }}> {{$role->role_name}}
                </label>
                <br>
            @endforeach
        </div>
    </div>


@stop