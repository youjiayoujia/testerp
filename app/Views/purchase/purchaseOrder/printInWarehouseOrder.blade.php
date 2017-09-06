<style type="text/css" media=print>
.noprint{display : none }
</style>
<style type="text/css" >
body{ margin:0;}
table{ font-family:Arial, Helvetica, sans-serif; border:1px solid #000;}
.STYLE1 {font-size: 14px;line-height:20px;}
.STYLE2 {
    font-size: 16px;
    font-weight: bold;
    line-height:20px;
}
.PageNext{page-break-after:always; clear:both; height:auto; overflow:auto;}
</style>
<!--<p class="noprint" style="margin-left:65%">-->
<!--    (<span style="font-size:12px">打印背景设置：文件->页面设置->勾选打印背景</span>)<input id="btnPrint" type="button" value="打印" onclick="javascript:window.print();" />-->
<!--</p>-->
<div style="width:804px;height:526px;margin:0 auto;overflow:hidden;">
      <table width="800"  align="center" cellspacing="0" cellpadding="0" border="1" style="float:left; margin:2px;">
      <tr>
        <td height="30" colspan="11"><div style="width:230px; float:left;font-size:12px">打印时间：<?php echo date('Y-m-d H:i:s',time());?></div><div align="center" class="STYLE2" style="width:330px; float:left">订单来货质检入库单</div><div style="width:230px; float:left">下单时间：<?php echo $model->created_at;?></div></td>
      </tr>
      
      <tr>
        <td height="20" colspan="3">采购联系人： {{$model->purchaseUser?$model->purchaseUser->name:''}}</td>
        <td colspan="2">订单类型：<b>{{config('purchase.purchaseOrder.type')[$model->type]}}</b></td>
        <td colspan="6">供应商：{{$model->supplier->name}}</td>
      </tr>
      <tr>
        <td colspan="5">付款方式：{{config('purchase.purchaseOrder.pay_type')[$model->pay_type]}}</td>
        <td colspan="6">是否已付款：{{config('purchase.purchaseOrder.close_status')[$model->close_status]}}</td>
      </tr>
      <tr height="20" align="center">
        <td width="30" style="font-size:12px;">序号</td>
        <td width="50">单据号</td>
        <td width="90">SKU</td>
        <td width="90">储位</td>
        <td width="165">产品描述</td>
        <td width="50" style="font-size:12px;" align="center">采购数量</td>
        <td width="50" style="font-size:12px;" align="center">到货数量</td>
        <td width="50" style="font-size:12px;" align="center">良品数量</td>
        <td width="50" style="font-size:12px;" align="center">不良数量</td>
        <td width="65" style="font-size:12px;">实际入库量</td>
        <td width="95">不良原因</td>
      </tr>
          @foreach($model->purchaseItem as $key=>$purchase_item)
            <tr style="word-break:break-all">
              <td align="center">{{$key+1}}</td>
              <td align="center">{{$model->id}}</td>
              <td align="center">{{$purchase_item->sku}}</td>
              <td align="center">{{$purchase_item->warehouse_position_name}}</td>
              <td align="center" style="font-size:12px">{{$purchase_item->productItem?$purchase_item->productItem->c_name:''}}</td>
              <td align="center">{{$purchase_item->purchase_num}}</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
          @endforeach
      <tr>
        <td colspan="11">{{$model->remark}}</td>
      </tr>
      <tr>
        <td colspan="11">
            <table width="100%" style="border:0;" cellpadding="2">
                <tr align="right" height="20;">
                    <td width="90">收货员：</td>
                    <td width="160" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="70">质检员：</td>
                    <td width="160" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="100">系统录入员：</td>
                    <td width="160" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align="right" height="20;">
                    <td>仓管员：</td>
                    <td style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>采购员：</td>
                    <td style="border-bottom: 1px solid #000;">&nbsp;</td><td>&nbsp;</td>
                    <td>财务：</td>
                    <td style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align="right" height="20;">
                    <td>入库仓库：</td>
                    <td align="left">义乌仓</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td style="font-size:12px;">说明</td>
        <td colspan="10">
        1.到货数量 = 良品数量 + 不良数量；&nbsp;&nbsp;2.实际入库量为良品数。
        </td>
      </tr>
    </table></div>
