@extends('common.form')
@section('formBody')
    @foreach($data as $item)
    <?php
	$freightSettingList = unserialize($item['freightSettingList']);
	foreach($freightSettingList as $row):
		echo '<table class="table table-bordered table-striped table-hover" style="word-break: break-all;">';
		echo '<col width="30%"><col width="70%">';
		if($row['template']): //物流公司运费设置信息
			echo '<tr><th colspan="2">物流公司运费设置信息:</th></tr>';
			echo '<tr><td>物流公司:</td><td>'.$row['template']['logisticsCompany'].'</td></tr>';
			echo (array_key_exists('allStandardDiscount', $row['template']) ? '<tr><td>标准运费减免率(%):</td><td>'.$row['template']['allStandardDiscount'].'</td></tr>' : '');
			echo '<tr><td>是否为全部免运费:</td><td>'.$row['template']['allFreeShipping'].'</td></tr>';
			echo (array_key_exists('freeShippingCountry', $row['template']) ? '<tr><td>自定义免运费国家:</td><td>'.$row['template']['freeShippingCountry'].'</td></tr>' : '');
			echo (array_key_exists('standardShippingCountry', $row['template']) ? '<tr><td>自定义标准运费的国家:</td><td>'.$row['template']['standardShippingCountry'].'</td></tr>' : '');
			echo (array_key_exists('standardShippingDiscount', $row['template']) ? '<tr><td>自定义标准运费减免率(%):</td><td>'.$row['template']['standardShippingDiscount'].'</td></tr>' : '');
		endif;
		if($row['selfdefine']):
			echo '<tr><th colspan="2">自定义运费:</th></tr>';
			echo (array_key_exists('startOrderNum', $row['selfdefine']) ? '<tr><td>自定义起始采购量:</td><td>'.$row['selfdefine']['startOrderNum'].'</td></tr>' : '');
			echo (array_key_exists('endOrderNum', $row['selfdefine']) ? '<tr><td>自定义截至采购量:</td><td>'.$row['selfdefine']['endOrderNum'].'</td></tr>' : '');
			echo (array_key_exists('minFreight', $row['selfdefine']) ? '<tr><td>截至采购量里的运费报价:</td><td>'.$row['selfdefine']['minFreight'].'</td></tr>' : '');
			echo (array_key_exists('perAddNum', $row['selfdefine']) ? '<tr><td>每增加定额产品采购量:</td><td>'.$row['selfdefine']['perAddNum'].'</td></tr>' : '');
			echo (array_key_exists('addFreight', $row['selfdefine']) ? '<tr><td>续增的运费:</td><td>'.$row['selfdefine']['addFreight'].'</td></tr>' : '');
			echo (array_key_exists('shippingCountry', $row['selfdefine']) ? '<tr><td>自定义运送国家:</td><td>'.$row['selfdefine']['shippingCountry'].'</td></tr>' : '');
		endif;
		if ($row['selfstandard']):
			echo '<tr><th colspan="2">自定义标准运费:</th></tr>';
			foreach($row['selfstandard'] as $k => $r):
				echo '<tr><td>'.($k+1).'-自定义标准运费国家:</td><td>'.$r['selfStandardCountry'].'</td></tr>';
				echo '<tr><td>'.($k+1).'-自定义标准运费减免率(%):</td><td>'.$r['selfStandardDiscount'].'</td></tr>';
			endforeach;
		endif;
		echo '</table>';
	endforeach;
    ?>
    @endforeach
   
@stop
@section('formButton')
    <div class="text-center">
        <button type="button"  class="btn btn-success submit_btn" onclick="history.go(-1)">返回</button> 
    </div>
@stop 