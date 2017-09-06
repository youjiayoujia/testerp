<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印顺友挂号面单</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    #main {
        width: 100mm;
        height: 129mm;
        margin: auto;
    }

    #main_border {
        width: 99mm;
        height: 128mm;
        margin: 2px auto 0;
        border: 1px solid;
        overflow: hidden;
    }

    body {
        font-size: 10px;
    }

    .f_l {
        float: left;
    }

    .f_r {
        float: right;
    }

    .address tr th {
        text-align: left;
    }

    .address tr td {
        text-align: right;
    }
</style>

<div id="main">
    <div id="main_border">
        <div style="width:98%; margin:auto;">
            <div class="f_l" style="width:145px; font-size: 8px;">
                If underliverable return to : <br/>
                Locked bag No <br/>
                Special Project Unit <br/>
                POS MALAYSIA INTERATIONAL HUB <br/>
                64000 MALAYSIA
            </div>
            <div class="f_r" style="width:133px; border:1px solid #000; text-align:center; line-height:10px;">
                <p>BAYARAN POS JELAS</p>
                <p>POSTAGE PAID</p>
                <p>POS MALAYSIA</p>
                <p>INTERNATIONAL HUB</p>
                <p>MALAYSIA</p>
                <p>PMK1348</p>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="width:100%; border-top:1px solid; border-bottom:1px solid; line-height:10px;">
            <table style="width:100%; margin:auto;" class="address">
                <tr>
                    <td align="left">SHIP TO:</td>
                    <th align="right">
                        {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}
                    </th>
                </tr>
                <tr>
                    <td align="left"></td>
                    <th align="right">
                        {{ $model->shipping_address . ' ' . $model->shipping_address1 }}
                    </th>
                </tr>
                <tr>
                    <td align="left"></td>
                    <th align="right">
                        {{ $model->shipping_city }},{{ $model->shipping_state }},{{ $model->shipping_zipcode }}
                    </th>
                </tr>
                <tr>
                    <td align="left"></td>
                    <th align="right">
                        {{ $model->country ? $model->country->name : '' }}
                        ({{ $model->country ? $model->country->code : '' }})
                    </th>
                </tr>
                <tr>
                    <td align="left">Tel:</td>
                    <th align="right">
                        {{ $model->shipping_phone }}
                    </th>
                </tr>
            </table>
        </div>
        <div>
            <div class="f_l" style="width:170px;height:50px;font-size:26px;font-weight:bold;text-align:right;line-height:50px;margin-right:5px;">
                R
            </div>
            <div class="f_l" style="text-align:center;">
                <p style="line-height:12px;"><b>MALAYSIA POST Airmail</b></p>
                <div>
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></div>
                <p style="line-height:12px;"><b>{{$model->tracking_no}}</b></p>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="border-top:1px solid; border-bottom:1px solid;">
            <div class="f_l">
                CUSTOMS DECLARATION
            </div>
            <div class="f_l" style="width:166px; text-align:center;">
                May be opened officially
            </div>
            <div class="f_l">
                <b>CN 22</b>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="padding:3px; border-bottom:1px solid;">
            <div class="f_l">
                Postal administration
            </div>
            <div class="f_r">
                Tick as appropriat
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="border-bottom:1px solid;">
            <div class="f_l" style="padding:5px 10px; border-right:1px solid;">
                <input name="wx" type="checkbox">
            </div>
            <div class="f_l" style="padding:4px; width:100px;">
                Gift
            </div>
            <div class="f_l" style="padding:5px 10px; border-right:1px solid; border-left:1px solid;">
                <input name="wx" type="checkbox">
            </div>
            <div class="f_l" style="padding:4px;">
                Commercial sample
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="border-bottom:1px solid #000;">
            <div class="f_l" style="padding:5px 10px; border-right:1px solid;">
                <input name="wx" type="checkbox">
            </div>
            <div class="f_l" style="padding:4px; width:100px;">
                Document
            </div>
            <div class="f_l" style="padding:5px 10px; border-right:1px solid; border-left:1px solid;">
                <input name="wx" type="checkbox" checked>
            </div>
            <div class="f_l" style="padding:4px;">
                Other
            </div>
            <div style="clear: both;"></div>
        </div>
        <table style="width:100%; border-collapse:collapse; border:medium none;">
            <colgroup>
                <col width="46%">
                <col width="26%">
                <col width="26%">
            </colgroup>
            <tr>
                <td style="border:1px solid #000;border-top:none;">Quantity and detailed description of<br> contents
                </td>
                <td style="border:1px solid #000;border-top:none;">Weight (in kg)</td>
                <td style="border:1px solid #000;border-top:none;">Value</td>
            </tr>
            <tr>
                <th style="border:1px solid #000;text-align:left;">{{ $model->getDeclaredInfo()['declared_en'] }}
                    x {{ $model->items->first()->quantity }}</th>
                <th style="border:1px solid #000;">{{ $model->getDeclaredInfo()['weight'] }}</th>
                <th style="border:1px solid #000;">{{ $model->getDeclaredInfo()['declared_value'] }}USD</th>
            </tr>
            <tr>
                <td style="border:1px solid #000;border-top:none;"></td>
                <td style="border:1px solid #000;border-top:none;">Total Weight (in kg)</td>
                <td style="border:1px solid #000;border-top:none;">Total Value(USD)</td>
            </tr>
            <tr>
                <th style="border:1px solid #000;border-top:none;"></th>
                <th style="border:1px solid #000;border-top:none;">{{ $model->total_weight }}</th>
                <th style="border:1px solid #000;border-top:none;">{{ $model->total_price }}</th>
            </tr>
        </table>
        <div style="padding:3px;">
            <div style="font-size: 8px;">
                I,the undersigned,whose name and address are given on the itme, certify that the particulars given in
                this declaration are correct and that this item does not contain any dangerous article or articles
                pro-hibited by legislation or by postal or by customs regulations
            </div>
            <div style="text-align:right; width: 97%;">
                <span style="font-size:15px;font-weight:bold;">【{{ $model->logistics ? $model->logistics->logistics_code : '' }}】</span>
                <b>SLME</b> {{ date('Y-m-d') }}
            </div>
        </div>
        <div style="padding:3px 6px; border-top:1px solid;">
            <div class="f_l">
                <div>
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" style="height: 12mm">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$model->id}}</p>
                </div>
            </div>
            <div class="f_r" style="width:200px;">
                {{ $model->sku_info }}
            </div>
            <div>
                <span style="float:right;margin-top:4%;font-weight:bold;display:inline-block;border:2px solid #000;width:50px;line-height:15px;height:15px;font-size:14px;">已验视 </span>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>
</body>
</html>