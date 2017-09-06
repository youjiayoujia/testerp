<?php
use App\Models\Logistics\Zone\GzAddressModel;
$GzAddressModel = new GzAddressModel();
$sender_info = $GzAddressModel->sender_info;
if(!$sender_info){
    //return redirect(route(''))->with('alert', $this->alert('danger', '今日寄件人地址已用完，不允许打印!'));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>广州邮政平邮</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    .main {
        border: 1px black solid;
        width: 96mm;
        height: 128mm;
        margin: auto;
        font-size: 12px;
        word-break: break-all;
    }

    .header {
        height: 31px;
        line-height: 31px;
    }

    .logo {
        height: 31px;
        float: left;
    }

    .header span {
        font-size: 31px;
        font-weight: bold;
    }

    .byair {
        width: 100px;
        float: left;
        font-size: 10px;
        text-align: center;
        line-height: 11px;
        font-weight: bold;
        margin-top: 1px;
    }

    .header1 {
        width: 140px;
        height: 31px;
        float: left;
    }

    .header3 {
        float: right;
        font-size: 10px;
        font-weight: bold;
        line-height: 25px;
        margin-right: 7px;
    }

    .agree {
        border-bottom: 1px black solid;
        border-top: 1px black solid;
        height: 15px;
        text-align: left;
        font-size: 10px;
        clear: both;
    }

    .from {
        border-bottom: 1px black solid;
        height: 27px;
        font-size: 12px;
        line-height: 13px;
    }

    .from div:nth-child(1) {
        width: 12mm;
        height: 28px;
        float: left;
    }

    .from div:nth-child(2) {
        width: 80mm;
        height: 28px;
        float: left;
    }

    .from span, .to span {
        font-weight: bold;
    }

    .to {
        border-bottom: 1px black solid;
        height: 76px;
        font-size: 13px;
        line-height: 13px
    }

    .to div:nth-child(1) {
        width: 10mm;
        height: 76px;
        float: left;
    }

    .to div:nth-child(2) {
        width: 83mm;
        height: 76px;
        float: right;
    }

    .tel {
        border: 1px black solid;
        height: 15px;
    }

    .tel div:nth-child(1), .tel div:nth-child(2) {
        width: 47mm;
        height: 15px;
        float: left;
        font-weight: bold;
    }

    .return {
        border-bottom: 1px black solid;
        height: 16px;
        font-size: 12px;
    }

    .khdm {
        border-bottom: 1px black solid;
        height: 64px;
    }

    .khimg {
        width: 255px;
        height: 50px;
        padding-top: 2px;
        margin-left: 42px;
        text-align: center;
        font-weight: bold;
        font-size: 15px;
    }

    .khdm div:nth-child(1) {
        font-weight: bold;
        font-size: 25px;
        float: left;
        height: 55px;
        line-height: 55px;
        margin-left: 10px;
    }

    .dm {
        font-weight: bold;
        font-size: 25px;
        float: right;
        width: 75px;
        height: 55px;
        line-height: 55px;
    }

    .bgqt {
        border-bottom: 1px black solid;
        height: 21px;
        font-size: 9px;
    }

    .bgqt div:nth-child(1) {
        width: 29mm;
        float: left;
        text-align: left;
        line-height: 10px;
    }

    .bgqt div:nth-child(1) span {
        font-weight: bold;
    }

    .bgqt div:nth-child(2) {
        width: 32mm;
        float: left;
        text-align: center;
        line-height: 10px;
    }

    .bgqt div:nth-child(3) {
        width: 35mm;
        float: left;
        text-align: center;
        line-height: 10px;
    }

    .detail {
        height: 140px;
        border-bottom: 1px black solid;
    }

    .footer {
        font-size: 8px;
        line-height: 9px;
        position: relative;
    }

    table {
        font-size: 10px;
        line-height: 10px;
    }

    table tr td {
        border-bottom: 1px solid black;
        vertical-align: text-top;
    }

    td {
        border-right: 1px solid black;
    }

</style>
<div class="main">
    <div class="header">
        <div class="header1">
            <img src="{{ asset('picture/post_logo.jpg') }}" class="logo"/><span style="line-height:36px;"></span>
        </div>
        <div class="byair">航空<br>Small packed<br/>BY Air</div>
        <div class="header3">
            {{ $model->country ? $model->country->cn_name : '' }}&nbsp;
            {{ $model->country ? $model->country->code : '' }}
        </div>
    </div>
    <div class="agree">协议客户 （SLME01）90000006605467</div>
    <div class="from">
        <div style="float:left;margin-right:10px;"><span>From:</span></div>
        <div class="fromads" style="float:left;">
            {{ $sender_info->sender . ' ' . $sender_info->address }}
        </div>
    </div>
    <div class="to">
        <div style="float:left;margin-right:10px"><span>To:</span></div>
        <div class="toads" style="float:left;">
            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
            {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
            {{ $model->shipping_city }},{{ $model->shipping_state }}<br/>
            {{ $model->country ? $model->country->name : '' }}({{ $model->country ? $model->country->cn_name : '' }})
        </div>
    </div>
    <div class="tel">
        Zip:{{ $model->shipping_zipcode }}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Tel:{{ $model->shipping_phone }}
    </div>
    <div class="return">退件单位&nbsp;&nbsp;
        {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->unit) : '') : '' }}
    </div>
    <div class="khdm" style="border-top:1px solid black;font-size:30px;">
        <p style="width:50px;padding:0;float:left;font-size:40px;text-align:center;height:50px;font-weight:bold;">
            {{ $model->country ? ($model->country->geKou ? $model->country->geKou->geID : '') : '' }}
        </p>
        <p style="display:inline-block;width:240px;float:left;font-size:12px;height:50px;text-align:center;padding-top:2px;">
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" style="height: 48px; width: 200px">
            <br/>
            {{ $model->tracking_no }}
        </p>
        <p style="display:inline-block;width:55px;float:left;font-size:20px;height:50px;text-align:center;">
            AAAJ
        </p>
    </div>
    <div class="bgqt" style="height:25px">
        <p style="display:inline-block;width:110px;float:left;font-size:10px;font-weight:bold;">
            <span style="font-size:8px;">报关签条<br/>CUSTOMS DECLARATION</span>
        </p>
        <p style="display:inline-block;width:100px;float:left;font-size:10px;text-align:center;">
            <span style="font-size:8px;">可以进行开拆<br/>May be open officially</span>
        </p>
        <p style="display:inline-block;width:120px;float:left;font-size:10px;text-align:center;">
            <span style="font-size:8px;padding-left:5px;">请先阅读背面的注意事项<br/>May be opend officially</span>
        </p>
    </div>
    <div class="detail" style="border-top:1px solid black;">
        <div style="height:125px;width:265px;float:left;">
            <table cellspacing="0" cellpadding="0" style="border-right:1px solid black;">
                <tr>
                    <td style="width:160px;padding:1px"><p style="width:140px;border-right:1px solid black;">邮件种类
                            Category of item</p></td>
                    <td colspan="2"></td>
                </tr>
                <tr style="height:30px;">
                    <td>内件详情名称和数量 Quantity and<br/>detailed description of contents</td>
                    <td style="text-align: center;">重量(千克)<br/>Weight(kg)</td>
                    <td style="text-align: center;">价值<br/>Value</td>
                </tr>
                <tr style="height:30px;">
                    <td style="text-align: center;padding-top:2px;">
                        {{ $model->items ? $model->items->first()->quantity : '' }} * {{ $model->getDeclaredInfo()['declared_en'] }}
                    </td>
                    <td style="text-align: center;">
                        {{ $model->getDeclaredInfo()['weight'] }}
                    </td>
                    <td style="text-align: center;">
                        USD{{ sprintf("%.2f", $model->getDeclaredInfo()['declared_value']) > 20 ? 20 : $model->getDeclaredInfo()['declared_value'] }}
                    </td>
                </tr>
                <tr>
                    <td>协调系统税则号列和货物原 产国(只对商品邮件填写)<br/>HS tatiff number and country of origin of goods (For commerical
                        items only)
                    </td>
                    <td style="text-align: center;border:1px solid black;">总重量(kg)Total weight(kg)</td>
                    <td style="text-align: center;border:1px solid black;">总价值<br/>Total value</td>
                </tr>
                <tr style="border-bottom: none;">
                    <td style="border-bottom: none;"></td>
                    <td style="border-bottom: none;width:50px;height:10px;text-align: center;padding-top:2px;">
                        {{ $model->total_weight }}
                    </td>
                    <td style="border-bottom: none;text-align: center;width:50px">
                        USD{{ sprintf("%.2f", $model->total_price > 20 ? 20 : $model->total_price) }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="float:right;border-bottom:1px solid black;">
            <table style="font-size:10px;float:right;width:97px;height:50px;text-align:center;" cellspacing="0" cellpadding="0" class="table2">
                <tr style="border-bottom:1px solid black;">
                    <td width="48">航空</td>
                    <td>Guangzhou<br/>China</td>
                </tr>
                <tr style="border-bottom:1px solid black;">
                    <td height="30">PAP AVON</td>
                    <td>已验视</td>
                </tr>
                <tr style="border-bottom:1px solid black;">
                    <td height="35">小包邮件</td>
                    <td>单位:<br/>广州小包中心</td>
                </tr>
                <tr>
                    <td height="30">PETIT<br/>PAQUET</td>
                    <td style="line-height:13px;">验视人:<br/>
                        <span style="font-size:12px;">林文勇</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="footer" style="border-top:1px solid black;">
        我保证上述申报准确无误,本函件内未装寄法律或邮政和海关规章禁止寄递的任何危险物品<br/>
        I, the undersigned,certify that the particulars given inthis declaration are correct and this item does not
        containany dangerous articles prohibited by legislation or bypostal or customs regulations.<br/>
        寄件人签字 Sender's signature:
        <div style="float:right;font-size:12px;font-weight:bold;margin-right:30px;">CN22</div>
        <div style="font-size:12px;padding-top:2px;right:0;bottom:0;width:100px;">
            ({{ $model->id }})
        </div>
        <div style="text-align:right;padding-right:2px;font-size:14px;font-weight:bold;width:100px;position:absolute;right:0;bottom:0">
            {{ $model->logistics ? $model->logistics->logistics_code : '' }}
        </div>
    </div>
</div>
</body>
</html>