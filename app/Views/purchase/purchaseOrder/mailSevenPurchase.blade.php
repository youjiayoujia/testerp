<table class="gridtable" align="center" valign="center">
    <tr>
        <th>序号</th>
        <th>SKU</th>
        <th>名称</th>
        <th>采购数量</th>
        <th>到货数量</th>
        <th>入库数量</th>
        <th>单据号</th>
        <th>外部单号</th>
        <th>采购负责人</th>
        <th>供应商编号</th>
        <th>下单时间</th>
        <th>未到货数量</th>
        <th>虚库存</th>
    </tr>
    @foreach($purchaseOrder as $key=>$purchase_order)
        @foreach($purchase_order->purchaseItem as $purchase_item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$purchase_item->sku}}</td>
                <td>{{$purchase_item->productItem->c_name}}</td>
                <td>{{$purchase_item->purchase_num}}</td>
                <td>{{$purchase_item->arrival_num}}</td>
                <td>{{$purchase_item->storage_qty}}</td>
                <td>{{$purchase_order->id}}</td>
                <td>{{$purchase_order->post_coding}}</td>
                <td>{{$purchase_order->purchaseUser->name}}</td>
                <td>{{$purchase_order->supplier_id}}</td>
                <td>{{$purchase_order->created_at}}</td>
                <td>{{$purchase_item->purchase_num-$purchase_item->arrival_num}}</td>
                <td>{{$purchase_item->productItem->available_quantity}}</td>
            </tr>
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