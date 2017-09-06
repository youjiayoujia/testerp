@extends('layouts.default')
@section('body')
@foreach($boxes as $box)
<div class='row'>
    <div class='form-group col-lg-6'>
        <div class='form-horizontal'>
            <div class='col-lg-3'>
                <h1>发货扫描</h1>
            </div><div class='col-lg-3'>
                <img src="{{ route('barcodeGen', ['content' => $box->boxnum])}}">
            </div><div class='col-lg-6'>
                <h1>萨拉摩尔SLME</h1>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <td>调拨单号:</td>
                <td>{{ $model->allotment_num }}</td>
                <td>目的仓库:</td>
                <td>{{ $model->inWarehouse ? $model->inWarehouse->name : '' }}</td>
            </tr>
            <tr>
                <td>运输方式:</td>
                <td>{{ $model->logistics ? ( $model->logistics->transport == '0' ? '海运' : '空运') : '' }}</td>
                <td>打印时间:</td>
                <td>{{ date('Y-m-d H:i:s', time()) }}</td>
            </tr>
            <tr>
                <td>创建人:</td>
                <td>{{ $model->allotmentBy ? $model->allotmentBy->name : '' }}</td>
                <td>创建时间:</td>
                <td>{{ $model->created_at }}</td>
            </tr>
        </table>
        
        <div class='form-horizontal'>
            <div class='col-lg-3'>
                <h1>收货扫描</h1>
            </div><div class='col-lg-3'>
                <img src="{{ route('barcodeGen', ['content' => $box->boxnum])}}">
            </div><div class='col-lg-6'>
                <h1>萨拉摩尔SLME</h1>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <td>箱号: {{ $box->boxnum }}</td>
                <td>实际重量: {{ $box->weight}} </td>
                <td>体积重: {{round($box->length * $box->height * $box->width / 6000, 3)}}</td>
                <td>尺寸: {{$box->length.'*'.$box->width.'*'.$box->height}}</td>
                <td>体积系数:{{ $box->weight != 0 ? round($box->length * $box->height * $box->width / 6000 / $box->weight, 3) : '重量为0' }}</td>
            </tr>
            <tr>
                <td>sku</td>
                <td>数量</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach($box->forms as $single)
                <td>{{$single->sku}}</td>
                <td>{{$single->quantity}}</td>
                <td></td>
                <td></td>
                <td></td>
            @endforeach
        </table>
    </div>
</div>
@endforeach
@stop