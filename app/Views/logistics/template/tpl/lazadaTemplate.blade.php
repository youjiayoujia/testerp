<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>lazada印尼面单</title>
</head>
<body>
<div style="width:380px;margin:0 auto;height:15px;font-size:13px;text-align:right;"> ID4&nbsp;&nbsp;&nbsp;</div>
<div style="width:380px;margin:0 auto;border:1px solid #000;height:471px;overflow:hidden;">
    <table style="width:370px">
        <tbody>
        <tr>
            <td>
                <div style="font-size:18px;margin-bottom:1px;">
                    <span>
                        <strong><u style="text-decoration:underline">Tracking number</u></strong>
                    </span>
                    <span style="padding-left:100px;font-size:12px;font-weight:bold;">
                        {{ $model->order_id }}&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                </div>
                <div style="text-align:center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                    <div style="font-size: 14px;">
                        {{ $model->tracking_no }}
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size:11px"><strong>Shipper:</strong><br/>
                    @if($model->channelAccount)
                        @if($model->channelAccount->account == '99706454@qq.com_PH')
                            {{ 'Moonarstore' }}
                        @elseif($model->channelAccount->account == 'lixuanpengwu@126.com_PH')
                            {{ 'Makiyo' }}
                        @else
                            {{ '该订单不符合打印条件' }}
                        @endif
                    @endif
                    <br/>
                    {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}<br/>
                    Phone number:{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '') : '' }}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div style="font-size:11px"><span><strong>Penerima :</strong></span></div>
                    <div style="font-size:11px;">
                        <span>
                            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                            {{ $model->shipping_address . ' ' }}
                            {{ $model->shipping_address1 }}<br/>
                            {{ $model->shipping_city }}<br/>
                            {{ $model->shipping_state }}<br/>
                            {{ $model->country ? $model->country->name : '' }}
                            {{ ' ' . $model->shipping_zipcode }}
					    </span>
                    </div>
                    <div style="font-size:11px;">
                        <span>
                            <span>
                                Phone number:&nbsp;{{ $model->shipping_phone }}
                            </span>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>
                <div style="font-size:11px"><strong>Items:</strong><br/>
                    <span style="width:15px;display:inline-block;font-weight:bold;">#</span>
                    <span style="width:140px;display:inline-block;font-weight:bold;">Product name</span>
                    <span style="width:100px;display:inline-block;font-weight:bold;">Seller Sku</span>
                    <span style="width:90px;display:inline-block;font-weight:bold;">Shop Sku</span>
                    <span style="width:15px;display:inline-block;font-weight:bold;">1</span>
                    <span style="width:140px;display:inline-block;font-weight:bold;">{{ $model->declared_en }}</span>
                    <span style="width:100px;display:inline-block;font-weight:bold;">{{ $model->items ? ($model->items->first()->item ? $model->items->first()->item->sku : '') : '' }}</span>
                    <span style="width:90px;display:inline-block;font-weight:bold;"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size:11px;margin-top:0px;">
                    <span>
                        <strong>Metode Pembayaran:&nbsp;</strong>
                    </span>
                    <span>
                        {{ $model->order ? $model->order->payment : '' }}
                    </span>
                </div>
                @if($model->logistics)
                    @if(in_array($model->logistics->name, ['【专线】LWE印尼-深圳', '【专线】LWE印尼-金华']))
                        {{ '' }}
                    @else
                        <div style="font-size:11px;margin-top:0px;">
                            <span>
                                <span><strong>Declared value:&nbsp;</strong></span>
                            </span>
                            <span>IDR&nbsp;
                                {{ $model->total_price }}
                            </span>
                        </div>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size:12px;margin-bottom:5px;">
                    <span>
                        <strong>
                            <u style="text-decoration:underline">package number</u>
                        </strong>
                    </span>
                    <span style="padding-left:200px;font-size:14px;font-weight:bold;">
                        【{{ $model->logistics_id }}】
                    </span>
                </div>
                <div style="text-align:center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->lazada_package_id]) }}">
                    <div style="font-size: 11px;">
                        <span>
                            {{ $model->lazada_package_id }}
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <img alt="logo_zps0515ee1e.jpg" src="http://i1378.photobucket.com/albums/ah107/listianpratomo/logo_zps0515ee1e.jpg" style="height:20px; width:122px"/>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>