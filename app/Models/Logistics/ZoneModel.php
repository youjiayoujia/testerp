<?php
/**
 * 物流分区模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:22
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;
use App\Models\CountriesModel;
use App\Models\Logistics\Zone\CountriesModel as ZoneCountriesModel;

class ZoneModel extends BaseModel
{
    public $table = 'logistics_zones';

    public $fillable = [
        'zone',
        'logistics_id',
        'type',
        'fixed_weight',
        'fixed_price',
        'continued_weight',
        'continued_price',
        'other_fixed_price',
        'discount',
        'discount_weather_all',
    ];

    public $searchFields = ['zone' => '物流分区', 'logistics_id' => '物流方式'];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

    public function logistics_zone_countries()
    {
        return $this->belongsToMany('App\Models\CountriesModel', 'logistics_zone_countries', 'logistics_zone_id', 'country_id');
    }

    public function zone_section_prices()
    {
        return $this->hasmany('App\Models\Logistics\Zone\SectionPriceModel', 'logistics_zone_id', 'id');
    }

    public function zone_countries()
    {
        return $this->hasMany('App\Models\Logistics\Zone\CountriesModel', 'logistics_zone_id', 'id');
    }

    /**
     * 遍历国家
     */
    public function country($country)
    {
        $str = '';
        foreach(explode(",", $country) as $value) {
            $countries = CountriesModel::where(['code' => $value])->get();
            foreach($countries as $country) {
                $val = $country->cn_name;
                $str = $str.$val.',';
            }
        }

        return substr($str, 0, -1);
    }

    public function inZone($code)
    {
        $countries = $this->logistics_zone_countries;
        foreach($countries as $country) {
            if($country->code == $code) {
                return true;
            }
        }
        return false;
    }

    public function weatherAvailable($id)
    {
        $logistics_id = $this->logistics_id;
        $models = $this->where('logistics_id', $logistics_id)->get();
        foreach($models as $model) {
            $countries = $model->logistics_zone_countries;
            foreach($countries as $country) {
                if($country->id == $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function createData($arr)
    {
        $model = $this->create($arr);
        if(array_key_exists('countrys', $arr)) {
            $model->logistics_zone_countries()->attach($arr['countrys']);
        }
        if($arr['type'] == 'second') {
            $tmp = $arr['arr'];
            $len = count($arr['arr']['weight_from']);
            for($i = 0; $i < $len; $i++) {
                $list = [];
                foreach($tmp as $key => $value) {
                    $value = array_values($value);
                    $list[$key] = $value[$i];
                } 
                $model->zone_section_prices()->create($list);
            }    
        }
    }

    public function updateData($arr)
    {
        $this->update($arr);
        $countries = $this->logistics_zone_countries;
        $buf = [];
        if(array_key_exists('countrys', $arr)) {
            $buf = array_unique($arr['countrys']);
        }
        $this->logistics_zone_countries()->sync($buf);
        if($arr['type'] == 'second') {
            $sectionPrices = $this->zone_section_prices;
            foreach($sectionPrices as $sectionPrice) {
                $sectionPrice->forceDelete();
            }
            $tmp = $arr['arr'];
            $len = count($arr['arr']['weight_from']);
            for($i = 0; $i < $len; $i++) {
                $list = [];
                foreach($tmp as $key => $value) {
                    $value = array_values($value);
                    $list[$key] = $value[$i];
                } 
                $this->zone_section_prices()->create($list);
            }    
        }
    }

    /**
     * 运费计算
     * 说明：运费 = 首重费用 + [(总重 - 首重) ÷ 续重] * 续重费用 + 操作费
     * @param $zoneId
     */
    public function getShipmentFee($zoneId,$productWeight){
        $zone_obj = $this->find($zoneId);
        //dd($zone_obj);exit;
        if($zone_obj && $productWeight){
            $shipment_fee = $zone_obj->fixed_price + (($productWeight - $zone_obj->fixed_weight) / $zone_obj->continued_weight)  * $zone_obj->continued_price + 0;
        }else{
            $shipment_fee = false;
        }
        return $shipment_fee;
    }
}