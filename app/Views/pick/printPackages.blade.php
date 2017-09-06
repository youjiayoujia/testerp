@extends('common.form')
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>状态</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">包裹信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-1'>package ID</td>
                    <td class='col-lg-1'>订单号</td>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-1'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($packages as $package)
                    @if(empty($package->deleted_at))
                        @foreach($package->items()->get() as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}">
                                @if($key == '0')
                                <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
                                <td rowspan="{{$package->items()->count()}}" class='col-lg-1'>{{ $package->order ? $package->order->id : '订单号有误' }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>{{ $packageitem->item ? $packageitem->item->remark : '' }}</td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                @if($key == '0')
                                <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">{{ $package->status ? $package->status_name : '' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        @foreach($package->items()->withTrashed()->get() as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}">
                                @if($key == '0')
                                <td rowspan="{{$package->items()->withTrashed()->count()}}" class='package_id col-lg-1'>{{ $package->id }}</td>
                                <td rowspan="{{$package->items()->withTrashed()->count()}}" class='col-lg-1'>{{ $package->order ? $package->order->id : '订单号有误' }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>{{ $packageitem->item ? $packageitem->item->remark : '' }}</td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                @if($key == '0')
                                <td class='status col-lg-1' rowspan="{{$package->items()->withTrashed()->count()}}">已取消</td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('formButton')@stop