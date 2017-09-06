<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;
use Excel;
use App\Models\PackageModel;
use App\Models\Package\ShipmentCostItemModel;
use App\Models\Package\ShipmentCostErrorModel;
use App\Models\CountriesModel;


class ShipmentCostModel extends BaseModel
{
    public $table = 'shipment_costs';

    protected $fillable = [
    	'shipmentCostNum',
    	'all_weight',
    	'theory_weight',
    	'all_shipment_cost',
    	'theory_shipment_cost',
    	'average_price',
    	'import_by',
    	'created_at'
    ];

    public $searchFields = ['shipmentCostNum' => '批次号'];

    public function items()
    {
        return $this->hasMany('App\Models\Package\ShipmentCostItemModel', 'parent_id', 'id');
    }

    public function errors()
    {
        return $this->hasMany('App\Models\Package\ShipmentCostErrorModel', 'parent_id', 'id');
    }

    public function importBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'import_by', 'id');
    }

    public function importProcess($file, $userId)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $path = $path . 'excelProcess.xls';
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        fclose($fd);
        $buf = [];
        $outer_all_weight = 0;
        $outer_theory_weight = 0;
        $outer_all_cost = 0;
        $outer_theory_cost = 0;
        $average_price = '';
        foreach ($arr as $key => $value) {
        	if(!$key) {
        		continue;
        	}
            $package = PackageModel::where('tracking_no', $value[0])->first();
            if(!$package) {
                ShipmentCostErrorModel::create([
                    'hang_num' => $value[0],
                    'channel_name' => $value[3],
                    'remark' => '追踪号不能匹配包裹',
                       ]);
                continue;
            }
            $country = CountriesModel::where('cn_name', iconv('gb2312', 'utf-8', $value[1]))->first();            
            if(!$country) {
                ShipmentCostErrorModel::create([
                    'hang_num' => $value[0],
                    'channel_name' => $value[3],
                    'remark' => '国家名不能匹配包裹',
                       ]);
                continue;
            }
            ShipmentCostItemModel::create([
                    'hang_number' => $value[0],
                    'type' => $package->type,
                    'package_id' => $package->id,
                    'shipped_at' => date('Y-m-d H:i:s', time()),
                    'logistics_id' => $package->logistics_id,
                    'code' => $country->code,
                    'destination' => $country->cn_name,
                    'all_weight' => $value['2'],
                    'theory_weight' => $package->actual_weight,
                    'all_cost' => $value['4'] + $value['5'],
                    'theory_cost' => $package->calculateLogisticsFee() - 0.35,
                    'channel_name' => $value['3'],
                ]);

            $outer_all_weight += $value['2'];
            $outer_theory_weight += $package->actual_weight;
            $outer_all_cost += $value['4'] + $value['5'];
            $outer_theory_cost += $package->calculateLogisticsFee() - 0.35;	

            if(!array_key_exists($value[3], $buf)) {
                $buf[$value[3]]['cost'] = $value[4] * (!empty($value[6]) ? $value[6] : $value[7]);
                $buf[$value[3]]['weight'] = $value[2];
            } else {
                $buf[$value[3]]['cost'] += $value[4] * (!empty($value[6]) ? $value[6] : $value[7]);
                $buf[$value[3]]['weight'] += $value[2];
            }
            foreach($buf as $key => $value) {
                $average_price .= $key.'均价是:'.round($value['cost']/$value['weight'], 2);
            }
        }

        $model = $this->create([
                'shipmentCostNum' => '批次导入'.date('m-d H:i', time()),
                'all_weight' => $outer_all_weight,
                'theory_weight' => $outer_theory_weight,
                'all_shipment_cost' => $outer_all_cost,
                'sheory_shipment_cost' => $outer_theory_cost,
                'average_price' => $average_price,
                'import_by' => $userId
            ]);

        foreach(ShipmentCostItemModel::where('parent_id', '0')->get() as $single) {
            $single->update(['parent_id' => $model->id]);
        }
        foreach(ShipmentCostErrorModel::where('parent_id', '0')->get() as $single) {
            $single->update(['parent_id' => $model->id]);
        }

        return true;
    }

    public function getAveratePriceAttribute()
    {
        foreach($this->items as $item) {
            
        }
    }
         
}