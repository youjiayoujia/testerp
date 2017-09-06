<table class="gridtable" align="center" valign="center">
    <tr>
        <th>采购单号</th>
        <th>采购负责人</th>
        <th>打印次数</th>
        <th>合计采购数量</th>
        <th>合计入库数量</th>
        <th>合计未入库数量</th>
        <th>仓库</th>
    </tr>
    @foreach($purchaseOrder as $key=>$purchase_order)
        @foreach($purchase_order->purchaseItem as $purchase_item)
            @if($purchase_item->purchase_num-$purchase_item->arrival_num)
                <tr>
                    <td>{{$purchase_order->id}}</td>
                    <td>{{$purchase_order->purchaseUser->name}}</td>
                    <td>{{$purchase_order->print_num}}</td>
                    <td>{{$purchase_order->purchaseItem->sum('purchase_num')}}</td>
                    <td>{{$purchase_order->purchaseItem->sum('arrival_num')}}</td>
                    <td>{{$purchase_order->purchaseItem->sum('purchase_num')-$purchase_order->purchaseItem->sum('arrival_num')}}</td>
                    <td>{{$purchase_order->warehouse->name}}</td>
                </tr>
            @endif
        @endforeach
    @endforeach
</table>

<style type="text/css">
    table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
    }
</style>