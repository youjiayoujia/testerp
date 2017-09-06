<html>
 <head>
  <title>打印SKU条码</title> 
  <link href="{{ asset('css/print.css') }}" rel="stylesheet">
 </head> 
<?php switch ($size) {
    case 'big':
?>
<body>
  <div style="width:100%;height:25mm;margin-bottom:2px;">
   <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"> 
    <tbody>
     <tr> 
      <td align="center" valign="middle"><b><span style="font-size:4mm;">{{$model->sku}}</span> </b> 
        <div style="font-weight:bold; font-size:2.5mm; text-align:center; width:190px;">
         {{$model->c_name}}
        </div> 
        <div>
            <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'40' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
        </div>
        <span style="font-size:10px;">
            {{date('m-d',time())}}[{{$model->warehousePosition?$model->warehousePosition->name:''}}][PO:{{$po_id}}]
        </span>
     </tr> 
    </tbody>
   </table> 
  </div>
 </body>
<?php  
    break;   
    case 'small':
?>
<body>
    <div style="width:100%;height:25mm;margin-bottom:2px;">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="middle">
                    <b>
                        <span style="font-size:4mm;">
                            {{$model->sku}}
                        </span>
                    </b>
                    <div style="font-weight:bold; font-size:2.5mm; line-height:4mm; text-align:center; width:100%; white-space: normal; word-break: break-all; word-wrap: break-word;">
                        {{$model->c_name}}
                    </div>
                    <div>
                        <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'40' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                    </div>
                    <?php //echo Tool::barcodePrint($model->sku,$type = 'C128', $width = '0.835', $height='30') ?>
                    <span style="font-size:10px;">
                        {{date('m-d',time())}}[{{$model->warehousePosition?$model->warehousePosition->name:''}}][PO:{{$po_id}}]
                    </span>
                </td>
            </tr>
        </table>
    </div>
    </body>

    <?php  break;
        case 'middle':
    ?>
    <body>
        <div style="width:40mm;height:15mm;margin-bottom:2px;">
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" valign="middle"><b><span style="font-size:4mm;"></span>
                        </b>
                        <div>
                            <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'35' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                        </div>
                        <?php //echo Tool::barcodePrint($model->sku,$type = 'C128', $width = '1.07', $height='30') ?>
                        <div style="font-weight:bold; font-size:2.8mm; text-align:center; width:40mm;">
                            {{$model->sku}}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>

    <?php  break;
    case 'middleSmall':
    ?>

    <body>
    <div style="width:100%;height:20mm;margin-bottom:5px;">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="middle">
                    <b>
                        <span style="font-size:4mm;">
                            {{$model->sku}}
                        </span>
                    </b>
                    <div style="font-weight:bold; font-size:2.5mm; line-height:4mm; text-align:center; width:100%; white-space: normal; word-break: break-all; word-wrap: break-word;">
                        {{$model->c_name}}
                    </div>
                    <div>
                        <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'40' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                    </div>
                    <?php //echo Tool::barcodePrint($model->sku,$type = 'C128', $width = '0.835', $height='30') ?>
                    <span style="font-size:10px;">
                        {{date('m-d',time())}}[{{$model->warehousePosition?$model->warehousePosition->name:''}}][PO:{{$po_id}}]
                    </span>
                </td>
            </tr>
        </table>
    </div>
    </body>
    <?php break;} ?>   
</html>

<script type="text/javascript">
    window.onload = function(){window.print();}
</script>