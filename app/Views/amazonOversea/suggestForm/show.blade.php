@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>sku</strong>: {{ $model->item ? $model->item->sku : '' }}
            </div>
            <div class="col-lg-4">
                <strong>渠道sku</strong>: {{ $model->channel_sku }}
            </div>
            <div class="col-lg-4">
                <strong>fnsku</strong>: {{ $model->fnsku }}
            </div>
            <div class="col-lg-4">
                <strong>fba库存总数量</strong>: {{ $model->fba_all_quantity }}
            </div>
            <div class="col-lg-4">
                <strong>fba可用数量</strong>: {{ $model->fba_available_quantity }}
            </div>
            <div class="col-lg-4">
                <strong>7天销量</strong>: {{ $model->sales_in_seven }}
            </div>
            <div class="col-lg-4">
                <strong>14天销量</strong>: {{ $model->sales_in_fourteen }}
            </div>
            <div class="col-lg-4">
                <strong>建议采购数</strong>: {{ $model->suggest_quantity }}
            </div>
            <div class="col-lg-4">
                <strong>渠道帐号</strong>: {{ $model->account ? $model->account->account : '' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop