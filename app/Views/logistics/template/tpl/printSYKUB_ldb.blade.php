<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印顺友K邮宝面单</title>
</head>
<body>
<style>
    *{margin:0;padding:0;}
    .main{width:98mm;height:98mm;margin:auto;font-size:13px;word-break:break-all;}
</style>

<div class="main">
    <div style="border-bottom:3px solid black;height:20mm;margin-top:5px;">
        <div style="float:left;width:80px;height:70px;border:2px solid black;font-size:60px;line-height:70px;text-align:center;font-weight:bold;">

        </div>
        <div style="float:left;height:60px;width:185px;text-align:center;">
            {{ substr(substr($model->tracking_no, 0, -2), 2) }}
        </div>
        <div style="float:right;width:90px;height:70px;border:2px solid black;text-align:center;">
            <p></p>
            <p></p>
            <p></p>
        </div>
    </div>
    <div style="border-bottom:3px solid black;height:26.5mm;text-align:center;line-height:14px;">
        <p>USPS Tracking #</p>
        <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
        <p style="font-weight:bold;margin-top:2px;">{{$model->id}}</p>
        <p style="font-size:11px;">USPS personnel scan barcode above for delivery event information</p>
    </div>
    <div style="border-bottom:3px solid black;height:23mm;">
        <div style="width:230px;float:left;border-right:1px solid black;height:24mm;line-height:13px;">
            <p>FROM: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel : 82-2-2662-8851</p>
            <p style="width:220px;margin-left:10px;">
                SHUNYOU<br/>
                KOREANAIR CARGO TERMINAL<br/>
                (RETURN TO SEOUL IPO) - HUBN<br/>
                ZIP 07505 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rep. OF KOREA
            </p>
            <p style="text-align:center;margin-top:5px;">SYBAA02011483 China</p>
        </div>
        <div style="float:right;height:24mm;text-align:center;">
            <p style="width:36mm;margin-top:10px;">
                <img src="{{ route('barcodeGen', ['content' => $model->shipping_zipcode]) }}">
            <p style="width:36mm;font-size:17px;font-weight:bold;margin-top:2px;">ZIP {{ $model->shipping_zipcode }}</p>
        </div>
    </div>
    <div style="border-bottom:3px solid black;height:21mm;line-height:13px;">
        <p>TO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel:{{ $model->shipping_phone }}</p>
        <p style="margin-left:10px;">
            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
            {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
            {{ $model->shipping_city }}<br/>
            {{ $model->shipping_state }}<br/>
            {{ $model->country ? $model->country->cn_name : '' }}
        </p>
    </div>
    <div style="line-height:12px;">
        <P style="margin-top:2px;"><?php echo date("Y.m.d"); ?>({{ $model->total_weight }}kg) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;
            <?php
            $qnumb = $model->shipping_zipcode;
            $qnfirst = substr($qnumb,0,1);
            if(in_array($qnfirst,array(0,1,2,3))){
                echo 'JFK';
            }elseif(in_array($qnfirst,array(4,5,6))){
                echo 'ORD';
            }elseif(in_array($qnfirst,array(7))){
                echo 'SFO';
            }elseif(in_array($qnfirst,array(8,9))){
                echo 'LAX';
            }

            ?>
            &gt;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Country : {{ $model->country ? $model->country->code : '' }}
        </P>
    </div>
</div>
<div style="width:98mm;height:95mm;margin:auto;font-size:13px;word-break:break-all;line-height:13px;margin-top:15px;">
    <table cellspacing="0" cellpadding="0">
        <tr style="height:14mm;">
            <td colspan="7" style="height:14mm;width:98mm;">
                <p style="width:95px;text-align:center;line-height:20px;float:left;height:13mm;margin-top:5px;">
                    CUSTOMS<br/>
                    DECLARATION
                </p>
                <p style="font-size:11px;float:left;width:220px;text-align:center;line-height:14mm;height:14mm;">IMPORTANT : May be opened offcially</p>
                <p style="line-height:14mm;height:14mm;font-weight:bold;font-size:18px;">CN 22</p>
            </td>

        </tr>
        <tr style="width:98mm;height:7mm;text-align:center;">
            <td style="border:2px solid black;border-right:none;width:30px;">√</td>
            <td colspan="2" style="border:2px solid black;border-right:none;width:60px;">Gift</td>
            <td style="border:2px solid black;border-right:none;width:50px;"></td>
            <td colspan="3" style="border:2px solid black;">Commercial sample</td>
        </tr>
        <tr style="width:98mm;height:6mm;text-align:center;">
            <td style="border:2px solid black;border-right:none;border-top:none;width:30px;"></td>
            <td colspan="2" style="border:2px solid black;border-right:none;border-top:none;width:60px;">Documents</td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;"></td>
            <td colspan="3" style="border:2px solid black;border-top:none;">Merchandise</td>
        </tr>
        <tr style="width:98mm;height:7mm;text-align:center;line-height:15px;font-size:12px;">
            <td colspan="2" style="border:2px solid black;border-right:none;border-top:none;width:275px;">
                Detailed description<br/>
                of contents
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;">Qty</td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;"
            >Weight<br/>
                [g]
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;">
                Value<br/>
                [US$]
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;">
                HS tariff<br/>
                Number
            </td>
            <td style="border:2px solid black;border-top:none;width:40px;">
                Goods<br/>
                Origin
            </td>
        </tr>
        <tr style="width:98mm;height:7mm;text-align:center;line-height:15px;font-size:12px;">
            <td colspan="2" style="border:2px solid black;border-right:none;border-top:none;width:125px;">
                {{ $model->declared_en }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:30px;">{{ $model->items ? $model->items->sum('quantity') : 0 }}</td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalWeight }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalPrice *  $model->quantity  }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">

            </td>
            <td style="border:2px solid black;border-top:none;">
                {{$model->quantity > 0 ? 'CN' : ''}}
            </td>
        </tr>

        <tr style="width:98mm;height:7mm;text-align:center;line-height:15px;font-size:12px;">
            <td colspan="2" style="border:2px solid black;border-right:none;border-top:none;width:125px;">
                {{ $model->declared_en }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:30px;">{{ $model->items ? $model->items->sum('quantity') : 0 }}</td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalWeight }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalPrice *  $model->quantity  }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">

            </td>
            <td style="border:2px solid black;border-top:none;">
                {{$model->quantity > 0 ? 'CN' : ''}}
            </td>
        </tr>
        <tr style="width:98mm;height:7mm;text-align:center;line-height:15px;font-size:12px;">
            <td colspan="2" style="border:2px solid black;border-right:none;border-top:none;width:125px;">
                {{ $model->declared_en }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:30px;">{{ $model->items ? $model->items->sum('quantity') : 0 }}</td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalWeight }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ $model->SignalPrice *  $model->quantity  }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">

            </td>
            <td style="border:2px solid black;border-top:none;">
                {{$model->quantity > 0 ? 'CN' : ''}}
            </td>
        </tr>
        <tr style="width:98mm;height:7mm;text-align:center;">
            <td colspan="3" style="border:2px solid black;border-right:none;border-top:none;width:270px;">Total Weight / Value</td>
            <td style="border:2px solid black;border-right:none;border-top:none;width:50px;">{{ $model->SignalWeight *  $model->quantity  }}</td>
            <td style="border:2px solid black;border-right:none;border-top:none;">
                {{ round($model->total_price,2) }}
            </td>
            <td style="border:2px solid black;border-right:none;border-top:none;">

            </td>
            <td style="border:2px solid black;border-top:none;">

            </td>
        </tr>
        <tr>
            <td colspan="7">
                <p style="font-size:12px;line-height:13px;margin-top:10px;">
                    1, the undersigned, whose name and address are given on the item, certify that the
                    particulars given in this declaration are correct and that this item does not contain any
                    dangerous article or articles prohibited by legislation or by postal or customs regulation.
                    Date and sender’s signature
                </p>
            </td>

        </tr>
        <tr>
            <td colspan="7" style="width:98mm">
                <p style="font-size:13px;line-height:10px;margin-top:6px;width:98mm;text-align:center;">
                    Signed by &nbsp;&nbsp;&nbsp;<font style="font-size:15px;font-weight:bold">SLME
                        &nbsp;&nbsp;&nbsp;&nbsp;</font>【{{ $model->logistics_id }}】{{$model->id}}
                </p>
            </td>

        </tr>
    </table>
</div>
</body>
</html>