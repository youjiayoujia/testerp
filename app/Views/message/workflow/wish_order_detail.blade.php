<div class="panel panel-primary">
    <div class="panel-heading"><p class="glyphicon glyphicon-tags"></p>&nbsp;Order detail From Wish</div>
    <div class="panel-body">
        <div class="col-lg-11">
            <table class="table table-bordered">
                <tr>
                    {{--<th><input type="checkbox"></th>--}}
                    <th>Img</th>
                    <th>ProductId</th>
                    <th>OrderId</th>
                    <th>SKU</th>
                    <th>State</th>
                    <th>Cost</th>
                    <th>Order Total</th>
                    <th>Tracking Num</th>
                    <th>Marked Shipped</th>

                   {{-- <th>Operation</th>--}}
                </tr>
                @if($message->MessageFieldsDecodeBase64 && isset($message->MessageFieldsDecodeBase64['order_items']))
                    @foreach($message->MessageFieldsDecodeBase64['order_items'] as $item_order)
                        <tr>
{{--
                            <td><input type="checkbox"></td>
--}}
                            <td><img src="{{ !empty($item_order['Order']['product_image_url']) ? $item_order['Order']['product_image_url'] : ''}}" width="80px" height="80px"/></td>
                            <td>{{ !empty($item_order['Order']['product_id']) ? $item_order['Order']['product_id'] : ''}}</td>
                            <td>{{ !empty($item_order['Order']['order_id']) ? $item_order['Order']['order_id'] : ''}}</td>
                            <td>{{ !empty($item_order['Order']['sku']) ? $item_order['Order']['sku'] : ''}}</td>
                            <td>{{ !empty($item_order['Order']['state']) ? $item_order['Order']['state'] : ''}}</td>
                            <td>{{ !empty($item_order['Order']['cost']) ? $item_order['Order']['cost'] : ''}}</td>
                            <td>{{ !empty($item_order['Order']['order_total']) ? $item_order['Order']['order_total'] : ''}}</td>

                            <td>
                                @if(!empty($item_order['Order']['tracking_number']))
                                {{$item_order['Order']['tracking_number']}}
                                @endif
                            </td>
                            <td>{!!  !empty($item_order['Order']['shipped_date']) ? $item_order['Order']['shipped_date'] . '&nbsp; <font color="red">'.ceil((time() - strtotime($item_order['Order']['shipped_date'])) / 86400) . '天</font>' : '' !!}<br />

                            </td>
{{--
                            <td></td>
--}}
                            {{--<td><a class="btn btn-danger" >退款</a></td>--}}
                        </tr>
                   {{--     <tr>
                            <td colspan="11"><a class="btn btn-danger" style="float: right">选中退款</a></td>
                        </tr>--}}
                    @endforeach
                @endif
            </table>
        </div>
        <div class="col-lg-1">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#wish-refund-order-{{$message->id}}">
                订单平台退款
            </button>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade wish-refund-order" id="wish-refund-order-{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">订单平台退款</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class="control-label">原因</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <select class="form-control wish-refund-code" name="wish-refund-code" id="wish-refund-code">
                                @foreach(config('crm.wish.refund.reason_code') as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" class="wish-message-id" id="wish-message-id" value="{{$message->id}}" />

                        </div>
                        <div class="form-group col-lg-12">
                            <label for="account" class="control-label">Enter your ticket reply</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <select class="form-control refund-lang-prompts" >
                                <option value="" prompts="">自定义</option>

                            @foreach(config('crm.wish.refund.lang_prompts') as $key => $value)
                                    <option value="{{$value}}" prompts="{{$value}}">{{$key}}</option>
                                @endforeach
                            </select>
                            <textarea class="form-control wish-refund-reply" rows="6" name="wish-refund-reply" id="wish-refund-reply"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary do-wish-refund">提交</button>
                </div>
            </div>
        </div>
    </div>
</div>