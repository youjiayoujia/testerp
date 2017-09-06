
﻿<style type="text/css" media=print>
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

  <table width="100%"  align="center" cellspacing="0" cellpadding="3" border="1">
  <tr>
    <td height="41" colspan="9"><div align="center" class="STYLE2">萨拉摩尔采购单</div></td>
  </tr>
  
  <tr>
    <td height="21" colspan="4"><div align="center" class="STYLE1"><strong>供应商资料</strong></div></td>
    <td colspan="5"><div align="center" class="STYLE1"><strong>收货资料</strong></div></td>
  </tr>
  <tr>
    <td height="93" colspan="4"><span class="STYLE1">供应商名称：{{$model->supplier->name ?  $model->supplier->name : '暂无名称'}}<br />
      联系地址：{{ $model->supplier->supplier_address ? $model->supplier->supplier_address : '暂无地址'}}<br />
      联系电话： {{ $model->supplier->telephone ? $model->supplier->telephone : '暂无电话'}}<br />
      联系人： {{$model->assigner > 0 ? $model->assigner_name : '暂无联系人'}}<br />
      <span class="STYLE2">付款方式：{{config('purchase.purchaseOrder.pay_type')[$model->pay_type]}}</span></span></td>
    <td colspan="5"><span class="STYLE1">交货地址:深圳市龙岗区坂田五和大道（南）41号和堪工业区B3栋四楼<br />
      采购联系人： {{$model->purchaseUser?$model->purchaseUser->name:''}}<br />
      仓库信息： {{$model->warehouse?$model->warehouse->name:''}}<br />
      <span class="STYLE2">预计到货日期：{{date('Y-m-d',time()+($model->supplier?$model->supplier->purchase_time:7)*24*3600)}}</span></span></td>
  </tr>

  <tr>
    <td width="35"><div align="center">序号</div></td>
    <td width="84"><div align="center">单据号</div></td>
    <td width="35"><div align="center">SKU</div></td>
    <td width="120"><div align="center">产品描述</div></td>
    <td width="42"><div style="font-size:10px;" align="center">采购数</div></td>
    <td width="42"><div style="font-size:10px;" align="center">入库数</div></td>
    <td width="42"><div style="font-size:10px;" align="center">不良数</div></td>
    <td width="42"><div style="font-size:10px;" align="center">单价RMB</div></td>
    <td width="42"><div style="font-size:10px;" align="center">小计RMB</div></td>
  </tr>
  @foreach($model->purchaseItem as $key=>$purchase_item)
      <tr>
        <td align="center">{{$key+1}}</td>
        <td align="center">{{$model->id}}</td>
        <td align="center">{{$purchase_item->sku}}</td>
        <td align="center" style="font-size:12px">{{$purchase_item->productItem->name}}</td>
        <td align="center">{{$purchase_item->purchase_num}}</td>
        <td align="center">{{$purchase_item->storage_qty}}</td>
        <td align="center">{{$purchase_item->purchase_num - $purchase_item->arrival_num}}</td>
        <td align="center">{{$purchase_item->purchase_cost}}</td>
        <td align="center">{{$purchase_item->purchase_cost * $purchase_item->purchase_num}}</td>
      </tr>
  @endforeach
 <tr><td colspan="4"><div align="center">总计</div></td><td align="center" >{{$purchase_num_sum}}</td><td align="center" >{{$storage_qty_sum}}</td><td align="center" >{{$purchase_num_sum - $storage_qty_sum}}</td><td align="center">{{$purchase_cost_sum}} </td><td align="center" >{{$purchaseAccount}} + YF{{$postage_sum}} = 总{{$purchaseAccount + $postage_sum}}</td></tr><tr>
    <td colspan="9"><div align="center">合计金额(RMB):{{$purchaseAccount}} + YF{{$postage_sum}} = 总{{$purchaseAccount + $postage_sum}}</div></td>
  </tr>
  <tr>
    <td colspan="9">备注：{{$model->remark}}</td>
  </tr>
</table><p align="right">【打印时间：2016-08-11 20:18:33】</p>

