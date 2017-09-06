<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>wishpost-DLE面单</title>
    <style>
        *{margin:0;padding:0;}
        body{ font-family:Arial, Helvetica, sans-serif; font-size:14px;}
        #main_frame_box{width:382px; margin:0 auto;height:378px;overflow:hidden;margin-bottom:2px;}
        td{border:1px solid #000;border-bottom:none;}
    </style>
</head>
<body>
<?php
        $buery_id = $model->channel_listnum;
if(strlen($buery_id) > 53){
    $str = explode('+',$buery_id);
    $i = 1;
    $j = '';
    foreach($str as $v){
        $j .= $v.'+';
        if($i%2 == 0 ){
            $j .= '<br>';
        }
        $i++;
    }
    $buery_id = $j;
}
//if($allParamArr['ordersInfo']['sku']){
//    $sku = '';
//    $i = 1;
//    foreach($allParamArr['ordersInfo']['sku'] as $k=>$v){
//        if($i%3 == 0){
//            $sku .= '<br>';
//        }
//        $sku .= $v['orders_sku'].',';
//        $i++;
//    }
//}
?>
<div id="main_frame_box">
    <div style="width:379px;height:150px;border:1px solid #000;border-bottom:none;">
        <p style="float:left;width:140px;height:30px;">
            <img src="{{ asset('picture/wishpost-dle.jpg') }}" />
        </p>
        <p style="float:left;width:238px;height:50px;text-align:center;font-size:13px;font-weight:bold;line-height:30px;border-right:0px solid #000;">

        </p>
        <p style="width:270px;height:45px;text-align:center;margin-left:25px; ">
        <div align="center"> <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />&nbsp;<font size="18">B</font></div>
        <div align="center">{{ $model->tracking_no }}</div>
        </p>
    </div>
    <table border="1" style="width:382px;height:155px;" cellspacing="0" cellpadding="0">
        <tr height="20">
            <td style="font-size:13px;border-bottom:none;">
               {{$buery_id}}
            </td>
        </tr>
        <tr height="40"><td style="font-size:13px;border-top:none;border-bottom:none;">

            </td></tr>
        <tr height="40"><td style="font-size:13px;border-bottom:none;">
                <b> 自编号:{{ $model->order ? $model->order->ordernum : '' }}</b>&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $model->logistics_id }}</b>
                &nbsp;SKU: {{ $model->sku_info }}
            </td></tr>
    </table>
</div>
</body>
</html>