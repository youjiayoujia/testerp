<?php

use Illuminate\Support\Facades\Storage;
$Storage = new Storage();
$tracking_no=$model->tracking_no;//追踪号
$url = "http://120.55.205.164/api/RmlDatamatrixPic?productbarcode=".$tracking_no."";
$headers = array(                                //Token生成规则  base64_encode('xxx:xxx');  账号：密码
    "Content-type: application/json;charset=utf-8",
    "Authorization:Basic U2VsbG1vcmU6U2VsbG1vcmU4ODg="
);
$skuString='';
foreach($model->items as $v){
    $skuString.=','.$v->item->product->model.'*'.$v->quantity;
}
$skuString = trim($skuString,',');
//echo "<pre/>";var_dump($skuString);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 0);
$data = curl_exec($ch);
curl_close($ch);
$filename = '/ost/'.$tracking_no.'.jpg';
@$Storage::put($filename,$data);//保存图片
$ship_code = '';
$ship_arr = array();
$ship_arr = str_split($tracking_no,1);
$i = 0;
foreach($ship_arr as $k=>$v){   //固定长度  添加格式
    if($i == 2){
        $ship_code .= '-';
    }
    if($i == 5){
        $ship_code .= ' ';
    }
    if($i == 8){
        $ship_code .= ' ';
    }
    if($i == 12){
        $ship_code .= '-';
    }
    if($i == 15){
        $ship_code .= ' ';
    }
    if($i == 18){
        $ship_code .= ' ';
    }
    $ship_code .= $v;
    $i++;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>广州邮政平邮</title>
    <style>
        *{margin:0;padding:0;}
        body{ font-family:Arial, Helvetica, sans-serif; font-size:14px;}
        #main{width:100mm; height:96mm; margin:0 auto;border:1px solid; overflow: hidden;}
        body{font-size: 10px;}
        .f_l{float:left;}
        .f_r{float:left;}
        .address tr th{text-align:left;}
        .address tr td{text-align:right;}
        #main_frame_box{width:100mm; height:100mm;overflow:hidden;margin-bottom:2px;}
        table { width: 100%; border: 0;}
        .border_r_b { border-right: 1px solid black; border-bottom: 1px solid black;}
        .border_b { border-bottom: 1px solid black;}
        .border_r { border-right: 1px solid black;}
        .border_t_r_l{ border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;}
        .border_r_b_l{ border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;}
        .border_r_l { border-right: 1px solid black; border-left: 1px solid black;}
        .border {border: 1px solid black;}
        .fontSize10 { font-size: 10px;}
        .fontSize11 { font-size: 11px;}
        .rotation{-moz-transform:rotate(90deg);-webkit-transform:rotate(-90deg);-o-transform:rotate(-90deg);-ms-transform:rotate(-90deg);transform:rotate(-90deg);text-align:left;font-size:7px;}
        .fontSize12{ font-size: 12px;}
        .fixed_box{ position: absolute; right: 0px; bottom: 0px; width: 30px; height: 30px; font-size: 28px; font-weight: bold; z-index:100;}
    </style>
</head>
<body>
<div id="main" style="height:360px;">

    <table cellpadding="5" cellspacing="0" class="fixed">
        <tr style="font-size:12px;">
            <td>
                <table cellspacing="0">
                    </tr>
                    <tr><td colspan="2" style="border-bottom: 1px solid #000000" ><font size="5"><b>RM48</b></font></td >
                        <td  colspan="4" style="border-bottom: 1px solid #000000"><font size="4"><b>Postage Paid GB</b></font></td>
                    </tr>
                    <td style="border:none" colspan="6" class="border_r_b_l" align="left"><font size="2"><b>{{$ship_code}}</b></font></td>
                    <tr>
                    </tr>
                    <tr height="100">
                        <td style="border-left: 0px solid #804040;border-right: 0px solid #804040;"colspan="6" class="border_r_b_l" align="left">
                            &nbsp;&nbsp&nbsp;<img style="margin:19px;" width="85px" height="85px" src="{{ asset('uploads/ost/'.$tracking_no.'.jpg')}}"/>
                        </td>
                    </tr>
                    <tr height="105">
                        <td style="border-left: 0px solid #804040;" style="border-right: 0px solid #804040"  colspan="3" class="border_r_b_l" align="left">
                            &nbsp;{{$model->shipping_firstname . ' ' . $model->shipping_lastname}}<br><br>
                            &nbsp;{{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                            &nbsp;{{ $model->shipping_city }}<br/>
                            &nbsp;{{ $model->shipping_state }}<br/>
                            &nbsp;{{ $model->shipping_zipcode }} <br/>
                            &nbsp;{{ $model->shipping_phone }}

                        </td>
                        <td style="border-left: 0px solid #804040;border-right: 0px solid #804040" colspan="3" class="border_r_b_l" align="left">
                            <div><img style="margin-left:60px;" width="90px" height="110px" src="{{ asset('picture/ost_img.jpg') }}"/>
                            </div>
                        </td>
                    </tr>
                    <tr height="22">
                        <td style="border-left: 0px solid #804040;border-right: 0px solid #804040"colspan="5"  class="border_r_b_l" align="left"><b>Special Instructions:</b></td>
                    </tr>
                    <tr height="35">
                        <td style="border-bottom: 0px solid #804040;border-right: 0px solid #804040;border-left: 0px solid #804040"colspan="5" class="border_r_b_l" align="left">
                            <b>Customer Reference:&nbsp;&nbsp;&nbsp;Sellmore-{{$model->id}}<br>
                                Department Reference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <img src="{{ route('barcodeGen', ['content' => $tracking_no])}}" />
                            </b></td>
                    </tr>
                </table>
            </td>
    </table>
</div>
<br>
<div id="main" style="height:370px;">

    <table cellpadding="5" cellspacing="0" class="fixed">
        <tr style="font-size:12px;">
            <td>
                <table cellspacing="0">
                    </tr>
                    <tr>
                        <font size="2">&nbsp;&nbsp;&nbsp;{{$skuString}}<b></b></font>
                        <td  style="border-left: 0px solid #804040;border-right: 0px solid #804040" colspan="6" class="border_r_b_l">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="140" style="font-weight: bold;"></td>
                                    <td align="right" valign="top"></td>
                                    <br><br>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr height="15">
                        <td style="border-left: 0px solid #804040" width="18" height="18" class="border_r_b_l" align="center"></td>
                        <td width="100" style="line-height: 14px;"><b>Gift</b></td>
                        <td width="18" class="border_r_b_l"></td>
                        <td style="border-right: 0px solid #804040" width="200" colspan="3" class="border_r"><b>Commerical Sample</b>\Echantillon commercial</td>
                    </tr>
                    <tr>
                        <td style="border-left: 0px solid #804040" class="border_r_b_l" height="18"></td>
                        <td class="border_b">Documents</td>
                        <td  class="border_r_b_l" align="center" style="font-family: 宋体; font-weight: bold; font-size: 16px;">&times;</td>
                        <td style="border-right: 0px solid #804040" colspan="3" class="border_r_b">
                            <div style="position: relative;">
                                <b>Other</b>\Autre
                                <span style="position: absolute; right: 0; bottom: -4px;">Tick one or more boxes</span>
                            </div>
                        </td>
                    </tr>
                    <tr style="line-height: 18px;">
                        <td style="border-left: 0px solid #804040" colspan="4" class="border_r_b_l">
                            <b><font size="1">Quantity and detailed description <br/>of contents(1)<br>
                                    Quantity et description detaillee du contenu</font></b>
                        </td>
                        <td width="25%" class="border_r_b">
                            Weight(in kg)(2) <br>Poids
                        </td>
                        <td style="border-right: 0px solid #804040" width="25%" class="border_r_b">
                            Value(3)<br>
                            Valeur
                        </td>
                    </tr>
                    <tr style="line-height: 16px; height: 39px;">
                        <td style="border-left: 0px solid #804040" colspan="4" class="border_r_b_l"  style="border-top:none;" valign="top" width="60%">
                            Health Care

                        </td>
                        <td class="border_r_b" valign="top">
                            {{ $model->total_weight }}
                        </td>
                        <td style="border-right: 0px solid #804040" class="border_r_b" valign="top">
                            {{ sprintf("%.2f", $model->total_price > 20 ? 20 : $model->total_price) }}
                        </td>
                    </tr>
                    <tr style="line-height: 16px;">
                        <td style="border-left: 0px solid #804040"  colspan="4" class="border_r_b_l" style="line-height: 12px;">
                            <font size="1">For commericial items onlyIf known, HS tariff<br/> number(4) and country of origin of goods(5)<br>
                                N"tarifaire du SH et pays aorigine des marchandises(si connus)
                            </font>
                        </td>
                        <td class="border_r">
                            Total Weight<br/>Poids total<br>(in kg)(6)
                        </td>
                        <td style="border-right: 0px solid #804040" class="border_r">
                            Total value(7)<br>Valeur totale
                        </td>
                    </tr>
                    <tr style="line-height: 35px;">
                        <td  style="border-left: 0px solid #804040" colspan="4" class="border_r_b_l"></td>
                        <td class="border_r_b">
                            {{ $model->total_weight }}
                        </td>
                        <td style="border-right: 0px solid #804040" class="border_r_b">
                            {{ sprintf("%.2f", $model->total_price > 20 ? 20 : $model->total_price) }}
                        </td>
                    </tr>
                    <tr style="line-height: 13px;height:60px;">
                        <td style="border-left: 0px solid #804040;border-right: 0px solid #804040"  colspan="6" class="border_r_b_l" style="word-wrap: normal; word-break: keep-all;">
                            I,the undersigned, whose name and address are given on the item, certity that the particulars given
                            in this declaration are correct and that this item does not contain
                            any dangerous article or articles pro-hibited by legislation or by postal or
                            customs regulations<br/>
                            Date and sender\'s signature(8)  SLME
                        </td>
                    </tr>
                </table>
            </td>
    </table>
</div>
</body>
</html>