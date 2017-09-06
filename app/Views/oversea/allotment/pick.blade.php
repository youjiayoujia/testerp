@extends('layouts.default')
@section('body')
<div class='col-lg-10 col-lg-offset-1'>
    <div class='row'>
        <div class='form-group col-lg-offset-5'>
            <h3>调拨单</h3>
        </div>
    </div>
    <div class='container col-lg-12'>
        <div class='row'>
            <div class='form-group col-lg-2'>
                <label>ID:</label>
                <input type='text' class='form-control' value="{{ $model->id }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>调拨单号:</label>
                <input type='text' class='form-control' value="{{ $model->allotment_num }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>审核人</label>
                <input type='text' class='form-control' value={{ $model->checkBy ? $model->checkBy->name : '' }}>
            </div>
            <div class='form-group col-lg-2'>
                <label>条码打印</label>
                <img src="{{ route('barcodeGen', ['content' => $model->allotment_num])}}">
                <p>{{ $model->allotment_num }}</p>
            </div>
        </div>
        <table class='table table-bordered'>
            <thead>
                <th>sku</th>
                <th>库位</th>
                <th>数量</th>
                <th>调拨单号</th>
            </thead>
            <tbody>
                @foreach($allotmentforms as $key => $allotmentform)
                <tr>
                    <td>{{ $allotmentform->item ? $allotmentform->item->sku : '' }}</td>
                    <td>{{ $allotmentform->position ? $allotmentform->position->name : '' }}</td>
                    <td>{{ $allotmentform->quantity }}</td>
                    <td>{{ $model->allotment_num }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop