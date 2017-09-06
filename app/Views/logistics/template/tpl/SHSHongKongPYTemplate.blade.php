<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SHS香港平邮面单</title>
    <style>
        body{ 
        	font-family:Arial, Helvetica, sans-serif,"宋体",Verdana; 
        	font-size:14px;
        	margin:0; 
        	padding:0;
        }
    						
        td{ 
        	white-space:nowrap;
        }
    						
        .PageNext{
            page-break-after:always;
        	clear:both; 
        	min-height:1px; 
        	height:auto; 
        	overflow:auto; 
        	width:100%;
        }
    						
        .float_box{ 
            position: relative; 
            width:370px; 
            height:364px; 
            overflow:hidden;
        	margin:0 auto; 
        	border:1px solid black;
        }
    						
        div, p{
        	margin:0; padding:0;
        }
        
        .tongyong table{
            border-collapse:collapse;
        }
            
       .tongyong td{
            white-space:normal;
            border:1px solid black;
        }
    </style> 
</head>
<body>
    <div id="main_frame_box">
        <div class="float_box">
    		<div align="center" style="width:100%;height:82px;">	
    			<div style="padding:7px 5px 0 5px;">
    	
    				<div style="float:left; width: 200px; text-align:left;">
    					<div><b>FROM:</b></div>
    					<div style="font-size: 10px;">Rm A1，10/F, Shun Luen Factory Building, 86 Tokwawan Road, Hong Kong，</div>
    				</div>
    		
    				<div style="float: right;">
    					<table cellspacing="0" border="1" height="62">
    						<tr>
    							<td>
    								<p style="font-size:24px;">1</p>
    							</td>
    							<td align="center">
    								<div style="font-size: 10px; padding:0 2px; font-weight:bold;">
    									POSTAGE<br/>
    									PAID<br/>
    									HONG KONG
    								</div>
    							</td>
    							
    							<td align="center">
    								<div style="font-size: 10px; padding:0 5px; font-weight:bold;">
    									PERMIT<br/>
    									NO.<br/>
    									<br/>
    									5743
    								</div>
    							</td>
    						</tr>
    					</table>
    				</div>
    				<div style="clear:both;"></div>
    			</div>
    		</div>	
    		<div style="width:100%;height:95px;">
    			<table style="width:368px;">
    				<tr>
    					<td align="left">
    						<strong style="font-size:15px;text-decoration:underline;margin-left:0px;">TO:</strong>
    						<strong style="font-size:15px;margin-left:5px;">{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}</strong>	
    						<span style="font-size:13px;margin-left:5px;">
    						    {{ $model->shipping_address}}<br/>
    							@if($model->shipping_address1)
    							{{$model->shipping_address1 }} <br/>
    							@endif 							
    						    {{ $model->shipping_city }}&nbsp;&nbsp;{{ $model->shipping_state }}<br/>				 
    							Zip:{{ $model->shipping_zipcode }}<br/>
    							Tel:{{  $model->shipping_phone }} 
    						</span>									
    					</td>
    					<td align="right">
    						<div style="width:80px;height:90px;border:2px solid #000;font-weight:bold;">
    	     					<table>
    								<tr>
    									<td>
    										<img width="80px;" height="70px;" style="margin-top:-3px;margin-left:-3px;" src="{{ asset('picture/mddHkPost2.jpg') }}"/>
    									</td>
    								</tr>
    								<tr>
    									<td align="center">
    										<span style="font-size:16px;">
    											 <?php 											 
											 $zip_arr = str_split($model->shipping_zipcode);	//分割邮编
											 $zip_first = strtoupper($zip_arr[0]);										 	

											 $US_arr = array(1, 34, 35);	 //美国根据邮编首位不同 会有3个分区
											 $AU_arr = array(5, 31, 32, 33); //澳大利亚
											 $CA_arr = array(7, 30);	     //加拿大
											 $CA_ptt = array();
											 $CA_a_z = range('A', 'Z');
											 $n = FALSE;											 	
											 foreach ($CA_a_z as $v){											 
											     if ($n){
											         $CA_ptt[$v] = 30;
											     }else {
											         $CA_ptt[$v] = 7;
											     }											 
											     if ($v == 'P')
											     {
											         $n = TRUE;
											     }											 
											 }
											 
											 $partition_arr = array(	//国家分区数组 key为邮编首位 value为分区
											     '美国' => array(
											         0 => $US_arr[0],
											         1 => $US_arr[0],
											         2 => $US_arr[0],
											         3 => $US_arr[0],
											         4 => $US_arr[1],
											         5 => $US_arr[1],
											         6 => $US_arr[1],
											         7 => $US_arr[1],
											         8 => $US_arr[2],
											         9 => $US_arr[2],
											     ),
											     	
											     '澳大利亚' => array(
											         0 => $AU_arr[0],
											         1 => $AU_arr[0],
											         2 => $AU_arr[0],
											         3 => $AU_arr[1],
											         4 => $AU_arr[2],
											         5 => $AU_arr[1],
											         6 => $AU_arr[3],
											         7 => $AU_arr[1],
											         8 => $AU_arr[1],
											         9 => $AU_arr[2],
											     ),
											     	
											     '加拿大' => $CA_ptt,
											 );
											 ?>
											 @if(!empty($partition_arr[$model->country ? $model->country->cn_name : ''][$zip_first]))
											     {{ $partition_arr[$model->country->cn_name][$zip_first] }}
											 @else
											    {{ $model->country ? ($model->country->shsHkZone ? $model->country->shsHkZone->partition : 50) : '' }}
											 @endif							
    										</span>
    									</td>
    								</tr>
    							</table>
    	  					</div>
    					</td>
    				</tr>
    			</table>
	    </div>	
    	<div style="width:50px;height:28px;border:1px solid #000;margin-left:5px;margin-top:5px;">
         	<strong style="font-size:28px;" >{{ $model->logistics_id }}</strong>				
      	</div>	
    	<div style="width:100%;height:20px;margin-left:5px;margin-top:10px;">
    		<span>{{ $model->shipping_country }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $model->country ? $model->country->name : '' }}</span>
    	</div>	
    	<div>
    		<table style="width:370px; border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;">
    			<tr>
    				<td align="center" colspan="2">
    					<strong>{{ $model->order_id }}</strong>
    				</td>
    			</tr>							
    		</table>
    		<table style="width:370px; border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;">
    			<tr>
    				<td align="center" colspan="2">
    					<img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
    				</td>
    			</tr>
    		</table>
    		<table height="52px;" style="border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-top: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;border-collapse:collapse;BORDER-right: rgb(0,0,0) 1px;">
				<tr>				
					<td align="left">
						<span>BAM</span>
					</td>											
				
					<td rowspan="2" align="left" style="border: solid thin #000;BORDER-top: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;">
						<textarea readonly="readonly" style=" font-size:13px; height: 45px; width: 232px; margin-top:-1px;margin-left:-1px;resize:none;border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-top: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;overflow-y:hidden" rows="3">
						{{ $model->sku_info }}
					</textarea>
					</td>
				</tr>
				<tr>
					<td align="left" style="border: solid thin #000;BORDER-left: rgb(0,0,0) 1px;BORDER-bottom: rgb(0,0,0) 1px;BORDER-right: rgb(0,0,0) 1px;">
						<span>Ref NO:S{{ $model->order ? $model->order->ordernum : '' }}</span>
					</td>																										
				</tr>
    		</table>						
    	</div>										
     </div>
       
		<div class="tongyong" style="width:375px;height:360px;margin:0 auto;margin-top:8px;">
		  <table border="1" style="width:375px;height:360px;margin:0;padding:0;">
		    <tr height="35">
		      <td colspan="3">
		        <p style="float:left;width:100px;height:35px;line-height:35px;font-size:12px;text-align:center;">
		           {{ $model->order ? $model->order->ordernum : ''}}
		        </p>
		        <p style="float:left;width:190px;height:35px;font-size:12px;font-weight:bold;text-align:center;">
		           	报关签条<br/>
		            CUMTOMS DECLARATION
		        </p>
		        <p style="float:left;width:70px;height:35px;font-size:11px;font-weight:bold;text-align:center;">
		                         邮2113<br/>
		           CN22
		        </p>
		      </td>
		    </tr>
		    <tr height="15">
		      <td colspan="3">
		        <p style="float:left;width:170px;line-height:15px;height:15px;font-size:11px;">
		           	可以经行拆开
		        </p>
		        <p style="float:left;width:190px;line-height:15px;height:15px;font-size:11px;">
		           	May be opened officially
		        </p>
		        
		      </td>
		    </tr>
		    <tr height="70">
		      <td colspan="3">
		         <table style="width:375px;height:70px;margin:0;padding:0;border:none;">
		            <tr height="34">
		              <td width="60" style="border-top:none;border-bottom:none;border-left:none;" rowspan="2">
		                <p style="height:34px;text-align:center;line-height:34px;font-size:11px;">
		                  	邮件种类
		                </p>
		              </td>
		              <td width="30" style="border-top:none;">
		                <p style="height:34px;text-align:center;line-height:34px;font-size:14px;font-weight:bold;">
		                  	X
		                </p>
		              </td>
		              <td width="80" style="border-top:none;">
		                <p style="height:34px;font-size:12px;">
		                  	礼品<br/>
		                  	gift
		                </p>
		              </td>
		              <td width="30" style="border-top:none;"></td>
		              <td width="174" style="border-top:none;border-right:none;">
		              	<p style="height:34px;font-size:12px;">
		                  	商品货样<br/>
		                  	Commercial Sample
		                </p>
		              </td>
		            </tr>
		            <tr height="34">
		              <td width="30" style="border-bottom:none;">
		              	
		              </td>
		              <td width="80" style="border-bottom:none;">
		               <p style="height:34px;font-size:12px;">
		                  	 文件<br/>
		                  	Documents
		                </p>
		              </td>
		              <td width="30" style="border-bottom:none;"></td>
		              <td width="174" style="border-bottom:none;border-right:none;">
		              	<p style="height:34px;font-size:12px;">
		                  	 其他<br/>
		                  	Other
		                </p>
		              </td>
		            </tr>
		         </table>
		      </td>
		    </tr>
		    <tr height="50">
		      <td width="225">
		        <p style="height:30px;font-size:11px;text-align:center;">
		        	内件详细名称和数量<br/>
		        	Quantity and detailed description ofcontents
		        </p>
		      </td>
		      <td width="80">
		        <p style="height:30px;font-size:11px;text-align:center;">
		        	重量(千克)<br/>
		        	Weight(Kg)
		        </p>
		      </td>
		      <td width="70">
		        <p style="height:30px;font-size:11px;text-align:center;">
		        	价值<br/>
		        	Value
		        </p>
		      </td>
		    </tr>
		    
		   <tr height="25">
		      <td width="225">
		        <p style="height:25px;font-size:11px;text-align:center;">		        	
		        	{{ $model->decleared_ename }}
		        </p>
		      </td>
		      <td width="80">
		        <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
		        	{{ $model->total_weight }}
		        </p>
		      </td>
		      <td width="70">
		        <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
		        	{{ sprintf("%.2f",$model->total_price > 20 ? 20 : $model->total_price) }}USD 
		        </p>
		      </td>
		    </tr>
		    <tr height="30">
		      <td rowspan="2">
		        <p style="height:55px;font-size:9px;">
		        	协调系统税则号列和货物原产国(只对商品邮件填写)<br/>
		        	HS tariff number and country of origin of goods(For Commercial items only)
		        </p>
		      </td>
		       <td width="80">
		        <p style="height:30px;font-size:11px;text-align:center;">
		        	重量(千克)<br/>
		        	Weight(Kg)
		        </p>
		      </td>
		      <td width="70">
		        <p style="height:30px;font-size:11px;text-align:center;">
		        	价值<br/>
		        	Value
		        </p>
		      </td>
		    </tr>
		    <tr height="25">
		      <td>
		        <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
		        	{{ $model->total_weight }}
		        </p>
		      </td>
		      <td>
		       <p style="height:25px;font-size:11px;text-align:center;line-height:25px;">
		        	{{ sprintf("%.2f",$model->total_price > 20 ? 20 : $model->total_price) }}USD 
		        </p>
		      </td>
		    </tr>
		    <tr height="80">
		      <td colspan="3">
		         <p style="height:90px;font-size:10px;">
		        	我保证上述申报准确无误，本函件内未装寄法律或邮件和海关规章禁止寄递的任何危险物品
					<br/>
					I the undersigned,certify that the particulars given in this declaration are correct and this item does not contain any dangerous articles prohibited by legislation or by postal or customers regulations.
					<br/>
					<span style="padding-left:80px;">寄件人签字 Sender\'s signature:SLME </span>
		         </p>
		      </td>
		    </tr>
		  </table>
		</div>
</body>
</html>
