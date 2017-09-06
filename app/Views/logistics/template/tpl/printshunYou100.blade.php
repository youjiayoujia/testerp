<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>顺友平邮面单100*100</title>
    <style>
        *{margin:0; padding:0;}
        #main{width:100mm; height:98mm; margin:0 auto;border:1px solid; overflow: hidden;}
        body{font-size: 10px;}
        .f_l{float:left;}
        .f_r{float:right;}
        .address tr th{text-align:left;}
        .address tr td{text-align:right;}
    </style>
</head>
<body>
<div id="main">
    <div style="width:100%;height:1mm;"></div>
    <div style="width:100%;height:42mm;">
        <p style="width:68mm;height:42mm;float:left;margin-left:5px;overflow:hidden;font-size:7px;">
            If underliverable return to : <br/>
            Locked bag No      <br/>
            Special Project Unit    <br/>
            POS MALAYSIA INTERATIONAL HUB <br/>
            64000 MALAYSIA<br/>

                <span style="font-size:11px;font-weight:bold;">
                To:
                    {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                    {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                    {{ $model->shipping_city . ',' . $model->shipping_state }}<br/>'
                    {{ $model->shipping_zipcode . ',' . $model->shipping_phone }}<br/>
                    {{ $model->shipping_country }}
                </span>
        </p>
        <p style="width:30mm;height:42mm;float:left;overflow:hidden;">
		        <span style="width:98%;height:24mm;border:1px solid #000;display:inline-block;">
		        	BAYARAN POS JELAS<br/>
					POSTAGE PAID<br/>
					POS MALAYSIA<br/>
					INTERNATIONAL HUB<br/>
					MALAYSIA<br/>
					PMK1348
		        </span>
            <?php $areaCode='';?>
		        <span style="width:98%;height:8mm;border:1px solid #000;display:inline-block;border-top:none;font-size:14px;font-weight:bold;line-height:8mm;">
		         &nbsp;Z&nbsp;:&nbsp;{{$areaCode}}
		        </span>
		        <span style="width:98%;height:8mm;line-height:8mm;border:1px solid #000;display:inline-block;border-top:none;">
		       {{ $model->order ? $model->order->ordernum : '' }}<span style="font-size:14px;font-weight:bold;">【{{ $model->logistics_id }}】</span>
		        </span>
        </p>
    </div>
    <div style="width:100%;height:12mm;">
        <p style="width:30%;height:12mm;float:left;font-weight:bold;font-size:30px;text-align:right;">

        </p>
        <p style="width:45%;height:12mm;float:left;text-align:center;">
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
            <br/><span style="font-size:12px;">{{$model->tracking_no}}</span>
        </p>
    </div>
    <div style="width:100%;height:50mm;overflow:hidden;">
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
                <td style="border:1px solid #000;border-top:none;" colspan="2">For commercial items only</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Total Wt</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Total Value</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;" colspan="2">Country of origin: CN</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">'.$allParamArr['total_weight'].'</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">$'.$allParamArr['total_price'].'</td>
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
                <td style="border:1px solid #000;border-top:none;" colspan="2">Signature of sender:'.$allParamArr['sign'].'</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;" colspan="2">Date:'.date('Y-m-d H:i:s').'</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;">Desoription of contents</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Qty</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Weight(kg)</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">Value</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;">
                    {{ $model->decleared_ename }}('.$allParamArr['productsInfo'][0]['orders_sku'].'*'.$allParamArr['productsInfo'][0]['item_count'].')
                </td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">'.$allParamArr['productsInfo'][0]['item_count'].'</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->signal_weight }}</td>
                <td style="border:1px solid #000;border-left:none;border-top:none;">{{ $model->signal_price}}
				  <span style="float:right;font-weight:bold;display:inline-block;border:2px solid #000;width:40px;line-height:11px;height:11px;text-align:center;font-size:12px;">
		 		             	已验视 </span></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>