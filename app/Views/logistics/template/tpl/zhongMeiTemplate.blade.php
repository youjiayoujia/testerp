<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>云途通用面单</title>
    </head>
    <body>
        <style>
        *{margin:0; padding:0;}
        #main{width:100mm; height:99mm; margin:auto;overflow:hidden;}
        body{font-size: 10px; font-family:Arial,Helvetica,sans-serif; color:#000000;}
        </style>
        <div id="main">
            <table style="width:100%; margin:auto;" border="1" cellspacing=0>
            <tr height="30">
            <td colspan="2" style="font-weight:bold;text-align:center;font-size:14px;">渠道：{{ $model->country ? $model->country->cn_name : ''}}专线挂号【{{ $model->logistics ? $model->logistics->code : ''}}】</td>
           </tr>
           <tr height="50">
            <td width=100 style="font-weight:bold;text-align:center;font-size:14px;">第1/1件</td>
            <td style="font-weight:bold;font-size:14px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;国家：<br/>
                &nbsp;&nbsp;{{ $model->country ? $model->country->cn_name : ''}}({{ $model->shipping_country }})
            </td>
           </tr>
           <tr height="60">
            <td colspan="2" style="font-weight:bold;text-align:center;">
                <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                <br/>
              <span style="font-weight:bold;font-size:12px;">{{$model->tracking_no}}</span>
            </td>
           </tr>
           <tr height="100">
            <td colspan="2" style="font-size:12px;">
            
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               Ship To：{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
               
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                        
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $model->shipping_city . ' ' . $model->shipping_state }}<br/>
                        
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $model->country ? $model->country->cn_name : '' }}({{ $model->shipping_country }})
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zip:{{ $model->shipping_zipcode }}
                        <br/>
                        
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 Tel:{{ $model->shipping_phone}}
            
            </td>
           </tr>
           <tr height="60">
            <td colspan="2" style="font-weight:bold;text-align:center;">
                <img src="{{ route('barcodeGen', ['content' => $model->order_id]) }}">
                <br/>
              <span style="font-weight:bold;font-size:12px;">SLM{{ $model->order_id }}</span>
            </td>
           </tr>
           <tr height="10">
            <td colspan="2" style="font-weight:bold;text-align:right;">
              {{date('Y-m-d')}}
            </td>
           </tr>
           <tr height="50">
            <td colspan="2" style="font-weight:bold;">
              @foreach($model->items as $key => $packageItem)
                @if($key != 0)
                {{$packageItem->item->sku}}*{{$packageItem->quantity}}【{{$packageItem->warehousePosition->name}}】
                @else
                {{$packageItem->item->sku}}*{{$packageItem->quantity}}【{{$packageItem->warehousePosition->name}}】
                @endif
              @endforeach
            </td>
           </tr>
        </table>
       </div>
    </body>
</html>
