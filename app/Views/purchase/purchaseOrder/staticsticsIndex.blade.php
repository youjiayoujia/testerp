@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th class="sort" data-field="id">采购负责人</th>
    <th>管理的SKU数</th>
    <th class="sort" data-field="sku">必须当天内下单SKU数</th>
    <th class="sort" data-field="c_name">15天缺货订单</th>
    <th>15天内所有订单</th>
    <th>订单缺货率</th>
    <th>缺货总数</th>
    <th>平均缺货天数</th>
    <th>最长缺货天数</th>
    <th>采购单超期</th>
    <th>当月累计下单数量</th>
    <th>当月累计下单总金额（Y）</th>
    <th>累计运费（Z）</th>
    <th>节约成本（A）</th>
    <th>获取时间</th>
@stop

@section('tableBody')
    @foreach($data as $staticstics)
        <tr>
            <td>{{$staticstics->user->name}}</td>
            <td>{{$staticstics->sku_num}}</td>
            <td>{{$staticstics->need_purchase_num}}</td>
            <td>{{$staticstics->fifteenday_need_order_num}}</td>
            <td>{{$staticstics->fifteenday_total_order_num}}</td>
            <td>{{round($staticstics->need_percent,2)}}%</td>
            <td>{{$staticstics->need_total_num}}</td>
            <td>{{$staticstics->avg_need_day}}</td>
            <td>{{$staticstics->long_need_day}}</td>
            <td>{{$staticstics->purchase_order_exceed_time}}</td>
            <td>{{$staticstics->month_order_num }}</td>
            <td>{{round($staticstics->month_order_money)}}</td>
            <td>{{$staticstics->total_carriage}}</td>
            <td>{{$staticstics->save_money}}</td>
            <td>{{$staticstics->get_time}}</td>
        </tr>
    @endforeach
@stop

@section('childJs')
    
@stop