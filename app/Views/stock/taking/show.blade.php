@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>盘点表ID</strong>: {{ $model->taking_id }}
            </div>
            <div class="col-lg-2">
                <strong>盘点人</strong>: {{ $model->stockTakingByName ? $model->stockTakingByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>盘点时间</strong>: {{ $model->stock_taking_time }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">盘点表信息</div>
        <div class="panel-body">
        <table class='table table-bordered'>
            <thead>
                <th>sku</th>
                <th>仓库</th>
                <th>库位</th>
                <th>可用数量</th>
                <th>hold数量</th>
                <th>总数量</th>
                <th>实盘数量</th>
                <th>盘点状态</th>
                <th>是否盘点更新</th>
            </thead>
            <tbody>
                @foreach($stockTakingForms as $stockTakingForm)
                <tr>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->item ? $stockTakingForm->stock->item->sku : '' : ''}}</td>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->warehouse ? $stockTakingForm->stock->warehouse->name : '' : ''}}</td>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->position ? $stockTakingForm->stock->position->name : '' : ''}}</td>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->available_quantity : '' }}</td>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->hold_quantity : '' }}</td>
                    <td>{{ $stockTakingForm->stock ? $stockTakingForm->stock->all_quantity : '' }}</td>
                    <td>{{ $stockTakingForm->quantity }}</td>
                    <td>{{ $stockTakingForm->stock_taking_status == 'more' ? '盘盈' : ($stockTakingForm->stock_taking_status == 'equal' ? '不处理' : '盘亏') }}</td>
                    <td>{{ $stockTakingForm->stock_taking_yn == '1' ? '是' : '否' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <?php echo $stockTakingForms->render(); ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">出库信息 : {{ $model->outwarehouse ? $model->outwarehouse->name : '' }}</div>
        <div class="panel-body">
        @foreach($stockouts as $stockout)
            <div class='row'>
                <div class="col-lg-2">
                    <strong>sku</strong>: {{ $stockout->stock ? $stockout->stock->item ? $stockout->stock->item->sku : '' : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>出库仓库</strong>: {{ $stockout->stock ? $stockout->stock->warehouse ? $stockout->stock->warehouse->name : '' : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>出库库位</strong>: {{ $stockout->stock ? $stockout->stock->position ? $stockout->stock->position->name : '' : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>出库数量</strong>: {{ $stockout->quantity }}
                </div>
                <div class="col-lg-2">
                    <strong>出库金额(￥)</strong>: {{ $stockout->amount }}
                </div>
                <div class="col-lg-2">
                    <strong>出库时间</strong>: {{ $stockout->created_at }}
                </div>
            </div>
        @endforeach
        <?php echo $stockouts->render(); ?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">入库信息 : {{ $model->inwarehouse ? $model->inwarehouse->name : '' }}</div>
        <div class="panel-body">
        @foreach($stockins as $stockin)
        <div class='row'>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $stockin->stock ? $stockin->stock->item ? $stockin->stock->item->sku : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库仓库</strong>: {{ $stockin->stock ? $stockin->stock->warehouse ? $stockin->stock->warehouse->name : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库库位</strong>: {{ $stockin->stock ? $stockin->stock->position ? $stockin->stock->position->name : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库数量</strong>: {{ $stockin->quantity }}
            </div>
            <div class="col-lg-2">
                <strong>入库金额(￥)</strong>: {{ $stockin->amount }}
            </div>
            <div class="col-lg-2">
                <strong>入库时间</strong>: {{ $stockin->created_at }}
            </div>
        </div>
        @endforeach
        <?php echo $stockins->render(); ?>
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
