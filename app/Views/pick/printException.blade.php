@extends('layouts.default')
@section('body')
    @foreach($buf as $key => $value)
    <div class='text-center'>
    <div class='row'>
        <div class='form-group col-lg-offset-3'>
            <img src="{{ route('barcodeGen', ['content' => $key ])}}">
            <p>包裹号:{{ $key }}</p>
        </div>
    </div>
    <div class='row'>
        <div class='form-group'>
            <p>{{ $packages[$key]->picklist->pickByName->name}}</p>
            @foreach($packages[$key]->items as $k1 => $v1)
                <p>sku:{{$v1->item->sku}} 数量:{{$v1->quantity}}</p>
            @endforeach
            <p>----------------未扫描-----------------</p>
            @foreach($buf[$key] as $sku => $quantity)
                <p>sku:{{$sku}} 数量:{{$quantity}}</p>
            @endforeach
        </div>
    </div>
    </div>
    @endforeach
@stop