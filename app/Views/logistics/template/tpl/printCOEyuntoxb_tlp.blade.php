<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印云途中华小包面单</title>
</head>
<body>
<style>
    * {
        margin: 0 auto;
        padding: 0;
    }

    .main {
        border: 1px solid black;
        width: 99mm;
        height: 96.5mm;
        word-break: break-all;
    }

    .content {
        border: 1px solid black;
        width: 98mm;
        height: 95.5mm;
        margin-top: 1px;
    }

    .fk {
        display: inline-block;
        width: 9px;
        height: 7px;
        border: 1px solid #000;
        padding-top: 4px
    }
</style>
<div class="main">
    <div class="content">
        <div style="height:46mm;">
            <div style="height:45mm;width:38mm;float:left">
                <div style="height:29mm;width:36mm;border:1px solid black;text-align: center;font-size:14px;margin-top:2px;">
                    <p>TAIPEI(TP)TAIWAN</p>
                    <p>R.O.C.</p>
                    <p>POSTAGE PAID</p>
                    <p>LICENCE NO.TP6627</p>
                </div>
                <div style="height:12mm;width:36mm;font-size:11px;line-height: 12px;margin-top:1px;padding-left:5px;">
                    <p>
                        From:<br/>
                        {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}
                    </p>
                </div>
            </div>
            <div style="height:46mm;width:58mm;margin-right:1px;float:right;font-size:11px;text-align: center;">
                <p style="line-height:14px;">CHUNGHWA POST CO., LTD.
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size: 14px;">CN22</b></p>
                <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                <div style="font-size:16px;font-weight:bold;line-height: 14px;margin-top:1px;">
                    {{ $model->tracking_no }}
                </div>
                <p style="line-height:13px;width:10mm;font-size:14px;font-weight: bold;text-align: left;float:left;">
                    TO</p>
                <p style="width:45mm;font-size:15px;font-weight: bold;text-align: right;margin-right:10px;float:right;line-height:13px;">
                    @if(in_array(substr($model->shipping_zipcode, 0, 1), [0, 1, 2, 3]))
                        {{ 'JFK' }}
                    @elseif(in_array(substr($model->shipping_zipcode, 0, 1), [4, 5, 6]))
                        {{ 'ORD' }}
                    @elseif(in_array(substr($model->shipping_zipcode, 0, 1), [7, 8, 9]))
                        {{ 'LAX' }}
                    @endif
                </p>
                <p style="font-size:12px;text-align:left;font-weight: bold;line-height: 13px;">
                    {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                    {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                    {{ $model->shipping_city . ' ' . $model->shipping_state . ',' }}
                    {{ $model->country ? $model->country->cn_name : '' }}<br/>
                    {{ '.' }}Postal code: {{ $model->shipping_zipcode . '.' }}<br/>
                    {{ '.' }}Tel: {{ $model->shipping_phone }}
                </p>
            </div>
        </div>
        <div style="height:4mm;width:96mm;border:1px solid black;font-size:11px;padding-left: 2px;padding-top: 1px;font-weight: bold;">
            <span class="fk" style="line-height: 7px;">√</span>&nbsp; Gift &nbsp;
            <span class="fk" style="line-height: 7px;"></span>&nbsp; Commercial sample&nbsp;
            <span class="fk" style="line-height: 7px;"></span>&nbsp; Documents&nbsp;
            <span class="fk" style="line-height: 7px;"></span>&nbsp; Others&nbsp;
        </div>
        <div style="height:26mm;">
            <div style="width:47mm;height:23mm;float:left;margin-left:2px;">
                <table style="margin-top:2px;font-size:10px;border:1px solid black;line-height: 11px;text-align: center;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="border-bottom: 1px solid black;border-right: 1px solid black;width:30mm;">Quantity
                            and detailed description of contents
                        </td>
                        <td style="border-bottom: 1px solid black;border-right: 1px solid black;">Weight(KG)</td>
                        <td style="border-bottom: 1px solid black;">Value(USD)</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid black;">
                            {{ $model->sku_info }}
                        </td>
                        <td style="border-right: 1px solid black;">
                            {{ $model->signal_weight }}
                        </td>
                        <td>
                            {{ sprintf("%.2f", $model->signal_price > 20 ? 20 : $model->signal_price) }}
                        </td>
                    </tr>
                </table>
            </div>
            <div style="margin-top:1px;border:1px solid black;width:48mm;height:24mm;float:right;margin-right:2px;font-size:11px;line-height: 10px;padding:1px;">
                <p style="line-height: 9px;border-bottom: 1px solid black;padding-bottom: 1px;">
                    <b>
                        {{ $model->logistics_id }}
                    </b>
                </p>
                <p>I,the undersigned,whose name and address are given on the item certify that the particulars given in
                    this declaration are correct and that this item does not contain any dangerous article or articles
                    prohibited by legislation or by postal or customs regulations.
                </p>
            </div>
        </div>
        <div style="height:11mm;width: 97mm;margin-top: 1px;">
            <table cellpadding="0" cellspacing="0" style="height:11mm;font-size:11px;line-height: 9px;text-align: center; border:1px solid black;">
                <tr>
                    <td rowspan="2" valign="top" style="width:30mm;border-right: 1px solid black;">For commerical items
                        only If known,HS tariff number and country of origin of goods
                    </td>
                    <td valign="top" style="border-bottom: 1px solid black;border-right: 1px solid black;">Total <br/>Weight
                    </td>
                    <td valign="top" style="border-bottom: 1px solid black;border-right: 1px solid black;">Total<br/>Value
                    </td>
                    <td valign="top" style="width:45mm;border-bottom: 1px solid black;line-height:18px;">Date and
                        sender's signature :
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="width:11mm;border-right: 1px solid black;line-height:15px;">
                        {{ $model->total_weight }}
                    </td>
                    <td valign="top" style="width:10mm;border-right: 1px solid black;line-height:15px;">
                        {{ sprintf("%.2f", $model->total_price > 20 ? 20 : $model->total_price) }}
                    </td>
                    <td valign="top" style="line-height:15px;">TSAI TSUNG LIANG &nbsp;{{ date('Y-m-d') }}</td>
                </tr>
            </table>
        </div>
        <div style="height:6mm;border:1px solid black;margin-top: 2px;width:96mm;font-size:11px;line-height: 11px;">
            <p style="width:56mm;float:left;">Taipei Forever incorporated company<br/>
                This parcel is transit inTaiwan Free Zone
            </p>
            <p style="width:35mm;float:right;text-align: right;margin-right: 4px;line-height: 25px;" valign="bottom">
                {{ $model->order_id . ' ' . date('Y-m-d') }}
            </p>
        </div>
    </div>
</div>
</body>
</html>