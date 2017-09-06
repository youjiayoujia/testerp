@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading"><strong>日期查询</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <input class="form-control start" id="start" placeholder="开始日期" value="{{ $start }}" name="start">
                </div>
                <div class="col-lg-2">
                    <input class="form-control end" id="end" placeholder="结束日期" value="{{ $end }}" name="end">
                </div>
                <div class="col-lg-1">
                    <button class="filter">筛选</button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><a href="{{ route('package.logisticsDelivery') }}"></a>物流发货统计</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>物流方式</th>
                    <th>物流编号</th>
                    <th>优先级</th>
                    <th>今日数量</th>
                    <th>重量小计</th>
                    <th>百分比</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $data['logisticsName'] }}</td>
                        <td>{{ $data['logisticsId'] }}</td>
                        <td>{{ $data['logisticsPriority'] }}</td>
                        <td>{{ $data['quantity'] }}</td>
                        <td>{{ $data['weight'] }}</td>
                        <td>{{ $data['percent'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center" colspan="6">
                        {{ '当前包裹数:' . $count . ' 当前总重:' . $totalWeight . 'Kg' }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#start').cxCalendar();
        $('#end').cxCalendar();
    });

    $(document).on('click', '.filter', function () {
        var start = $('#start').val();
        var end = $('#end').val();
        if (start && end) {
            location.href="{{route('package.logisticsDelivery')}}/?start="+start+"&end="+end;
        }
    });
</script>