@extends('layouts.default')
@section('body')
@foreach($picklistitemsArray as $picklistitems)
<div class='container'>
    <div class='row'>
        <div class='form-group col-lg-offset-5'>
            <h3>仓库发货拣货单(面单尺寸:{{$size}})</h3>
        </div>
        <div class='form-group col-lg-offset-8'>
            <img src="{{ route('barcodeGen', ['content' => $model->picknum ])}}">
            <p>{{ $model->picknum }}</p>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label>ID:</label>
            <input type='text' class='form-control' value="{{ $model->picknum }}">
        </div>
        <div class='form-group col-lg-3'>
            <label>仓库</label>
            <input type='text' class='form-control' value="{{ $model->warehouse ? $model->warehouse->name : '仓库信息有误' }}">
        </div>
        <div class='form-group col-lg-3'>
            <label>状态:</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
        <div class='form-group col-lg-3'>
            <label>单号:</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label>类型:</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-3'>
            <label>物流:</label>
            <input type='text' class='form-control' value={{ $model->logistic ? $model->logistic->name : '混合物流' }}>
        </div>
        <div class='form-group col-lg-3'>
            <label>建单时间:</label>
            <input type='text' class='form-control' value={{ $model->created_at }}>
        </div>
        <div class='form-group col-lg-3'>
            <label>拣货人:</label>
            <input type='text' class='form-control' value={{ $model->pickByName ? $model->pickByName->name : '' }}>
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <th>sku</th>
            <th>库位</th>
            <th>数量</th>
            <th>注意事项</th>
            <th>品名</th>
            <th>拣货单id</th>
            <th>可用库存</th>
        </thead>
        <tbody>
            @foreach($picklistitems as $key => $picklistitem)
                @if($picklistitem->quantity - $picklistitem->packed_quantity > 0)
                <tr>
                    <td>{{ $picklistitem->items ? $picklistitem->items->sku : '' }}</td>
                    <td>{{ $picklistitem->position ? $picklistitem->position->name : '' }}</td>
                    <td>{{ $picklistitem->quantity - $picklistitem->packed_quantity }}</td>
                    <td>{{ $picklistitem->items ? $picklistitem->items->remark : '' }}</td>
                    <td>{{ $picklistitem->items ? $picklistitem->items->c_name : '' }}</td>
                    <td>{{ $model->picknum }}</td>
                    <td>{{ $picklistitem->items->stocks()->where('warehouse_position_id', $picklistitem->warehouse_position_id)->first()->all_quantity }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endforeach
@stop