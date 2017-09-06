@extends('common.form')
@section('formAction') {{ route('package.storeAllocateLogistics', ['id' => $id]) }} @stop
@section('formBody')
<div class='row'>
    <div class="col-lg-2">
        <label>物流方式</label>
        <select name='logistics_id' class='form-control'>
        @foreach($logisticses as $logistics)
            <option value="{{ $logistics->id }}">{{ $logistics->code }}</option>
        @endforeach
        </select>
    </div>
</div>
@stop