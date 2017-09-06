@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('takingupdate') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="ID" class='control-label'>ID</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="sku" class='control-label'>sku</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="仓库" class='control-label'>仓库</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="库位" class='control-label'>库位</label>
        </div>
        <div class="form-group col-lg-1">
            <label for="可用数量" class='control-label'>可用数量</label>
        </div>
        <div class="form-group col-lg-1">
            <label for="hold数量" class='control-label'>hold数量</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="实盘数量" class='control-label'>实盘数量</label>
        </div>
    </div>
    @foreach($takings as $key => $taking)
        <div class='row'>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[id][{{$key}}]' class='form-control' value="{{ $taking->id }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[sku][{{$key}}]' class='form-control' value="{{ $taking->stock ? $taking->stock->item ? $taking->stock->item->sku : '' : '' }}" >
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[warehouse_id][{{$key}}]' class='form-control' value="{{ $taking->stock ? $taking->stock->warehouse ? $taking->stock->warehouse->name : '' : '' }}" >
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[warehouse_position_id][{{$key}}]' class='form-control' value="{{ $taking->stock ? $taking->stock->position ? $taking->stock->position->name : '' : '' }}" >
            </div>
            <div class="form-group col-lg-1">
                <input type='text' name='arr[available_quantity][{{$key}}]' class='form-control' value="{{ $taking->stock ? $taking->stock->available_quantity : '' }}" >
            </div>
            <div class="form-group col-lg-1">
                <input type='text' name='arr[hold_quantity][{{$key}}]' class='form-control' value="{{ $taking->stock ? $taking->stock->hold_quantity : '' }}" >
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[quantity][{{$key}}]' class='form-control' value="{{ $taking->quantity ? $taking->quantity : ($taking->stock ? $taking->stock->all_quantity : '')}}" {{ $taking->check_status == 'Y' ? 'readonly' : ''}}>
            </div>
        </div>
    @endforeach
@stop