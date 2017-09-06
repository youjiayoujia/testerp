<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>LAZADA新加坡面单SG3</title>
    </head>
    <body>
        <style>
            *{margin:0;padding:0;}
            .main{width:98mm;height:96mm;border:1px solid black;margin:auto;font-size:14px;padding:1mm;}
        </style>
    <div class="main">
        <table cellspacing=0 cellpadding=0>
            <tr>
                <td colspan=2 style="height:20px;font-weight: bold;text-align: right;">
                    SG3
                </td>
            </tr>
            <tr>
                <td style="width:50mm;" valign="top">
                    <p style="font-size: 11px;line-height: 11px;">
                        If undelivered, please return to:<br/>
                        20 Toh Guan Road<br/>
                        #08-00 CJ Korea Express Building<br/>
                        Singapore 608839<br/>
                    </p>
                    <p style="margin-top:15px;">Deliver To:</p>
                    <p>
                        {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                        {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                        {{ $model->shipping_city }}<br/>
                        {{ $model->shipping_state . ' ' . $model->shipping_zipcode . ',' }}{{ $model->country ? $model->country->name : '' }}<br/>
                        {{ $model->shipping_phone }}
                    </p>
                    <p style="margin-top:5px;">
                        {{ $model->lazada_package_id }}
                    </p>
                </td>
                <td valign="top" >
                    <p style="text-align:right;width:47mm;">
                        <img src="{{ asset('picture/lazada.png') }}" style="width:180px;"/>
                    </p>
                    <p style="text-align:center;width:143px;border:3px solid black;margin-top:10px;margin-left:22px;font-weight: bold;padding:2px;font-size: 16px;">
                        Registered Mail
                    </p>
                    <p style="text-align:center;margin-top:20px;font-size:20px;font-weight: bold">
                        {{ $model->shipping_zipcode }}
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="height:25mm;">
                    <div style="width:14mm;height:20mm;font-size: 20px;font-weight: bold;float:left;line-height: 90px;margin-top: 10px;" >
                        RX
                    </div>
                    <div style="width:82mm;height:20mm;float:right;text-align:center;margin-top: 10px;">
                        <p>{{$model->tracking_no}}</p>
                        <p><img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></p>
                        <p>{{ $model->tracking_no }}</p>
                    </div>
                    <div>{{ $model->order_id }}</div>
                </td>
            </tr>
        </table>
    </div>
    </body>
</html>