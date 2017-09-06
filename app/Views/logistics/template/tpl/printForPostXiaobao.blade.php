<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印中国邮政一体化面单</title>
</head>
<body>
<style>
    *{margin:0;}
    body{ font-family:Arial, Helvetica, sans-serif,"宋体",Verdana; font-size:14px;line-height:14px;}
    td{ white-space:nowrap;}
    .PageNext{page-break-after:always; clear:both; min-height:1px; height:auto; overflow:auto; width:100%;}
    #main_frame_box{width:380px; margin:0 auto;}
    .float_box1{ float:left; width:370px; height:370px;overflow:hidden; margin:1px 3px 1px; border:1px solid black;}
    .float_box2{ float:left; width:370px; height:334px;  margin:1px 3px 1px; border:1px solid black;}
</style>

<div id="main_frame_box">
    <div class="float_box1">
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
            <tr height="56">
                <td style="border-bottom: 1px solid black;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr align="center">
                            <td width="112" rowspan="3">
                                <img src="{{ asset('image/post_logo.jpg') }}" width="112" height="34" border="0" /></td>
                            <td width="120"><strong>航空</strong></td>
                            <td rowspan="3">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr align="center">
                                        <td>

                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td style="width:13mm;font-weight:bold;font-size:16px;">
                                            {{ $model->country ? $model->country->cn_name : '' }}
                                            {{ $model->country ? $model->country->code : '' }}
                                            @if($model->country)
                                                @foreach(['RU' => 21,'US' => 22,'GB' => 23,'BR' => 24,
                                                          'AU' => 25,'FR' => 26,'ES' => 27,'CA' => 28,
                                                          'IL' => 29,'IT' => 30,'DE' => 31,'CL' => 32,
                                                          'SE' => 33,'BY' => 34,'NO' => 35,'NL' => 36,
                                                          'UA' => 37,'CH' => 38,'MX' => 39,'PL' => 40,] as $key => $value )
                                                    @if($key == $model->country->code)
                                                         {{ $value }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr align="center">
                            <td><strong>Small Packet</strong></td>
                        </tr>
                        <tr align="center">
                            <td><strong>BY AIR</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr valign="top" height="22">
                <td style="border-bottom: 1px solid black;">
                    <div style="float:left;font-size: 12px">协议客户：
                        {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->customer) : '') : '' }}</div>
                </td>
            </tr>
            <tr height="56">
                <td style="border-bottom: 1px solid black;font-size: 12px">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr><td width="50" align="right" valign="top"><strong>FROM:&nbsp;</strong></td><td colspan="3" style="white-space: normal; word-break: break-all;">{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}</td></tr>
                        <tr><td align="right"><strong>ZIP:&nbsp;</strong></td><td width="100">{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->zipcode) : '') : '' }}</td>
                            <td width="40" align="right"><strong>TEL:&nbsp;</strong></td><td>{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '') : '' }}</td></tr>
                    </table>
                </td>
            </tr>
            <tr height="100">
                <td valign="top">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="40" valign="top" align="right">
                                <strong>TO:&nbsp;</strong>
                            </td>
                            <td colspan="3" style="white-space: normal; word-break: keep-all;">
                                {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                                {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                                {{ $model->shipping_city }}
                                {{ $model->shipping_state }}
                                {{ $model->country ? $model->country->cn_name : '' }}
                            </td>
                        </tr>
                        <tr><td align="right">ZIP:&nbsp;</td><td width="100">{{ $model->shipping_zipcode }}</td><td width="40" align="right">TEL:&nbsp;</td><td>{{ $model->shipping_phone }}</td></tr>
                        <tr><td>&nbsp;</td><td colspan="3" style="white-space: normal; word-break: break-all; font-size:9px;">{{ $model->sku_info }}</td></tr>
                        <tr>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="width:370px;height:30px; font-size:25px;">
            <span style="font-size:25px;">{{ $model->shipping ? $model->shipping->logistics_code : '' }}</span>

            <span style="margin-left:240px;">{{ $model->items ? $model->items->sum('quantity') : 0 }}</span>
        </div>
        <div style="width: 370px;  bottom:60px; text-align: center; clear:both; border-top:1px solid black; padding-bottom: 2px;">

        </div>
        <div style="width: 370px;  bottom:60px; text-align: center; clear:both; border-top:1px solid black; padding-bottom: 2px;">
            退件单位：{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->unit) : '') : '' }}
        </div>
        <div style="width: 370px; height:58px; bottom:0px; text-align: center; clear:both; border-top:1px solid black; padding-top: 2px;">
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
            <span class="fontSize10"><br>{{ $model->tracking_no ? $model->tracking_no : '' }}</span>
        </div>
    </div>

    <div class="float_box2">
        <table border="0" cellpadding="2" cellspacing="0" width="100%" style="font-size: 11px;">
            <tr style="">
                <td style="border-bottom: 1px solid black;" colspan="7">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr align="center">
                            <td width="90" rowspan="2">{{ $model->id ? $model->id : '' }}</td>
                            <td width="175"><strong>报关签条</strong></td>
                            <td><strong>邮2113</strong></td>
                        </tr>
                        <tr align="center">
                            <td><strong>CUMTOMS DECLARATION</strong></td>
                            <td><strong>CN22</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black;" colspan="7">
                    <div style="width:150px; float:left;">可以经行拆开</div>
                    <div style="float: left;">May be opened officially</div>
                </td>
            </tr>
            <tr style="line-height:12px;">
                <td width="50" align="center" style="border-bottom:1px solid black; border-right:1px solid black;" rowspan="2">邮件种类</td>
                <td width="20" style="border-bottom: 1px solid black; border-right:1px solid black;" align="center">&nbsp;</td>
                <td width="80" style="border-bottom: 1px solid black; border-right:1px solid black;">礼品<br/>gift</td>
                <td width="20" style="border-bottom: 1px solid black; border-right:1px solid black;">&nbsp;</td>
                <td style="border-bottom: 1px solid black;" colspan="3">商品货样<br/>Commercial Sample</td>
            </tr>
            <tr style="line-height:12px;">
                <td style="border-bottom: 1px solid black; border-right:1px solid black;">&nbsp;</td>
                <td style="border-bottom: 1px solid black; border-right:1px solid black;">文件<br/>Documents</td>
                <td width="20" style="border-bottom: 1px solid black; border-right:1px solid black;" align="center"><b style="font-family: \'宋体\'; font-size:16px;">X</b></td>
                <td style="border-bottom: 1px solid black;" colspan="3">其他<br/>Other</td>
            </tr>
            <tr style="line-height:12px;">
                <td width="240" style="border-bottom: 1px solid black; border-right:1px solid black;" colspan="5" align="center">内件详细名称和数量<br/><span style="font-size: 10px;">Quantity and detailed description ofcontents</span></td>
                <td width="70" style="border-bottom: 1px solid black;border-right:1px solid black;" align="center">重量(千克)<br/>Weight(Kg)</td>
                <td width="60" style="border-bottom: 1px solid black;" align="center">价值<br/>Value</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black; border-right:1px solid black;" colspan="5" align="center">
                    {{ $model->declared_en }}*{{ $model->items ? $model->items->sum('quantity') : 0 }}
                </td>
                <td style="border-bottom: 1px solid black;border-right:1px solid black;" align="center">
                    {{ $model->signal_weight }}
                </td>
                <td style="border-bottom: 1px solid black;" align="center">
                    {{ $model->signal_price }}USD
                </td>
            </tr>

            <tr style="font-size:11px; line-height:12px;">
                <td width="240" style="border-bottom: 1px solid black; border-right:1px solid black; white-space: normal; word-break: break-all;" colspan="5" rowspan="2">
                    协调系统税则号列和货物原产国(只对商品邮件填写)<br/>
                    <p style="word-spacing: 0px; padding:0px; margin:0px; word-break: keep-all;">HS tariff number and country of origin of goods(For Commercial items only)</p>
                </td>
                <td style="border-bottom: 1px solid black;border-right:1px solid black;" align="center">总重量<br/>Total Weight(kg)</td>
                <td style="border-bottom: 1px solid black;" align="center">总价值<br/>Total Value</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black;border-right:1px solid black;" align="center">
                    {{ $model->total_weight }}
                </td>
                <td style="border-bottom: 1px solid black;" align="center">
                    {{ round($model->total_price,2) }}USD
                </td>
            </tr>
            <tr>
                <td colspan="7" style="white-space:normal;">
                    我保证上述申报准确无误，本函件内未装寄法律或邮件和海关规章禁止寄递的任何危险物品<br/>
                    <p style="word-wrap:normal; word-break: keep-all; margin:0; padding:0;">I the undersigned,certify that the particulars given in this declaration are correct and this item does not contain any dangerous articles prohibited by legislation or by postal or customers regulations.</p>
                    <p style="white-space:normal; word-break: keep-all; margin:0; padding:0; text-align: center;">寄件人签字 Sender\'s signature: *sender_signature</p>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>