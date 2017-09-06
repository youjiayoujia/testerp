@extends('common.table')
@section('tableHeader')
    <th>SKU</th>
    <th>平台</th>
    <th>站点</th>
    <th class="sort" data-field="sale_different">相邻两周销量差</th>
    <th class="sort" data-field="sale_different_proportion">相邻两周销量差比例</th>
    <th class="sort" data-field="one_sale">1天销量</th>
    <th class="sort" data-field="seven_sale">7天销量</th>
    <th class="sort" data-field="fourteen_sale">14天销量</th>
    <th class="sort" data-field="thirty_sale">30天销量</th>
    <th class="sort" data-field="ninety_sale">90天销量</th>
    <th class="sort" data-field="created_time">SKU创建时间</th>
    <th>状态</th>
    <th class="sort" data-field="is_warning">预警</th>
@stop
@section('tableBody')
    @foreach($data as $ebaySkuSaleReport)
        <tr>
            <td>{{ $ebaySkuSaleReport->sku }}</td>
            <td>{{ $ebaySkuSaleReport->channel_name }}</td>
            <td>{{ $ebaySkuSaleReport->site }}</td>
            <td>{{ $ebaySkuSaleReport->sale_different }}</td>
            <td>{{ $ebaySkuSaleReport->sale_different_proportion * 100 . '%' }}</td>
            <td>{{ $ebaySkuSaleReport->one_sale }}</td>
            <td>{{ $ebaySkuSaleReport->seven_sale }}</td>
            <td>{{ $ebaySkuSaleReport->fourteen_sale }}</td>
            <td>{{ $ebaySkuSaleReport->thirty_sale }}</td>
            <td>{{ $ebaySkuSaleReport->ninety_sale }}</td>
            <td>{{ $ebaySkuSaleReport->created_time }}</td>
            <td>{{ $ebaySkuSaleReport->status_name }}</td>
            <td>
                @if($ebaySkuSaleReport->is_warning == '1')
                    <strong class="text-danger">预警</strong>
                @endif
            </td>
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