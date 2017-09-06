<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>新顺丰荷兰面单</title>
    </head>
    <body>
    <style>
        *{margin:0;padding:0;}
        .main{width:98mm;height:98mm;border:1px dashed black;margin: 2px auto;font-size:12px;line-height: 12px;}
        .fk{display:inline-block;width:10px;height:10px;border:1px solid #000;}
    </style>
    <div class="main">
        <table cellpadding="0" cellspacing="0">
            <tr style="height:50px;">
                <td style="width:98mm;">
                    <p style="width:100px;font-size:22px;font-weight:bold;float:left;line-height:50px;margin-left:10px;">PRIORITY</p>
                    <p style="float:right;height:50px;margin-right:7px;"><img src="{{ asset('picture/hl-logo.jpg') }}" style="height:50px;margin-top:5px;"></p>
                    <p style="float:right;height:50px;margin-right:7px;"><img src="{{ asset('picture/sf-logo.png') }}" style="margin-top:5px;"></p>
                </td>
            </tr>
            <tr style="height:25px;line-height:12px;">
                <td >
                    <p style="margin-left:5px;">Return if undliverable:</p>
                    <p style="margin-left:5px;">H-11940SFT,Postbus 7040,3109AA Schiedam The Netherlands</p>
                </td>
            </tr>
            <tr style="height:80px;">
                <td >
                    <div style="width:10mm;height:80px;line-height:80px;font-weight:bold;font-size:40px;float:left;margin-left:5px;">X</div>
                    <div style="height:80px;float:right;width:83mm;">
                        <p style="font-size:20px;font-weight:bold;margin-top:2px;">R</p>
                        <P style="margin-top:5px;text-align:center;"><img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></P>
                        <p style="margin-top:5px;text-align:center;font-size:17px;font-weight:bold;">{{$model->tracking_no}}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height:138px;width:98mm;word-break:break-all;word-wrap:break-word;" valign="top">
                    <p style="font-size:13px;line-height:15px;height:138px;margin-left:5px;">
                        {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                        {{ $model->shipping_address . ' ' . $model->shipping_address1}}<br/>
                        {{ $model->shipping_city }}<br/>
                        {{ $model->shipping_state }}<br/>
                        {{ $model->shipping_zipcode }}<br/>
                        {{ $model->country ? $model->country->name : $model->shipping_country }}
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="width:73mm;float:left;font-size:12px;line-height:14px;margin-left:5px;">
                        <p>SF No:{{ $model->logistics_order_number }}</p>
                        <p>TEL:{{ $model->shipping_phone }}</p>
                        <p>【7550001183】 Ref No:{{ $model->id }}【{{ $model->logistics ? $model->logistics->logistics_code : '' }}】</p>
                        <p>{{ date('Y-m-d H:i:s') }}</p>
                        <p>{{ $model->sku_info }}</p>
                    </div>
                    <div style="width:22mm;float:right;font-size:18px;font-weight:bold;line-height:30px;text-align:center;">
                        <p>{{ $model->shipping_country ? $model->shipping_country : ($model->country ? $model->country->name : '') }}</p>
                        <p>{{ $model->shun_fen }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="width:98mm;margin: 2px auto;font-size:12px;line-height: 12px;margin-top:15px;">
        <table cellpadding="0" cellspacing="0" style="border:1px solid black;">
            <tr>
                <td colspan="4" style="text-align:center;width:98mm;border-bottom:1px solid black;height:13mm;">
                    <p style="font-size:23px;font-weight:bold;margin-top:8px;">CUSTOMS DECLARATION</p>
                    <p style="font-size:17px;font-weight:bold;margin-top:8px;">May be opened officially</p>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="width:98mm;height:9mm;border-bottom:1px solid black;">
                <div style="float:left;width:50mm;border-right:1px solid black;margin-left:10px;">
                    <p style="margin-top:3px;"><span class="fk" >√</span> Commercial items</p>
                    <p><span class="fk" ></span> Gift/Commercial sample</p>
                </div>
                <div style="float:right;width:40mm;margin-top:3px;text-align:center;line-height:25px;">
                    Operator:NL 
                </div>
                </td>
            </tr>
            <tr style="text-align:center;height:5mm;">
                <td style="border-right:1px solid black;border-bottom:1px solid black;">description of contents</td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;">Qty</td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;">Kg</td>
                <td style="border-bottom:1px solid black;">Value</td>
            </tr>
            <tr style="text-align:center;height:5mm;">
                @if($model->items)
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">{{ $model->getDeclaredInfo()['declared_en'] }}</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">{{ $model->items->first()->quantity }}</td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">{{ $model->getDeclaredInfo()['weight'] }}</td>
                    <td style="border-bottom:1px solid black;">{{ $model->getDeclaredInfo()['declared_value'] }}</td>
                @endif
            </tr>
            <tr style="text-align:center;height:5mm;">
                <td style="border-right:1px solid black;border-bottom:1px solid black;">Totals of contents</td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;">Pcs</td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;">{{ $model->total_weight }}Kg</td>
                <td style="border-bottom:1px solid black;">${{ $model->total_price < 20 ? $model->total_price : 20 }}</td>
            </tr>
            <tr>
                <td colspan="4" style="height:7mm;line-height:17px;">
                    <p style="width:53mm;float:left;font-size:14px;font-weight:bold;">Country of origin of goods</p>
                    <p style="width:20mm;border-bottom:2px dashed black;float:left;font-size:14px;">PR China</p>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:14px;line-height:14px;">
                    I,the undersigned,whose name and address are given on the item,certify that the particulars given in this declaration are correct and that this item does not contain any dangerous goods and other articles prohibited by transport export / impot regulations 
                    
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:13px;line-height:13px;height:6mm">
                    <p style="margin-top:5px;width:46mm;float:left;">Date and sender's signature </p>
                    <p style="margin-top:5px;border-bottom:2px dashed ;width:36mm;float:left;">{{ date('Y-m-d H:i:s') }}</p>
                    <p style="margin-top:5px;width:10mm;float:left;margin-left:10px;font-weight:bold;">SLME</p>
                </td>
            </tr>
        </table>
        <div style="width:80mm;float:left;line-height:18px;">
            <p style="width:80mm;text-align:center;margin-right:7px;"><img src="{{ route('barcodeGen', ['content' => $model->logistics_order_number]) }}" style="height:40px;margin-top:5px;"></p>
            <p style="width:80mm;text-align:center;margin-right:7px;font-size:14px;">{{ $model->logistics_order_number }}</p>

        </div>
        <div style="width:16mm;float:right;"><img src="{{ asset('picture/start.png') }}" style="width:16mm;"></div>
    </div>
    </body>
</html>