<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印4px新加坡平邮小包面单</title>
</head>
<body>
<style>
    *{margin:0;padding:0;}
    body{ font-family:Tahoma,Arial,"Times New Roman","微软雅黑","Arial Unicode MS"; font-size:14px; line-height: 1.3;}
    #main_frame_box{width:300px;height:370px;margin:0 auto;position: relative; overflow:hidden;}
    .top{width:354px;height:70px;margin:10px auto;}
    table{border-collapse:collapse;border:none;width:354px;height:270px;margin:15px auto;}
    td{border:1px solid #000;}
    div, span {word-wrap: break-word;}
</style>

<div id="main_frame_box" style="width:300px;height:370px;margin:0 auto;position: relative; overflow:hidden;">
    <div>
        <div style="width: 100%; overflow: hidden;">
            <div style="float: left; height: auto; width: auto;">
                <div style="font-size:10px; width:120px; font-style:normal; line-height:12px;">
                    CHANGI AIRFREIGHT
                    CENTRE PO BOX 1192
                    SINGAPORE 918118
                </div>
            </div>

            <div style="float: left; height: auto; width: auto;">
                <div style="font-size:16px;font-weight:bold; width:25px; height:83px; line-height:80px; text-align:center; vertical-align:middle;">
                    {{ $model->Fourcode ? $model->Fourcode->partition : '' }}
                </div>
            </div>
        </div>
        <div style="width: 45%;border:2px solid #000;float: center; position:absolute;left:142px;top:0px;width:153px;height:72px;z-index:2;">
            <div style="padding-center: 50px; font-size: 17px;" align="center">
                <b>PP</b> 60108<br><br></div>
            <div style="font-family:STFangsong;font-size: 13px;color: #112" align="center">SINGAPORE</div>
        </div>
        <div style="overflow: hidden; width: 100%;">
            <div style="float: left; height: auto; width: auto;">
                <div style="font-size:12px;max-height: 125px; width: 200px;">
                    <div style="font-size: 13px; line-height: 13px; word-break: break-all;">
                        <b>TO: {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}</b>
                    </div>

                    <div style="word-wrap: break-word;">
                        <b>Tel: </b>{{ $model->shipping_phone }}
                    </div>

                    <div style="word-wrap: break-word;">
										<span>
										{{ $model->shipping_address . ' ' . $model->shipping_address1 }}
                                            {{ $model->shipping_city }}
                                            {{ $model->shipping_state }}
										</span>

                        <span>{{ $model->shipping_zipcode }}</span>
                    </div>

                    <div style="word-wrap: break-word;">
                        <b>{{ $model->country ? $model->country->cn_name : '' }}</b>
                    </div>

                </div>
            </div>

            <div style="float: right; height: auto; width: auto;">
                <div style="border: 1px solid black; font-weight: bold; text-align: center; width: 54px; word-wrap: break-word;">
                    <div style="border-bottom: 1px solid black; font-size: 6px; padding: 2px;">
                        <div>AIR MAIL</div>
                        <div>航PAR AVION空</div>
                    </div>

                    <div style="border-bottom: 1px solid black; font-size: 10px;">zone</div>

                    <div style="font-size: 18px; font-weight: bold;">{{$model->country ? $model->country->code : ''}}</div>

                    <div style="clear:both;"></div>
                </div>
                <div style="clear:none;"> &nbsp;&nbsp;&nbsp;{{ $model->fourpx ? $model->fourpx->fourpx_num : '' }}</div>
            </div>
        </div>

        <div style="height: 5mm; width: 100%;">
        </div>

        <div style="width: 100%;">
            <div>
                <div style="overflow: hidden; width: 100%;">
                    <div style="text-align: center; width: 100%; float: left; height: auto;">
                        <div><b>{{$model->tracking_no}}</b></div>
                        <div style="padding-top: 3px; text-align: center; ">
                            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"> </div>
                    </div>
                </div>
                <br>

                <div style="border: 1px solid black; margin-bottom: 2px; overflow: hidden; width: 100%;">
                    <div style="float: left; height: auto; width: auto;">
                        <div style="padding-right: 5px; font-size: 9px;">
                            4PSGOM+
                        </div>
                    </div>

                    <div style="float: left; height: auto; width: auto;">
                        <div style="padding-right: 5px; font-size: 9px;">
                            【21268260】
                        </div>
                    </div>

                    <div style="float: left; height: auto; width: auto;">
                        <div style="padding-right: 5px; font-size: 9px;">
                            <span style="word-wrap: break-word;">Ref No: </span>
                            LME{{$model->id}}
                        </div>
                    </div>
                </div>

                <div style="border: 1px solid black; margin-bottom: 2px; overflow: hidden; width: 100%;">
                    <div style="float: left; height: auto; width: auto;">
                        <div style="padding-right: 5px; font-size: 9px;">
                            CS: S4305
                        </div>
                    </div>

                    <div style="float: left; height: auto; width: auto;">
                        <div style="padding-right: 5px; font-size: 9px;">
                            SD: S0365
                            (X011)
                        </div>
                    </div>
                </div>

                <div style="overflow: hidden; width: 100%; height: 20mm;">
                    <div style="float: left; height: auto; width: auto;">
                        <div style="font-size: 9px;">
                            <b>【{{ $model->logistics ? $model->logistics->logistics_code : '' }}】</b>
                            {{ $model->sku_info }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>table{border-collapse:collapse;}td{white-space:normal;border:1px solid black;}</style>
<div class="tongyong" style="width:375px;height:360px;margin:0 auto;margin-top:8px;">
    <table border="1" style="width:375px;height:360px;margin:0;padding:0;">
        <tr height="35">
            <td colspan="3">
                <p style="float:left;width:100px;height:35px;line-height:35px;font-size:12px;text-align:center;">
                    {{$model->id}}
                </p>
                <p style="float:left;width:190px;height:35px;font-size:12px;font-weight:bold;text-align:center;">
                    报关签条<br/>
                    CUMTOMS DECLARATION
                </p>
                <p style="float:left;width:70px;height:35px;font-size:11px;font-weight:bold;text-align:center;">
                    邮2113<br/>
                    CN22
                </p>
            </td>
        </tr>
        <tr height="15">
            <td colspan="3">
                <p style="float:left;width:170px;line-height:15px;height:15px;font-size:11px;">
                    可以经行拆开
                </p>
                <p style="float:left;width:190px;line-height:15px;height:15px;font-size:11px;">
                    May be opened officially
                </p>

            </td>
        </tr>
        <tr height="70">
            <td colspan="3">
                <table style="width:375px;height:70px;margin:0;padding:0;border:none;">
                    <tr height="34">
                        <td width="60" style="border-top:none;border-bottom:none;border-left:none;" rowspan="2">
                            <p style="height:34px;text-align:center;line-height:34px;font-size:11px;">
                                邮件种类
                            </p>
                        </td>
                        <td width="30" style="border-top:none;">
                            <p style="height:34px;text-align:center;line-height:34px;font-size:14px;font-weight:bold;">
                                X
                            </p>
                        </td>
                        <td width="80" style="border-top:none;">
                            <p style="height:34px;font-size:12px;">
                                礼品<br/>
                                gift
                            </p>
                        </td>
                        <td width="30" style="border-top:none;"></td>
                        <td width="174" style="border-top:none;border-right:none;">
                            <p style="height:34px;font-size:12px;">
                                商品货样<br/>
                                Commercial Sample
                            </p>
                        </td>
                    </tr>
                    <tr height="34">
                        <td width="30" style="border-bottom:none;">

                        </td>
                        <td width="80" style="border-bottom:none;">
                            <p style="height:34px;font-size:12px;">
                                文件<br/>
                                Documents
                            </p>
                        </td>
                        <td width="30" style="border-bottom:none;"></td>
                        <td width="174" style="border-bottom:none;border-right:none;">
                            <p style="height:34px;font-size:12px;">
                                其他<br/>
                                Other
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr height="30">
            <td width="225">
                <p style="height:30px;font-size:11px;text-align:center;">
                    内件详细名称和数量<br/>
                    Quantity and detailed description ofcontents
                </p>
            </td>
            <td width="80">
                <p style="height:30px;font-size:11px;text-align:center;">
                    重量(千克)<br/>
                    Weight(Kg)
                </p>
            </td>
            <td width="70">
                <p style="height:30px;font-size:11px;text-align:center;">
                    价值<br/>
                    Value
                </p>
            </td>
        </tr>

        <tr height="25">
            <td width="225">
                <p style="height:25px;font-size:11px;text-align:center;">
                    {{ $model->getDeclaredInfo()['declared_en'] }}*{{ $model->items ? $model->items->first()->quantity : 0 }}
                </p>
            </td>
            <td width="80">
                <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
                    {{ $model->getDeclaredInfo()['weight'] }}
                </p>
            </td>
            <td width="70">
                <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
                    {{ $model->getDeclaredInfo()['declared_value'] > 20 ? 20 : $model->getDeclaredInfo()['declared_value'] }}USD
                </p>
            </td>
        </tr>
        <tr height="30">
            <td rowspan="2">
                <p style="height:55px;font-size:9px;">
                    协调系统税则号列和货物原产国(只对商品邮件填写)<br/>
                    HS tariff number and country of origin of goods(For Commercial items only)
                </p>
            </td>
            <td width="80">
                <p style="height:30px;font-size:11px;text-align:center;">
                    重量(千克)<br/>
                    Weight(Kg)
                </p>
            </td>
            <td width="70">
                <p style="height:30px;font-size:11px;text-align:center;">
                    价值<br/>
                    Value
                </p>
            </td>
        </tr>
        <tr height="25">
            <td>
                <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
                    {{ $model->total_weight }}
                </p>
            </td>
            <td>
                <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
                    {{ $model->total_price > 20 ? 20 : $model->total_price }}USD
                </p>
            </td>
        </tr>
        <tr height="80">
            <td colspan="3">
                <p style="height:90px;font-size:10px;">
                    我保证上述申报准确无误，本函件内未装寄法律或邮件和海关规章禁止寄递的任何危险物品
                    <br/>
                    I the undersigned,certify that the particulars given in this declaration are correct and this item does not contain any dangerous articles prohibited by legislation or by postal or customers regulations.
                    <br/>
                    <span style="padding-left:80px;">寄件人签字 Sender's signature:SLME </span>
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>