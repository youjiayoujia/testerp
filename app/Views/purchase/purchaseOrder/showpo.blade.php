<html>
 <head>
    <link href="{{ asset('css/print.css') }}" rel="stylesheet">
  <title>打印SKU条码</title> 
 </head> 
<?php switch ($size) {
    case 'big':
?>
<body style="margin-bottom:-30px;">
  <div style="width:100%;height:24mm;margin-bottom:2px;">
   <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"> 
    <tbody>
     <tr> 
      <td align="center" valign="middle"><b><span style="font-size:4mm;">{{$model->sku}}</span> </b> 
       <div style="font-weight:bold; font-size:2.5mm; text-align:center; width:190px;">
         {{$model->productItem->c_name}}
       </div> 
        <div>
            <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'30' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
        </div>
        <span style="font-size:10px;">{{date('m-d',time())}}[{{$model->warehouse_position_name}}][PO:{{$po_id}}]</span> </td> 
     </tr> 
    </tbody>
   </table> 
  </div>
 </body>
<?php  
    break;   
    case 'small':
?>
<body  style="margin-bottom:-30px;">
    <div style="width:70mm;height:24mm;">
        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="middle">
                    <b>
                        <span style="font-size:4mm;">
                            {{$model->sku}}
                        </span>
                    </b>
                    <div style="font-weight:bold; font-size:2.5mm; line-height:4mm; text-align:center; width:100%; white-space: normal; word-break: break-all; word-wrap: break-word;">
                        {{$model->productItem->c_name}}
                    </div>
                    <div>
                        <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'30' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                    </div>
                    
                    
                    <span style="font-size:10px;">
                        {{date('m-d',time())}}[{{$model->warehouse_position_name}}][PO:{{$po_id}}]
                    </span>
                </td>
            </tr>
        </table>
    </div>
    </body>

    <?php  break;
        case 'middle':
    ?>
    <body style="margin-bottom:-30px;">
        <div style="width:40mm;height:15mm;margin-bottom:2px;">
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" valign="middle">
                        <b>
                            <span style="font-size:4mm;">
                            </span>
                        </b>
                        <div>
                            <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'26' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                        </div>
                        
                        
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
    <body style="margin-bottom:-30px;">
        <div style="width:100%;height:20mm;margin-bottom:2px;">
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" valign="middle">
                        <b>
                            <span style="font-size:3.2mm;">
                                {{$model->sku}}
                            </span>
                        </b>
                        <div style="font-weight:bold; font-size:2mm; text-align:left; width:190px;">
                           {{$model->productItem->c_name}}
                        </div>
                        <div>
                            <img src="{{ route('barcodeGen', ['content' => $model->sku ,'height'=>'30' , 'orientation'=>'horizontal', 'type'=>'code128' ])}}">
                        </div>
                        <?php //echo Tool::barcodePrint($model->sku) ?>
                        
                        <span style="font-size:8px;">
                            {{date('m-d',time())}}[{{$model->warehouse_position_name}}][PO:{{$po_id}}]
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </body>
    <?php break;} ?>   
</html>

<script type="text/javascript">
    window.onload = function(){
        //window.print();
    }
</script>