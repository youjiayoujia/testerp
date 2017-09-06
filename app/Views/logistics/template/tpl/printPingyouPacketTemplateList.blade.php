<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印平邮小包面单</title>
</head>
<body>
<style>body{ font-family:Arial, Helvetica, sans-serif,"宋体",Verdana; font-size:14px;margin:0; padding:0;}
    td{ white-space:nowrap;}
    #main_frame_box{width:382px; margin:0 auto;height:378px;overflow:hidden;margin-bottom:2px;}
    .PageNext{page-break-after:always; clear:both; min-height:1px; height:auto; overflow:auto; width:100%;}
    .float_box{ position: relative; width:370px; height:364px; overflow:hidden; margin:11px 3px 1px; border:1px solid black;}
</style>

<div id="main_frame_box" style="margin-left:15px;">
    <div class="float_box">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td width="224" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <div style="width:217px; overflow:hidden; white-space: normal; word-break: keep-all; float:left; margin-top:0; padding:3px;">
                        <b>SHIP TO:</b><br/>
                        {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
                        {{ $model->shipping_address . ' ' . $model->shipping_address1 }}
                        {{ $model->shipping_city }} {{ $model->shipping_state }} {{ $model->shipping_zipcode }}<br/>
                        {{ $model->country ? $model->country->code : '' }}<br/>
                        Tel:{{ $model->shipping_phone }}<br/>
                        {{$model->tracking_no}}
                    </div>
                </td>
                <td width="146" style="border-bottom: 1px solid black;">
                    <div style="width:145px; overflow: hidden; float:left; margin-top:0px;">
                        <div style="float: left; margin-top:0; border-bottom:1px solid black; width: 139px; padding:3px;">
                            BY AIR MAIL<br/>
                            航PAR AVION空
                        </div>
                        <div style="float: left; width:139px; padding:3px;">
                            Weight:{{ $model->total_weight }}Kg<br/>
                            LastPrint:{{ date('Y-m-d') }}<br/>
                            {{$model->tracking_no}}
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="left" valign="middle" height="50" style="border-bottom: 1px solid black;padding: 5px;" colspan="3">
                    <div style="padding-top: 12px;">
                        <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                        <br>&nbsp;&nbsp;&nbsp;&nbsp;{{$model->id}}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" valign="top" style="border-bottom: 1px solid black; ">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr><td width="120" style="white-space: normal; word-wrap: break-word;">{{ $model->items ? $model->items->first()->item->sku : ''}}</td><td width="25">{{ $model->items ? $model->items->first()->item->cost : ''}}</td><td  style="text-align:right">{{$model->items ? $model->items->first()->item->warehouse_id : ''}}</td></tr>
                        <tr><td width="120" style="white-space: normal; word-wrap: break-word;">{{ $model->items ? $model->items->first()->item->sku : ''}}</td><td width="25">{{ $model->items ? $model->items->first()->item->cost : ''}}</td><td  style="text-align:right">{{$model->items ? $model->items->first()->item->warehouse_id : ''}}</td></tr>

                    </table>
                </td>
            </tr>

        </table>
        <div style="width:100%;font-size: 28px;font-weight: bold;">
            <div style="width:50px;float:left;font-size:30px;">{{ $model->logistics ? $model->logistics->logistics_code : '' }}</div>
            <div style="width:50px;float:right;">{{ $model->items ? $model->items()->sum('quantity') : '' }}</div>
        </div>
    </div>
</div>
</body>
</html>