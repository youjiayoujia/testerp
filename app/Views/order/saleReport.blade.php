@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading"><strong>筛选条件</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <input class="form-control" id="sku" placeholder="SKU" value="" name="sku">
                </div>
                <div class="col-lg-2">
                    <select class="form-control" name="site" id="site">
                        <option value="NULL">站点</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->site }}" {{ Tool::isSelected('site', $site->site) }}>
                                {{ $site->site }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1">
                    <button class="filter">查询</button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><a href="{{ route('sku.saleReport') }}"></a>EbaySku销量报表</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>SKU</th>
                    <th>平台</th>
                    <th>站点</th>
                    <th>1天销量</th>
                    <th>7天销量</th>
                    <th>14天销量</th>
                    <th>30天销量</th>
                    <th>90天销量</th>
                    <th>SKU创建时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $data['sku'] }}</td>
                        <td>{{ $data['channel_name'] }}</td>
                        <td>{{ $data['site'] }}</td>
                        <td>{{ $data['one_sale'] }}</td>
                        <td>{{ $data['seven_sale'] }}</td>
                        <td>{{ $data['fourteen_sale'] }}</td>
                        <td>{{ $data['thirty_sale'] }}</td>
                        <td>{{ $data['ninety_sale'] }}</td>
                        <td>{{ $data['created_at'] }}</td>
                    </tr>
                @endforeach
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
        var sku = $('#sku').val();
        var site = $('#site').val();
        if (sku || site) {
            location.href="{{route('sku.saleReport')}}/?sku="+sku+"&site="+site;
        }
    });
</script>