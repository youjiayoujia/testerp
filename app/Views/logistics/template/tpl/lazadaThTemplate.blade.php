<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>lazada泰国面单</title>
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
        height: 128mm;
        overflow: hidden;
        margin-bottom: 2px;
        border-bottom: 1px solid #000;
    }

    td {
        border: 1px solid #000;
        border-bottom: none;
    }
</style>
<div id="main_frame_box">
    <table border="0" style="width:382px;height:128mm;" cellspacing="0" cellpadding="0">
        <tr height="100">
            <td style="border-right:none;width:35%;text-align:center;font-weight:bold;">
                {{ $model->order ? $model->order->by_id : '' }}<br>
                【{{ $model->logistics_id }}】
            </td>
            <td style="border-right:none;width:58%;text-align:center;font-weight:bold;">
                EMS Tracking No:<br/>
                <p style="font-size:5px;">&nbsp;</p>
                <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                <br/>
                {{ $model->tracking_no }}
            </td>
            <td style="width:7%;text-align:center;font-weight:bold;">
                TH1
            </td>
        </tr>
        <tr height="70">
            <td colspan="3" style="text-align:center;">
                Package No:<br/>
                <p style="font-size:5px;">&nbsp;</p>
                <img src="{{ route('barcodeGen', ['content' => $model->lazada_package_id]) }}">
                <br/>
                <span style="font-weight:bold;">
                    {{ $model->lazada_package_id }}
                </span>
            </td>
        </tr>
        <tr height="200" style="overflow:hidden;">
            <td style="border-right:none;width:35%;text-align:center;font-weight:bold;">
                @if($model->channelAccount)
                    @if($model->channelAccount->account == '99706454@qq.com_PH')
                        {{ 'Moonarstore' }}
                    @elseif($model->channelAccount->account == 'lixuanpengwu@126.com_PH')
                        {{ 'Makiyo' }}
                    @else
                        {{ '该订单不符合打印条件' }}
                    @endif
                @endif
                <br/>
                ชื่อบริษัท:
                กรณีนำจ่ายไม่ได้ กรุณาส่งคืน ศป.EMS 10020<br/>
                <img src="{{ asset('picture/TH_label_lzd_log.png') }}" style="width:120px;"/>
            </td>
            <td colspan="2">
                {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                {{ $model->shipping_city }}<br/>
                {{ $model->shipping_state }}<br/>
                {{ $model->country ? $model->country->name : '' }}&nbsp;&nbsp;{{ $model->shipping_zipcode }}<br/>
                {{ $model->shipping_phone }}

            </td>
        </tr>
        <tr height="50" style="font-weight:bold;">
            <td style="border-right:none;width:35%;">
                ไม่เก็บเงินค่าสินค้า
            </td>
            <td colspan="2">
                {{ $model->shipping_zipcode }}
            </td>
        </tr>
    </table>

</div>
</body>
</html>