<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>顺丰俄罗斯平邮面单模板(130x100)</title>
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
            width: 372px;
            height: 378px;
            overflow: hidden;
            margin-bottom: 2px;
        }

        table {
            width: 100%;
            border: 0;
        }

        .border_r_b {
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        .border_b {
            border-bottom: 1px solid black;
        }

        .border_r {
            border-right: 1px solid black;
        }

        .border_t_r_l {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
        }

        .border_r_b_l {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
        }

        .border_r_l {
            border-right: 1px solid black;
            border-left: 1px solid black;
        }

        .border {
            border: 1px solid black;
        }

        .fontSize10 {
            font-size: 10px;
        }

        .fontSize11 {
            f ont-size: 11px;
        }

        .fontSize12 {
            font-size: 12px;
        }

        .fixed_box {
            position: absolute;
            right: 0px;
            bottom: 0px;
            width: 30px;
            height: 30px;
            font-size: 28px;
            font-weight: bold;
            z-index: 100;
        }
    </style>
</head>
<body>
<div id="main_frame_box">
    <div style="width:100%;height:90px;">
        <p style="width:230px;height:100%;border:2px solid #000;float:left;">
            From:SLME<br/><br/>
            Forward SF-EXPRESS <br/> P.O. Box 7023,14002 Tallinn,<br/> Estonia
        </p>
        <p style="width:130px;height:100%;border:2px solid #000;float:right;text-align:center;font-weight:bold;">
            POSTIMAKS TASUTUD TAXE PERÇUE ESTONIE No. 199
        </p>
    </div>
    <div style="width:100%;height:185px;">
        <div style="width:130px;height:105px;float:left;margin-top:5px;font-weight:bold;text-align:center;">
            <p style="border:2px solid #000;line-height:85px;font-size:25px;">
                PRIORITY
            </p>
            <p style="font-size:18px;">
                {{ $model->order ? $model->order->ordernum : '' }}
            </p>
        </div>

        <p style="width:230px;height:auto;border:2px solid #000;float:right;margin-top:5px;word-wrap: break-word;">
            To:{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
            {{ $model->shipping_address}}' {{ $model->shipping_address1}}<br/>
            {{ $model->shipping_city}}<br/>
            {{ $model->shipping_state }}<br/>
            ZIP:{{ $model->shipping_zipcode }}<br/>
            TEL:{{ $model->shipping_phone }}<br/>
            {{ $model->shipping_country}}
        </p>
    </div>
    <div style="width:100%;height:68px;">
        <p style="width:240px;height:100%;float:left;font-weight:bold;text-align:center;">
            <br/>
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"/>
            <br/>{{ $model->tracking_no }}
        </p>
        <p style="width:130px;height:100%;line-height:68px;float:right;text-align:center;font-weight:bold;font-size:22px;">
            @if($model->russiaPYCode)
                @foreach($model->russiaPYCode as $itemCode)
                    @if($itemCode->type == 'p')
                        {{ $itemCode->express_code}}
                    @endif
                @endforeach
            @endif
        </p>
    </div>
</div>
<div id="main_frame_box" style="height:370px;">
    <table cellpadding="5" cellspacing="0" class="fixed">
        <tr style="font-size:12px;">
            <td>
                <table cellspacing="0">
                    <tr>
                        <td colspan="6" class="border">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-weight: bold;">
                                        CUSTOMS<br/>
                                        DECLARATION
                                    </td>
                                    <td>
                                        May be opened<br/>
                                        officially
                                    </td>
                                    <td style="font-size: 20px; font-weight: bold;">
                                        CN22
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="border_r_b_l">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="140" style="font-weight: bold;">Designated operator</td>
                                    <td align="right" valign="top">

                                        Important!<br/>See instructions on the back
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr height="15">
                        <td width="18" height="18" class="border_r_b_l" align="center"></td>
                        <td width="100" style="line-height: 14px;">Gift</td>
                        <td width="18" class="border_r_b_l"></td>
                        <td width="200" colspan="3" class="border_r">Commerical Sample</td>
                    </tr>
                    <tr>
                        <td class="border_r_b_l" height="18"></td>
                        <td class="border_b">Documents</td>
                        <td class="border_r_b_l" align="center" style="font-family: 宋体; font-weight: bold; font-size: 16px;">&times;</td>
                        <td colspan="3" class="border_r_b">
                            <div style="position: relative;">
                                Other
                                <span style="position: absolute; right: 0; bottom: -4px;">Tick one or more boxes</span>
                            </div>
                        </td>
                    </tr>
                    <tr style="line-height: 12px;">
                        <td colspan="4" class="border_r_b_l">
                            Quantity and detailed description <br/>of contents(1)
                        </td>
                        <td width="25%" class="border_r_b">
                            Weight(in kg)
                        </td>
                        <td width="25%" class="border_r_b">
                            Value(3)
                        </td>
                    </tr>
                    <tr style="line-height: 12px; height: 39px;">
                        <td colspan="4" class="border_r_b_l" valign="top" width="60%">
                            {{ $model->decleared_ename }}*1

                        </td>
                        <td class="border_r_b" valign="top">
                            {{ $model->total_weight }}
                        </td>
                        <td class="border_r_b" valign="top">
                            {{ sprintf("%.2f",$model->total_price) }}
                        </td>
                    </tr>
                    <tr style="line-height: 12px;">
                        <td colspan="4" class="border_r_b_l" style="line-height: 12px;">
                            For commericial items only<br/>If known, HS tariff number(4) <br/>and country of origin of
                            goods(5)
                        </td>
                        <td class="border_r">
                            Total Weight<br/>(in kg)(6)
                        </td>
                        <td class="border_r">
                            Total value(7)
                        </td>
                    </tr>
                    <tr style="line-height: 14px;">
                        <td colspan="4" class="border_r_b_l">CN</td>
                        <td class="border_r_b">
                            {{ $model->total_weight }}
                        </td>
                        <td class="border_r_b">
                            {{ sprintf("%.2f",$model->total_price) }}
                        </td>
                    </tr>
                    <tr style="line-height: 12px;height:60px;">
                        <td colspan="6" class="border_r_b_l" style="word-wrap: normal; word-break: keep-all;">
                            I,the undersigned, whose name and address are given on the item, certity that the
                            particulars given
                            in this declaration are correct and that this item does not contain
                            any dangerous article or articles pro-hibited by legislation or by postal or
                            customs regulations<br/>
                            Date and sender\'s signature(8) SLME
                        </td>
                    </tr>
                </table>
            </td>
    </table>
    <hr style="height:5px;border:none;border-top:5px solid #000;margin-top:5px;"/>
    <div style="width:100%;height:55px;">
        <p style="width:190px;height:55px;float:left;text-align:center;font-weight:bold;">

            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"/>
            <br/>{{ $model->tracking_no }}
        </p>
        <p style="width:40px;height:55px;float:left;font-weight:bold;line-height:60px;font-size:20px;text-align:center;">
            {{ $model->shipping_country }}
        </p>
        <p style="width:40px;height:55px;float:left;font-weight:bold;line-height:60px;font-size:20px;text-align:center;">
            P
        </p>
        <p style="width:96px;height:55px;float:left;border:2px solid #000;font-size:20px;text-align:center;">
            Electric<br/>
            {{ $model->is_battery ? 'Y' : 'N'}}
        </p>
    </div>
</div>
</body>
</html>