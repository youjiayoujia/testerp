  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
          <title>EUB-100×100热敏标签</title>
      </head>
      <body>
      <style>
          body{ font-family:Arial, Helvetica, sans-serif,"宋体",Verdana; font-size:14px;}
          *{margin:0;padding:0;}
          .PageNext{page-break-after:always; clear:both; min-height:1px; height:auto; overflow:auto; width:100%;}
          #main_frame_box{width:380px; margin:0 auto;}
          .float_box1{ position: relative; float:left; width:370px; height:364px;  margin:2px 3px 1px; border:1px solid black;}
          .float_box2{ float:left; width:370px; height:364px; margin:2px 3px 1px;border:1px solid black;overflow:hidden;}
      </style>
          <div id="main_frame_box">
            <div class="float_box1">
            <table border="0" cellpadding="0" cellspacing="0" style="border:#000000 1px solid; width:370px; height:364px;">
                <tr>
                   <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" margin-left:5px">
                         <tr>
                            <td width="24%" style=" margin-right:120px;">
                                <table width="80" height="50" border="0" cellpadding="0" cellspacing="0" style="border:2px solid #000; text-align:center; margin-top:0px;">
                                    <tr>
                                        <td width="80" height="60" >
                                            &nbsp;<font style="font-family:Arial; font-size:60px; line-height:60px;"><strong>F</strong></font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="40%" align="center">
                                <table width="92%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" ><img src="{{ asset('picture/EUB01.jpg') }}" width="110" height="20" style="margin-top:5px;"/></td>
                                    </tr>
                                    <tr>
                                        <td align="center"></td>
                                    </tr>
                                    <tr>
                                        <td align="center" ><img src="{{ asset('picture/EUB02.jpg') }}" width="160" height="45" /></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="30%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="left">
                                            <table width="80%" border="0" align="left" cellpadding="0" cellspacing="0" style="border:2px solid #000; text-align:center; margin-top:5px; margin-right:10px;">
                                                <tr>
                                                    <td width="47" height="45" align="left"><span style="font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:13px"> 
                                                        &nbsp;Aimail<br/>
                                                        &nbsp;Postage&nbsp;Paid<br/>
                                                        &nbsp;China&nbsp;Post</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td align="center" style="height:20px;"><font style="font-family:Arial; font-size:20px; margin-right:15px;"><strong>{{ $model->fen_jian }}</strong></font>&nbsp;</td>
                                     </tr>
                                </table>
                             </td>
        </tr>
        <tr>
          <td height="7" colspan="3" valign="top" style=" margin-right:120px"><span style="font-family:Arial, Helvetica, sans-serif; font-size:9px">From:</span></td>
        </tr>
      </table>
          <div style="font-family:Arial, Helvetica, sans-serif; font-size:7px"></div></td>
    </tr>
    <tr>
      <td height="" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" style=" border-bottom:#000 1px solid; border-top:#000 1px solid">
        <tr>
          <td width="59%" valign="top" style="border-right:#000 1px solid">
          <div style="font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:12px">
              &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender) : '') : '' }}<br />
              &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_street) : '') : '' }}<br />
              &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_province) : '') : '' }}<br />
              &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_country) : '') : '' }}
              {{ ' ' . $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_zipcode) : '') : ''  }}<br />
          </div>
          <div style="font-family:Arial, Helvetica, sans-serif; height:13px;" align="center">
          &nbsp;<strong style="font-size:16px;">{{ $model->id }}</strong>
          </div>
          </td>
          <td width="41%" rowspan="2" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0">
            <tr>
              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left">
                      <div style="margin-top:3px; margin-right:5px; ">
                          @if($model->shipping_country == 'US')
                              <img src="{{ route('barcodeGen', ['content' => '420' . explode('-', $model->shipping_zipcode)[0]]) }}">
                          @endif
                      </div>
                  </td>
                </tr>
                <tr>
                  <td align="center" valign="bottom"><div style="font-size:14px; margin-top:0px;"><strong>ZIP {{ explode('-', $model->shipping_zipcode)[0] }}</strong></div></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="20" valign="top" style="border-right:#000 1px solid"><div style="font-family:Arial, Helvetica, sans-serif; font-size:7px; margin-top:6px ;margin-left:5px; vertical-align:bottom; line-height:6px;"> Customs information avaliable on attached CN22.<br />
            USPS Personnel Scan barcode below for delivery event information </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="23" valign="top"><table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="15%" height="60" style=" border-right: 1px solid #000
              "><div style="font-family:Arial, Helvetica, sans-serif; font-size:22px; margin-left:12px">To:</div>
                     </td>
          <td width="85%" valign="top">
              <div style="font-family:Arial; font-size:12px;">
                  {{ strtoupper($model->shipping_firstname . ' ' . $model->shipping_lastname) }}<br/>
                  {{ strtoupper($model->shipping_address) . ' ' . strtoupper($model->shipping_address1) }}<br/>
                  {{ strtoupper($model->shipping_city) . ' ' . strtoupper($model->shipping_state) . ' ' . $model->shipping_zipcode }}<br/>
                  {{ $model->shipping_country }}
              </div>
            </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td valign="bottom" style="border-bottom:0px"><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:#000 5px solid; border-top:#000 5px solid">
        <tr>
          <td height="90" valign="top" style="border-right:#000 1px solid; font-size: 9px;"><table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td height="20" align="center" valign="bottom"><span style=" font-family: Arial, Helvetica, sans-serif; font-size:15px"><strong>USPS TRACKING #</strong></span></td>
            </tr>
            <tr>
              <td align="center">
                  <div >
                      <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                      <span style="font-size:12px;margin-top:5px;">
                          <br>
                          <strong>
                            {{ $model->tracking_no }}
                          </strong>
                      </span>
                  </div>
               </td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
          </table>
          </div>
          <div class="float_box2">
           <table border="0" cellpadding="0" cellspacing="0" style="border:#000000 1px solid; width:368px; height:364px;font-size: 9px; font-family: Arial, Helvetica, sans-serif;">
           <tr>
          <td height="31"><div style="font-family:Arial, Helvetica, sans-serif; font-size:7px">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr>
            <td width="35%" valign="top"><table width="100%" height="75" border="0" cellpadding="0" cellspacing="0" style="">
              <tr>
                <td colspan="2" valign="top"><img src="{{ asset('picture/EUB01.jpg') }}" alt="" width="110" height="20" /></td>
              </tr>
              <tr>
                <td width="51%" height="40" valign="bottom"><div style="font-family:Arial; font-size:8px; line-height:11px;">IMPORTANT:<br/>
                  The item/parcel may be<br />
                  opened officially.<br />
                  Please print in English<br />
                </div></td>
                <td width="49%"><table width="36" height="32" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000; text-align:center; margin-right:">
                  <tr>
                    <td width="100" height="20" ><font style="font-family:Arial; font-size:24px">{{ $model->fen_jian }}</font>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="65%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><div style=" margin-top:5px;text-align:center;"><img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}"></div></td>
              </tr>
              <tr>
                <td align="center" valign="top"><div style="font-size:12px"><strong>{{ $model->tracking_no }}</strong></div></td>
              </tr>
            </table></td>
          </tr>
        </table>
      </div></td>
       </tr>
       <tr>
         <td height="46" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="42%" valign="top" style="border-bottom: 1px solid #000; border-right: 1px #000 solid"><div style="font-family:Arial; font-size:9px; padding-left:6px;"> 
            FROM:<br />
                  &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender) : '') : '' }}<br />
                  &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_street) : '') : '' }}<br />
                  &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_province) : '') : '' }}<br />
                  &nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_country) : '') : '' }}
                  {{ ' ' . $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_zipcode) : '') : ''  }}<br />
            <div style="font-size:16px;" align="center"><strong>{{ $model->id }}</strong></div>
            PHONE:{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_phone) : '') : '' }}</div></td>
          <td width="58%" rowspan="2" valign="top" style="border-top:#000 solid 1px">
                  <div style=" font-size:11px">
          SHIP TO:
                      {{ strtoupper($model->shipping_firstname . ' ' . $model->shipping_lastname) }}<br/>
                      {{ strtoupper($model->shipping_address) . ' ' . strtoupper($model->shipping_address1) }}<br/>
                      {{ strtoupper($model->shipping_city) . ' ' . strtoupper($model->shipping_state) . ' ' . $model->shipping_zipcode }}<br/>
                      {{ $model->shipping_country }}
                  </div>
          </td>
        </tr>

        <tr >

          <td style="border-bottom: 1px solid #000; border-right:#000 solid 1px"><div style=" font-size:10px; padding-left:5px;">Fees(US $):</div></td>
        </tr>
        <tr >
          <td height="16" style="border-bottom: 1px solid #000; border-right:#000 solid 1px"><div style="font-family:Arial; font-size:10px; padding-left:5px;">Certificate No.</div></td>
          <td style="border-bottom: 1px solid #000"><div style=" font-size:12px">PHONE: {{ $model->shipping_phone }}</div></td>
        </tr>
        <tr >
          <td height="16" colspan="2" style="border-bottom: 1px solid #000; border-right:#000 solid 1px"><table border="0" cellspacing="0" cellpadding="0" style="width:368px;">
            <tr>
              <td width="5%" align="center"  style="border-bottom: 1px solid #000; border-right:#000 1px solid;font-size:10px;"><span class="STYLE2">No</span></td>
              <td width="5%" align="center"  style="border-bottom: 1px solid #000; border-right:#000 1px solid;font-size:10px;"><span class="STYLE2">Qty</span></td>
              <td width="43%" height="15" align="left"  style="border-bottom: 1px solid #000; border-right:#000 1px solid;font-size:10px;"><span class="STYLE2">Description of Contents</span></td>
              <td width="12%" align="center"  style="border-bottom: 1px solid #000; border-right:#000 1px solid;font-size:10px;"><span class="STYLE2">Kg.</span></td>
              <td width="13%" align="left"  style="border-bottom: 1px solid #000; border-right:#000 1px solid;font-size:10px;"><span class="STYLE2">Val(sus$)</span></td>
              <td width="22%" align="left"  style="border-bottom: 1px solid #000; font-size:10px;"><span class="STYLE2">Goods Origin</span></td>
            </tr>
           <tr style="height:15mm;">
              <td align="center" valign="top" style="border-right:#000 1px solid; border-bottom:#000 1px solid; font-size:10px;">
               {{ $model->items()->count() }}
              </td>
              <td align="center" valign="top" style="border-right:#000 1px solid; border-bottom:#000 1px solid;font-size:10px; ">
               {{ $model->items()->first()->quantity }}
              </td>

              <td height="" align="left" valign="top" style="border-bottom:#000 1px solid; ">
                  <div style=" font-size:10px;color#000;">
                      <strong>
                          {{ $model->getDeclaredInfo()['declared_en'] }}
                      @foreach($model->items as $packageItem)
                        {{ $packageItem->item->sku }}'_'{{ $packageItem->quantity }}<br/>
                      @endforeach
                      {{ date('Y-m-d H:i:s', strtotime($model->printed_at)) }}
                      </strong>
                  </div>
              </td>

              <td align="center" valign="top" style=" border-right:#000 1px solid;border-bottom:#000 1px solid;border-left:#000 1px solid; font-size:10px; ">
                  {{ $model->getDeclaredInfo()['weight'] }}
              </td>

              <td align="center" valign="top" style= "border-right:#000 1px solid; border-bottom:#000 1px solid; font-size:10px;">
                  {{ $model->getDeclaredInfo()['declared_value'] }}
              </td>
              <td align="left" valign="top" style="font-size:10px; border-top:#000 1px solid; border-bottom:#000 1px solid;">
                  {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->eub_sender_country) : '') : '' }}
              </td>
          </tr>   
             <tr>
              <td align="center"  style="border-right:#000 1px solid;  font-size:10px;">&nbsp;</td>
              <td align="center"  style="border-right:#000 1px solid; font-size:10px; ">&nbsp;{{ $model->items()->sum('quantity')}}</td>
              <td align="left"  style=" "><div style=" font-size:10px">Total Gross Weight (Kg.):</div></td>
              <td align="center"  style=" border-right:#000 1px solid; font-size:10px; border-left:#000 1px solid; ">&nbsp;{{ $model->total_weight }}</td>
              <td align="center"  style= "border-right:#000 1px solid; font-size:10px;">&nbsp;{{ $model->total_price }}</td>
              <td align="center"  style="font-size:10px;">&nbsp;</td>
            </tr>
          </table>
              <tr>
                     <td colspan="6" valign="bottom" >
                     <div style="font-family:Arial; font-size:6px;">
                     I certify the particulars given in this customs declaration are correct. This item does not contain any dangerous article, or articles prohibited<br> by 
                    legislation or by postal or customs regulations. I have met all applicable export filing requirements under the Foreign Trade Regulations. </div>
                    <div style="font-family:Arial; font-size:8px;">
                     <strong>Sender\'s Signature &amp; Date Signed:</strong>
                     <strong style="font-family:Arial; font-size:12px; text-align:right; margin-left:70px;">【{{ $model->logistics_id }}】</strong>
                     <strong style="font-family:Arial; font-size:12px; text-align:right; margin-left:90px;">CN22</strong>
                     </div>
                    </td>
              </tr>
      </table>
      </td>
       </tr>
           </table>
          </div>
      </body>
  </html>