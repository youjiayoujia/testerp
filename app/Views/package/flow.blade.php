@extends('common.detail')
@section('detailBody')
    <div class="text-center">
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-info" href="{{ route('order.createVirtualPackage') }}">
                    Do Package <span class="badge">{{ $ordernum }}</span>
                </a>
            </div>
            <div class="col-lg-8">
                <p type="button" class="btn btn-warning" >
                    CRM消息回复失败统计 <span class="badge">{{ $message_replies_failed}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-info" href="{{ route('package.putNeedQueue') }}">
                    匹配库存 <span class="badge">{{ $packageNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-warning" href="{{ route('package.assignLogistics') }}">
                    自动分配物流 <span class="badge">{{ $assignNum }}</span>
                </a>
            </div>
            <div class="col-lg-4 text-left">
                <a type="button" class="btn btn-default" href="{{ route('package.manualLogistics') }}">
                    手动分配物流 <span class="badge">{{ $assignFailed }}</span>
                </a>
                <a type="button" class="btn btn-default" href="{{ route('package.autoFailAssignLogistics') }}">
                    自动全部放入匹配物流
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-warning" href="{{ route('package.placeLogistics') }}">
                    物流商下单 <span class="badge">{{ $placeNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('package.manualShipping') }}">
                    手工发货 <span class="badge">{{ $manualShip }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.indexPrintPickList', ['content' => 'forceOut']) }}">
                    强制出库
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-info" href="{{ route('package.processingAssignStocks') }}">
                    尚需匹配库存 <span class="badge">{{ $weatherNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    生成拣货单 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'print']) }}">
                    打印拣货单 <span class="badge">{{ $printNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'single']) }}">
                    单单包装 <span class="badge">{{ $singlePack }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'singleMulti']) }}">
                    单多包装 <span class="badge">{{ $singleMultiPack }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'inbox']) }}">
                    多多分拣 <span class="badge">{{ $multiInbox }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'multi']) }}">
                    多多包装 <span class="badge">{{ $multiPack }}</span>
                </a>
            </div>
            <div class="col-lg-3 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.oldPrint') }}">
                    原面单重新打印
                </a>
                <a type="button" class="btn btn-default" href="{{ route('pickList.updatePrint') }}">
                    更换物流面单
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-success" href="{{ route('package.shipping') }}">
                    出库复检 <span class="badge">{{ $packageShipping }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">包裹中心</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>订单平台</td>
                    <td>待发货数量(不欠货/不能拣货)</td>
                    <td>发货中数量(不欠货/可以拣货)</td>
                    <td>已发货数量</td>
                    <td><font color='red'>拣货中(3天以上不欠货)</font></td>
                    <td><font color='red'>拣货中(最近3天)</font></td>
                    <td>当日已发货</td>
                    <td>欠货</td>
                    <td>昨日销售额</td>
                    <td>当月销售额</td>
                    <td>目标完成率</td>
                    <td>时间进度</td>
                    <td>日均销售额</td>
                </tr>
                </thead>
                <tbody>
                @if(count($reportModel))
                @foreach($reportModel->groupBy('channel_id') as $key => $channel)
                    <tr>
                        <td>{{ $channel->first()->channel ? $channel->first()->channel->name : '' }}平台</td>
                        <td>{{ $channel->sum('wait_send') }}</td>
                        <td>{{ $channel->sum('sending') }}</td>
                        <td>{{ $channel->sum('sended') }}</td>
                        <td><a href='javascript:' class='all' data-channelid="{{$key}}">{{ $channel->sum('more') }}</a></td>
                        <td>{{ $channel->sum('less') }}</td>
                        <td>{{ $channel->sum('daily_send') }}</td>
                        <td>{{ $channel->sum('need') }}</td>
                        <td>{{ $channel->sum('daily_sales') }}</td>
                        <td>{{ $channel->sum('monty_sales') }}</td>
                        <td></td>
                        <td>{{ round((strtotime('now') - strtotime(date('Y-m', strtotime($channel->first()->day_time))))/(strtotime(date('Y-m', date(strtotime('+1 month')))) - strtotime(date('Y-m', strtotime($channel->first()->day_time))))*100, 2) }}%</td>
                        <td>{{ round($channel->sum('monty_sales')/(strtotime('now') - strtotime(date('Y-m', strtotime($channel->first()->day_time)))/(strtotime('now') - strtotime('-1 day'))), 2) }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td>总计</td>
                        <td>{{ $reportModel->sum('wait_send') }}</td>
                        <td>{{ $reportModel->sum('sending') }}</td>
                        <td>{{ $reportModel->sum('sended') }}</td>
                        <td>{{ $reportModel->sum('more') }}</td>
                        <td>{{ $reportModel->sum('less') }}</td>
                        <td>{{ $reportModel->sum('daily_send') }}</td>
                        <td>{{ $reportModel->sum('need') }}</td>
                        <td>{{ $reportModel->sum('daily_sales') }}</td>
                        <td>{{ $reportModel->sum('monty_sales') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @if(count($reportModel))
    @foreach($reportModel->groupBy('warehouse_id') as $warehouseId => $body)
        <div class="panel panel-default">
            <div class="panel-heading">{{ $body->first()->warehouse ? $body->first()->warehouse->name : '仓库未对应' }}中心</div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>订单平台</td>
                        <td>待发货数量(不欠货/不能拣货)</td>
                        <td>发货中数量(不欠货)</td>
                        <td>已发货数量</td>
                        <td><font color='red'>拣货中(3天以上不欠货)</font></td>
                        <td><font color='red'>拣货中(最近3天)</font></td>
                        <td>当日已发货</td>
                        <td>欠货</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($body->groupBy('channel_id') as $k => $channel)
                        <tr>
                            <td>{{ $channel->first()->channel ? $channel->first()->channel->name : '' }}平台</td>
                            <td>{{ $channel->sum('wait_send') }}</td>
                            <td>{{ $channel->sum('sending') }}</td>
                            <td>{{ $channel->sum('sended') }}</td>
                            <td><a href='javascript:' class='single' data-channelid="{{$k}}" data-flag='more' data-warehouseid="{{$warehouseId}}">{{ $channel->sum('more') }}</a></td>
                            <td><a href='javascript:' class='single' data-channelid="{{$k}}" data-flag='less' data-warehouseid="{{$warehouseId}}">{{ $channel->sum('less') }}</a></td>
                            <td>{{ $channel->sum('daily_send') }}</td>
                            <td>{{ $channel->sum('need') }}</td>
                        </tr>
                    @endforeach
                        <tr>
                            <td>总计</td>
                            <td>{{ $arr[$warehouseId][0] }}</td>
                            <td>{{ $arr[$warehouseId][1] }}</td>
                            <td>{{ $arr[$warehouseId][2] }}</td>
                            <td>{{ $arr[$warehouseId][3] }}</td>
                            <td>{{ $arr[$warehouseId][4] }}</td>
                            <td>{{ $arr[$warehouseId][5] }}</td>
                            <td>{{ $arr[$warehouseId][6] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    @endif
@stop
@section('detailTitle')@parent <font color='red'>(面单打印不产生出入库信息)</font> @stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.all', function () {
        id = $(this).data('channelid');
        location.href="{{route('package.index')}}/?outer=all&id="+id;
    });

    $(document).on('click', '.single', function () {
        id = $(this).data('channelid');
        outer = $(this).data('warehouseid');
        flag = $(this).data('flag');
        location.href="{{route('package.index')}}/?outer="+outer+"&id="+id+"&flag="+flag;
    });
})
</script>
@stop