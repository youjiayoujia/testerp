<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>DHL自制面单PKD</title>
    <style>
        *{margin:0 auto;padding:0;}
        .main{width:99mm;height:199mm;}
        .header1{border:1px solid black;width:98mm;height:96mm;}
        .header2{width:96mm;height:98mm;}
        .fk{display:inline-block;width:12px;height:10px;border:1px solid #000;padding-top:4px}
    </style>
</head>
<body>
<div class="main">
    <div class="header1">
        <div style="height:27mm;line-height:16px;margin-top:3px;">
            <div style="height:18px;">
                <p style="width:120px;float:left;text-align:right;font-size:14px;">Shipment ID:</p>
                <p style="width:130px;float:left;text-align:center;font-size:13px;">{{$model->tracking_no}}</p>
            </div>
            <div style="text-align:center;width:97mm;"><img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></div>
            <div style="text-align:right;width:160px;float:left;">Remarks: Remarks</div>
        </div>
        <div style="height:23mm;margin-top:5px;">
            <div style="width:56px;float:left;text-align:right;margin-top:10px;font-size:18px;font-weight:bold;">PKD</div>
            <div style="width:210px;height:80px;margin-right:13px;float:right;text-align:center;line-height:17px;border:2px solid black;"><p style="font-size:14px;font-weight:bold;">PAID</p>
                <p style="font-size:12px;">if undelicerable,please return to:</p>
                <p style="font-size:13px;font-weight:bold;">PO BOX 556</p>
                <p style="font-size:12px;font-weight:bold;">Marsa Mrs 1001 MALTA</p></div>
        </div>
        <div style="margin-top:30px;float:right;width:93mm;line-height:17px;">
            <p>
                {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                {{ $model->shipping_city . ',' . $model->shipping_state }}<br/>
                ZIP:{{ $model->shipping_zipcode }}&nbsp;&nbsp;&nbsp;TEL:{{ $model->shipping_phone }}
            </p>
            <p style="font-size:19px;">{{ $model->shipping_city }}</p>
            <p style="font-size:22px;">{{ $model->country ? $model->country->name : '' }}</p>
        </div>
    </div>
    <div style="width:98mm;height:99mm;border:1px solid black;margin-top: 3mm">
        <div class="header2">
            <div style="height:27mm;line-height:16px;margin-top:5px;">
                <div style="height:18px;">
                    <p style="width:120px;float:left;text-align:right;font-size:14px;">Shipment ID:</p>
                    <p style="width:130px;float:left;text-align:center;font-size:13px;">{{$model->tracking_no}}</p>
                </div>
                <div style="text-align:center;width:95mm;margin-top:2px;"><img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></div>
                <div style="font-size:15px;">Remarks: Remarks</div>
            </div>
            <div style="border-bottom:1px solid black;height:17px;line-height:14px;">
                <p style="width:45mm;float:left;font-size:14px;">COMMERCIAL INVOICE</p>
                <p style="width:50mm;float:left;font-size:11px;text-align:right;">May be opened offcially</p>
            </div>
            <table style="width:90mm;font-size:13px;">
                <tr>
                    <td><span class="fk" style="line-height: 7px;"></span></td>
                    <td>Documents</td>
                    <td><span class="fk" style="line-height: 7px;"></span></td>
                    <td>Commercial sample</td>
                </tr>
                <tr>
                    <td><span class="fk" style="line-height: 7px;"></span></td>
                    <td>Gift</td>
                    <td><span class="fk" style="line-height: 7px;">√</span></td>
                    <td><div style="float:left;">Other</div><div style="width:100px;height:15px;float:left;border-bottom:1px solid black;"></div></td>
                </tr>
            </table>
            <table style="font-size:13px;width:96mm;text-align:center;border-top:1px solid black;line-height:23px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">Detail Desciption of Contents</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">Weight</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">Qty</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">Value</td>
                    <td style="border-bottom:1px solid black;">Origin</td>
                </tr>
                <tr style="height:40px;">
                    <td style="border-right:1px solid black;border-bottom:1px solid black;" valign=top>{{ $model->getDeclaredInfo()['declared_en'] }}</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;" valign=top>{{ sprintf("%.1f", $model->getDeclaredInfo()['weight'] * 1000) }}</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;" valign=top>{{ $model->items ? $model->items->first()->quantity : 0 }}</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;" valign=top>{{ sprintf("%.1f", $model->getDeclaredInfo()['declared_value']) }}</td>
                    <td style="border-bottom:1px solid black;" valign=top>CN</td>
                </tr>
                <tr style="font-weight:bold;">
                    <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:right;">Total</td>
                    <td colspan="2" style="border-right:1px solid black;border-bottom:1px solid black;">{{ $model->total_weight }}G</td>
                    <td colspan="2" style="border-bottom:1px solid black;">{{ $model->order ? $model->order->currency : '' }}&nbsp;{{ sprintf("%.2f", $model->total_price) }}</td>
                </tr>
            </table>
            <div style="font-size:12px;line-height:12px;">
                <p>I,the undersigned,whose name and address are given on the item certify that the particulars given in this declaration are correct and that this item does not contain any dangerous article or articles prohibited by legislation or by postal or customs regulations.</p>
                <p style="font-size:13px;font-weight:bold;margin-top:5px;">Date and Senders signature</p>
            </div>
            <div style="font-size:13px;line-height:13px;margin-top:5px;">
                <p style="float:left;width:200px;font-size:13px;">SELLMORE (HK) TRADE CO.,Ltd</p>
                <p style="float:right;width:140px;">{{ date("d-m-Y") }}&nbsp;Page 1/1</p>
            </div>
            <div style="text-align:right;width:93mm">{{ $model->id }}</div>
    </div>
</div>
</div>
</body>
</html>