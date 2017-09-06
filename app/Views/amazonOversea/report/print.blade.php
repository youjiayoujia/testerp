@extends('layouts.default')
@section('body')
@foreach($formArray as $forms)
<div class='col-lg-10 col-lg-offset-1'>
    <div class='row'>
        <div class='form-group col-lg-offset-5'>
            <h3>FBA调拨单</h3>
        </div>
    </div>
    <div class='container col-lg-12'>
        <div class='row'>
            <div class='form-group col-lg-2'>
                <label>ID:</label>
                <input type='text' class='form-control' value="{{ $model->id }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>发货名称:</label>
                <input type='text' class='form-control' value="{{ $model->shipment_name }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>发货地址</label>
                <input type='text' class='form-control' value={{ $model->from_address }}>
            </div>
            <div class='form-group col-lg-2'>
                <label>条码打印</label>
                <img src="{{ route('barcodeGen', ['content' => $model->id ])}}">
                <p>{{ $model->id }}</p>
            </div>
        </div>
        <div class='row'>
            <div class='form-group col-lg-2'>
                <label>plan Id:</label>
                <input type='text' class='form-control' value="{{ $model->plan_id }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>shipment Id:</label>
                <input type='text' class='form-control' value="{{ $model->shipment_id }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>reference Id:</label>
                <input type='text' class='form-control' value="{{ $model->reference_id }}">
            </div>
            <div class='form-group col-lg-2'>
                <label>reference Id:</label>
                <input type='text' class='form-control' value="{{ $model->reference_id }}">
            </div>
        </div>
        <table class='table table-bordered'>
            <thead>
                <th>sku</th>
                <th>库位</th>
                <th>数量</th>
                <th>FBA调拨单号</th>
            </thead>
            <tbody>
                @foreach($forms as $key => $form)
                <tr>
                    <td>{{ $form->item ? $form->item->sku : '' }}</td>
                    <td>{{ $form->position ? $form->position->name : '' }}</td>
                    <td>{{ $form->report_quantity }}</td>
                    <td>{{ $model->id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@stop