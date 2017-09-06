<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/9
 * Time: 16:32
 */
namespace App\Modules\Alibaba;
use App\Models\Purchase\AlibabaSupliersAccountModel;
class Alibaba {
    public $app_key='1023183';

    public $secret_key='ZCCNtKHIiGG';

    public $params;

    public $ali_url = 'http://gw.open.1688.com:80';

    public $redirect_uri = "http://erp.moonarstore.com/aliAPI/Index.php";

    //public $order_list_api_url = 'param2/2/cn.alibaba.open/trade.order.list.get';//获取订单列表
    public $order_list_api_url = 'param2/2/cn.alibaba.open/trade.order.detail.get';//获取订单详情

    function __construct(){
        $accounts = json_decode($this->getSlmeAliAccount(),true);
        if(!empty($accounts)){
            foreach ($accounts as $account){
                $obj_account               = AlibabaSupliersAccountModel::firstOrNew(['resource_owner' => $account['resource_owner']]);
                $obj_account->memberId     = $account['memberId'];
               // $obj_account->access_token = $account['access_token'];
                $obj_account->access_token = $account['access_token'];
                $obj_account->save();
            }
        }
    }
    
    /*
     *生成阿里接口访问地址及参数
     */
    public function getRequestUrl(){}

    /**
     * 获取用户token
     */
    public function getToken($code)
    {

    }


    /**
     * http://gw.open.1688.com
     * /auth/authorize.htm?client_id=xxx&site=china&redirect_uri=YOUR_REDIRECT_URL&state=YOUR_PARM&_aop_signature=SIGENATURE
     */
    public function getCode(){
        $param = [
            'client_id'    => $this->app_key,
            'site'         => 'china',
            'redirect_uri' => $this->redirect_uri,
        ];
    }

    /**
     * 请求中用到的签名
     */
    public function getSignature($code_arr, $api_url){
       // $sign = '/param2/1/system/currentTime/' . $this->app_key;

        $sign_str = $api_url;

        //var_dump($this->secret_key);
        
        if(!empty($code_arr)){
            ksort($code_arr);
            foreach ($code_arr as $key => $val){
                $sign_str .= $key . $val;
            }
        }

        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $this->secret_key, true)));

        return $code_sign;


    }

    public static function get($url,$params)
    {
        $http=$url.'?'.http_build_query($params);
        $ch = curl_init($http) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        $output = curl_exec($ch) ;
        curl_close($ch);
        return  $output;

    }



    public function getSlmeAliAccount(){
        $post_data = array('appkey'=>'slme');
        $url="http://120.24.100.157:60/api/returnAccessToken.php";
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_FAILONERROR,1);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
        $buf = curl_exec($c);

        return $buf;
    }



}