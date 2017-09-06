<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>lazada菲律宾面单</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    #main_frame_box {
        width: 382px;
        margin: 0 auto;
        height: 378px;
        overflow: hidden;
        margin-bottom: 2px;
    }

    td {
        border: 1px solid #000;
    }
</style>
<div id="main_frame_box">
    <table border="0" style="width:382px;height:375px;" cellspacing="0" cellpadding="0">
        <tr height="12%">
            <td colspan="2">
                <p style="float:left;width:298px;height:50px;margin-top:5px;text-align:center;">
                    Package Number:{{ $model->lazada_package_id }}<br/>
                    <img src="{{ route('barcodeGen', ['content' => $model->lazada_package_id]) }}">
                </p>
                <p style="float:left;width:80px;height:100%;text-align:center;line-height:20px;font-weight:bold;font-size:14px;border-left:1px solid #000;">
                    Parcel<br/> Green
                </p>
            </td>
        </tr>
        <tr height="35%">
            <td colspan="2" style="border-top:none;">
                <p style="float:left;width:265px;margin-left:10px;height:100%;text-align:left;overflow:hidden;word-wrap:break-word;font-size:13px;">
                    {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                    {{ $model->shipping_address . ' ' }}
                    {{ $model->shipping_address1 }}<br/>
                    {{ $model->shipping_city }}<br/>
                    {{ $model->shipping_state }}<br/>
                    {{ $model->shipping_zipcode . ' ' }}
                    {{ $model->country ? $model->country->name : '' }}
                </p>
                <p style="float:left;width:98px;height:100%;text-align:center;font-weight:bold;font-size:15px;">
                    {{ $model->shipping_phone }}
                    <br/><br/><br/><br/><br/>
                    【{{ $model->logistics_id }}】
                </p>
            </td>
        </tr>
        <tr height="15%">
            <td colspan="2" style="border-top:none;">
                <p style="float:left;width:238px;height:50px;margin-top:5px;text-align:center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                    <br/>{{ $model->tracking_no }}
                </p>
                <p style="float:left;width:140px;height:100%;text-align:center;line-height:20px;font-size:12px;border-left:1px solid #000;">
                    <span style="font-weight:bold;">Payment Method:</span>
                    <br/>
                    Pre-paid
                </p>
            </td>
        </tr>
        <tr height="25%" style="text-align:center;">
            <td colspan="2" style="border-top:none;">
                <img src="{{ asset('picture/LBC.jpg') }}" style="width:370px;height:80px;"/>
                Sold and fulfilled by:
                @if($model->channelAccount)
                    @if($model->channelAccount->account == '99706454@qq.com_PH')
                        {{ 'Moonarstore' }}
                    @elseif($model->channelAccount->account == 'lixuanpengwu@126.com_PH')
                        {{ 'Makiyo' }}
                    @else
                        {{ '该订单不符合打印条件' }}
                    @endif
                @endif
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{ $model->order_id }}
            </td>
        </tr>
    </table>
</div>
</body>
</html>