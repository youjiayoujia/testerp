@extends('common.form')
@section('formAction')  {{ route('purchaseAbnormal.update', ['id' => $model->id]) }}  @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type="hidden" name="update_userid" value="2"/>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="warehouse">仓库:</label>
            {{$model->warehouse->name}}
        </div>
        <div class="form-group col-lg-4">
            <label for="sku_id">sku:</label>
            {{$model->sku}}
        </div>
        <div class="form-group col-lg-4">
            <label for="type">异常种类：</label>
            @foreach(config('purchase.purchaseItem.active') as $k=>$active)
                @if($model->active == $k)
                    <td>{{ $active }}</td>
                @endif
            @endforeach
        </div>
    </div>
    <div class="row">

        <div class="form-group col-lg-4">
            <label class='control-label'>异常状态:</label>
            <select name="active_status">
                @if($model->active == 1)
                    @foreach(config('purchase.purchaseItem.active_status.1') as $key=>$v)
                        <option {{$model->active_status == $key ? 'selected' : ''}} value="{{$key}}">{{$v}}</option>
                    @endforeach
                @elseif($model->active == 2)
                    @foreach(config('purchase.purchaseItem.active_status.2') as $key=>$v)
                        <option {{$model->active_status == $key ? 'selected' : ''}} value="{{$key}}">{{$v}}</option>
                    @endforeach
                @elseif($model->active == 3)
                    @foreach(config('purchase.purchaseItem.active_status.3') as $key=>$v)
                        <option {{$model->active_status == $key ? 'selected' : ''}} value="{{$key}}">{{$v}}</option>
                    @endforeach
                @elseif($model->active == 4)
                    @foreach(config('purchase.purchaseItem.active_status.4') as $key=>$v)
                        <option {{$model->active_status == $key ? 'selected' : ''}} value="{{$key}}">{{$v}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        @if($model->active == 1)
        @if($model->item->second_supplier_id >0)
            <div class="form-group col-lg-4">
                <label for="sku_id">辅助供应商:</label>
                <input type="radio" name="supplier_id" value="{{$secondSupplier->id}}">名：{{$secondSupplier->name}}&nbsp;电话：{{$secondSupplier->telephone}}
                &nbsp;地址：{{$secondSupplier->province}}{{$secondSupplier->city}}{{$secondSupplier->address}}
            </div>
            @else
            没有辅助供应商
            @endif
        @elseif($model->active == 2)
            <div class="form-group col-lg-4">
                <label for="sku_id">预计报等时间:</label>
                {{$model->wait_time}}
            </div>
            <div class="form-group col-lg-4">
                <label for="sku_id">报等备注:</label>
                {{$model->wait_remark}}
            </div>
        @endif
    </div>
@stop
 
 
 
 