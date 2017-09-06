<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>燕文荷兰小包挂号</title>
</head>
<body>
<style>
    * {
        padding: 0;
        margin: 0 auto;
    }

    .main1 {
        width: 97mm;
        height: 97mm;
        margin: auto;
        font-size: 10px;
        line-height: 12px;
        border: 1px solid black;
        margin-top: 1mm;
    }

    .header1 {
        height: 80px;
    }

    .fk {
        display: inline-block;
        width: 8px;
        height: 8px;
        border: 1px solid #000;
        padding-top: 0px;
        line-height: 8px;
    }

    table tr td {
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        text-align: center;
    }

    table tr td:nth-child(1) {
        text-align: left;
    }
</style>

<div style="width:100mm;height:100mm;margin:auto;">
    <div class="main1">
        <div class="header1">
            <p style="width:105px;float:left;font-size:22px;font-weight:bold;margin-left:7px;margin-top:20px;">
                PRIORITY</p>

            <p style="width:50px;float:right;font-size:22px;font-weight:bold;margin-left:7px;">
                <img src="{{ asset('picture/hl-logo.jpg') }}" style="width:50px; "></p>

            <p style="clear:both;padding-top:5px;font-size:9px;text-align:center;">Return if
                undeliverable:H-10905,Postbus 7040,3109 AA Schiedam The Netherlands</p>
        </div>
        <div style="height:80px;">
            <div style="width:22px;height:80px;float:left;font-size:25px;font-weight:bold;line-height:70px;margin-left:7px;">
                R
            </div>
            <div style="height:80px;width:335px;float:right;text-align:center;">
                <p style="margin-top:3px;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                </p>
                <p style="font-size:18px;margin-top:5px;">{{ $model->tracking_no }}</p>
            </div>
        </div>
        <div style="width:95mm;word-break:break-all;word-wrap:break-word;margin-top:2px;">
            <p style="font-size:16px;">{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<p>
            <p style="font-size:16px;line-height:17px;">
                {{ $model->shipping_address}}<br/>{{ $model->shipping_address1 }}<br/>
                {{ $model->shipping_city . ' ' . $model->shipping_state }}<br/>
                {{ $model->shipping_zipcode }}<br/>
                Tel:{{ $model->shipping_phone }}<br/>{{ $model->shipping_country }}
                <br/>{{ $model->country ? $model->country->cn_name : ''}}
            </p>
            <p style="text-align:right;font-weight:bold;font-size:25px;margin-right:10px;margin-bottom:10px;">{{ $model->shipping_country }}</p>
            <p style="float:left;width:100px;text-align:center;font-weight:bold;font-size:25px;">
                @foreach(['Austria','Belgium','Bulgaria','Cyprus','Croatia','CzechRepublic','Denmark','Estonia','Finland','France','Germany','Greece','Hungary','Ireland','Italy','Latvia','Lithuania','Luxembourg','Malta','Poland','Portugal','Romania','Slovakia','Slovenia','Spain','Sweden','United Kingdom','Netherlands'] as $key => $country)
                    @if(strtolower($country) == strtolower($model->country->name))
                        <font>EU</font>
                    @endif
                @endforeach
                &nbsp;&nbsp;D</p>
            <p style="text-align:left;font-size:15px;">OrderNo:{{ $model->order ? $model->order->ordernum : '' }}</p>
        </div>
    </div>
</div>

<table cellspacing="0" cellpadding="0" style="border:1px solid black;border-right:none;width:97mm;margin-top: 15px;font-size:14px;">
    <tr>
        <td colspan="4" style="font-size:13px;border-bottom:1px solid black;">
            <p style="width:5s0mm;text-align:center;float:left;border-right:1px solid black;">CUSTOMS DECLARATION</p>

            <p style="width:45mm;text-align:center;float:right;">May be opened officially</p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="font-size:12px;text-align:center;">
            <p style="width:75mm;float:left;border-right:1px solid black;">
                <span class="fk"></span>&nbsp;Commercial items&nbsp;
                <span class="fk"></span>&nbsp;Gift/Commercial sample
            </p>

            <p>Operstor:NL</p>
        </td>
    </tr>
    <tr>
        <td style="text-align:left">Description of contents</td>
        <td style="border-right:1px solid black;">Quantity</td>
        <td>Kg</td>
        <td>Value</td>
    </tr>
    <tr style="height:50px;">
        <td style="text-align:left"> {{ $model->items()->first()->item->name }}</td>
        <td>1</td>
        <td>{{ $model->items()->first()->item->weight }}</td>
        <td>{{ $model->items()->first()->item->cost > 20 ? 20 : $model->items()->first()->item->cost }}</td>
    </tr>
    <tr style="height:50px;">
        <td style="text-align:left">Totals of contents</td>
        <td>{{ $model->items()->sum('quantity') }} Pcs</td>
        <td>{{ $model->weight }} Kg</td>
        <td>{{ $model->self_value > 22 ? 22 : $model->self_value }} $</td>
    </tr>
    <tr style="height:30px;text-align:left;">
        <td colspan="4" style="text-align:left;">Country of origin of goods</td>
    </tr>
    <tr style="height:100px;">
        <td colspan="4" style="line-height:15px;text-align:left;">
            I,the undersigned whose name and address are given on the item,certify that the data given in this
            declaration are correct and that this item does not contain any dangerous goods or articles prohibited by
            transport or export / import regultions.
        </td>
    </tr>
    <tr style="font-size:12px;height:30px;">
        <td colspan="4" style="border-bottom:none;">
            <p>Date and sender\'s signature:SLME &nbsp;&nbsp;302035 &nbsp;&nbsp;{{date('d-m-Y')}}
                &nbsp;{{ $model->country ? $model->country->number : '' }}</p>
        </td>
    </tr>
</table>
</body>
</html>
