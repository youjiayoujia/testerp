@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <div class='row'>
            <div class="col-lg-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <label>纠纷详情</label>
                    </div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class="col-lg-2">
                                <strong>纠纷原因</strong>: {{$issue->issueList->reasonChinese}}
                            </div>
                            <div class="col-lg-2">
                                <strong>纠纷状态</strong>: <font color="green">纠纷协商中</font>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <strong>
                                纠纷原因：
                                <small>{{$issue->issueReason}}</small>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <label>订单详情</label>
                        </div>
                        <div class="panel-body">
                            <div class='row'>
                                <div class="col-lg-4">
                                    <strong>订单号</strong>: {{$issue->issueList->orderId}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    价格：<strong style="color: red">{{$issue->ProductInfo}}</strong><br>
                                    产品名称：
                                    <strong>{{$issue->productName}}</strong><br>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <label>买家解决方案</label>
                        </div>

                        <div class="panel-body">
                                @foreach($issue->BuyerSolutionInfo as $buyer_info)
                                    <div class="col-lg-6">
                                        类型:<strong style="color: red">{{$buyer_info->solutionType}}</strong>
                                    </div>
                                    <div class="col-lg-6">
                                        状态:<strong style="color: red">{{$buyer_info->status}}</strong>

                                    </div>
                                    <div class="col-lg-12">
                                        <h4>{{$buyer_info->content}}</h4>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <label>我的方案</label>
                        </div>

                        <div class="panel-body">
                            <h3>等待您提供方案
                                您可以同意买家方案/拒绝并新增一个方案来响应纠纷。</h3>
                        </div>
                    </div>
                </div>
            </div>
    </div>

@stop