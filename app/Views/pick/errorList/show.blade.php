@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>拣货单号</strong>: {{ $model->picklist ? $model->picklist->picknum : '' }}
            </div>
            <div class="col-lg-2">
                <strong>拣货单类型</strong>: {{ $model->picklist ? ($model->picklist->type == 'SINGLE' ? '单单' : ($model->picklist->type == 'MULTI' ? '多多' : '单多')) : '' }}
            </div>
            <div class="col-lg-2">
                <strong>package ID</strong>: {{ $model->package_id }}
            </div>
            <div class="col-lg-2">
                <strong>状态</strong>: {{ $model->status ? '已处理' : '未处理' }}
            </div>
            <div class="col-lg-2">
                <strong>处理人</strong>: {{ $model->processByName ? $model->processByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>处理时间</strong>: {{ $model->process_time }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">包裹信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-3'>package ID</td>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>数量</td>
                    <td class='col-lg-3'>包装数量</td>
                </thead>
                <tbody>
                @foreach($packages as $package)
                    <table class='table table-bordered table-condensed'>
                    @foreach($package->items as $key => $packageitem)
                        <tr>
                            @if($key == '0')
                            <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-3'>{{ $package->id }}</td>
                            @endif
                            <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                            <td class='quantity col-lg-3'>{{ $packageitem->quantity}}</td>
                            <td calss='col-lg-3'>{{ $packageitem->picked_quantity }}</td>
                        </tr>
                    @endforeach
                    </table>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop