<table class="gridtable" align="center" valign="center">
    <tr>
        <th>序号</th>
        <th>采购单号</th>
        <th>仓库</th>
        <th>SKU</th>
        <th>到货数量</th>
        <th>到货操作人</th>
        <th>到货时间</th>
    </tr>


    @foreach($purchase_items_ary as $purchase_item)
        <tr>
            <td>{{$purchase_item['id']}}</td>
            <td>{{$purchase_item['purchase_order_id']}}</td>
            <td>{{$purchase_item['warehouse_name']}}</td>
            <td>{{$purchase_item['sku']}}</td>
            <td>{{$purchase_item['arrival_num']}}</td>
            <td>{{$purchase_item['user_name']}}</td>
            <td>{{$purchase_item['arrival_time']}}</td>
        </tr>
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