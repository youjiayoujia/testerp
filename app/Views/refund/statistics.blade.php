@extends('common.detail')

@section('detailBody')
    <div class="panel-body">

    <form id="refund-statistics-form" method="post" action="">
        {!! csrf_field() !!}
    <div class="row">
        <div class="col-lg-2">
            <div class="row">
                <label>SKU：<font color="red">如果填写多个SKU请用英文逗号（,）隔开</font></label>

                <textarea class="form-control" rows="5" name="skus"></textarea>
            </div>

        </div>
        <div class="col-lg-8">
            <div class="row">

                <div class="col-lg-3">
                    <label>时间类型:</label>
                    <select name="time-type" class="form-control">
                        <option value="order">交易</option>
                        <option value="refund">退款</option>
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <label>开始时间:</label>
                    <input type="text" value="" class="form-control datetime_select" name="start" placeholder="开始时间">
                </div>
                <div class="col-lg-3 form-group">
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <label>结束区间:</label>
                    <input type="text" value="" class="form-control datetime_select" name="end" placeholder="结束时间">
                </div>

        </div>
        <div class="row">
            <div class="col-lg-3">
                <label>渠  道:<font color="red">不选默认为所有渠道</font></label>
                <select name="channel" id="channel" class="form-control js-example-basic-multiple" multiple="multiple">
                    @foreach($channels as $channel)
                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label>账   号:<font color="red">不选默认为 选中渠道的所有账号</font></label>
                <select name="account" class="form-control  js-example-basic-multiple" multiple="multiple" id="account">
                </select>

            </div>
            <div class="col-lg-3">
                <label>退款原因：</label>
                <select  class="form-control js-example-basic-multiple" multiple="multiple" name="reason" id="reason">
                    @foreach(config('order.reason') as $value => $name)
                        <option value="{{$value}}" >{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        </div>
        <div class="col-lg-2">
                <div class="row">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            选择操作
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
{{--                            <li><a href="javascript:" class="do-action" data-status="1" >按SKU导出</a></li>
                            <li><a href="javascript:"  class="do-action" >按原因导出</a></li>--}}
                            <li><a href="javascript:"  class="do-action" action="{{route('refund.exportRefundDetail')}}" >导出详情</a></li>
                        </ul>
                    </div>
                </div>

        </div>

    </div>
        <input type="hidden" name="refund-statistics-channel-ids" value="">
        <input type="hidden" name="refund-statistics-account-ids" value="">
        <input type="hidden" name="refund-statistics-reason" value="">
    </form>
    </div>
@stop
@section('pageJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.datetime_select').datetimepicker({theme: 'default'});
            $(".js-example-basic-multiple").select2();
            $("#channel").change(function(){
                var channel_ids = '';
                $('#channel option:selected').each(function(key){
                   channel_ids = (channel_ids == '') ? $(this).val() : channel_ids +','+ $(this).val();
                });
                if(channel_ids != ''){
                    getChannelAccount(channel_ids);
                }
            });

            $('.do-action').click(function () {
                var start = $("input[name='start']").val();
                var end   = $("input[name='end']").val();
                if(start == '' || end == ''){
                    alert('请先填写导出的时间！');
                    return;
                }

                $("input[name='refund-statistics-channel-ids']").val(selectValuesString('channel'));
                $("input[name='refund-statistics-account-ids']").val(selectValuesString('account'));
                $("input[name='refund-statistics-reason']").val(selectValuesString('reason'));

                $('#refund-statistics-form').attr('action',$(this).attr('action'));
                $('#refund-statistics-form').submit();
            });
        });

        function getChannelAccount(channelId){
            $.ajax({
                url:"{{route('refund.getChannelAccount')}}",
                dataType:'JSON',
                type:'POST',
                data:{channel_id:channelId},
                success:function($returnInfo){
                    $('#account').html('');
                    $.each($returnInfo,function (index,item) {
                        $('#account').append('<option value="' + item.id + '">' + item.account + '</option>');
                    });
                }
            });
        }

        function selectValuesString(id){
            var ids = '';
            $('#'+id+' option:selected').each(function(key,obj){
                ids = (ids == '') ? obj.value : ids +','+ obj.value;
            });
            return ids;
        }
    </script>
@stop