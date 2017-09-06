<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>JHDHK香港平邮面单</title>
</head>
<body>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif, "宋体", Verdana;
        font-size: 14px;
        margin: 0;
        padding: 0;
    }

    td {
        white-space: nowrap;
    }

    .PageNext {
        page-break-after: always;
        clear: both;
        min-height: 1px;
        height: auto;
        overflow: auto;
        width: 100%;
    }

    .float_box {
        position: relative;
        width: 370px;
        height: 364px;
        overflow: hidden;
        margin: 0 auto;
        border: 1px solid black;
    }

    div, p {
        margin: 0;
        padding: 0;
    }
</style>
<div id="main_frame_box">
    <div class="float_box">
        <div align="center" style="width:100%;height:60px;">
            <div style="padding:2px 0px 0 0px;">

                <div style="float:left; width: 85px; text-align:left;">
                    <div><b>AM - ZDXL</b></div>
                </div>

                <div style="float:center;">
                    <table cellspacing="0" cellpadding="0" border="1" height="40px;">
                        <tr>
                            <td align="LEFT" width="70px;">
                                <div style="font-family:Microsoft YaHei;font-size: 9px; padding:0 2px;height: 20px;font-weight:bold;">
                                POSTAGE PAID<br>
                                HONG KONG<br> PORT PAYE
                                </div>
                            </td>
                            <td align="center" width="50px;">
                                <div style="font-family:Microsoft YaHei;font-size: 9px; padding:0 5px; font-weight:bold;">
                                    PERMIT<br>
                                    NO.<br>
                                    <br>
                                    6985
                                    </div>
                            </td>
                            <td align="center">
                                <div style="font-family:Microsoft YaHei;font-size: 9px; padding:0 5px; font-weight:bold;">
                                    BY AIR MAIL<br>
                                    航&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;空<br>
                                    PAR AVION
                                    </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="width:100%;height:95px;">
            <table style="width:368px;">
                <tr>
                    <td align="left">
                        <strong style="font-size:12px;margin-left:0px;">From:P.O.Box </strong>
                        <strong style="font-size:12px;margin-left:5px;">No 6844 at </strong>
									<span style="font-size:12px;margin-left:5px;">
									<br>GENERAL POST OFFICE <br>Hong Kong
									<br>
								</span>
                    </td>
                    <td align="left" style="font-size:12px;margin-left:5px;">
                        <b>Send TO:</b>{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br>
                        <b>Address:</b>{{ $model->shipping_address }}<br>
                        {{ $model->shipping_city }}
                        {{ $model->shipping_state }}<br>
                        {{ $model->country ? $model->country->code : '' }}
                        {{ $model->country ? $model->country->name : '' }}<br>
                        {{ $model->country ? $model->country->cn_name : '' }}<br>
                        Poland Postal <b>Code</b>:{{ $model->shipping_zipcode }}<br>
                        Phone Number:{{ $model->shipping_phone }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="width:100%;height:18px;margin-left:5px;margin-top:10px;">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
        <div>
            <table border="1" cellspacing="0" cellpadding="0" style="width:370px; border: solid thin #000;BORDER-left: rgb(2,2,2) 1px;BORDER-right:1px;BORDER-bottom:1px;">
                <tr>
                    <td align="left">description of contents</td>
                    <td align="left">KG</td>
                    <td align="left">Val(U$$)</td>
                </tr>
                <tr>
                    <td align="left">plaything</td>
                    <td align="left">{{ $model->getDeclaredInfo()['weight'] }}</td>
                    <td align="left">{{ $model->getDeclaredInfo()['declared_value'] }}</td>
                </tr>
                <tr>
                    <td align="left">Total Gress Weight(KG)</td>
                    <td align="left">{{ $model->total_weight }}</td>
                    <td align="left">{{ $model->total_price > 20 ? 20 : $model->total_price }}</td>
                </tr>
            </table>
            <table style="width:370px; border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;">
                <tr>
                    <td align="left" style="font-size:10px;" colspan="2">
                        I certify that the particulars given in this declaration are correct and this item<br>
                        does not contain any dangerous articles prohi bited by legislation or by postal<br> or
                        customs regulations.<br>
                        senden‘s signature &Data Signod:&nbsp;SLME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;{{ $model->id }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CN22
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        02
                    </td>
                </tr>
            </table>
            <table height="60px;" style="font-size:12px;border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-top: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;border-collapse:collapse;BORDER-right: rgb(0,0,0) 1px;">
                <tr>
                    <td align="center" style="font-size:12px;text-align: center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $model->tracking_no }}
                    </td>
                    <td align="center" width="150px;">
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
</body>
</html>