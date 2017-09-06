<?php
/**  燕文物流下单
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-08-19
 */
namespace App\Modules\Logistics\Adapter;
use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;

class YanWenAdapter extends BasicAdapter{
    public function getTracking($package)
    {
        $this->config = $package->logistics->api_config;
        $config = $this->config;
        echo "<pre/>";var_dump($config);exit;
    }
}