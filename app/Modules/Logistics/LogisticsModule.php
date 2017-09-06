<?php
namespace App\Modules\Logistics;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:27
 */
use Exception;
use App\Modules\Logistics\Adapter\ChukouyiAdapter;
use App\Modules\Logistics\Adapter\CoeAdapter;
use App\Modules\Logistics\Adapter\szChinaPostAdapter;
use App\Modules\Logistics\Adapter\WinitAdapter;
use App\Modules\Logistics\Adapter\FpxAdapter;
use App\Modules\Logistics\Adapter\SmtAdapter;
use App\Modules\Logistics\Adapter\YwAdapter;
use App\Modules\Logistics\Adapter\ShunyouAdapter;
use App\Modules\Logistics\Adapter\ShunfengAdapter;
use App\Modules\Logistics\Adapter\ShunfenghlAdapter;
use App\Modules\Logistics\Adapter\EubofflineAdapter;
use App\Modules\Logistics\Adapter\EubAdapter;

use App\Modules\Logistics\Adapter\WishyouAdapter;
use App\Modules\Logistics\Adapter\BpostAdapter;
use App\Modules\Logistics\Adapter\YuntuAdapter;
use App\Modules\Logistics\Adapter\kuaiyouAdapter;
use App\Modules\Logistics\Adapter\MalaixiyaAdapter;
use App\Modules\Logistics\Adapter\DiouAdapter;
use App\Modules\Logistics\Adapter\SzPostXBAdapter;
use App\Modules\Logistics\Adapter\DhlAdapter;
use App\Modules\Logistics\Adapter\JhdAdapter;
use App\Modules\Logistics\Adapter\OstAdapter;
use App\Modules\Logistics\Adapter\GuoYangAdapter;


class LogisticsModule
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
     * 出口易接口驱动
     *
     * @param $config
     * @return ChukouyiAdapter
     */
    public function createChukouyiDriver($config)
    {
        return new ChukouyiAdapter($config);
    }

    public function createCoeDriver($config)
    {
        return new CoeAdapter($config);
    }

    
    public function createSzChinaPostDriver($config)
    {
        return new SzChinaPostAdapter($config);
    }
    
    public function createWinitDriver($config){
        return new WinitAdapter($config);
    }
    
    /**
     * 实例化4px
     * @param $config
     * @return object \App\Modules\Logistics\Adapter\FpxDriver
     */
    public function createFpxDriver($config){
        return new FpxAdapter($config);
    }
    
    /**
     * 实例化速卖通线上发货
     * @param  $config
     * @return \App\Modules\Logistics\Adapter\SmtAdapter
     */
    public function createSmtDriver($config){
        return new SmtAdapter($config);
    }
    
    public function createYwDriver($config){
        return new YwAdapter($config);
    }


    /** 实例化顺友
     * @param $config
     * @return ShunyouAdapter
     */
    public function createShunyouDriver($config)
    {
        return new ShunyouAdapter($config);
    }

    /** 实例化顺丰俄罗斯
     * @param $config
     * @return ShunfengAdapter
     */
    public function createShunfengDriver($config){
        return new ShunfengAdapter($config);
    }

    /**实例化顺丰荷兰
     * @param $config
     * @return ShunfenghlAdapter
     */
    public function createShunfenghlDriver($config){
        return new ShunfenghlAdapter($config);
    }

    /**实例化线下Eub
     * @param $config
     * @return EubofflineAdapter
     */
    public function createEubofflineDriver($config){
        return new EubofflineAdapter($config);
    }

    /**实例化线上eub
     * @param $config
     * @return EubAdapter
     */
    public function createEubDriver($config){
        return new EubAdapter($config);
    }
	/*
	*wish_you驱动
	*@param $config
    *@return WishyouAdapter
	*/
	public function createWishyouDriver($config)
    {
        return new WishyouAdapter($config);
    }
	/*
	*Malaixiya驱动
	*@param $config
    *@return MalaixiyaAdapter
	*/
	public function createMalaixiyaDriver($config)
    {
        return new MalaixiyaAdapter($config);
    }
	
	/*
	*Yuntu驱动
	*@param $config
    *@return YuntuAdapter
	*/
	public function createYuntuDriver($config)
    {
        return new YuntuAdapter($config);
    }
	
	/*
	*Diou驱动
	*@param $config
    *@return BpostAdapter
	*/
	public function createDiouDriver($config)
    {
        return new DiouAdapter($config);
    }

    public function createSzPostXBDriver($config){
        return new SzPostXBAdapter($config);
    }
    /*
	*DHL驱动
	*@param $config
    *@return BpostAdapter
	*/
    public function createDhlDriver($config){
        return new DhlAdapter($config);
    }
    /*
	*京华东驱动
	*@param $config
    *@return BpostAdapter
	*/
    public function createJhdDriver($config){
        return new JhdAdapter($config);
    }
    /*
	*欧速通
	*@param $config
    *@return OstAdapter
	*/
    public function createOstDriver($config){
        return new OstAdapter($config);
    }

    public function createGuoYangDriver($config){
        return new GuoYangAdapter($config);
    }
}