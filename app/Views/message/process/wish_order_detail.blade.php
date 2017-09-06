<div class="panel panel-primary">
    <div class="panel-heading"><p class="glyphicon glyphicon-tags"></p>&nbsp;Order detail From Wish</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <th><input type="checkbox"></th>
                <th>Img</th>
                <th>ProductId</th>
                <th>OrderId</th>
                <th>SKU</th>
                <th>State</th>
                <th>Cost</th>
                <th>Tracking Num</th>
                <th>Marked Shipped</th>
                <th>Marked Refunded</th>
                <th>Operation</th>
            </tr>
            @if($message->MessageFieldsDecodeBase64 && isset($message->MessageFieldsDecodeBase64['order_items']))
            @foreach($message->MessageFieldsDecodeBase64['order_items'] as $item_order)
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="{{$item_order['Order']['product_image_url']}}" width="80px" height="80px"/></td>
                <td>{{$item_order['Order']['product_id']}}</td>
                <td>{{$item_order['Order']['order_id']}}</td>
                <td>{{$item_order['Order']['sku']}}</td>
                <td>{{$item_order['Order']['state']}}</td>
                <td>{{$item_order['Order']['cost']}}</td>
                <td>{{$item_order['Order']['tracking_number']}}</td>
                <td>{{ !empty($item_order['Order']['shipped_date']) ? $item_order['Order']['shipped_date'] : ''}}</td>
                <td></td>
                <td><a class="btn btn-danger" >退款</a></td>
            </tr>
            <tr>
                <td colspan="11"><a class="btn btn-danger" style="float: right">选中退款</a></td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
</div>