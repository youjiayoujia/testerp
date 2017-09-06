@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="javascript:" class="btn btn-warning download">到货记录导出
                <i class="glyphicon glyphicon-arrow-down"></i>
            </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th>单据号</th>
    <th>SKU</th>
    <th>库位</th>
    <th>到货数量</th>
    <th>入库数量</th>
    <th>名称</th>
    <th>到货时间</th>
    <th>收货人</th>
@stop

@section('tableBody')
    @foreach($data as $recieve)
        <tr>
            <td>{{$recieve->id}}</td>
            <td>{{$recieve->purchase_order_id}}</td>
            <td>{{$recieve->purchaseItem->productItem->sku}}</td>
            <td>{{$recieve->purchaseItem->warehouse_position_name}}</td>
            <td>{{$recieve->arrival_num}}</td>
            <td>{{$recieve->good_num}}</td>
            
            <td>{{$recieve->purchaseItem->productItem->c_name}}</td>
            <td>{{$recieve->created_at}}</td>
            <td>{{$recieve->user?$recieve->user->name:''}}</td>
        </tr>
    @endforeach
@stop

@section('childJs')
    <script type='text/javascript'>
    //采购单导出
        $(document).ready(function(){
            $(document).on('click', '.download', function(){
                subUrl = window.location.search;
                location.href="{{ route('purchaseOrder.purchaseArrivalLogOut')}}"+subUrl;
            })
        })
    </script>
@stop