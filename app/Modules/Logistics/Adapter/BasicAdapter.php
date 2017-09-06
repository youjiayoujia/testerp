<?php
namespace App\Modules\Logistics\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:31
 */
class BasicAdapter
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     *
     *   code =>['success','error','again']
     *   result=>'tracking_no'
     *   result_other =>'logistics_order_number'
     *
     *
     * 物流下单
     * @param $package package model
     * @return
     * SUCCESS
     * [
     *  'code' => 'success',
     *  'result' => $trackingNumber
     *  'result_other' => $logistics_order_number
     * ]
     * ERROR
     * [
     *  'code' => 'error',
     *  'result => $errorDescription
     * ]
     */
    public function getTracking($package)
    {
        return ['code' => 'error', 'result' => 'Interface is not instantiated.'];
    }

    public function curlPost($url, $request_json, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        $data = curl_exec($ch);
        $return = ['status' => 0, 'msg' => ''];
        if (curl_errno($ch)) {
            $return['msg'] = curl_error($ch);
        } else {
            curl_close($ch);
            $return['status'] = 1;
            $return['msg'] = $data;
        }
        return $return;
    }

    public function getLogisticUrl($content = "")
    {
        return $this->config["url"] . "?Content=" . $content . "&UserId=" . $this->config['userId'] . "&UserPassword=" . $this->config['userPassword'] . "&Key=" . $this->config['key'];
    }
}