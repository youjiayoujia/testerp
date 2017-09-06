<?php
/** 线上Eub
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-12-21
 * Time: 13:55
 */

namespace App\Modules\Logistics\Adapter;

use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;
use App\Models\Logistics\SupplierModel;
use App\Models\PackageModel;
use Illuminate\Support\Facades\Storage;
class JhdAdapter extends BasicAdapter
{
    public function __construct($config)
    {
        $this->_express_type =!empty($config['type'])?$config['type']:'香港平邮';
        $this->url = !empty($config['url'])?$config['url']:'http://114.119.9.156:8012/Order.aspx';
        $this->prefix = 'ERP3';
    }
    public function getTracking($orderInfo){
        $totalWeight = 0;
        $sku_count =0;
        foreach($orderInfo->items as $key => $item){
            $totalWeight += $item->quantity * $item->item->weight;
            $sku_count += $item->quantity;
        }
        $orderno=$orderInfo->id;
        $orderInfo->shipping_state=$orderInfo->shipping_state?$orderInfo->shipping_state:'.';
        $content = '<request>
                    <orderno>'.$this->prefix.$orderno.'</orderno>
                    <Clno>J-SLME</Clno>
                    <HubIn>'.$this->_express_type.'</HubIn>
                    <DestNO>'.$orderInfo->shipping_country.'</DestNO>
                    <Weig>'.$totalWeight.'</Weig>
                    <Pcs>'.$sku_count.'</Pcs>
                    <ReCompany>'.$orderInfo->shipping_firstname.' '.$orderInfo->shipping_lastname.'</ReCompany>
                    <ReTel>'.$orderInfo->shipping_phone.'</ReTel>
                    <ReAddr>'.$orderInfo->shipping_address.' '.$orderInfo->shipping_address1.'</ReAddr>
                    <ReCity>'.$orderInfo->shipping_city.'</ReCity>
                    <ReZip>'.$orderInfo->shipping_zipcode.'</ReZip>
                    <ReState>'.$orderInfo->shipping_state.'</ReState>
                    <ReConsinee>'.$orderInfo->shipping_firstname.' '.$orderInfo->shipping_lastname.'</ReConsinee>
                </request>';
        $str = 'service=tms_order_notify&content='.$content.'&sign='.md5($content.'123456');
        $url=$this->url;
        $resx = $this->postCurlHttpsData($url,$str);
        $res = (array)simplexml_load_string($resx);
        if(@$res['is_success'] == 'T' && @$res['jobno']){
            //success
            $result = [
                'code' => 'success',
                'result' =>$res['jobno'] //跟踪号
            ];
        }else{
            $error = '';
            if(@$res['error']){
                $error=$res['error'];
            }else{
                $error=$resx;
            }
            $result =[
                'code' => 'error',
                'result' => $error
            ];
        }
        return $result;

    }
    public function postCurlHttpsData($url, $data) { // 模拟提交数据函数
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        //curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec ( $curl ); // 执行操作
        if (curl_errno ( $curl )) {
            die(curl_error ( $curl )); //异常错误
        }
        curl_close ( $curl ); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}