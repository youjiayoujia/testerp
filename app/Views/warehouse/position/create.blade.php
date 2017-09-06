@extends('common.form')
@section('formAction') {{ route('warehousePosition.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-4">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="库位名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ $warehouse->id == old('$warehouse->warehouse->id') ? 'selected' : '' }}>{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="remark">备注信息</label>
        <input type='text' class="form-control" id="remark" placeholder="备注信息" name='remark' value="{{ old('remark') }}">
    </div>
</div>
<div class='row'>
<div class="form-group col-lg-2">
    <label for="size">库位大小</label>
    <div class='radio'>
        <label>
            <input type='radio' name='size' value='small' {{ old('size') ? (old('size') == 'small' ? 'checked' :  '') : '' }}>小
        </label>
        <label>
            <input type='radio' name='size' value='middle' {{ old('size') ? (old('size') == 'middle' ? 'checked' :  '') : 'checked' }}>中
        </label>
        <label>
            <input type='radio' name='size' value='big' {{ old('size') ? (old('size') == 'big' ? 'checked' :  '') : '' }}>大
        </label>
    </div>       
</div>
<div class='form-group col-lg-6'>
    <div class='col-lg-2'>
        <label for='length'>长(cm)</label>
        <input type='text' name='length' class='form-control' placeholder='长' value={{ old('length')}}>
    </div>
    <div class='col-lg-2'>
        <label for='length'>宽(cm)</label>
        <input type='text' name='width' class='form-control' placeholder='宽' value={{ old('width') }}>
    </div>
    <div class='col-lg-2'>
        <label for='length'>高(cm)</label>
        <input type='text' name='height' class='form-control' placeholder='高' value={{ old('height')}}>
    </div>
</div>
<div class="form-group col-lg-2">
    <label for="is_available">库位是否启用</label>
    <div class='radio'>
        <label>
            <input type='radio' name='is_available' value='1' {{ old('is_available') ? (old('is_available') == '1' ? 'checked' :  '') : 'checked' }}>启用
        </label>   
        <label>
            <input type='radio' name='is_available' value='0' {{ old('is_available') ? (old('is_available') == '0' ? 'checked' :  '') : '' }}>不启用
        </label>
    </div>    
</div>
</div>
@stop