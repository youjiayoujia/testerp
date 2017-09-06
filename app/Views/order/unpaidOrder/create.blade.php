@extends('common.form')
@section('formAction') {{ route('unpaidOrder.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="ordernum" class="control-label">买家ID/Email/订单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="ordernum" placeholder="买家ID/Email/订单号" name='ordernum' value="{{ old('ordernum') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="channel_id">销售账号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="channel_id" class="form-control" id="channel_id">
                @foreach($channels as $channel)
                    <option value="{{$channel->id}}" {{ Tool::isSelected('channel_id', $channel->id) }}>
                        {{$channel->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="customer_id">客服</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="customer_id" class="form-control" id="customer_id">
                <option value="{{$users->id}}" {{ Tool::isSelected('customer_id', $users->id) }}>
                    {{$users->name}}
                </option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="date" class="control-label">日期</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="date" placeholder="日期" name='date' value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="remark" class="control-label">要求</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="remark" id="remark">
                @foreach(config('order.remark') as $remark_key => $remark)
                    <option value="{{ $remark_key }}" {{ old('remark') == $remark_key ? 'selected' : '' }}>
                        {{ $remark }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-6">
            <label for="note" class="control-label">备注</label>
            <input class="form-control" id="note" placeholder="备注" name='note' value="{{ old('note') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="status" class="control-label">状态</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="status" id="status">
                @foreach(config('order.unpaid_status') as $status_key => $status)
                    <option value="{{ $status_key }}" {{ old('status') == $status_key ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('#date').cxCalendar();
        });
    </script>
@stop