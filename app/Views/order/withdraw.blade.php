@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('withdrawUpdate', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="panel panel-default">
        <div class="panel-heading">撤单</div>
        <div class="panel-body">
            <div class="form-group col-lg-6">
                <label for="withdraw" class='control-label'>撤单原因</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="withdraw" id="withdraw">
                    <option value="NULL">==选择原因==</option>
                    @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                        <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                            {{ $withdraw }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@stop