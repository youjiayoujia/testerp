<?php
/**
 * 物流方式模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:13
 */

namespace App\Models;
use Logistics;
use App\Base\BaseModel;
use App\Models\Logistics\LimitsModel;
use App\Models\PackageModel;

class LogisticsModel extends BaseModel
{
    public $table = 'logisticses';

    public $searchFields = ['code' => '简码', 'name' => '物流方式名称', 'logistics_code' => '物流编码'];

    public $fillable = [
        'id',
        'code',
        'name',
        'warehouse_id',
        'logistics_supplier_id',
        'type',
        'docking',
        'logistics_catalog_id',
        'logistics_email_template_id',
        'logistics_template_id',
        'pool_quantity',
        'is_enable',
        'limit',
        'driver',
        'logistics_code',
        'priority',
        'is_express',
        'is_confirm',
    ];

    //多重查询
    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => [
                'code',
                'name',
                'logistics_code',
            ],
        ];
    }

    public $rules = [
        'create' => [
            'code' => 'required',
            'name' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            //'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
            'driver' => 'required',
//            'priority' => 'required|unique:logisticses,priority',
        ],
        'update' => [
            'code' => 'required',
            'name' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'docking' => 'required',
            'logistics_catalog_id' => 'required',
            //'logistics_email_template_id' => 'required',
            'logistics_template_id' => 'required',
            'is_enable' => 'required',
            'driver' => 'required',
//            'priority' => 'required',
        ],
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Models\Logistics\SupplierModel', 'logistics_supplier_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    public function logisticsLimit()
    {
        return $this->belongsTo('App\Models\Logistics\LimitsModel', 'limit', 'id');
    }

    public function codes()
    {
        return $this->hasMany('App\Models\Logistics\CodeModel', 'logistics_id');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\Logistics\CatalogModel', 'logistics_catalog_id', 'id');
    }

    public function emailTemplate()
    {
        return $this->belongsTo('App\Models\Logistics\EmailTemplateModel', 'logistics_email_template_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\Logistics\TemplateModel', 'logistics_template_id', 'id');
    }

    public function channelName()
    {
        return $this->belongsToMany('App\Models\Logistics\ChannelNameModel', 'logistics_belongstos', 'logistics_id',
            'logistics_channel_id');
    }

    public function logisticsChannels()
    {
        return $this->hasMany('App\Models\Logistics\ChannelModel', 'logistics_id', 'id');
    }

    public function zones()
    {
        return $this->hasMany('App\Models\Logistics\ZoneModel', 'logistics_id');
    }

    public function logisticsRules()
    {
        return $this->hasMany('App\Models\Logistics\RuleModel', 'type_id', 'id');
    }

    public function getApiConfigAttribute()
    {
        $config = [];
        $config['type'] = $this->type;

        $config['url'] = $this->supplier->url;
        $config['userId'] = $this->supplier->customer_id;
        $config['userPassword'] = $this->supplier->password;
        $config['key'] = $this->supplier->secret_key;

        if(!empty($this->emailTemplate)){
            $config['returnCompany'] = $this->emailTemplate->unit;
            $config['returnContact'] = $this->emailTemplate->sender;
            $config['returnPhone'] = $this->emailTemplate->phone;
            $config['returnAddress'] = $this->emailTemplate->address;
            $config['returnZipcode'] = $this->emailTemplate->zipcode;
            $config['returnCountry'] = $this->emailTemplate->country_code;
            $config['returnProvince'] = $this->emailTemplate->province;
            $config['returnCity'] = $this->emailTemplate->city;
        }

        return $config;
    }

    public function belongsToWarehouse($id)
    {
        $logistics = $this->where(['code' => $this->code, 'warehouse_id' => $id])->first();
        if($logistics) {
            return true;
        }
        
        return false;
    }

    //物流方式停用启用颜色
    public function getEnableColorAttribute()
    {
        switch ($this->is_enable) {
            case '0':
                $color = 'danger';
                break;
            case '1':
                $color = '';
                break;
            default:
                $color = '';
                break;
        }
        return $color;
    }

    public function getDockingNameAttribute()
    {
        $arr = config('logistics.docking');
        return $arr[$this->docking];
    }

    public function hasLimits($id)
    {
        $arr = explode(',', $this->limit);
        if (in_array($id, $arr)) {
            return true;
        }
        return false;
    }

    public function inType($id)
    {
        $multi = $this->channelName;
        foreach ($multi as $single) {
            if ($single->id == $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * 物流商下单
     * todo:分方式下单
     */
    public function placeOrder($packageId)
    {
        $return['status'] = 'error';
        $return['tracking_no'] = '';
        $return['logistics_order_number'] = '';
        switch ($this->docking) {
            case 'CODE':
                $code = $this->codes->where('status', '0')->first();
                if ($code) {
                    $code->update([
                        'status' => 1,
                        'package_id' => $packageId,
                        'used_at' => date('y-m-d', time())
                    ]);
                    $return['status'] ='success';
                    $return['tracking_no'] = $code->code;
                }else{
                    $return['tracking_no'] = '号码池无可用号码';
                }
                break;
            case 'API':
                $package =  PackageModel::where('id',$packageId)->first();
                $apiResult = Logistics::driver($package->logistics->driver, $package->logistics->api_config)->getTracking($package);
                $return['status'] = $apiResult['code'];
                $return['tracking_no'] = $apiResult['result'];
                $return['logistics_order_number'] = isset($apiResult['result_other'])?$apiResult['result_other']:'';
                break;
            case 'MANUAL':
                $return['tracking_no'] = 'manual';
                break;
            case 'SELFAPI':
                $return['status'] = 'success';
                $return['tracking_no'] = 'S'.$packageId;  //slme 为S+内单号， 现在改为S+包裹id  防止 1个订单对应多个包裹 出现重复追踪号情况
                break;
        }
        return $return;

    }

    /**
     * 遍历物流限制
     */
    public function limit($limit)
    {
        $str = '';
        foreach (explode(",", $limit) as $value) {
            $limits = LimitsModel::where(['id' => $value])->get();
            foreach ($limits as $limit) {
                $val = $limit['name'];
                $str = $str . $val . ',';
            }
        }
        return substr($str, 0, -1);
    }
}