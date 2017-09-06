<?php
namespace App\Modules\Channel;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 上午10:11
 */
use Exception;
use App\Modules\Channel\Adapter\AmazonAdapter;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use App\Modules\Channel\Adapter\LazadaAdapter;
use App\Modules\Channel\Adapter\WishAdapter;
use App\Modules\Channel\Adapter\EbayAdapter;
use App\Modules\Channel\Adapter\CdiscountAdapter;
use App\Modules\Channel\Adapter\JoomAdapter;

class ChannelModule
{
    public function driver($adapter, $config)
    {
        $driverMethod = 'create' . ucfirst(strtolower($adapter)) . 'Driver';
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new Exception("Driver [{$adapter}] not supported.");
        }
    }

    /**
     * 亚马逊接口驱动
     *
     * @param $config
     * @return AmazonAdapter
     */
    public function createAmazonDriver($config)
    {
        return new AmazonAdapter($config);
    }

    public function createEbayDriver($config)
    {
        return new EbayAdapter($config);
    }

    /**
     * wish接口驱动
     *
     * @param $config
     * @return WishAdapter
     */
    public function createWishDriver($config)
    {
        return new WishAdapter($config);
    }

    public function createAliexpressDriver($config)
    {
        return new AliexpressAdapter($config);
    }

    public function createLazadaDriver($config)
    {
        return new LazadaAdapter($config);
    }
    public function createCdiscountDriver($config)
    {
        return new CdiscountAdapter($config);
    }
	public function createJoomDriver($config)
    {
        return new JoomAdapter($config);
    }
}