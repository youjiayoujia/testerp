
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印中邮通用平邮面单</title>
     <style>
        * {
            margin:0 auto;
            padding:0;
            font-size:12px;
            line-height:12px;
         }
         
		.main {
            width:98mm;height:98mm;
        }
        
		table {
			border:1px solid black;
			border-right:none;
			border-bottom:none;
        }
        
		table tr td {
			border-right:1px solid black;
			border-bottom:1px solid black;
			text-align:center;
        }
    </style>
</head>
<body>
   <div class="main" style="margin-left:5px;">
		<table cellspacing="0" cellpadding="0" style="width:98mm;height:95mm;">
			<tr style="height:35px;text-align:center;">
				<td style="width:35mm;text-align:center;"><img src="{{ asset('picture/post_logo.jpg') }}" style="height:35px;"/></td>
				<td style="width:20mm;">Small Packet<br/>BY AIR</td>
				<td style="width:13mm;font-weight:bold;font-size:16px;">
				    {{ $model->country ? $model->country->code : '' }}
                    @if($model->country)
                        @foreach(['RU' => 21,'US' => 22,'GB' => 23,'BR' => 24,
                                  'AU' => 25,'FR' => 26,'ES' => 27,'CA' => 28,
                                  'IL' => 29,'IT' => 30,'DE' => 31,'CL' => 32,
                                  'SE' => 33,'BY' => 34,'NO' => 35,'NL' => 36,
                                  'UA' => 37,'CH' => 38,'MX' => 39,'PL' => 40,] as $key => $value )
                            @if($key == $model->country->code)
                                {{ $value }}
                            @endif
                        @endforeach
                    @endif
				</td>
				<td style="font-weight:bold;font-size:16px;width:30mm;">{{ $model->logistics ? $model->logistics->logistics_code : '' }}</td>
			</tr>
			<tr style="height:23mm;" valign="top">
				<td style="width:35mm;text-align:left;padding:1px;">
					From:<br/>					
					 {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}<br/>
					<b style="font-weight:bold;">Phone:
						{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '') : '' }}
					</b>
				</td>
				<td colspan="3" style="text-align:left;padding:2px;font-size:9px">
					{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}&nbsp;&nbsp;&nbsp;&nbsp;<br/>
					{{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
					{{ $model->shipping_city }}<br/>
					{{ $model->shipping_state }}<br/>
					{{ $model->shipping_zipcode }}<br/>
					<b style="font-weight:bold;">{{ $model->country ? $model->country->name : '' }}</b><br/>
					<b style="font-weight:bold;">Phone：</b>{{ $model->shipping_phone }}
				</td>
			</tr>
			<tr style="height:5mm;">
				<td style="text-align:left;"> 自编号:{{ $model->id }}</td>
				<td>
				    <?php  $countryZone = config('countryZone'); ?>  
				    @if($model->country)				   
				        @foreach($countryZone as $key => $val)  
				            @if(in_array($model->country->cn_name, explode(',',$val)))
				               {{$key}}
				            @endif
				        @endforeach				     				            
				   	@endif
				</td>
				<td colspan="2">{{ $model->country ? $model->country->cn_name : '' }}</td>
			</tr>
			<tr style="height:16mm;" valign="center">
				<td style="font-size:16px;text-align:center;" valign="top">				
				<p style="text-align:left;height:4mm;border-bottom:1px solid black;line-height:4mm;">{{ $model->tracking_no }}  </p>
				<p style="font-size:16px;font-weight:bold;text-align:center;">UNTRACKED</p>
				</td>
				<td colspan="3" >
					<p style="margin-top:2px;">
						<img src="{{ route('barcodeGen', ['content' => $model->logistics_order_number]) }}" style="max-height:200px;">
					</p>
					<p style="margin-top:3px;">{{ $model->logistics_order_number }}</p>
				</td>
			</tr>
			<tr style="height:5mm;">
				<td colspan="4" style="text-align:left;">退件单位: {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->unit) : '') : '' }}</td>
			</tr>
			<tr style="height:5mm;line-height:11px;font-size:12px;" >
				<td colspan="2" >Dcscription of Contents</td>
				<td>Kg</td>
				<td>Val(US $)</td>
			</tr>
			<tr style="height:5mm;line-height:11px;font-size:12px;">
				<td colspan="2">
					{{ $model->getDeclaredInfo()['declared_en'] }}({{ $model->getDeclaredInfo()['declared_cn'] }})
				</td>
				<td>
					{{ $model->getDeclaredInfo()['weight'] }}
				</td>
				<td>
					{{ sprintf("%.2f", $model->getDeclaredInfo()['declared_value']) }}
				</td>
			</tr>
			<tr style="height:5mm;line-height:11px;font-size:12px;">
				<td colspan="2">Total Gross Weight(KG)</td>
				<td>{{ $model->total_weight }}</td>
				<td>{{ sprintf("%.2f",$model->total_price) }}</td>
			</tr>
			<tr style="height:10mm;line-height:11px;font-size:12px;">
				<td colspan="4" style="text-align:left;">
					I certify that the particulars given in this declaration are correct and this item does not contain any angerous articles prohibited by legislation or by postal or customs regulations.
				</td>
			</tr>
			<tr style="height:5mm;line-height:11px;font-size:11px;">
				<td colspan="2" style="text-align:left;"> Sender's Singniture:SLME</td>
				<td>CN22</td>
				<td>{{ date('Y-m-d') }}</td>
			</tr>
		</table>
		<div style="margin:0 auto;font-size:8px;white-space:normal;overflow:hidden;">
		      {{ $model->sku_info }}
		</div>
	</div>		
</body>
</html>