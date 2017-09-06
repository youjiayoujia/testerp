<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印顺友挂号面单100*100</title>
</head>
<body>
<style>
    *{margin:0; padding:0;}
    #main{width:100mm; height:99mm; margin:0 auto;border:1px solid; overflow: hidden;}
    body{font-size: 10px;}
    .f_l{float:left;}
    .f_r{float:right;}
    .address tr th{text-align:left;}
    .address tr td{text-align:right;}
</style>

<div id="main">
    <div style="width:100%;height:1mm;"></div>
    <div style="width:100%;height:35mm;">
        <p style="width:68mm;height:35mm;float:left;margin-left:5px;overflow:hidden;font-size:7px;">
            If underliverable return to : <br/>
            Locked bag No      <br/>
            Special Project Unit    <br/>
            POS MALAYSIA INTERATIONAL HUB <br/>
            64000 MALAYSIA<br/>
                <span style="font-size:11px;font-weight:bold;">
                To:{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                    {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                    {{ $model->shipping_city }}{{ $model->shipping_state }}<br/>
                    {{ $model->shipping_zipcode }} ,{{ $model->shipping_phone }}<br/>
                    {{ $model->country ? $model->country->name : '' }}
                    ({{ $model->country ? $model->country->cn_name : '' }})
                </span>
        </p>
        <p style="width:30mm;height:35mm;float:left;overflow:hidden;">
		        <span style="width:98%;height:20mm;border:1px solid #000;display:inline-block;">
		        	BAYARAN POS JELAS<br/>
					POSTAGE PAID<br/>
					POS MALAYSIA<br/>
					INTERNATIONAL HUB<br/>
					MALAYSIA<br/>
					PMK1348
		        </span>
		        <span style="width:98%;height:6mm;border:1px solid #000;display:inline-block;border-top:none;font-size:14px;font-weight:bold;line-height:8mm;">
		         &nbsp;Z&nbsp;:&nbsp;{{ $model->shunyou ? $model->shunyou->area_code : '' }}
		        </span>
		        <span style="width:98%;height:6mm;line-height:8mm;border:1px solid #000;display:inline-block;border-top:none;">
		        {{$model->id}}<span style="font-size:14px;font-weight:bold;">【{{ $model->logistics ? $model->logistics->logistics_code : '' }}】</span>
		        </span>
        </p>
    </div>
    <div style="width:100%;height:18mm;">
        <p style="width:30%;height:12mm;float:left;font-weight:bold;font-size:30px;text-align:right;"></p>
        <p style="width:45%;height:14mm;float:left;text-align:center;">
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
            <span style="font-size:12px;">{{$model->tracking_no}}</span>
        </p>
    </div>
    <div style="width:100%;height:44mm;overflow:hidden;">
        <table style="width:98%; border-collapse:collapse; border:medium none;margin:0 auto;" >
            <colgroup>
                <col width="42%">
                <col width="18%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <tr>
                <td style="border:1px solid #000;" colspan="2">CUSTOMS DECLARATION CN 23</td>
                <td style="border:1px solid #000;border-left:none;" colspan="2">May be opened officially</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;">Desoription of contents</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Qty</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Weight(kg)</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Value</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;">
                    {{ $model->getDeclaredInfo()['declared_en'] }}
                    ({{ $model->sku_info }})
                </td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->items ? $model->items->sum('quantity') : 0 }}</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->getDeclaredInfo()['weight'] }}</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->getDeclaredInfo()['declared_value'] }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;" colspan="2">For commercial items only</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Total Wt</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Total Value</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;" colspan="2">Country of origin: CN</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->total_weight }}</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">${{ $model->total_price }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;" rowspan="2">
                    <span style="width:7px;height:7px;border:1px solid #000;display:inline-block;font-size:8px;font-weight:bold;text-indent:-999px;">√</span>&nbsp;Gift&nbsp;
                    <span style="width:7px;height:7px;border:1px solid #000;display:inline-block;font-size:8px;font-weight:bold;text-indent:-999px;">√</span>&nbsp;Commercial sample<br/>
                    <span style="width:7px;height:7px;border:1px solid #000;display:inline-block;font-size:8px;font-weight:bold;">√</span>&nbsp;Other
                    <span style="width:7px;height:7px;border:1px solid #000;display:inline-block;font-size:8px;font-weight:bold;text-indent:-999px;">√</span> &nbsp;Documents<br/>
                </td>
                <td style="border:1px solid #000;border-left:none;border-top:none;" rowspan="2">HS Tariff No:</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;" colspan="2">Certificate No:</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-left:none;border-top:none;" colspan="2">Invoice No:</td>
            </tr>
            <tr style="font-size:8px;">
                <td style="border:1px solid #000;border-top:none;">Importer`s ref(taxcode/VAT no)if any:</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Postage Fees:</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;" colspan="2">Office of origin/Date of posting:</td>
            </tr>
            <tr style="font-size:7px;">
                <td style="border:1px solid #000;border-top:none;" colspan="4">
                    I certify that the particulars given in this customs declaration are correct and that this item doesnot
                    contain any dangerous article prohibited by legislation or by postal or customs regulations.
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;" colspan="2">Signature of sender：<?php if($model->warehouse){echo $sign = $model->warehouse->id == 5 ? 'szslm': 'ywslm';}?></td>
                <td style="border:1px solid #000;border-left:none;border-top:none;" colspan="2">Date:{{ date('Y-m-d') }}</td>
            </tr>

        </table>
    </div>
</div>
</body>
</html>