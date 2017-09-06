<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-19
 * Time: 15:50
 */
?>
@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"></th>
    <th class="sort text-center" data-field="id">ID</th>
    <th class="text-center">站点</th>
    <th class="text-center">帐号</th>
    <th class="text-center">ItemID</th>
    <th class="text-center">标题</th>
    <th class="text-center">无货在线</th>
    <th class="text-center">EbaySku</th>
    <th class="text-center">物品名称</th>
    <th class="text-center">Local</th>
    <th class="sort text-center" data-field="start_time">刊登时间</th>
    <th class="text-center">刊登人</th>
    <th class="text-center">是否在线</th>
    <th class="sort text-center" data-field="start_price">价格</th>
    <th class="text-center">运费</th>
    <th class="sort text-center" data-field="quantity_sold">销量</th>
    <th class="text-center">在线数量</th>
    <th class="text-center">PayPal</th>
    <th class="text-center">处理天数</th>
    {{--
        <th class="sort" data-field="created_at">创建时间</th>
    --}}
    {{--<th>日志</th>--}}
@stop

@section('tableBody')
    @foreach($data as $detail)
        <tr class="text-center">
            <td><input type='checkbox' name='tribute_id' value="{{ $detail->id }}"></td>
            <td>{{ $detail->id }}</td>
            <td>{{ $detail->ebayProduct->site_name}}</td>
            <td>{{ $detail->ebayProduct->channelAccount->alias}}</td>
            <td><a target=_blank href="{{$detail->ebayProduct->view_item_url}}">{{ $detail->item_id}}</a></td>
            <td>{{ $detail->ebayProduct->title}}</td>
            <td>{{$detail->ebayProduct->EbayOutControl}}</td>
            <td>{{ $detail->sku}}</td>
            <td>
                @if(isset($detail->erpProduct->c_name))
                    {{$detail->erpProduct->c_name}}
                @endif
            </td>
            <td>{{ $detail->ebayProduct->location}}</td>
            <td>{{ $detail->ebayProduct->start_time}}</td>
            <td>
                @if(isset( $detail->operator->name))
                    {{ $detail->operator->name}}
                @endif
            </td>
            <td>{{$detail->EbayStatus}}</td>
            <td>{{ $detail->ebayProduct->currency.' '.$detail->start_price}}</td>
            <td>
                @if(!empty($detail->ebayProduct->shipping_details))
                    <?php
                    $shipping_details = json_decode($detail->ebayProduct->shipping_details);
                    if (!empty($shipping_details->Shipping)) {
                        foreach ($shipping_details->Shipping as $ship) {
                            echo $ship->ShippingService . ': ' . $ship->ShippingServiceCost . '<br/>';
                        }
                    }
                    if (!empty($shipping_details->InternationalShipping)) {
                        foreach ($shipping_details->InternationalShipping as $ship) {
                            echo $ship->ShippingService . ': ' . $ship->ShippingServiceCost . '<br/>';
                        }
                    }
                    ?>
                @endif
            </td>
            <td>{{ $detail->quantity_sold}}</td>
            <td>{{ $detail->quantity}}</td>
            <td>{{ $detail->ebayProduct->paypal_email_address}}</td>


            <td>{{ $detail->ebayProduct->dispatch_time_max}}</td>
            {{--  <td>
                 <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse"
                          data-target=".packageDetails{{$detail->id}}" aria-expanded="false"
                          aria-controls="collapseExample">
                      <span class="glyphicon glyphicon-eye-open"></span>
                  </button>
              </td>--}}
        </tr>
    @endforeach
    <div class="modal fade " id="addlog" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width: 50%">
            <div class="modal-content">
                <form action="" method="POST">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">补货日志</h4>
                    </div>
                    {{--

                    --}}


                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-2 ">
                                <input type="text" class="form-control datetime_select" id="update_time_start_log" placeholder="起始时间">
                            </div>

                            <div class="form-group col-lg-2 ">
                                <input type="text" class="form-control  datetime_select" id="update_time_end_log" placeholder="截止时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input type="text" class="form-control" placeholder="ItemNumber" id="item_id_log">
                            </div>
                            <div class="form-group col-lg-2 ">
                                <input type="text" class="form-control" placeholder="SKU" id="sku_log">
                            </div>
                            <div class="form-group col-lg-2 ">
                                <select class="form-control select_select0" id="token_id_log">
                                    <option value="">账号</option>
                                    @foreach($mixedSearchFields['selectRelatedSearchs']['ebayProduct']['account_id'] as $key => $v)
                                        <option value="{{ $key }}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2 ">
                                <select class="form-control select_select0" id="is_api_success_log">
                                    <option value="">结果</option>
                                    <option value="1">成功</option>
                                    <option value="2">失败</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-lg-1 ">
                                <a class="btn btn-primary" id="checklog">提交</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-1 text-center">
                                <span>编号</span>
                            </div>
                            <div class="form-group col-lg-1 text-center">
                                <span>账号</span>
                            </div>
                            <div class="form-group col-lg-2 text-center">
                                <span>ItemNumber</span>
                            </div>
                            <div class="form-group col-lg-2 text-center">
                                <span>SKU</span>
                            </div>
                            <div class="form-group col-lg-2 text-center">
                                <span>数量设为</span>
                            </div>
                            <div class="form-group col-lg-2 text-center">
                                <span>备注</span>
                            </div>
                            <div class="form-group col-lg-2 text-center">
                                <span>补货时间</span>
                            </div>
                        </div>
                        <div id="datalog">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{--  <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('tableToolButtons')
    <button class="btn btn-info"
            data-toggle="modal"
            data-target="#addlog"
            title="查看补货日志"
            id="addlogclick">
        查看补货日志
    </button>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='batchedit' data-name="changeOutOfStock">开启无货在线</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changeItemQuantity">设置Item数量</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changePrice">修改价格</a></li>
            <li><a href="javascript:" class='batchedit' data-name="updateShipFee">修改运费</a></li>
            <li><a href="javascript:" class='batchedit' data-name="endItems">批量下架</a></li>
            <li><a href="javascript:" class='batchedit' data-name="modifyPayPalEmailAddress">批量变更paypal</a></li>
            <li><a href="javascript:" class='batchedit' data-name="modifyProcessingDays">批量需要处理天数</a></li>
        </ul>
    </div>
@stop

@section('childJs')
    <script type="text/javascript">
        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                ids += checkbox[i].value + ",";
            }
            ids = ids.substr(0, (ids.length) - 1);
            if (ids == '') {
                alert("请先勾选");
                return false;
            }
            var url = "{{ route('ebay.productBatchEdit') }}";
            window.location.href = url + "?ids=" + ids + "&param=" + param;
        });

        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
        $("#addlogclick").click(function(){
            $("#datalog").empty();
        });
        $("#checklog").click(function(){
            var update_time_start_log = $("#update_time_start_log").val();
            var update_time_end_log = $("#update_time_end_log").val();
            var item_id_log = $("#item_id_log").val();
            var sku_log = $("#sku_log").val();
            var token_id_log = $("#token_id_log").val();
            var is_api_success_log = $("#is_api_success_log").val();
            $.ajax({
                url: "{{ route('ebayProduct.ajaxGetLog') }}",
                data: {update_time_start_log: update_time_start_log, update_time_end_log: update_time_end_log, item_id_log: item_id_log,sku_log:sku_log,token_id_log:token_id_log,is_api_success_log:is_api_success_log},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    $("#datalog").empty();
                    if(result){
                        for(var i= 0;i<result.length;i++){
                            var  html = '<div class="row"><div class="form-group col-lg-1 text-center"><span>'+result[i].id+'</span></div>' +
                            '<div class="form-group col-lg-1 text-center"><span>'+result[i].token_id+'</span></div>' +
                            '<div class="form-group col-lg-2 text-center"><span>'+result[i].item_id+'</span></div>' +
                            '<div class="form-group col-lg-2 text-center"><span>'+result[i].sku+'</span></div>' +
                            '<div class="form-group col-lg-2 text-center"><span>'+result[i].quantity+'</span></div>' +
                            '<div class="form-group col-lg-2 text-center"><span>'+result[i].remark+'</span></div>'+
                              '<div class="form-group col-lg-2 text-center"><span>'+result[i].update_time+'</span></div></div>';
                            $("#datalog").append(html);
                        }

                    }
                }
            });
        })
    </script>
@stop

