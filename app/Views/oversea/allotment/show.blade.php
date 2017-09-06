@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_num }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outWarehouse ? $model->outWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inWarehouse ? $model->inWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>头程物流</strong>: {{ $model->logistics ? $model->logistics->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotmentBy ? $model->allotmentBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>状态</strong>: {{ config('oversea.allotmentStatus')[$model->status] }}
        </div>
        <div class="col-lg-2">
            <strong>审核人</strong>: {{ $model->checkBy ? $model->checkBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>审核状态</strong>: {{ $model->check_status == 'new' ? '未审核' : ($model->check_status == 'fail' ? '未审核' : '已审核') }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">调拨单sku列表</div>
    <div class="panel-body">
    <table class="table table-bordered">
        <thead>
            <tr>
            <th>sku</th>
            <th>库位</th>
            <th>数量</th>
            <th>实发数量</th>
            </tr>
        </thead>
        <tbody>
        @foreach($allotments as $allotment)
        <tr>
            <td>{{ $allotment->item ? $allotment->item->sku : '' }}</td>
            <td>{{ $allotment->position ? $allotment->position->name : '' }}</td>
            <td>{{ $allotment->quantity }}</td>
            <td>{{ $allotment->inboxed_quantity }}</td>
        </tr>
        @endforeach
        <tr>
            <td>sku总数:{{ $allotments->groupBy('item_id')->count() }}</td>
            <td>调拨总数:{{ $allotments->sum('quantity') }}</td>
            <td>实发总数:{{ $allotments->sum('inboxed_quantity') }}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr class='danger'>
        <th>名称</th>
        <th>值</th>
        <th>公式</th>
        </tr>
    </thead>
    <tbody>
    <tr class='success'><td>ERP总重量</td><td>{{$all_weight}}kg</td><td>=∑每个SKU的重量x实际发走的每个SKU体积系数</td></tr>
    <tr class='success'><td>实测总重量</td><td>{{$model->boxes ? $model->boxes->sum('weight') : ''}}kg</td><td>=∑各箱实际录入重量之和</td></tr>
    <tr class='success'><td>体积重</td><td>{{round($volumn, 2)}}</td><td>=∑每箱体积重（每箱子长*宽*高）÷6000</td></tr>
    <tr class='success'><td>实际总运费</td><td>{{ $model->fee }}￥</td><td>人工手动输入</td></tr>
    <tr class='success'><td>ERP总运费</td><td>{{ $model->boxes ? $model->boxes->sum('expected_fee') : ''}}￥</td><td>=头程物流单价xERP总重量</td></tr>
    <tr class='success'><td>预计到货时间</td><td>{{ $model->expected_date }}</td><td></td></tr>
    <tr class='success'><td>ERP总税金</td><td>{{ $model->virtual_rate }}</td><td></td></tr>
    <tr class='success'><td>实际总税金</td><td>{{ $model->actual_rate_value }}</td><td></td></tr>
    </tbody>
</table>

<div class="panel panel-info">
    <div class="panel-heading">装箱信息</div>
    <div class="panel-body">
    @foreach($boxes as $key => $box)
    <div class='row'>
        <div class="form-group col-lg-3">
            <strong>箱号</strong>: {{ $box->boxnum }}
        </div>
        <div class="form-group col-lg-3">
            <strong>物流方式</strong>: {{ $box->logistics ? $box->logistics->name : '' }}
        </div>
        <div class="form-group col-lg-3">
            <strong>体积(cm3)</strong>: {{ $box->length . '*' . $box->width . '*' . $box->height }}
        </div>
        <div class="form-group col-lg-3">
            <strong>预估重量(kg)</strong>: {{ $arr[$key] }}
        </div>
        <div class="form-group col-lg-3">
            <strong>实际重量(kg)</strong>: {{ $box->weight }}
        </div>
        <div class="form-group col-lg-3">
            <strong>体积重(kg)</strong>: {{ round($box->length * $box->height * $box->width / 6000, 3) }}
        </div>
        <div class="form-group col-lg-3">
            <strong>体积系数</strong>: {{ $box->weight != 0 ? round($box->length * $box->height * $box->width / 6000 / $box->weight, 3) : '重量为0' }}
        </div>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
            <th>sku</th>
            <th>数量</th>
            <th>申报名称</th>
            <th>申报价值</th>
            </tr>
        </thead>
        <tbody>
        @foreach($box->forms as $form)
        <tr class='success'>
            <td>{{ $form->sku }}</td>
            <td>{{ $form->quantity }}</td>
            <td>{{ $form->item->product->declared_en }}</td>
            <td>{{ $form->item->declared_value }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    @endforeach
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
@stop