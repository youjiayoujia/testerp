@extends('common.table')
@section('tableHeader')
    <th>平台</th>
    <th>用户</th>
    <th class="sort" data-field="prefix">刊登前缀</th>
    <th class="sort" data-field="january_sales">1月累计销售额</th>
    <th class="sort" data-field="profit_rate">平均利润率</th>
    <th class="sort" data-field="january_publish">1月刊登</th>
    <th class="sort" data-field="january_publish_quantity">1月刊登售出量</th>
    <th class="sort" data-field="january_publish_amount">1月刊登售出额</th>
    <th class="sort" data-field="january_publish_ratio">1月刊售比</th>
    <th class="sort" data-field="january_advertisement_rate">1月动销广告率</th>
    <th class="sort" data-field="sku_sell_rate">SKU动销率</th>
    <th class="sort" data-field="yesterday_publish">昨日刊登</th>
    <th class="sort" data-field="updated_at">更新时间</th>
@stop
@section('tableBody')
    @foreach($data as $ebayAmountStatistics)
        <tr>
            <td>{{ $ebayAmountStatistics->channel_name }}</td>
            <td>{{ $ebayAmountStatistics->user ? $ebayAmountStatistics->user->name : '' }}</td>
            <td>{{ $ebayAmountStatistics->prefix == 0 ? '无' : $ebayAmountStatistics->prefix }}</td>
            <td>{{ $ebayAmountStatistics->january_sales }}</td>
            <td>{{ $ebayAmountStatistics->profit_rate * 100 . '%' }}</td>
            <td>{{ $ebayAmountStatistics->january_publish }}</td>
            <td>{{ $ebayAmountStatistics->january_publish_quantity }}</td>
            <td>{{ $ebayAmountStatistics->january_publish_amount }}</td>
            <td>{{ $ebayAmountStatistics->january_publish_ratio }}</td>
            <td>{{ $ebayAmountStatistics->january_advertisement_rate * 100 . '%' }}</td>
            <td>{{ $ebayAmountStatistics->sku_sell_rate * 100 . '%' }}</td>
            <td>{{ $ebayAmountStatistics->yesterday_publish }}</td>
            <td>{{ $ebayAmountStatistics->updated_at }}</td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
@show
@section('childJs')
    <script type="text/javascript">
        $(document).ready(function () {

        })
    </script>
@stop