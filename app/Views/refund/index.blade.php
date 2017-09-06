@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <form method="POST" action="{{ route('refund.changeReundNoteStatus') }}" enctype="multipart/form-data" id="add-lots-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="file" class="file" id="refund-excel" placeholder="excel" name="excel" value="">
        </form>
    </div>
    <div class="btn-group">
        <a class="btn btn-warning add-lots-of-catagory" href="javascript:void(0);" data-toggle="modal" data-target="#refund-by-channel">
            财务导出平台退款记录
            <i class="glyphicon glyphicon-arrow-down"></i>
        </a>
        <a href="{{route('refund.cvsformat')}}" class="btn btn-warning">财务导入退款结果Excel格式
            <i class="glyphicon glyphicon-arrow-down"></i>
        </a>
        <a class="btn btn-success change-refund-status" href="javascript:void(0);">
            财务导入退款凭证<i class="glyphicon glyphicon-plus"></i>
        </a>

    </div>
    <div class="modal fade" id="refund-by-channel" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <form id="compute-form" action="{{route('refund.financeExport')}}">
                {!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="text-align: left;">财务导出需要退款的记录</h4>
                    </div>
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>渠道：</label>
                                    <select class="form-control" name="channel">
                                        @foreach($channels as $channel)
                                            <option value="{{$channel->id}}">{{$channel->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>记录状态：</label>
                                    <select class="form-control" name="process">
                                        @foreach(config('refund.process') as $key => $status)
                                            <option value="{{$key}}" @if($key == 'FINANCE') selected @endif >{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">导出</button>
                    </div>
                </div>
            </form>

        </div>
    </div>


@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>内单号</th>
    <th>渠道</th>
    <th>退款方式</th>
    <th>退款类型</th>
    <th>买家ID</th>
    <th>sku</th>
    <th>退款金额</th>
    <th>交易凭证</th>
    <th>退款原因</th>
    <th>客服</th>
    <th>处理状态</th>
    <th class="sort" data-field="created_at">录入时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')
@foreach($data as $item)
    <tr>
        <td><input type="checkbox" value="{{$item->id}}" name="refund_id" class="refund-ids"></td>
        <td>{{$item->id}}</td>
        <td>{{$item->order_id}}</td>
        <td>{{$item->ChannelName}}</td>
        <td>{{$item->RefundName}}</td>
        <td>{{config('refund.type')[$item->type]}}</td>
        <td>{{$item->Order->by_id}}</td>
        <td>{{$item->SKUs}}</td>
        <td>
            {{$item->refund_amount}}
            <code>{{$item->refund_currency}}</code>
        </td>
        <td>{{$item->refund_voucher}}</td>
        <td>{{$item->ReasonName}}</td>
        <td>{{$item->CustomerName}}</td>
        <td>{{$item->ProcessStatusName}}</td>
        <td> {{$item->created_at}}</td>
        <td> {{$item->updated_at}}</td>
        <td>
            <a href="{{ route('refundCenter.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs" title="查看">
                <span class="glyphicon glyphicon-eye-open"></span>
            </a>
            <a href="{{ route('refundCenter.edit', ['id'=>$item->id])}}" class="btn btn-warning btn-xs" title="编辑">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
            <a href="javascript:void(0);" class="btn btn-danger btn-xs" data-id="7" data-url="" title="退款" data-toggle="modal" data-target="#myModal_{{$item->id}}">
                <span class="glyphicon glyphicon-usd"></span>
            </a>
            <button class="btn btn-primary btn-xs dialog"
                    data-toggle="modal"
                    data-target="#dialog" data-table="{{ $item->table }}" data-id="{{$item->id}}">
                <span class="glyphicon glyphicon-road"></span>
            </button>
        </td>
        <div class="modal fade" id="myModal_{{$item->id}}" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document" style="width:800px;">
                <form id="compute-form-{{$item->id}}" action="{{route('refund.dopaypalrefund')}}">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel" >Paypal退款</h4>
                    </div>
                    <div class="modal-body">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-8"><label>内单号：</label>{{$item->order_id}} </div>
                                <div class="col-lg-4"><label>买家ID：</label>{{$item->Order->by_id}}</div>
                            </div>


                            <div class="row">
                                <div class="col-lg-8"><label>退款金额：{{$item->refund_amount}} <code>{{$item->refund_currency}}</code></label></div>
                                <div class="col-lg-4"><label>国家：</label>{{$item->Order->currency}}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8"><label>交易号ID：</label>{{$item->Order->transaction_number}}</div>
                                <div class="col-lg-4"><label>销售账号：</label>{{$item->Order->channelAccount->account}}</div>
                            </div>
                        <div class="row">
                            <div class="col-lg-12"><label>订单备注：</label>
                                {!! $item->OrderRemarks !!}
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-lg-12" ><label>退款原因：</label>{{$item->ReasonName}}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12"><label>Memo（英文）：
                                    </label>
                                    @if($item->memo)
                                        {{$item->memo}}
                                    @else
                                        无
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12"><label>退款备注：</label>
                                    @if($item->detail_reason)
                                        {{$item->detail_reason}}
                                    @else
                                        无
                                    @endif
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-lg-12"><label>查看截图：</label>
                                @if($item->image)
                                    <a href="../../{{$item->image}}" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                                @else
                                    无
                                @endif
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-lg-6"><label>Paypal账号：</label>
                                    @if(! $item->paypalDetail->isEmpty())
                                        <select class="form-control" name="paypal_id">
                                            @foreach($item->paypalDetail as $paypal)
                                                <option value="{{$paypal->paypalAccount->id}}">{{$paypal->paypalAccount->paypal_email_address}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <label><code>没有匹配到订单对应的paypal账号</code></label>
                                        <select class="form-control" name="paypal_id">
                                            @foreach($paypals as $paypal)
                                                <option value="{{$paypal->id}}">{{$paypal->paypal_email_address}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-lg-6"><label>退款密码：</label>
                                    <input type="password" name="password" class="form-control" />
                                    <input type="hidden" name="id" class="form-control" value="{{$item->id}}" />
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认退款</button>
                    </div>
                </div>

                </form>

            </div>
        </div>
    </tr>
@endforeach
@section('doAction')
    <div class="btn-group dropup">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            审核
            <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            @foreach(config('refund.process') as $key => $name)
                <li><a href="javascript:void(0);" class="process-status" process-status="{{$key}}" data-name="{{$name}}">{{$name}}</a></li>
            @endforeach
        </ul>
    </div>
@stop
<br>
@stop

@section('childJs')
    <script>
        $(document).ready(function(){
            $('.process-status').click(function () {
                var process = '';
                var ids = '';
                process = $(this).attr('process-status');
                if(getfilterIds()){
                    ids = getfilterIds();
                    $.ajax({
                        url:"{{route('refund.batchProcessStatus')}}",
                        type: 'POST',
                        dataType:'JSON',
                        data:{process:process,ids:ids},
                        success:function(data){
                            if(data == 10){
                                location.reload();
                            }else{
                                alert('批量修改失败');

                            }
                        },
                        error:function () {
                            alert('未知错误');
                        }

                    });
                }else{
                    alert('请先选中需要修改状态的记录');
                }
            });

            $('.change-refund-status').click(function (){
                if($('#refund-excel').val()){
                    $('#add-lots-form').submit();
                }else{
                    alert('请先上传excel表格');
                }
            });
        });
        function getfilterIds(){
            var refund_ids = '';
            $.each($('.refund-ids:checked'), function(){
                refund_ids += (refund_ids == '') ? $(this).val() : ',' + $(this).val();
            });
            return refund_ids;
        }
    </script>
@stop
