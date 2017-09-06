<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>COE平邮面单100*100</title>
    <style>
        *{margin:0;padding:0;}
        .main{width:98mm;height:97mm;border:1px solid black;margin:auto;font-size:10px;line-height: 12px;}
        .header{width:98mm;height:15mm;}
        .middle{width:98mm;height:77mm;}

        td{ border-top:0;border-bottom:1px black solid ;border-left:1px solid black;}
        table{ border-top:1px black solid;border-right:0 ;border-bottom:0 ;}
        td{border-left:0}
        .fk{display:inline-block;width:10px;height:8px;border:1px solid #000;padding-top:4px}
        .tb2 td{border-top:0;border-right:0;border-bottom:0 ;line-height: 5px;}
        .tb2{border-top:0;}
        .leftborder{border-left:1px solid black;}
    </style>
</head>
<body>
<div style="width:100mm;height:100mm;margin:auto;">
    <div class="main">
        <div class="header">
            <div style="width:70mm;float:left;height:13mm;padding:2px;line-height: 13px;font-size:11px;">
                FW by Clevy <br/>
                LLC TLP  <br/>
                PO box 198/1 Tbilisi, <br/>
                Georgia<b>【{{ $model->logistics_id }}】</b>
            </div>
            <div style="border-left:1px solid black;border-bottom:1px solid black;width:25mm;height:12mm;float:right;text-align:center;font-size:13px;line-height:16px;">
                PORT PAYE<br/>
                GEORGIA<br/>
            </div>
        </div>
        <div class="middle">
            <div style="width:58mm;height:80mm;float:left;border-right: 1px black solid ;" >
                <table class="btable" cellspacing=0 cellpadding=0 >
                    <tr >
                        <td colspan=3>
                            <div style="width:23mm;height:6mm;border-right:1px solid black;text-align: center;font-size:10px;line-height: 10px;float:left">
                                <p><b> CUSTOMS<br> DECLARATION</b> </p>
                            </div>
                            <div style="width:22mm;height:6mm;border-right:1px solid black;text-align: center;font-size:10px;line-height: 10px;float:left">
                                <p> May be opened<br> officially </p>
                            </div>
                            <div style="height:6mm;text-align: center;padding-left:1px;font-size:10px;line-height: 10px;float:left">
                                <p> CN22</p>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td colspan=3>
                            <div style="width:29mm;height:4mm;text-align: center;font-size:10px;line-height: 10px;float:left">
                                <p>Designated operator</p>
                            </div>
                            <div style="width:24mm;height:4mm;;text-align: center;font-size:10px;line-height: 11px;float:left">
                                <p><b> </b> </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=3>
                            <table class="tb2" >
                                <tr>
                                    <td style="width: 20mm;"><span class="fk" ></span>
                                        Gift</td>
                                    <td>
                                        <span class="fk"></span>
                                        Commercial sample
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <span class="fk"></span> Documents
                                    </td>
                                    <td >
                                        <span class="fk">√</span> Others Tick one or <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;more boxes
                                    </td>
                                </tr>
                            </table>
                        </td>

                    </tr>
                    <tr style="font-size:9px;text-align:center;">
                        <td style="width:30mm;padding:1px;text-align:left;"><p>Quantity and detailed description of contents </p></td>
                        <td class="leftborder">Weight(KG)</td>
                        <td class="leftborder">Value(USD)</td>
                    </tr>
                    <tr style="text-align:center;">
                        <td style="text-align:left;"><b>{{ $model->decleared_ename }}</b></td>
                        <td class="leftborder"> <b>{{ sprintf("%.1f",$model->signal_weight) }}</b></td>
                        <td class="leftborder"> <b>{{ sprintf("%.1f",$model->signal_price) }}</b></td>
                    </tr>
                    <tr style="text-align:center;">
                        <td style="text-align:left;">For commerical items only If known,HS tariff number and country of origin of goods </td>
                        <td class="leftborder">Total Weight(KG)</td>
                        <td class="leftborder">Total Value(USD)</td>
                    </tr>
                    <tr style="text-align:center;">
                        <td><b>CHINA </b></td>
                        <td class="leftborder"><b>{{ $model->total_weight }}</b></td>
                        <td class="leftborder"><b>{{ $model->total_price}}</b></td>
                    </tr>
                    <tr >
                        <td colspan=3 style="height:96px;line-height: 10px;padding:1px;border-bottom: 0;" valign="top">
                            <span style="font-size:12px;">I,the undersigned,whose name and address are given on the item certify that the particulars given in this declaration are correct and that this item does not contain any dangerous article or articles prohibited by legislation or by postal or customs regulations.</span><div style="text-align:right;width:100%"><?php echo date("d-m-Y");?>&nbsp;</div>
                            Date and sender's signature :   SLME
                        </td>

                    </tr>
                </table>
            </div>
            <div style="width:39mm;height:76mm;float:right;font-size:13px;line-height: 13px;">
                <div style="width:39mm;height:50mm;float:right;font-size:13px;line-height: 13px;">
                    TO:<br/>
                    {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                    {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
                    {{ $model->shipping_city . ',' . $model->shipping_state }}<br/>
                    {{ $model->shipping_zipcode}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $model->country ? $model->country->name : '' }}<br/>
                    {{ $model->shipping_zipcode .','.$model->shipping_phone  }}
                </div>
                <div style="width:39mm;height:26mm;float:right;font-size:13px;line-height: 13px;text-align: center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                    <p style="font-weight:bold;">{{$model->tracking_no}}</p>
                    <div style="width:39mm;height:5mm;text-align:left;padding:0;marign:0" valign="bottom"><p><span style="font-size:17px;font-weight:bold;"></span><span style="font-size:16px;font-weight:bold;">D</span><span style="font-size:10px;">OrderNo:{{ $model->order ? $model->order->ordernum : '' }}</span></p></div>
                    <div style="float:right;border:1px solid black;font-size:25px;font-weight: bold;padding:4px ;margin-right:5px;">E</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>