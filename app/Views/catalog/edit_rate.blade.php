@extends('common.form')
@section('formAction')  {{ route('updateCatalogRates') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$filters}}' name="filter">
    <div class="form-group">
        <label for="model">正在修改的分类名：</label>
    </div>
    <div class="row">
        @foreach($catalogs as $catalog)
            <div class="form-group col-md-1">
                <label for="model">{{$catalog->c_name}}</label>
            </div>
        @endforeach
    </div>
    <div class="row">
        @foreach($channels as $channel)
            <div class="form-group col-md-3">
                <label for="color">{{$channel->name}}</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control rates" placeholder="{{$channel->name}}" name='{{$channel->id}}' value=" ">
            </div>
       @endforeach
    </div>
@stop