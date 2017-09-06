<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>万邑邮选面单</title>
    <style>
        *{margin:0; padding:0;}
        #main{width:100mm; height:99mm; margin:0 auto;border:1px solid #000; overflow: hidden;}
        body{font-size: 10px;}
        .f_l{float:left;}
        .f_r{float:right;}
        .address tr th{text-align:left;}
        .address tr td{text-align:right;}
    </style>
</head>
<body>
<div id="main">
    <div style="width:100%;height:55px;border-bottom:1px solid #000;">
        <p style="width:100%;height:60%;font-size:14px;font-weight:bold;">
            Track No:{{$model->tracking_no}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{ $model->country ? $model->country->code : '' }}
            &nbsp;&nbsp;
            {{ $model->country ? $model->country->cn_name : '' }}
        </p>
        <p style="width:100%;height:40%;font-size:14px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @if(($model->logistics ? $model->logistics->logistics_code : '') == 524)
                {{ 'eDS易递宝 - 香港渠道(平邮) - ebay' }}
            @else
                {{ '万邑邮选-香港渠道（平邮）-eBay IDSE' }}
            @endif
        </p>
    </div>
    <div style="width:100%;height:68px;border-bottom:1px solid #000;text-align:center;font-size:12px;">
        <div style="width:100%;height:2px;"></div>
        <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
        <br/>
        Intl Tracking No:{{$model->tracking_no}}
    </div>
    <div style="width:100%;height:180px;border-bottom:1px solid #000;">
        <div style="width:100%;height:105px;overflow:hidden;font-size:12px;">
            <span style="font-weight:bold;">To:</span>
            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
            {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
            {{ $model->shipping_city . ' ' . $model->shipping_state }}<br/>
            {{ $model->shipping_zipcode . ' ' . $model->shipping_phone }}<br/>
            {{ $model->country ? $model->country->name : '' }}
        </div>
        <div style="width:100%;height:75px;overflow:hidden;font-size:12px;">
            <span style="font-weight:bold;font-size:12px;">From:</span>{{($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->customer) : '')}}
            &nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">CN:</span>10004110
            &nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">渠道:</span>{{ $model->logistics_id }}
            &nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">Tel:</span>{{($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '')}}<br/>
            <span style="font-weight:bold;font-size:12px;">Add:</span>{{($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->unit) : '')}}
        </div>
    </div>
    <div style="width:100%;height:70px;">
        <p style="margin-top:2px;width:50%;height:100%;float:left;text-align:center;font-size:12px;">
            <img src="{{ route('barcodeGen', ['content' => $model->id]) }}" />
            <br/>
            {{ $model->id }}
        </p>
        <p style="width:50%;height:100%;float:left;">
            {{ $model->getDeclaredInfo()['declared_cn'] }}
            {{ $model->getDeclaredInfo()['declared_en'] }}
        </p>
    </div>
</div>
</body>
</html>