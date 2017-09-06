@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">黑名单汇总</div>
        <div class="panel-body">
            <p>全部数据共{{ count($errors[0])}}条，出错{{ count($errors)-1 }}条</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">失败记录</div>
        <div class="panel-body">
            <div class='row'>
                <div class='col-lg-1'><label>平台</label></div>
                <div class='col-lg-1'><label>订单号</label></div>
                <div class='col-lg-1'><label>姓名</label></div>
                <div class='col-lg-2'><label>邮箱</label></div>
                <div class='col-lg-2'><label>买家ID</label></div>
                <div class='col-lg-1'><label>邮编</label></div>
                <div class='col-lg-2'><label>销售账号</label></div>
                <div class='col-lg-1'><label>类型</label></div>
                <div class='col-lg-1'><label>退款订单数</label></div>
                <div class='col-lg-1'><label>订单总数</label></div>
                <div class='col-lg-1'><label>退款率</label></div>
                <div class='col-lg-2'><label>备注</label></div>
            </div>
            @foreach($errors as $key => $value)
                @if($key != 0)
                    <div class='row'>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['channel_id']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['ordernum']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['name']) }}"></div>
                        <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['email']) }}"></div>
                        <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['by_id']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['zipcode']) }}"></div>
                        <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['channel_account']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['type']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['refund_order']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['total_order']) }}"></div>
                        <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['refund_rate']) }}"></div>
                        <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['remark']) }}"></div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@stop