@extends('common.detail')
@section('detailBody')
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
            @if(count($model))
            @foreach($model->groupBy('channel_id') as $key => $channel)
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
                    <td>{{ $model->sum('wait_send') }}</td>
                    <td>{{ $model->sum('sending') }}</td>
                    <td>{{ $model->sum('sended') }}</td>
                    <td>{{ $model->sum('more') }}</td>
                    <td>{{ $model->sum('less') }}</td>
                    <td>{{ $model->sum('daily_send') }}</td>
                    <td>{{ $model->sum('need') }}</td>
                    <td>{{ $model->sum('daily_sales') }}</td>
                    <td>{{ $model->sum('monty_sales') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@if(count($model))
@foreach($model->groupBy('warehouse_id') as $warehouseId => $body)
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
<script src="{{ asset('js/jquery.min.js') }}"></script>
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