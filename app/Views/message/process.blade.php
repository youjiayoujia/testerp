@extends('layouts.default')
@section('content')
    <!--编辑工具插件-->
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">{{-- OUR CSS --}}

    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    <link href="{{ asset('plugins/pace/dataurl.css') }}" rel="stylesheet" />
        @if($driver == 'wish')
            <div class="col-lg-12">

            @include('message.workflow.wish_order_detail')
            </div>
        @endif
    <div class="col-lg-8">
        @include('message.workflow.content')

        @include('message.workflow.reply')

    </div>

    <div class="col-lg-4">
        @include('message.workflow.operate')
        @if($message->related)
            @include('message.workflow.orders')
        @else
            @include('message.workflow.relate')
        @endif
    </div>
    @include('message.workflow.more')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">日志信息</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <strong>创建时间</strong>: {{ $message->created_at }}
                            </div>
                            <div class="col-lg-6">
                                <strong>更新时间</strong>: {{ $message->updated_at }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
@section('pageJs')
    @include('message.workflow.javascript')
@stop