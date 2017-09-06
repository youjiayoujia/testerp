@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">包裹中心</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>订单平台</th>
                <th>待发货数量(不欠货/不能拣货)</th>
                <th>发货中数量(不欠货/可以拣货)</th>
                <th>已发货数量</th>
                <th><font color='red'>拣货中(3天以上不欠货)</font></th>
                <th><font color='red'>拣货中(最近3天)</font></th>
                <th>当日已发货</th>
                <th>欠货</th>
                <th>昨日销售额</th>
                <th>当月销售额</th>
                <th>目标完成率</th>
                <th>时间进度</th>
                <th>日均销售额</th>
            </tr>
            </thead>
            <tbody>
            @foreach($all as $key => $channel)
                <tr>
                    <td>{{ $channel->first()->channel ? $channel->first()->channel->name : '' }}平台</td>
                    <td>{{ $channel->filter(function($single){
                        return in_array($single->status, ['ASSIGNED', 'ASSIGNFAILED', 'TRACKINGFAILED']);
                    })->count() }}</td>
                    <td>{{ $channel->filter(function($single){
                        return in_array($single->status, ['PREOCESS', 'PICKING', 'PACKED']);
                    })->count() }}</td>
                    <td>{{ $channel->filter(function($single){
                        return $single->status == 'SHIPPED';
                    })->count() }}</td>

                    <td><a href='javascript:' class='all' data-channelid="{{$key}}">{{ $channel->filter(function($single){
                        return $single->status == 'PICKING' && strtotime($single->created_at) < strtotime('-3 days');
                    })->count() }}</a></td>
                    <td>{{ $channel->filter(function($single){
                        return $single->status == 'PICKING' && strtotime($single->created_at) > strtotime('-3 days');
                    })->count() }}</td>
                    <td>{{ $channel->filter(function($single){
                        return date('Y-m', strtotime($single->created_at)) == date('Y-m', strtotime($single->shipped_at));
                    })->count()}}</td>
                    <td>{{ $channel->where('status', 'NEED')->count() }}</td>
                    <td>{{ $arr[$key][0] }}</td>
                    <td>{{ $arr[$key][1] }}</td>
                    <td>{{ 1111 }}</td>
                    <td>{{ 22 }}</td>
                    <td>{{ 255 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@foreach($allByWarehouseId as $warehouseId => $body)
    <div class="panel panel-default">
        <div class="panel-heading">{{ $body->first()->warehouse ? $body->first()->warehouse->name : '仓库未对应' }}中心</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>订单平台</th>
                    <th>待发货数量(不欠货/不能拣货)</th>
                    <th>发货中数量(不欠货)</th>
                    <th>已发货数量</th>
                    <th><font color='red'>拣货中(3天以上不欠货)</font></th>
                    <th><font color='red'>拣货中(最近3天)</font></th>
                    <th>当日已发货</th>
                    <th>欠货</th>
                </tr>
                </thead>
                <tbody>
                @foreach($body->groupBy('channel_id') as $k => $channel)
                    <tr>
                        <td>{{ $channel->first()->channel ? $channel->first()->channel->name : '' }}平台</td>
                        <td>{{ $channel->filter(function($single){
                            return in_array($single->status, ['ASSIGNED', 'ASSIGNFAILED', 'TRACKINGFAILED']);
                        })->count() }}</td>
                        <td>{{ $channel->filter(function($single){
                            return in_array($single->status, ['PREOCESS', 'PICKING', 'PACKED']);
                        })->count() }}</td>
                        <td>{{ $channel->filter(function($single){
                            return $single->status == 'SHIPPED';
                        })->count() }}</td>
                        <td><a href='javascript:' class='single' data-channelid="{{$k}}" data-flag='more' data-warehouseid="{{$warehouseId}}">{{ $channel->filter(function($single){
                            return $single->status == 'PICKING' && strtotime($single->created_at) < strtotime('-3 days');
                        })->count() }}</a></td>
                        <td><a href='javascript:' class='single' data-channelid="{{$k}}" data-flag='less' data-warehouseid="{{$warehouseId}}">{{ $channel->filter(function($single){
                            return $single->status == 'PICKING' && strtotime($single->created_at) > strtotime('-3 days');
                        })->count() }}</a></td>
                        <td>{{ $channel->filter(function($single){
                            return date('Y-m', strtotime($single->created_at)) == date('Y-m', strtotime($single->shipped_at));
                        })->count()}}</td>
                        <td>{{ $channel->where('status', 'NEED')->count() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
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