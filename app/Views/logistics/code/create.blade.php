@extends('common.form')

<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logisticsCode.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="logistics_id">物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="logistics_id" class="form-control" id="logistics_id">
                @foreach($logisticses as $logistics)
                    <option value="{{ $logistics->id }}" {{ Tool::isSelected('logistics_id', $logistics->id) }}>
                        {{ $logistics->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="code" class="control-label">跟踪号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="code" placeholder="跟踪号" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-3" id="package_id">
            <label for="package_id" class="control-label">包裹ID</label>
            <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id' value="{{ old('package_id') }}" disabled>
        </div>
        <div class="form-group col-lg-3" id="used_at">
            <label for="used_at" class="control-label">使用时间</label>
            <input class="form-control" id="used_at" placeholder="使用时间" name='used_at' value="{{ old('used_at') }}" disabled>
        </div>
        <div class="form-group col-lg-12" id="status">
            <label for="status" class="control-label">状态</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="status" value="1" disabled>启用
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="status" value="0" checked>未启用
                </label>
            </div>
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('#used_at').cxCalendar();
        document.getElementById('package_id').style.display='none';
        document.getElementById('used_at').style.display='none';
        document.getElementById('status').style.display='none';
    });
</script>