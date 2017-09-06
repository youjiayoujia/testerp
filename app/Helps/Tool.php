<?php
namespace App\Helps;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Tool
{
    public function dir($directory)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }
    }

    public function getFileExtension($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    /**
     * 创建sku对应属性的笛卡尔积
     * 2015-12-18 10:43:48 YJ
     * @param array $data
     * @return array
     */
    public function createDikaer($data)
    {
        $cnt = count($data);
        $result = array();
        foreach ($data[0] as $item) {
            $result[] = array($item);
        }
        for ($i = 1; $i < $cnt; $i++) {
            $result = $this->combineArray($result, $data[$i]);
        }
        return $result;

    }

    /**
     * 2个数组对笛卡尔积的处理
     * 2015-12-18 10:43:48 YJ
     * @param array $arr1 ,$arr2
     * @return array
     */
    function combineArray($arr1, $arr2)
    {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 随机创建sku
     * 2015-12-18 10:43:21 YJ
     * @return str
     */
    public function createSku($code, $code_num)
    {
        $spu = $code . sprintf("%05d", $code_num + 1);

        return $spu;

    }

    public function isSelected($field, $value, $model = null)
    {
        if (old($field) == $value) {
            return 'selected';
        } elseif ($model) {
            if ($model->$field == $value) {
                return 'selected';
            }
        }
        return false;
    }

    public function isChecked($field, $value, $model = null, $default = false)
    {
        if (old($field) == $value) {
            return 'checked';
        } elseif ($model) {
            if ($model->$field == $value) {
                return 'checked';
            }
        } elseif ($default) {
            return 'checked';
        }
        return false;
    }

    public function show($value, $type = true)
    {
        echo "<pre>";
        var_dump($value);
        if ($type == true) {
            exit;
        }
    }

    public function curl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }




    // 1 处理捆绑的情况   A+B
    // 2 去除前后缀    $type = 2 的时候 sku前缀是  S*001KU[TEST]  这样存在的
    // 3 处理SKU（10）  处理打包的情况
    /*
     * 返回数据格式:
     * [
     *      'skuNum'=>'', //sku 总数量
     *      [
     *          'erpSku'=>'', //erpsku
     *          'qty'=>'',  //数量
     *      ]
     *
     * ]
     *
     *
     */
    public function filter_sku($channel_sku, $type = 1)
    {

        $tmpSku = explode('+', $channel_sku);
        $skuNum = 0;
        $returnSku = array();
        foreach ($tmpSku as $k => $sku) {

            if (stripos($sku, '[') !== false) {
                $sku = preg_replace('/\[.*\]/', '', $sku);
            }
            if ($type == 2) {

                $prePart = substr($sku, 0, 1);
                $suffPart = substr($sku, 4);
                $sku = $prePart . $suffPart;
                $newSku = $sku;
            } else {

                $tmpErpSku = explode('*', $sku);
                $i = count($tmpErpSku) - 1;
                $newSku = $tmpErpSku[$i];
            }
            $newSku = explode('#', $newSku);
            $newSku = $newSku[0];


            if (strpos($newSku, 'FBA') !== false) {

                $newSku =explode('FBA',$newSku);
                $newSku =$newSku[1];
            }

            $qty = 1;
            if (strpos($newSku, '(') !== false) {
                $matches = array();
                preg_match_all("/(.*?)\([a-z]?([0-9]*)\)?/i", $newSku, $matches);
                $newSku = trim($matches[1][0]);
                $qty = trim($matches[2][0]) ? trim($matches[2][0]) : 1;
            }
            $skuArray = array();
            $skuArray['erpSku'] = $newSku;
            $skuArray['qty'] = $qty;

            $skuNum = $skuNum + $qty;
            $returnSku[] = $skuArray;
        }

        $returnSku['skuNum'] = $skuNum;

        return $returnSku;

    }

    public function base64Decode($content)
    {
        $content = strtr($content, '-_', '+/');
        return base64_decode($content);
    }

    public function base64Encode($content)
    {
        //return $content;
        return rtrim(strtr(base64_encode($content), '+/', '-_'), '=');
    }

    /**
     * 生成随机字符串
     *
     * @access public
     * @param integer $length 字符串长度
     * @param string $specialChars 是否有特殊字符
     * @return string
     */
    public function randString($length, $specialChars = false)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if ($specialChars) {
            $chars .= '!@#$%^&*()';
        }

        $result = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }

    public function getPercent($num)
    {
        return $num . '%';
    }


    public function isCheckedByJson($field, $value, $model = null, $default = false)
    {
        if (old($field) == $value) {
            return 'checked';
        } elseif ($model) {
           $value_array = json_decode($model->$field,true);
            if(in_array($value,$value_array)){
                return 'checked';
            }
        } elseif ($default) {
            return 'checked';
        }
        return false;
    }



    /** 获取对应的erp SKU
     * @param $wishSku
     * @param $type
     * @return string
     */
    public function getErpSkuBySku($sku, $type=1)
    {

        $tmpSku = explode('+', $sku);
        $returnSku = array();
        foreach ($tmpSku as $k => $sku) {
            if (stripos($sku, '[') !== false) {
                $sku = preg_replace('/\[.*\]/', '', $sku);
            }
            if (stripos($sku, '(') !== false) {
                $sku = preg_replace('/\(.*\)/', '', $sku);
            }
            if ($type == 2) {

                $prePart = substr($sku, 0, 1);
                $suffPart = substr($sku, 4);
                $sku = $prePart . $suffPart;
                $newSku = $sku;
            } else {

                $tmpErpSku = explode('*', $sku);
                $i = count($tmpErpSku) - 1;
                $newSku = $tmpErpSku[$i];
            }

            $newSku =explode("#",$newSku);
            $newSku =$newSku[0];
            $returnSku[] = $newSku;

        }

        return implode('+', $returnSku);

    }

    public function getSellCode($wishSku, $type=1)
    {
        if ($type == 2) {

            $tmpErpSku = substr($wishSku, 1, 3);

            return $tmpErpSku;
        } else {

            $tmpErpSku = explode('*', $wishSku);
            return $tmpErpSku[0];
        }


    }

    /** 设置新的后缀
     * @param $sku
     * @param $suffix
     * @return string
     */
    public function getNewEbaySku($sku,$suffix='')
    {
        $tmpSku = explode('+', $sku);
        $returnSku = array();
        foreach ($tmpSku as $k => $sku) {
            if (stripos($sku, '[') !== false) {
                $sku = preg_replace('/\[.*\]/', '', $sku);
            }
            if ($suffix != '') {
                $returnSku[] = $sku . '[' . $suffix . ']';
            } else {
                $returnSku[] = $sku;
            }
        }
        return implode('+', $returnSku);
    }

    //先base64解码，再反序列化
    public function unserializeBase64Decode($data){
        $array = unserialize(base64_decode($data));//先base64解码，再反序列化
        return $array;

    }

    /**
     * Curl Post JSON 数据
     */
    public function postCurlHttpsData($url, $data)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
/*        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );*/
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            // $this->setCurlErrorLog(curl_error ( $curl ));
            die(curl_error($curl)); //异常错误
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }

    /**
     * 获取编辑之后的跳转地址
     * @param $mainIndex
     */
    public function referUrl($mainIndex=null)
    {
        $refer_url = request()->input('refer_url');
        if(! empty($refer_url)){
            return $refer_url;
        } else {
            return $mainIndex;
        }
    }
}