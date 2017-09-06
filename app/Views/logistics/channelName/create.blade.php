@extends('common.form')
@section('formAction') {{ route('logisticsChannelName.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="channel" class="control-label">渠道</label>
            <select name='channel_id' class='form-control'>
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
            @endforeach
            </select>
        </div>
        <div class='form-group col-lg-2'>
            <label for="name" class="control-label">名称</label>
            <input type='text' name='name' placeholder='名称' class='form-control' value="{{ old('name') ? old('name') : ''}}">
        </div>
        <div class='form-group col-lg-2'>
            <label for="logistics_key" class="control-label">回传编码</label>
            <input type='text' name='logistics_key' placeholder='回传编码' class='form-control' value="{{ old('logistics_key') ? old('logistics_key') : ''}}">
        </div>
    </div>
@stop