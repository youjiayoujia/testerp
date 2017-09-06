@extends('common.form')
@section('formAction') {{ route('exportPackage.exportPackageDetail') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>模板名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='field_id' class='form-control field_id'>
                @foreach($fields as $field)
                    <option value="{{ $field->id }}" {{ old('field_id') ? ($field->id == old('field_id') ? 'selected' : '') : '' }}>{{ $field->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>渠道</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='channel_id' class='form-control channel_id'>
                    <option value=''>请选择渠道</option>
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}" {{ old('channel_id') ? ($channel->id == old('channel_id') ? 'selected' : '') : '' }}>{{ $channel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' class='form-control warehouse_id'>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') ? ($warehouse->id == old('warehouse_id') ? 'selected' : '') : '' }}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>包裹状态</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='status' class='form-control status'>
                @foreach($statuses as $key => $status)
                    <option value="{{ $key }}" {{ old('status') ? ($key == old('status') ? 'selected' : '') : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>物流</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='logistics_id' class='form-control logistics_id'>
                @foreach($logisticses as $logistics)
                    <option value="{{ $logistics->id }}" {{ old('logistics_id') ? ($logistics->id == old('logistics_id') ? 'selected' : '') : '' }}>{{ $logistics->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>起始发货时间</label>
            <input type='text' name='begin_shipped_at' class='form-control begin_shipped_at' placeholder='起始发货时间' value="{{ old('begin_shipped_at') ? old('begin_shipped_at') : '' }}">
        </div>

        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>结束发货时间</label>
            <input type='text' name='over_shipped_at' class='form-control over_shipped_at' placeholder='结束发货时间' value="{{ old('over_shipped_at') ? old('over_shipped_at') : ''}}">
        </div>

        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>根据挂号码导出</label>
            <div class='input-group'>
                <input type='file' name='accordingTracking'>
                <a href="javascript:" class='btn btn-info tracking_no'>模板</a>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>根据包裹id导出</label>
            <div class='input-group'>
                <input type='file' name='accordingPackageId'>
                <a href="javascript:" class='btn btn-info packageId'>模板</a>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.field_id').select2();
            $('.channel_id').select2();
            $('.status').select2();
            $('.logistics_id').select2();
            $('.begin_shipped_at').datetimepicker({theme: 'dark'});
            $('.over_shipped_at').datetimepicker({theme: 'dark'});

            $('.tracking_no').click(function () {
                location.href = "{{ route('exportPackage.getTnoExcel')}}";
            })

            $('.packageId').click(function () {
                location.href = "{{ route('exportPackage.getTnoExcelById')}}";
            })
        })
    </script>
@stop