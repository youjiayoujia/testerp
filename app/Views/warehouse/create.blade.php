@extends('common.form')
@section('formAction') {{ route('warehouse.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="仓库名字" name='name' value="{{ old('name') }}">
        </div>
        <div class='form-group col-lg-3'>
            <label for='city'>详细地址</label>
            <input type='text' class="form-control" name="address" placeholder="详细地址" value="{{ old('address') }}">
        </div>
        <div class='form-group col-lg-3'>
            <label for='city'>联系人</label>
            <select name='contact_by' class='form-control contact_by'>
                <option value=''></option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class='form-group col-lg-3'>
            <label for='city'>联系电话</label>
            <input type='text' class="form-control" name="telephone" placeholder="联系电话" value="{{ old('telephone') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="type">仓库类型</label>
            <div class='radio'>
                <label>
                    <input type='radio' name='type' value='local' {{ old('type') ? (old('type') == 'local' ? 'checked' : '') : 'checked' }}>本地仓库
                </label>
                <label>
                    <input type='radio' name='type' value='oversea' {{ old('type') ? (old('type') == 'oversea' ? 'checked' : '') : '' }}>海外仓库
                </label>
                <label>
                    <input type='radio' name='type' value='fbaLocal' {{ old('type') ? (old('type') == 'fbaLocal' ? 'checked' : '') : '' }}>海外中转仓
                </label>
            </div>
        </div>
        <div class="form-group col-lg-3">
            <label for="volumn">仓库体积(m3)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="volumn" placeholder="仓库体积" name='volumn' value="{{ old('volumn') }}">
        </div>
        <div class="form-group col-lg-3">
            <label>仓库编码(海外仓专用)</label>
            <input type='text' class="form-control" placeholder="仓库编码" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="is_available">仓库是否启用</label>
            <div class='radio'>
                <label>
                    <input type='radio' name='is_available' value='1' {{ old('is_available') ? (old('is_available') == '1' ? 'checked' : '') : 'checked' }}>启用
                </label>
                <label>
                    <input type='radio' name='is_available' value='0' {{ old('is_available') ? (old('is_available') == '0' ? 'checked' : '') : '' }}>不启用
                </label>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.contact_by').select2();
});
</script>
@stop