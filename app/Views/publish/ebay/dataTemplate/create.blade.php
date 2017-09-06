<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 17:01
 */
 ?>

@extends('common.form')
@section('formAction') {{ route('ebayDataTemplate.store') }} @stop
@section('formBody')

    <div class="panel panel-default">
        <div class="panel-heading">基础设置</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">模板名称：</label>
                </div>
                <div class="form-group col-sm-4">
                    <input class="form-control" type="text" name="name">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">站点：</label>
                </div>
                <div class="form-group col-sm-6">
                    <select class="select_select0 col-sm-4" name="site"  id="site">
                        <option value="">==请选择==</option>
                        @foreach(config('ebaysite.site_name_id') as $name=>$id)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">仓库：</label>
                </div>
                <div class="form-group col-sm-6">
                    <select class="select_select0 col-sm-4" name="warehouse">
                        <option value="">==请选择==</option>
                        @foreach(config('ebaysite.warehouse') as $key=>$name)
                            <option value="{{$key}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">重量：</label>
                </div>
                <div class="form-group col-sm-1">
                 <input type="text" class="form-control" placeholder="起始重量" name="start_weight"/>
                </div>
                <div class="form-group col-sm-1">
                    <input type="text" class="form-control" placeholder="结束重量" name="end_weight"/>
                </div>
            </div>



            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">价格：</label>
                </div>
                <div class="form-group col-sm-1">
                    <input type="text" class="form-control" placeholder="起始价格" name="start_price"/>
                </div>
                <div class="form-group col-sm-1">
                    <input type="text" class="form-control" placeholder="结束价格" name="end_price"/>
                </div>
            </div>

        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">退货政策</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="returns_option">
                        <option value="ReturnsAccepted">ReturnsAccepted</option>
                        <option value="ReturnsNotAccepted">ReturnsNotAccepted</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货天数：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" id="returns_with_in" name="returns_with_in">
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" value="true" name="extended_holiday">提供节假日延期退货至12月31日
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退款方式：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" id="refund" name="refund">
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货运费由谁负担：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="shipping_costpaid_by"
                            id="shipping_costpaid_by">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策详情：</label>
                </div>
                <div class="form-group col-sm-4">
                    <textarea class="form-control" name="refund_description"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">买家要求</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_paypal" value="true"> 没有PayPal用户
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_ship" value="true">  主要运送地址在我的运送范围之外
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="unpaid_on" value="true"> 曾收到
                    <select class="select_select0 col-sm-1" name="unpaid">
                        @foreach(config('ebaysite.unpaid')as $key=>$value)
                            <option value="{{$value}}">{{$value}}</option>
                        @endforeach
                    </select>
                    个弃标个案，在过去
                    <select class="select_select0 col-sm-2" name="unpaid_day">
                        @foreach(config('ebaysite.unpaid_day')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="policy_on" value="true"> 曾收到
                    <select class="select_select0 col-sm-1" name="policy">
                        @foreach(config('ebaysite.policy')as $key=>$value)
                            <option value="{{$value}}">{{$value}}</option>
                        @endforeach
                    </select>
                    个违反政策检举，在过去
                    <select class="select_select0 col-sm-2" name="policy_day">
                        @foreach(config('ebaysite.policy_day')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="feedback_on" value="true">信用指标等于或低于：
                    <select class="select_select0 col-sm-1" name="feedback">
                        @foreach(config('ebaysite.feedback')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-6">
                    <input type="checkbox" name="item_count_on" value="true">在过去10天内曾出价或购买我的物品，已达到我所设定的限制
                    <select class="select_select0 col-sm-1" name="item_count">
                        @foreach(config('ebaysite.item_count')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    这项限制只适用于买家信用指数等于或低于
                    <select class="select_select0 col-sm-1" name="item_count_feedback">
                        @foreach(config('ebaysite.item_count_feedback')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">物品所在地</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品所在地：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="location" id="location">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">国家或地区：</label>
                </div>
                <div class="form-group col-sm-4">
                    <select class="select_select0 col-sm-4" name="country" id="country">
                        @foreach(config('ebaysite.ebay_country')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">邮编：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="postal_code" id="postal_code">
                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">国内运输</div>
        <div class="panel-body">


            <?php
            $ship_name = [
                    1 => '第一运输',
                    2 => '第二运输'
            ];
            $dispatch_time_max =[0,1,2,3,4,5,6,7,8,9];

            ?>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">处理天数：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control select_select0 col-sm-1" name="dispatch_time_max">
                        @foreach($dispatch_time_max as $v)
                            <option value="{{$v}}">{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @for($i=1;$i<3;$i++)
                <div id="{{'shipping'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 shipping"
                                    name="shipping[{{$i}}][ShippingService]">

                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="shipping[{{$i}}][ShippingServiceCost]">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text"
                                   name="shipping[{{$i}}][ShippingServiceAdditionalCost]">
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">国际运输</div>
        <div class="panel-body">
            @for($i=1;$i<3;$i++)
                <div id="{{'international'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 international"
                                    name="InternationalShipping[{{$i}}][ShippingService]">

                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceCost]">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceAdditionalCost]">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输国家：</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <select class="form-control select_select0 col-sm-1"
                                    name="InternationalShipping[{{$i}}][ShipToLocation][]" multiple>
                                @foreach(config('ebaysite.ebay_country') as $key=> $v)
                                    <option value="{{$key}}" @if(in_array($v,array())){{'selected="selected"'}}  @endif  >{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">不运输国家</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">不运输国家：</label>
                </div>
                <div class="form-group col-sm-8">
                    <select class="form-control select_select0 col-sm-1"
                            name="un_ship[]" id="un_ship" multiple>
                        @foreach(config('ebaysite.ebay_country') as $key=> $v)
                            <option value="{{$key}}" @if(in_array($v,array())){{'selected="selected"'}}  @endif  >{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>





@stop



@section('pageJs')
    <script type="text/javascript">
        $('.select_select0').select2();


        $("#site").change(function () {
            initSite();
        });

        function initSite() {
            var site = $("#site").val();
            if (site == '') {
                return false;
            }
            $.ajax({
                url: "{{ route('ebayPublish.ajaxInitSite') }}",
                data: {
                    site: site
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result) {
                        $('.shipping').select2({
                            placeholder: "Select a shipping",
                            allowClear: true
                        }).empty().append(result.ship_text);
                        $('.international').select2({
                            placeholder: "Select a international shipping",
                            allowClear: true
                        }).empty().append(result.international_text);

                        $("#returns_with_in").empty().append(result.returns_with_in);
                        $("#shipping_costpaid_by").empty().append(result.shipping_costpaid_by);
                        $("#refund").empty().append(result.refund);
                    }

                }
            });

        }
    </script>

@stop