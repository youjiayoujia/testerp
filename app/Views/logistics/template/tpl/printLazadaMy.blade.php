<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印lazada马来西亚面单</title>
    <style>
        *{margin:0;padding:0;}
        body{ font-family:Arial, Helvetica, sans-serif,"宋体",Verdana; font-size:14px;}
        #main_frame_box{width:99mm;height:125mm;margin:0 auto; overflow:hidden;font-size:12px;border:1px solid #000;}
        table{border-collapse:collapse;border:none;width:99mm;height:99mm;border:1px solid black;}
        table .detail{
            width:380px;height:93px;border:none;
        }
        td{border:1px solid #000;}
    </style>
</head>
<body>
<div style="width:99mm;margin:0 auto;height:4mm;text-align:right;">AS-Poslaju&nbsp;&nbsp;&nbsp;</div>
<div id="main_frame_box">
    <div style="width:100%;height:50mm;">
        <p style="height:1mm;"></p>
        <p style="height:16mm;">
            &nbsp;&nbsp;&nbsp;
	            <span style="display:inline-block;width:45mm;height:50px;">
	              <img src="{{ asset('picture/poslaju_logo2.png') }}" style="width:45mm;height:50px;"/>
	            </span>
	            <span style="display:inline-block;width:47mm;backgrond:red;text-align:center;font-weight:bold;">
	              <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
	              <br/> {{$model->tracking_no}}
	            </span>
        </p>
        <p style="height:33mm;">
	           <span style="width:60mm;height:33mm;display:inline-block;float:left;">
	            &nbsp;&nbsp;&nbsp;From:<br/>
                   <?php
                   //根据不同的账号获取不同的名称
                   $shipName = '';
                   //$allParamArr['ordersInfo']['sales_account']
                       $account = $model->channel_account_id;
                   if(trim($account)=='99706454@qq.com'){
                       $shipName = 'Moonarstore';
                   }elseif(trim($account)=='lixuanpengwu@126.com'){
                       $shipName = 'Makiyo';
                   }

                   ?>
	             &nbsp;&nbsp;&nbsp;{{$shipName}}<br/>
	             &nbsp;&nbsp;&nbsp;Logistics Worldwide Express<br/>
	             &nbsp;&nbsp;&nbsp;Block A, G Floor, GL06<br/>
	             &nbsp;&nbsp;&nbsp;Kelana Square, 17 Jalan SS7/26<br/>
	             &nbsp;&nbsp;&nbsp;Petaling Jaya<br/>
	             &nbsp;&nbsp;&nbsp;47301&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selangor<br/>
	             &nbsp;&nbsp;&nbsp;MALAYSIA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel: 60378038830
	           </span>
	           <span style="width:38mm;height:33mm;text-align:center;display:inline-block;font-size:14px;font-weight:bold;float:right;">
	           		<br/>
	            	 POS LAJU ACC #<br/>
					8800400431【{{ $model->logistics_id }}】
	           </span>
        </p>
    </div>
    <hr style="height:1px;border:none;border-top:1px solid #000;" />
    <div style="width:100%;height:32mm;">
	       	   <span style="width:60mm;height:32mm;display:inline-block;float:left;overflow:hidden;">
	            &nbsp;&nbsp;&nbsp;To:<br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->shipping_address}}<br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->shipping_address1}}<br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->shipping_city }}<br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->shipping_zipcode }}
	             					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   {{ $model->shipping_state }}
	             <br/>
	             &nbsp;&nbsp;&nbsp;{{ $model->country ? $model->country->name : '' }}
	             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	             Tel：{{ $model->shipping_phone }}

	           </span>
	           <span style="width:38mm;height:27mm;text-align:center;display:inline-block;font-size:14px;font-weight:bold;float:right;">
	           		<br/>
	           		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   {{ $model->order ? $model->order->ordernum : '' }}
	           		<br/><br/>
	           		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	            	 <b style="font-size:26px;border:1px solid #000;">MY</b>
	           </span>
    </div>
    <hr style="height:1px;border:none;border-top:1px solid #000;" />
    <div style="width:100%;height:43mm;">
        <p style="width:100%;height:18mm;">
	            <span style="width:55mm;height:18mm;display:inline-block;float:right;">
	            	&nbsp;&nbsp;&nbsp;Transaction Ref:{{$model->channel_listnum}}<br/><br/>
	                &nbsp;&nbsp;&nbsp;Product: Charges - Domestic<br/>
	                &nbsp;&nbsp;&nbsp;Type: MERCHANDISE
	           </span>
	           <span style="width:43mm;height:18mm;display:inline-block;float:right;">
	           		&nbsp;&nbsp;&nbsp;Item Information<br/>
	           		&nbsp;&nbsp;&nbsp;Date:<?php echo date('Y-m-d');?><br/><br/>
	           		&nbsp;&nbsp;&nbsp;Weight：{{ $model->total_weight }}
	           </span>
        </p>
        <p style="width:98%;height:23mm;margin:0 auto;">
            Please use the number above to track the shipment status through Customer Service Center (Posline) 1-300-300-300 or Pos Malaysia web at www.pos.com.my Note:Liability of PosLaju for any delay, damage or lost be limited to and subject to the terms and conditions as stated behind the consignment note (PL1A)
        </p>
    </div>
    </div>
</body>
</html>