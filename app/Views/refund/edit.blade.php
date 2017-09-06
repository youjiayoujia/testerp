
@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"> 编辑退款记录 </div>
        <div class="panel-body">
            <form action="{{route('refundCenter.update',['id' => $refund->id ])}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT"/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="refund_currency" class='control-label'>退款类型</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <select class="form-control" name="type" id="type">
                            @foreach(config('refund.type') as $key => $type)
                                <option value="{{$key }}" @if($refund->type == $key) selected @endif >
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="refund_currency" class='control-label'>退款方式</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <select class="form-control" name="refund" id="refund" >
                            @foreach(config('refund.refund') as $key => $type)
                                <option value="{{$key }}" @if($refund->refund == $key) selected @endif >
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 paypal-account" @if($refund->refund == 2) style="display: none;" @endif >
                        <label for="refund_currency" class='control-label'>客户退款Paypal账号</label>
                        <input type="text" class="form-control"   name="user_paypal_account" value="{{$refund->user_paypal_account}}">
                    </div>

                    <div class="col-lg-3 refund-voucher"  @if($refund->refund == 2) style="display: none;" @endif>
                            <label for="refund_currency" class='control-label'>退款凭证：</label>
                            <input type="text" class="form-control"   name="refund_voucher" value="{{$refund->refund_voucher}}">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">内单号</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type="text" class="form-control"   name="order_id" value="{{$refund->order_id}}" disabled>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">买家ID</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <label  class="form-control">{{$refund->Order->by_id}}</label>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">退款金额</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type="text" class="form-control" name="refund_amount" value="{{$refund->refund_amount}}">
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">确认金额</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type="text" class="form-control" name="price" value="{{$refund->price}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="refund_currency" class='control-label'>退款币种</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <select class="form-control" name="refund_currency">
                            @foreach($currency as $refund_currency)
                                <option value="{{ $refund_currency->code }}" @if($refund->refund_currency == $refund_currency->code) selected @endif>
                                    {{ $refund_currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="reason" class='control-label'>退款原因</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <select class="form-control" name="reason" id="reason">
                            <option value="NULL">==退款原因==</option>
                            @foreach(config('order.reason') as $reason_key => $reason)
                                <option value="{{ $reason_key }}" @if($refund->reason == $reason_key) selected @endif >
                                    {{ $reason }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">销售帐号简称</label>
                        <label class="form-control">{{$refund->Order->channelAccount->alias}}</label>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="group_id" class="control-label">交易号ID</label>
                            <input type="text" class="form-control" id="group_id" value="{{$refund->Order->transaction_number}}">
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="memo" class='control-label'>Memo(只能填写英文)</label>
                        <label class="text-danger">发给客户看的</label>
                        <textarea class="form-control" rows="3" name="memo">{{ $refund->memo }}</textarea>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="detail_reason" class='control-label'>详细原因</label>
                        <label class="text-danger">挂号的,必须填写查询结果</label>
                        <textarea class="form-control" rows="3" name="detail_reason" id="detail_reason">{{ $refund->detail_reason }}</textarea>
                    </div>
                    <div class="form-group col-lg-6">
                        @if($refund->image)
                            <label>图片：</label>
                            <filearea id="filearea">
                                <a href="../../{{$refund->image}}" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                                &nbsp;&nbsp;<a class="glyphicon glyphicon-remove" href="javascript:void(0)" onclick="deleteFile()" ></a>
                            </filearea>
                        @else
                            <input type='file' class=" file" id="qualifications" placeholder="截图" name='image' value="" />
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-success">提交</button>
            </form>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function(){
            $("#refund").change(function(){
                if($(this).val() == 1){
                    $('.paypal-account').show();
                    $('.refund-voucher').show();
                }else{
                    $('.paypal-account').hide();
                    $('.refund-voucher').hide();
                }
            });
        });

        function InputHtml(name,label){
            return '<label for="memo" class="control-label">'+label+'：</label>' +
                    '<input type="text" class="form-control" name="'+name+'" />';
        }

        function deleteFile(){
            if(confirm("确定删除原来的文件？")){
                var fileThml = '<input type="file" class="file white-space:nowrap"  placeholder="截图" name="image" value="" />';
                $('#filearea').html(fileThml);
            }
        }



    </script>
@stop