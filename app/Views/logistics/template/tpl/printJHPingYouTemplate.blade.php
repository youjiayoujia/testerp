<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>JH邮局平邮面单模板</title>
    <style>
	   *{
	       margin:0;padding:0;
        }
        
		body{ 
			font-family:Arial, Helvetica, sans-serif; 
			font-size:14px;
        }
        
		#main_frame_box{
            width:382px; 
            margin:0 auto;
            height:378px;
            overflow:hidden;
        }
        
		td{
			border:1px solid #000;
			border-bottom:none;
        }
	</style>
</head>
<body>
    <div id="main_frame_box">
    	<div style="width:380px;border:1px solid #000;border-bottom:none;">
    	  <p style="float:left;width:140px;height:30px;">
    	    <img src="{{ asset('picture/EUB01.jpg') }}" />
    	  </p>
    	  <p style="float:left;width:124px;height:30px;text-align:center;font-size:10px;font-weight:bold;line-height:30px;border-right:1px solid #000;">
    	  Small Packet By Air
    	  </p>
    	  <p style="float:left;width:54px;line-height:30px;text-align:center;font-weight:bold;height:30px;border-right:1px solid #000;">
    	    {{ $model->country ? $model->country->code : '' }}
            @if($model->country)
                @foreach(['RU' => 21,'US' => 22,'GB' => 23,'BR' => 24,
                          'AU' => 25,'FR' => 26,'ES' => 27,'CA' => 28,
                          'IL' => 29,'IT' => 30,'DE' => 31,'CL' => 32,
                          'SE' => 33,'BY' => 34,'NO' => 35,'NL' => 36,
                          'UA' => 37,'CH' => 38,'MX' => 39,'PL' => 40,] as $key => $value )
                    @if($value[$key] == $model->country->code)
                        {{ $key }}
                    @endif
                @endforeach
            @endif
    	  </p>
    	 <p style="float:left;width:60px;height:30px;line-height:30px;text-align:center;font-weight:bold;">
    	    {{ $model->logistics_id }}
    	  </p>
    	  <p style="float:left;width:140px;">
    	     <span style="width:140px;display:inline-block;border-bottom:1px solid #000;font-size:11px;">
    	       From:<br/>
    	       {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}<br/>
    	       <b style="font-weight:bold;">Phone:{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '') : '' }}</b>
    	     </span>
    	     <span style="width:140px;line-height:29px;font-size:12px;background:#fff;display:inline-block;border-bottom:1px solid #000;">
    	       	自编号:{{ $model->order ? $model->order->ordernum : '' }}
    	     </span>
    	  </p>
    	  <p style="float:left;width:238px;border:1px solid #000;border-right:none;font-size:12px;">
    	    <span style="font-weight:bold;font-size:12px;">Ship To:</span><br/>
    	    	{{ $model->shipping_firstname }}&nbsp;&nbsp;{{ $model->shipping_lastname }}<br/>
    			{{ $model->shipping_address }}&nbsp;&nbsp;{{ $model->shipping_address1 }}<br/>
    			{{ $model->shipping_city }} {{ $model->shipping_state }}<br/>    			
    			{{ $model->shipping_zipcode }}<br/>
    			<b style="font-weight:bold;">{{ $model->country ? $model->country->name : '' }}
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    			<b style="font-weight:bold;">
    			 <?php  $countryZone = config('countryZone'); ?>  
				    @if($model->country)				   
				        @foreach($countryZone as $key => $val)  
				            @if(in_array($model->country->cn_name, explode(',',$val)))
				               {{$key}}
				            @endif
				        @endforeach				     				            
				   @endif
    			</b>
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    			{{ $model->country ? $model->country->cn_name : ''}}</b><br/>
    			<b style="font-weight:bold;">Phone：</b>{{ $model->shipping_phone }}
    	  </p>
        </div>
        
          <table border="0" style="width:382px;height:155px;"  cellspacing="0" cellpadding="0">
             <tr height="45">
               <td colspan="3" style="border-top:none;">
                  <p style="width:80px;text-align:center;font-weight:bold;line-height:50px;height:50px;float:left;">
                  	Untracked
                  </p>
                  <p style="width:270px;height:45px;float:left;text-align:center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
                    <br/>
                    {{ $model->tracking_no }}
                  </p>
               </td>
             </tr>
             <tr style="height:15px;font-weight:bold;font-size:10px;text-align:center;">
               <td width="70%" style="border-right:none;">
                 Description of Contents
               </td>
               <td width="15%" style="border-right:none;">
                Kg
               </td>
               <td width="15%">
                Val(US $)
               </td>
             </tr>
             <tr style="font-size:12px;">
    			   <td width="70%" style="border-right:none;">{{ $model->decleared_ename }}</td>
    	           <td width="15%" style="border-right:none;">{{ $model->signal_weight }}</td>
    	           <td width="15%">{{ sprintf("%.2f",$model->signal_price) }}</td>
    	      </tr>
             <tr height="15" style="font-size:12px;">
               <td width="70%" style="border-right:none;font-size:12px;">
                 Totalg Gross Weight(kg)
               </td>
               <td width="15%" style="border-right:none;">{{ $model->total_weight }}</td>
               <td width="15%">{{ sprintf("%.2f",$model->total_price) }}</td>
             </tr>
             <tr height="50">
               <td colspan="3" style="border-bottom:1px solid #000;font-size:9px;">
                 I the undersigned,certify that the particulars given in this declaration are correct and this item 
                 does not contain any dangerous articles prohibited by legislation or by postal or customers 
                 regulations.<br/>
                 <span style="font-weight:bold;">Sender\'s signature:SLME </span>
                 <!-- 
    			 <span  style="font-weight:bold;padding-left:20px;">'.$old_shipping.'</span>
    			 -->
                 <span  style="font-weight:bold;padding-left:20px;"></span>
    			 @if($model->logistics_id == '345')  <!-- 针对345-【平邮】JTXM -->
    			 <span style="font-weight:bold;padding-left:90px;">CN22</span>
    			 @else
                 <span style="font-weight:bold;padding-left:170px;">CN22</span>
                 @endif
               </td>
             </tr>
          </table>
          <div style="width:382px;height:40px;margin:0 auto;font-size:10px;white-space:normal;overflow:hidden;">
    		{{ $model->sku_info }}
          </div>
    </div>
    
</body>