<script type="text/javascript">
    function batchOperate(param){
        var str = prompt("输入数值(百分比不需要%)");
        var type = $("input[name='operateType']:checked").val();
        if (str) {
            $("."+param).each(function(){
                var old_value = $(this).val();
                if(type=='add'){
                     old_value = Number(old_value)+Number(str);
                }else if(type=='percentage'){
                    old_value = Number(old_value) + Number(str)/100*Number(old_value);
                }else if(type=='fixed'){
                     old_value = Number(str);
                }
                if(param=='price'||param=='shipping'||param=='international'){
                    $(this).val(old_value.toFixed(2));
                }else{
                    $(this).val(old_value);
                }
            })
        }
    }

    function changePaypal(){
        var payPal = $("#payPalModify").val();
          $('.paypal').each(function(){
         $(this).val(payPal);
         })
    }
    function payPalModify(){


    }
</script>
@extends('common.form')
@section('formAction')  {{ route('ebay.batchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <div class="form-group">
        <label for="model">待处理的Ebay Item_id：</label>
    </div>
    <div class="row">
        @foreach($products as $key=> $product)
            @if($key==0)
                <div class="form-group col-md-1">
                    <label for="model"><a target=_blank
                                          href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a></label>
                </div>
            @elseif($product->item_id != $products[$key-1]->item_id)
                <div class="form-group col-md-1">
                    <label for="model"><a target=_blank
                                          href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a></label>
                </div>
            @endif
        @endforeach
    </div>
    <div class="row">
        <?php
        switch ($param) {
        case 'changeOutOfStock':
        ?>
        <div class="form-group col-md-3">
            <label for="color">是否开启无货在线</label>
            <input type="text" class="hidden"  name="operate" value="changeOutOfStock" />
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type="radio" name='outStock' checked value="true"> 是
            <input type="radio" name='outStock' value="false" > 否
        </div>
        <?php
        break;
        case 'changeItemQuantity':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置Item数量</label>
            <input type="text" class="hidden" name="operate" value="changeItemQuantity" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('quantity')"
                    >批量设置数量
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)
            @if($key==0||$product->item_id != $products[$key-1]->item_id)
                <hr/>
                <div class="form-group col-md-12">
                    <label><a target=_blank
                              href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a></label>
                    <br/>
                    <label><span class="text-danger">  {{$product->sku}}</span></label>
                    <input class="form-control quantity" placeholder="在线数量"
                           name='quantity[id][{{$product->id}}]' value="{{$product->quantity}}">
                </div>
            @elseif($product->item_id = $products[$key-1]->item_id)
                <div class="form-group col-md-12">
                    <label><span class="text-danger"> {{$product->sku}}</span></label>
                    <input class="form-control quantity"  placeholder="在线数量"
                           name='quantity[id][{{$product->id}}]' value="{{$product->quantity}}">
                </div>
            @endif
        @endforeach
        <?php
        break;
        case 'changePrice':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置Item价格</label>
            <input type="text" class="hidden"  name="operate" value="changePrice" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('price')"
                    >批量设置价格
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="percentage"> 百分比
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)
            @if($key==0||$product->item_id != $products[$key-1]->item_id)
                <hr/>
                <div class="form-group col-md-12">
                    <label><a target=_blank
                              href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a></label>
                    <br/>
                    <label><span class="text-danger">  {{$product->sku}}：</span><span
                                class="text-success">{{$product->start_price}} {{$product->ebayProduct->currency}}</span></label>
                    <input class="form-control price" id="purchase_url" placeholder="在线价格"
                           name='start_price[id][{{$product->id}}]' value="{{$product->start_price}}">
                </div>
            @elseif($product->item_id = $products[$key-1]->item_id)
                <div class="form-group col-md-12">
                    <label><span class="text-danger"> {{$product->sku}}：</span><span
                                class="text-success">{{$product->start_price}} {{$product->ebayProduct->currency}}</span></label>
                    <input class="form-control price" id="purchase_url" placeholder="在线价格"
                           name='start_price[id][{{$product->id}}]' value="{{$product->start_price}}">
                </div>
            @endif
        @endforeach
        <?php
        break;
        case 'updateShipFee':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置Item运费</label>
            <input type="text" class="hidden"  name="operate" value="updateShipFee" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('shipping')"
              >批量国内
            </a>
            <a href="javascript:void(0);" class="btn btn-success btn-sm "
               onclick="batchOperate('international')">批量国际</a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="percentage"> 百分比
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)
            @if($key==0||$product->item_id != $products[$key-1]->item_id)
                <hr/>
                <div class="form-group col-md-12">
                    <label><a target=_blank
                              href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}} </a> {{ '  '.$product->ebayProduct->channelAccount->account}}
                    </label>
                    <br/>
                    @if(!empty($product->ebayProduct->shipping_details))
                        <?php
                        $shipping_details = json_decode($product->ebayProduct->shipping_details);
                        if (!empty($shipping_details->Shipping)) {
                        foreach ($shipping_details->Shipping as $key=> $ship) {
                        ?>
                        @if($key==1)
                            <label class="text-success">国内第{{$key}}运输：{{$ship->ShippingService}}
                                原运费{{$ship->ShippingServiceCost.$product->ebayProduct->currency}} </label>
                            <input class="form-control shipping"  placeholder="运费"
                                   name='ship_detail[id][shipping][{{$product->item_id}}]'
                                   value="{{$ship->ShippingServiceCost}}">
                        @endif
                        <?php
                        }
                        }
                        if (!empty($shipping_details->InternationalShipping)) {
                        foreach ($shipping_details->InternationalShipping as $key=> $ship) {
                        ?>
                        @if($key==1)
                            <label class="text-danger">国际第{{$key}}运输 ：{{$ship->ShippingService}}
                                原运费 {{   $ship->ShippingServiceCost.$product->ebayProduct->currency}}  </label>
                            <input class="form-control international"  placeholder="运费"
                                   name='ship_detail[id][international][{{$product->item_id}}]'
                                   value="{{$ship->ShippingServiceCost}}">
                        @endif
                        <?php
                        }
                        }
                        ?>
                    @endif
                </div>
            @endif

        @endforeach
        <?php
        break;
        case 'modifyPayPalEmailAddress':
        ?>
        <div class="form-group col-md-12">
            <label >设置Item PayPal  </label>
            <input type="text" class="hidden" name="operate" value="modifyPayPalEmailAddress" />
        @if(!empty($paypal))
                <select id="payPalModify" onchange="changePaypal()">
                    <option value="">PayPal</option>
               @foreach($paypal as $v)
                        <option value="{{ $v }}">{{$v}}</option>
               @endforeach
                </select>
            @endif


            @foreach($products as $key=> $product)
                @if($key==0||$product->item_id != $products[$key-1]->item_id)
                    <hr/>
                    <div class="form-group col-md-12">
                        <label><a target=_blank
                                  href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a>

                            <span class="text-success">原PayPal {{$product->ebayProduct->paypal_email_address}}</span>
                        </label>
                        <input class="form-control paypal"  placeholder="PayPal"
                               name='pay_pal[id][{{$product->id}}]'
                               value="{{$product->ebayProduct->paypal_email_address}}">
                    </div>
                @endif
            @endforeach
        </div>
        <?php
        break;
        case 'endItems':
        ?>
        <div class="form-group col-md-3">
            <label for="color">是否下架Item</label>
            <input type="text"  class="hidden" name="operate" value="endItems" />
            <small class="text-danger glyphicon glyphicon-asterisk"></small>

            <input type="radio" name='endItem[id][{{$product->id}}]' checked value="true"> 是
            {{--<input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') }}">--}}
        </div>
        <?php
        break;
        case 'modifyProcessingDays':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置Item处理天数</label>
            <input type="text" class="hidden" name="operate" value="modifyProcessingDays" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('dispatch')"
                    >批量设置数量
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)
            @if($key==0||$product->item_id != $products[$key-1]->item_id)
                <hr/>
                <div class="form-group col-md-12">
                    <label><a target=_blank
                              href="{{$product->ebayProduct->view_item_url}}">{{ $product->item_id}}</a>

                        <span class="text-success">原处理天数 {{$product->ebayProduct->dispatch_time_max}}</span>
                    </label>
                    <input class="form-control dispatch" id="purchase_url" placeholder="处理天数"
                           name='processing_days[id][{{$product->id}}]'
                           value="{{$product->ebayProduct->dispatch_time_max}}">
                </div>
            @endif
        @endforeach
        <?php
        break;
            default:
                break;
        }
        ?>
    </div>
@stop


