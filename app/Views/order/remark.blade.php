@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('remarkUpdate', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="panel panel-default">
        <div class="panel-heading">补充备注</div>
        <div class="panel-body">
            <div class="form-group col-lg-6">
                <label for="remark" class='control-label'>订单备注</label>
                <textarea class="form-control" rows="3" id="remark" name='remark'>{{ old('remark') }}</textarea>
            </div>
        </div>
    </div>
    @if(count($remarks) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">历史备注</div>
            <div class="panel-body">
                @foreach($remarks as $remark)
                    <div>
                        <div class="col-lg-2">{{ $remark->user?$remark->user->name:'系统创建' }}</div>
                        <div class="col-lg-2">{{ $remark->created_at }}</div>
                        <div class="col-lg-8">{{ $remark->remark }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@stop