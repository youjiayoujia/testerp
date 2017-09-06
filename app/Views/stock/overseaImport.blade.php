@extends('common.form')
@section('formAction')@stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-2">
            <label>sku</label>
        </div>
        <div class="form-group col-sm-2">
            <label>海外仓sku</label>
        </div>
        <div class="form-group col-sm-2">
            <label>海外仓sku单价</label>
        </div>
        <div class="form-group col-sm-2">
            <label>库位</label>
        </div>
        <div class="form-group col-sm-2">
            <label>调整 (正(入库)/负(出库))</label>
        </div>
        <div class="form-group col-sm-2">
            <label>备注</label>
        </div>
    </div>
    @foreach($arr as $key => $single)
    @if($key != 0)
    <div class='row'>
        <div class="form-group col-lg-2">
            <input type='text' class='form-control' name='arr[sku][]' value="{{ $arr[0][$single['key']]['sku']}}">
        </div>
        <div class="form-group col-sm-2">
            <input type='text' class='form-control' name='arr[oversea_sku][]' value="{{ $arr[0][$single['key']]['oversea_sku']}}">
        </div>
        <div class="form-group col-sm-2">
            <input type='text' class='form-control' name='arr[oversea_sku][]' value="{{ $arr[0][$single['key']]['oversea_cost']}}">
        </div>
        <div class="form-group col-sm-2">
            <input type='text' class='form-control' name='arr[position][]' value="{{ $arr[0][$single['key']]['position']}}">
        </div>
        <div class="form-group col-sm-2">
            <input type='text' class='form-control' name='arr[quantity][]' value="{{ isset($single['quantity']) ? $single['quantity'] : ''}}">
        </div>
        <div class="form-group col-sm-2">
            <input type='text' class='form-control' value="{{ isset($single['remark']) ? $single['remark'] : ''}}">
        </div>
    </div>
    @endif
    @endforeach
@stop
@section('formButton')@stop
