<?php  
namespace App\Modules\Common;
class common_helper{    
    function json_to_array($json){
    	if (!is_string($json)) {
    		return array();
    	}
        $value = json_decode($json,TRUE);
        return $value ? $value : array();
    }
    
    function date_array($start,$end){ 
    
    	$data = array(); 
    	
    	$date = $start; 
    	
    	while ($date <= $end){ 
    	
    	$data[] = $date; 
    	
    	$date = date('Y-m-d',strtotime('+1 day',strtotime($date))); 
    	
    	} 
    	
    	return $data; 
    
    }
    
    /**
     * 生成订单号算法
     *
     * 订单号13位长度（时间绰11+随机数3）
     *
     * @return string
     */
    function genSn()
    {
    	return time().mt_rand(100,999);
    }
    
    /**
     * 删除数组的指定key，如果存在
     * @param array $array
     * @param string $key
     * @return array
     */
    function array_unset_key($array = array(),$key=0){
    	if(array_key_exists($key,$array)){
    		unset($array[$key]);
    	}
    	return $array;
    }
    
    /**
     * php自带的array_merge方法，会将键名为整数的数组重新索引，所以自己写了一个
     * 合并数组,键名重复会覆盖
     */
    function my_array_merge(){
    	$arg_list = func_get_args();
    
    	$returnList = array();
    	foreach ($arg_list as $list){
    		if(!is_array($list))continue;
    		foreach ($list as $k=>$v){
    			$returnList[$k] = $v;
    		}
    	}
    	return $returnList;
    }
    
    //email地址,长度大于6位
    function is_email($email) {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }
    
    //字母开头，允许5-16字节，允许字母数字下划线
    function is_username($username) {
    	
    	if (preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/', $username)) {  
    	
    		return true;  
    	} else { 
    	 
    		return false;  
    	}  
    }
    
    //密码6到16个字符
    function is_password($password) {
        return preg_match("/^[a-zA-Z0-9_.]{6,16}$/", $password);
    }
    
    //json返回数据结构
    function ajax_return($info='', $status=1, $data='') {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit( json_encode($result) );
    }
    
    //随机数
    function random($length, $chars = '2345689abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    
    //时间date
    function datetime($time=0){
    	if(!$time){
    		return '';
    	}
    	return @date('Y-m-d H:i:s', $time);	
    }
    
    //创建目录
    function create_dir($path, $mode = 0777) {
        if( is_dir($path) ) return true;
    
        $dir_name = $path . DIRECTORY_SEPARATOR;
        @mkdir($dir_name, 0777, true);
        @chmod($dir_name, 0777);
        return $dir_name;
    }
    
    function formhash() {
        $hash = md5( random(12) );
        $_codeigniter =& get_instance();
        $_codeigniter->session->set_userdata( array('hash'=>$hash) );
        return $hash;
    }
    
    function checkformhash($ajax = FALSE) {
        /*
        $_codeigniter =& get_instance();
        $hash = $_codeigniter->input->get('hash');
        $sess = $_codeigniter->session->userdata('hash');
        if( ! $ajax ) {
            if($hash !== $sess) ajax_return('', 'Access Denied !', 0);
        } else {
            return ($hash === $sess) ? TRUE : FALSE;
        }
        */
        return TRUE;
    }
    
    //后台动态目录
    function admin_base_url($uri = '',$directory='') {
    	$directory = $directory ? $directory : config_item('site_admin_dir').'/';
    
        return base_url($directory.$uri);
        
        
    }
     
    function site_url($uri = '') {
    
        return base_url($uri);
    }
    
    
    function static_url($uri = '') {
    	
        return base_url(config_item('site_static_dir').'/'. $uri);
    }
    
    function showmessage($content = 'NULL', $continue = 'back', $icon = 'success', $time = 3) {
    
        $time = ((int) $time * 1000);
    
        if ($continue == 'back') {
            $continue = 'history.back()';
        } else {
            $continue = stripos($continue, 'http://') ? 'window.location="' . $continue . '"' : 'window.location="' . site_url($continue) . '"';
        }
    
    //     $list = array('success', 'error', 'warning', 'question');
    
    //     $icon = in_array($icon, $list) ? $icon : $list[0];
    
        $html  = '<!DOCTYPE HTML><html><head><title>提示信息</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<style type="text/css">body{color:#000;font:12px verdana, arial, tahoma;background:#F5F7F6;}';
        $html .= '#box{width:520px;border:1px solid #CCC;background:#FFF;margin:180px ';
        $html .= 'auto;padding:20px 35px 20px 100px;border-radius:5px;box-shadow:0 0 10px #C0C0C0}#box h1{font-size:20px;font-weight:normal}#box a{';
        $html .= 'color:#1A7613;text-decoration:none}</style><script type="text/javascript">function url() { ' . $continue . ' }setTimeout("url()", ' . $time . ')';
        $html .= '</script></head><body><div id="box"><h1>' . $content . '</h1><p>如果浏览器没有自动跳转，请 <a href="javascript:;" onClick="url()">点击这里</a>';
        $html .= '</p></div></body></html>';
    
        exit($html);
    }
    
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;	
        $key = md5($key ? $key : config_item('site_auth_key'));
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
    
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
    
        $result = '';
        $box = range(0, 255);
    
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
    
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
    
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
    
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            $string = $keyc . str_replace('=', '', base64_encode($result));
            return str_replace('=', '', base64_encode($string));
        }
    }
    
    function build_verify ( $string = '', $width = 60, $height = 24, $type = 'PNG' ) {
    
        $length = strlen ( $string );
    
        $width = ($length * 10 + 10) > $width ? ($length * 10 + 10) : $width;
    
        if ( $type != 'GIF' && function_exists('imagecreatetruecolor') ) {
            $im = imagecreatetruecolor ( $width, $height );
        } else {
            $im = imagecreate ( $width, $height );
        }
    
        $backColor = imagecolorallocate ( $im, rand(200, 255), rand(200, 255), rand(200, 255) );
    
        imagefilledrectangle ( $im, 0, 0, $width - 1, $height - 1, $backColor );
    
        $stringColor = imagecolorallocate ( $im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120) );
    
        for ($i = 0; $i < 25; $i++) {
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $stringColor);
        }
    
        for ($i = 0; $i < $length; $i++) {
            imagestring($im, 5, $i * 10 + 10, mt_rand(1, 5), $string{$i}, $stringColor);
        }
    
        // ob_clean();
        header('Content-type: image/' . strtolower($type) );
        $function = 'image' . strtolower($type);
        $function( $im );
        imagedestroy( $im );
    }
    
    //内容过滤
    function filter($str){
    	 $farr = array(
    	   "/\s+/", //过滤多余的空白
    	   "/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU", //过滤 script等恶意代码,还可以加入object的过滤flash
    	   "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU", //过滤javascript的on事件
    	 );
    	 $tarr = array(
    	   " ",
    	   " ", //如果要直接清除不安全的标签，这里可以留空
    	   " ",
    	 );
    	 $str = preg_replace( $farr,$tarr,$str);
    	 return $str;
    }
    
    /*
     *仓库数据返回组装数组
    */	
    function warehouseInfoZuZhuang($warehouseInfo){
    	$reZuZhuangArr = array();
    	$arr		   = array();
    	$arr		   = $warehouseInfo;
    	foreach($arr as $arr_val){
    		$reZuZhuangArr[$arr_val['warehouseID']] = $arr_val['warehouseTitle'];
    	}
    	return $reZuZhuangArr;
    }
    
    //获取权限
    function get_user_permission($data) {
        $pArr = '';
        $GArr = '';
        
        if (is_array($data))
        {
    	    $pArr = $data['permissionId'];
    	    $GArr = $data['group_permission_id'];
    	        
    	    if( $GArr != ''  && $pArr != '' ){//返回用户群和用户的权限		
    	        $pArr = explode ( ",", $pArr );
    	        $GArr = explode ( ",", $GArr );	
    	
    	       return array_unique( array_merge( $pArr, $GArr ) );
    	        
    	    }
    	    if( !empty( $GArr ) && empty($pArr) ){	
    	    
    	        return explode ( ",", $GArr );	
    	        
    	    }
    	    if( !empty($pArr) && empty($GArr) ){
    	    
    	        return explode ( ",", $pArr );
    	        
    	    }
        }
        return array();
    }
    
    //定义数据的状态数组
    function defineProdcutsDataStatus( )
    {
            $arr = array(
                 'selling' => array(
                     'text' => '在售',
                    'canBe' => array( ),
                    'showPrice' => true,
                    'canPurchase' => true,
                    'alert' => true 
                ),
                'sellWaiting' => array(
                     'text' => '待售',
                    'canBe' => array( ),
                    'showPrice' => true,
                    'canPurchase' => true,
                    'alert' => true 
                ),
                //'unSelling' => array('text' => '不可售','canBe' => array(),'showPrice' => false,'canPurchase' => false,'alert' => true),
                'stopping' => array(
                     'text' => '停产',
                    'canBe' => array( ),
                    'showPrice' => false,
                    'canPurchase' => false,
                    'alert' => false 
                ),
                //'stoppingother' => array('text' => '停产-其他原因','canBe' => array(),'showPrice' => false,'canPurchase' => false,'alert' => false),
                //'unSalable_ge' => array('text' => '一般滞销','canBe' => array(),'showPrice' => true,'canPurchase' => false,'alert' => false),
                //'unSalable_se' => array('text' => '严重滞销','canBe' => array(),'showPrice' => true,'canPurchase' => false,'alert' => false),
                'saleOutStopping' => array(
                     'text' => '卖完下架',
                    'canBe' => array( ),
                    'showPrice' => true,
                    'canPurchase' => false,
                    'alert' => false 
                ),
                /*
                'unSellTemp' => array(
                     'text' => '货源待定',
                    'canBe' => array( ),
                    'showPrice' => true,
                    'canPurchase' => true,
                    'alert' => true 
                ),
                */
                //	'sellingTemp' => array('text' => '恢复销售','canBe' => array(),'showPrice' => true,'canPurchase' => true,'alert' => true),
    //             'skuChanged' => array(//取消，suwei --20140701，by fangzheng
    //                  'text' => '改sku下架',
    //                 'canBe' => array( ),
    //                 'showPrice' => false,
    //                 'canPurchase' => false,
    //                 'alert' => false 
    //             ), 
                'trySale' => array(
                     'text' => '试销(卖多少采多少)',
                    'canBe' => array( ),
                    'showPrice' => true,
                    'canPurchase' => true,
                    'alert' => false 
                ),             
                //	'supportSMT' => array('text' => '专供SMT','canBe' => array(),'showPrice' => true,'canPurchase' => true,'alert' => true),
                //	'supportEBAY' => array('text' => '专供EBAY','canBe' => array(),'showPrice' => true,'canPurchase' => true,'alert' => true),
            );
            return $arr;
    }
    
    /**
     * Curl http Get 数据
     * 使用方法：
     * getCurlData('http://www.test.cn/restServer.php');
     */
    function getCurlData($remote_server)
    {
    	$ch = curl_init();
    	curl_setopt( $ch, CURLOPT_URL, $remote_server );
    	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 获取数据返回  
    	curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true ); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
    	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    	$output = curl_exec( $ch );
    	if (curl_errno ($ch))
    	{
    		//die(curl_error ( $ch )); //异常错误
    		return false;
    	}
    	curl_close($ch);
    	return $output;
    }
    
    //定义供应商类型
    function defineSuppliersArrivalMinDays()
    {
    	$arr = array(
    		'3' => array(
    			'text' => '3天',
    			'modulus' => 0.7
    		) ,
    		'5' => array(
    			'text' => '5天',
    			'modulus' => 0.9
    		) ,
    		'7' => array(
    			'text' => '7天',
    			'modulus' => 1.2
    		) ,
    		'10' => array(
    			'text' => '10天',
    			'modulus' => 1.5
    		) ,
    		'14' => array(
    			'text' => '14天',
    			'modulus' => 2.2
    		) ,
    		'20' => array(
    			'text' => '20天',
    			'modulus' => 3.2
    		)
    	);
    	return $arr;
    }
    
    /**
     * 供应商周期系数
     */
    function getStatisticsArr( )
    {
    	$newStatisticsModulus      = explode( '|', SUGGEST_AND_ALERT_STATISTICS_MODULUS );
    	$newStatisticsModulusArray = array( );
    	foreach ( $newStatisticsModulus as $nSM )
    	{
    		$newNSM                      = explode( ':', $nSM );
    		$newStatisticsModulusArray[] = array(
    			'days' => $newNSM[0],
    			'modulus' => $newNSM[1]
    		);
    	}
    	return $newStatisticsModulusArray;
    }
    
    /**
     * 判断SKU的是不是以E开头
     * @param unknown $sku
     */
    function checkProductFirstAlpha($sku)
    {
    	$sku = trim($sku);
    	if (stripos($sku, 'E') === 0) {
    		return true;
    	}
    	return false;
    }
    
    function chkuserper($id, $typ, $usr)
    {
        $permissionArray = get_user_permission($usr);
    
        if (in_array($id, $permissionArray)) {
            return true;
        } else {
            return false;
        }
    }
    
    function defineProductPublishPlatArray( )
        {
            $array = array(
                 array(
                     'platID' => '101',
                    'platTitle' => 'eBay.us',
                    'platType' => 'USD',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '102',
                    'platTitle' => 'eBay.au',
                    'platType' => 'AUD',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '103',
                    'platTitle' => 'eBay.uk',
                    'platType' => 'GBP',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '104',
                    'platTitle' => 'eBay.de',
                    'platType' => 'EUR',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '105',
                    'platTitle' => 'eBay.fr',
                    'platType' => 'EUR',
                    'platTypeID' => '1' 
                ),
                array(
                'platID' => '106',
                    'platTitle' => 'eBay.ca',
                    'platType' => 'C',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '199',
                    'platTitle' => 'eBay.other',
                    'platType' => 'ebay.other',
                    'platTypeID' => '1' 
                ),
                array(
                     'platID' => '201',
                    'platTitle' => 'Amazon.de',
                    'platType' => 'Amazon.de',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '202',
                    'platTitle' => 'Amazon.uk',
                    'platType' => 'Amazon.uk',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '203',
                    'platTitle' => 'Amazon.us',
                    'platType' => 'Amazon.us',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '204',
                    'platTitle' => 'Amazon.ca',
                    'platType' => 'Amazon.ca',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '205',
                    'platTitle' => 'Amazon.fr',
                    'platType' => 'Amazon.fr',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '299',
                    'platTitle' => 'Amazon.other',
                    'platType' => 'Amazon.other',
                    'platTypeID' => '3' 
                ),
                array(
                     'platID' => '301',
                    'platTitle' => 'Aliexpress',
                    'platType' => 'SMT',
                    'platTypeID' => '6' 
                ),
                array(
                     'platID' => '401',
                    'platTitle' => 'DHgate',
                    'platType' => 'DHgate',
                    'platTypeID' => '4' 
                ),
                array(
                     'platID' => '501',
                    'platTitle' => '网站',
                    'platType' => 'B2C',
                    'platTypeID' => '5' 
                )
            );
            return $array;
        }
        
    function accountFormat($string) {
        $string = trim($string);
        $newString = $string;
        if ($string == '速卖通' || $string == '线下交易' || $string == '网站' || $string == 'ebay补货' || strlen($string) <= 4 || substr_count($string, '@') > 0) {
            //
        } else {
            $newString = substr($string, 0, 4) . '****' . substr($string, strlen($string) - 1, 1);
            if ( $string == 'happy-store2013' )  $newString = 'h***st***3';
            if($string=='happyfish2012')$newString='happ**fish**2';
            if($string=='happycow2012')$newString='happ**cow**2';
            if($string=='happywill2013')$newString='happ**will**3';
            if($string=='pandamotos2012')$newString='pand**tos**2';
            if($string=='pandacars2012')$newString='pand**car**2';
            
        }
        return $newString;
    }
    
    //计算2个日期相隔的时间,返回xx小时xx分
    function date_to_date($start_date,$end_date=''){
        
        if(empty($start_date)){
            return '';
        }
        $result = array();
        
        $result['h'] = 0;
        
        $result['m'] = 0;
    
        $start_dates = $start_date;
        
        if(empty($end_date)){
          $end_dates=time();
        }else{
          $end_dates=$end_date;
        }
    
        $time = $end_dates - $start_dates;
    
        $h= $time>=3600 ? floor($time/3600) : 0;//如果时间戳差小于3600，应该显示为0小时
    
        $m=ceil(($time%(3600))/60);
    
        $result['h'] = ($h < 1) ? 0 : $h;
    
        $result['m'] = ($m < 1) ? 0 : $m;
    
        return $result['h'].'小时'.$result['m'].'分';
    
    }
    
    /**
    	 * Curl http Get 数据
    	 * 使用方法：
    	 * getCurlData('http://www.test.cn/restServer.php');
    	 */
    	 function getCurlData2($remote_server,$path='smt') {
    	 	
    		$ch = curl_init ();
    		curl_setopt ( $ch, CURLOPT_URL, $remote_server );
            //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
    		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // 获取数据返回  
    		curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, true ); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
    		$output = curl_exec ( $ch );
    		if (curl_errno ( $ch )) {
    			setCurlErrorLog($path,curl_error ( $ch ));
    			die(curl_error ( $ch )); //异常错误
    		}
    		curl_close ( $ch );
    		return $output;
    	}
    	
    	/**
    	 * Curl http Post 数据
    	 * 使用方法：
    	 * $post_string = "app=request&version=beta";
    	 * postCurlData('http://www.test.cn/restServer.php',$post_string);
    	 */
    	 function postCurlData($remote_server, $post_string,$path='smt') {
    		$ch = curl_init ();
    		curl_setopt ( $ch, CURLOPT_URL, $remote_server );
    		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_string );
    		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    		curl_setopt ( $ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta" );
    		$data = curl_exec ( $ch );
    		if (curl_errno ( $ch )) {
    			setCurlErrorLog($path,curl_error ( $ch ));
    			die(curl_error ( $ch )); //异常错误
    		}
    		curl_close ( $ch );
    		return $data;
    	}
    	
    	/**
    	 * Curl https Post 数据
    	 * 使用方法：
    	 * $post_string = "app=request&version=beta";
    	 * request_by_curl('https://www.test.cn/restServer.php',$post_string);
    	 */
    	function postCurlHttpsData($url, $data,$path='smt') { // 模拟提交数据函数
    		$curl = curl_init (); // 启动一个CURL会话
    		curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
    		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
    		curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
    		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
    		curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
    		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
    		curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
    		curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
    		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    		$tmpInfo = curl_exec ( $curl ); // 执行操作
    		if (curl_errno ( $curl )) {
    			setCurlErrorLog($path,curl_error ( $curl ));
    			die(curl_error ( $curl )); //异常错误
    		}
    		curl_close ( $curl ); // 关闭CURL会话
    		return $tmpInfo; // 返回数据
    	}
    	
    	/**
    	 * 保存设置Curl日志
    	 * 
    	 */
    	 function setCurlErrorLog($path='',$str){	
    	 	$_curlErrorLogPath=$_SERVER['DOCUMENT_ROOT'].'/attachments/'.$path.'/curl/';
    		$time = date ( "Y-m-d H:i:s", time () );
    		$now = date ( "Y-m-d", time () );
    		$nowTime = date ( "H-i-s", time () );
    		$logPath = "$_curlErrorLogPath"."/$now";
    		$is=@mkdir ( $logPath, 0777, true );
    		$logPathUrl = "$logPath/$nowTime.txt";
    		writeDatelog($logPathUrl,"时间：$time 原因：$str");	
    	}
    	
    
    	
    	/**
    	 * 设置订单日志文件路径与文件名
    	 * Enter description here ...
    	 */
    	function setSellerOrderLogPath($type = '',$sellerAccount,$path='') {
    		$now = date ( "Y-m-d", time () );
    		$nowTime = date ( "H-i-s", time () );
    		$orderErrorLogPath=$_SERVER['DOCUMENT_ROOT'].'/attachments/'.$path.'/order/';
    		$logPath = "$orderErrorLogPath".$sellerAccount."/$now";
    		@mkdir ( $logPath, 0777, true );
            chown($logPath, 'nobody');
    		switch ($type) {
    			case "evaluate" :
    				$logPathUrl = "$logPath/Evaluate_$nowTime.txt";
    				break;
    			case "filter" :
    				$logPathUrl = "$logPath/Filter_$nowTime.txt";
    				break;
    			case "error" :
    				$logPathUrl = "$logPath/error_$nowTime.txt";
    				break;
    			case "ship" :
    				$logPathUrl = "$logPath/ship_$nowTime.txt";
    				break;
    			default :
    				$logPathUrl = "$logPath/order_$nowTime.txt";
    				break;
    		}
    		return $logPathUrl;
    	}
    	
    	
    	/**
    	 * 保存日志
    	 * @param string $log
    	 * @param string $text
    	 */
    	 function writeDatelog($log, $text = '',$debug=false) {
    		$fp = fopen ( $log, 'a+' );
    		@fwrite ( $fp, $text );
    		fclose ( $fp );
            //输出日志
            if ( $debug ) echo str_replace("\r\n", "\n", $text);
    	}
    
    /**
     * 解析属性并返回 --这礼现在不解析SKU属性
     * $array:属性数组
     * @param $att:属性数值，循环后的一维数组
     * @param $pid:父属性ID
     * @param bool $isSku:是否SKU属性
     * @param array $array
     * @param array $array2
     * @return string
     */
    function parseAttribute($att, $pid, $isSku=false, $array=array(), $array2=array()){
        $child_string      = ''; //属性值的子属性
        $customized_string = ''; //自定义属性
        $product_attribute = '';
        $required          = $att['required'] ? true : false; //必要？
        $required_string   = $att['required'] ? '<span class="red">*</span>' : '';        //必要属性
        $key_string        = $att['keyAttribute'] ? '<span class="green">！</span>' : '';  //关键属性
        $attribute_string  = ''; //属性显示方式
        $customized_flag   = ($att['customizedName'] || $att['customizedPic']) ? true : false; //自定义
        $other_string      = '';//其他属性
    
        $inputType         = $att['inputType']; //属性值的类型
        $units             = array_key_exists('units', $att) ? $att['units'] : array(); //单位
    
        switch ($att['attributeShowTypeValue']){
            case 'check_box': //复选框
                $attribute_string = '<div class="col-sm-10">';
                $attribute_string .= '<ul class="list-inline">';
                foreach ($att['values'] as $item):
                    $checked = false;
                    if (!empty($array)){
                        foreach ($array as $row){ //判断是否存在这个属性ID和值ID
                            if (empty($row['attrNameId']) || $att['id'] != $row['attrNameId']) continue;
                            if ($row['attrValueId'] == $item['id']) {$checked = true;break;}
                        }
                    }
    
                    $attribute_string .= '<li>';
                    $attribute_string .= '<label class="checkbox-inline">';
                    $attribute_string .= '<input type="checkbox" value="'.($isSku ? $item['id'] : $item['id'].'-'.$item['names']['en']).'" name="'.($isSku ? '' : 'sysAttrValueIdAndValue['.$att['id'].'][]').'" '.($checked ? 'checked' : '').'/>'.$item['names']['zh'];
                    $attribute_string .= '</label>';
                    $attribute_string .= '</li>';
                endforeach;
                $attribute_string .= '</ul>';
                $attribute_string .= '</div>';
                break;
            case 'list_box': //下拉列表
                $attribute_string = '<div class="col-sm-10">';
                $attribute_string .= '<select name="'.($isSku ? '' : 'sysAttrValueIdAndValue['.$att['id']).']" class="form-control" '.($required ? 'datatype="*"' : '').' attr_id="'.$att['id'].'">';
                $attribute_string .= '<option value="">---请选择---</option>';
                foreach ($att['values'] as $item):
                    $checked = false;
                    if (!empty($array)){
                        foreach ($array as $row){
                            if (empty($row['attrNameId']) || $att['id'] != $row['attrNameId']) continue;
                            if ($row['attrValueId'] == $item['id']) {$checked = true;break;}
                        }
                    }
    
                    //lang=0,说明没有子属性了
                    $attribute_string .= '<option value="'.($isSku ? $item['id'] : $item['id'].'-'.$item['names']['en']).'" lang="'.(!empty($item['attributes']) ? 0 : 1).'" attr_value_id="'.$item['id'].'" '.($checked ? 'selected="selected"' : '').'>'.$item['names']['zh'].'('.$item['names']['en'].')'.'</option>';
                    if (!empty($item['attributes'])){ //值还有子属性
                        foreach ($item['attributes'] as $i){
                            $child_string .= parseAttribute($i, $item['id'], $isSku, $array);
                        }
                    }
                    $customized_string .= '<tr class="hide tr-p-'.$att['id'].'-'.$item['id'].'"><td>'.$item['names']['zh'].'</td>'.($att['customizedName'] ? '<td><input type="text" name="customizedName['.$att['id'].'_'.$item['id'].']" /></td>' : '').($att['customizedPic'] ? '<td><a href="javascript: void(0);" class="btn btn-defaut btn-xs">选择图片</a><a href="" class="view-custom-image"></a><a href="" class="del-custom-image">删除</a><input type="hidden" name="customizedPic['.$att['id'].'_'.$item['id'].']" value="" /></td>' : '').'</tr>';
                endforeach;
                $attribute_string .= '</select>';
                $attribute_string .= '</div>';
                if (array_key_exists($att['id'], $array2) && $array2[$att['id']]){
                    $other_string .= '<div class="form-group">';
                    $other_string .= '<div class="col-sm-10 col-sm-offset-2">';
                    $other_string .= '<input type="text" name="otherAttributeTxt['.$att['id'].']" class="form-control" value="'.$array2[$att['id']]['attrValue'].'"/>';
                    $other_string .= '</div>';
                    $other_string .= '</div>';
                }
                break;
            case 'group_table': //复选框 再有子复选框 --待扩展
                $attribute_string = '<div class="col-sm-10">';
                $attribute_string .= '<ul class="list-inline">';
                foreach ($att['values'] as $item):
                    $attribute_string .= '<li class="col-sm-4 no-padding-left groupTab">';
                    $attribute_string .= '<label class="checkbox-inline">';
                    $attribute_string .= '<input type="checkbox" value="'.($isSku ? $item['id'] : $item['id'].'-'.$item['names']['en']).'" name="'.($isSku ? '' : 'sysAttrValueIdAndValue['.$att['id'].']').'"/>'.$item['names']['zh'];
                    $attribute_string .= '</label>';
                    $attribute_string .= '</li>';
                endforeach;
                $attribute_string .= '</ul>';
                $attribute_string .= '</div>';
                break;
            case 'input':
            default:
                //验证信息类型及错误信息
                if ($inputType == 'NUMBER') {
                    $dataType = 'num';
                } else {
                    $dataType = '*';
                }
                //看看是否有单位
                $inputValue = filterData($att['id'], $array2) ? $array2[$att['id']]['attrValue'] : '';
                if ($units) {
                    $input = '';
                    $u = '';
                    if ($inputValue){
                        list($input, $u) = explode(' ', $inputValue);
                    }
    
                    $attribute_string = '<div class="col-sm-8">';
                    $attribute_string .= '<input type="text" class="form-control" name="sysAttrIdAndValueName[' . $att['id'] . ']" ' . ($dataType ? 'datatype="' . $dataType . '" ' : ' ') . ($required ? '' : 'ignore="ignore" ') . ($dataType == 'n' ? 'errormsg="请输入数字" ' : '') . ' value="'.$input.'" />';
                    $attribute_string .= '</div>';
    
                    //单位处理
                    $attribute_string .= '<div class="col-sm-2">';
                    $attribute_string .= '<select name="sysAttrIdAndUnit['.$att['id'].']" class="form-control">';
                    foreach ($units as $unit){
                        $attribute_string .= '<option value="'.$unit['unitName'].'" '.($u == $unit['unitName'] ? 'selected="selected"' : '').'>'.$unit['unitName'].'</option>';
                    }
                    $attribute_string .= '</select>';
                    $attribute_string .= '</div>';
                } else {
                    $attribute_string = '<div class="col-sm-10">';
                    $attribute_string .= '<input type="text" class="form-control" name="sysAttrIdAndValueName[' . $att['id'] . ']" ' . ($dataType ? 'datatype="' . $dataType . '" ' : ' ') . ($required ? '' : 'ignore="ignore" ') . ($dataType == 'n' ? 'errormsg="请输入数字" ' : '') . ' value="' .$inputValue. '" />';
                    $attribute_string .= '</div>';
                }
                break;
        }
        $product_attribute .= '<div class="form-group p-'.$pid.' '.($pid > 0 && !filterData($att['id'], $array) ? 'hide' : '').' '.($isSku ? 's_attr' : 'p_attr').'" attr_id="'.$att['id'].'" custome="'.(($att['customizedName'] || $att['customizedPic']) ? '1' : '0').'">';
        $product_attribute .= '<label class="col-sm-2 control-label">'.$required_string.$key_string.$att['names']['zh'].'：</label>';
        $product_attribute .= $attribute_string;
        $product_attribute .= '</div>';
        
        //还要添加些内容，比如自定义属性的设置
        if($customized_flag){ //自定义名称或者图片
            $product_attribute .= '<div class="form-group hide">';
            $product_attribute .= '<div class="col-sm-offset-2 col-sm-10">';
            $product_attribute .= '<table class="table table-bordered table-vcenter" id="custome-'.$att['id'].'">';
            $product_attribute .= '<thead><tr><th>'.$att['names']['zh'].'</th>'.($att['customizedName'] ? '<th>自定义名称</th>' : '').($att['customizedName'] ? '<th>图片（无图片可以不填）</th>' : '').'</tr></thead>';
            $product_attribute .= '<tbody>'.$customized_string.'</tbody>';
            $product_attribute .= '</table>';
            $product_attribute .= '</div>';
            $product_attribute .= '</div>';
        }
        
        return $product_attribute.$other_string.$child_string;
    }
    
    /**
     * 解析SKU属性 --应该都是check_box这个类型的
     * @param $att   属性数组
     * @param $array 多属性的值列表
     * @param $token_id 账号
     * @return string
     */
    function parseSkuAttribute($att, $array, $token_id){
        $child_string      = ''; //属性值的子属性
        $customized_string = ''; //自定义属性
        $product_attribute = '';
        $required          = $att['required'] ? true : false; //必要？
        $required_string   = $att['required'] ? '<span class="red">*</span>' : '';         //必要属性
        $key_string        = $att['keyAttribute'] ? '<span class="green">！</span>' : '';  //关键属性
        $customized_flag   = ($att['customizedName'] || $att['customizedPic']) ? true : false; //自定义
    
        $attribute_string = '<div class="col-sm-10">';
        $attribute_string .= '<ul class="list-inline">';
        foreach ($att['values'] as $k => $item):
            $attribute_string .= '<li>';
            $attribute_string .= '<label class="checkbox-inline">';
            $attribute_string .= '<input type="checkbox" name="'.$att['id'].'" value="'.$item['id'].'" '.(array_key_exists($item['id'], filterData($att['id'], $array, true)) ?  'checked' : '').(($required && $k == 0) ? ' datatype="*" nullmsg="'.$att['names']['zh'].'不能为空"' : '').' />'.$item['names']['zh'];
            $attribute_string .= '</label>';
            $attribute_string .= '</li>';
            //自定义名称或图片
            $customized_string .= '<tr class="'.(array_key_exists($item['id'], filterData($att['id'], $array, true)) ? '' : 'hide').' tr-p-'.$att['id'].'-'.$item['id'].'">'
            .'<td>'.$item['names']['zh'].'</td>'
            .($att['customizedName'] ? '<td><input type="text" name="customizedName['.$att['id'].'_'.$item['id'].']" value="'.(filterData($att['id'], $array, true) && filterData($item['id'], $array[$att['id']]) ? $array[$att['id']][$item['id']]['propertyValueDefinitionName'] : '').'" /></td>' : '')
            .'<td><span class="customize-pic pull-right">'
            .((filterData($att['id'], $array) && filterData($item['id'], $array[$att['id']]) && $array[$att['id']][$item['id']]['skuImage']) ? '<img src="'.$array[$att['id']][$item['id']]['skuImage'].'" width="30" height="30" /><a href="javascript: void(0);" class="del-custom-image">删除</a>' : '').'</span>'
            .'<a href="javascript: void(0);" class="btn btn-default btn-xs add-custom-image pull-left" lang="'.$att['id'].'_'.$item['id'].'">选择图片</a>'
            .'<a href="javascript: void(0);" class="btn btn-default btn-xs copyToCust" onclick="copyToHere(this, \'pic-detail\', \''.admin_base_url("publish/smt/ajaxUploadOneCustomPic?token_id=$token_id").'\');">详情图片</a>'
            .'<input type="hidden" class="customized-pic-input customizedPic-'.$att['id'].'_'.$item['id'].'" name="customizedPic['.$att['id'].'_'.$item['id'].']" value="'.((filterData($att['id'], $array) && filterData($item['id'], $array[$att['id']]) && $array[$att['id']][$item['id']]['skuImage']) ? $array[$att['id']][$item['id']]['skuImage'] : '').'" /></td>'
            .'</tr>';
        endforeach;
        $attribute_string .= '</ul>';
        $attribute_string .= '</div>';
    
        $product_attribute .= '<div class="form-group  s_attr" attr_id="'.$att['id'].'" custome="'.(($att['customizedName'] || $att['customizedPic']) ? '1' : '0').'">';
        $product_attribute .= '<label class="col-sm-2 control-label">'.$required_string.$key_string.$att['names']['zh'].'：</label>';
        $product_attribute .= $attribute_string;
        $product_attribute .= '</div>';
    
        //还要添加些内容，比如自定义属性的设置
        if($customized_flag){ //自定义名称或者图片
            $product_attribute .= '<div class="form-group '.(filterData($att['id'], $array) ? '' : 'hide').'">';
            $product_attribute .= '<div class="col-sm-offset-2 col-sm-10">';
            $product_attribute .= '<table class="table table-bordered table-vcenter" id="custome-'.$att['id'].'">';
            $product_attribute .= '<thead><tr><th>'.$att['names']['zh'].'</th>'.($att['customizedName'] ? '<th>自定义名称</th>' : '').($att['customizedName'] ? '<th>图片（无图片可以不填）</th>' : '').'</tr></thead>';
            $product_attribute .= '<tbody>'.$customized_string.'</tbody>';
            $product_attribute .= '</table>';
            $product_attribute .= '</div>';
            $product_attribute .= '</div>';
        }
    
        return $product_attribute.$child_string;
    }
    
    function accounterFormat($string) {
        $string = trim($string);
        $newString = $string;
        if ($string == '速卖通' || $string == '线下交易' || $string == '网站' || $string == 'ebay补货' || strlen($string) <= 4 || substr_count($string, '@') > 0) {
            //
        } else {
            $newString = substr($string, 0, 4) . '****' . substr($string, strlen($string) - 1, 1);
            if ( $string == 'happy-store2013' )  $newString = 'h***st***3';
            if($string=='happyfish2012')$newString='happ**fish**2';
            if($string=='happycow2012')$newString='happ**cow**2';
            if($string=='happywill2013')$newString='happ**will**3';
            if($string=='pandamotos2012')$newString='pand**tos**2';
            if($string=='pandacars2012')$newString='pand**car**2';
            
        }
        return $newString;
    }
    
    /**
     * 对速卖通的SKU属性进行排序处理 --基本属性不进行处理
     * @param $attribute
     * @return mixed
     */
    function sortAttribute($attribute){
        if ($attribute) {
            $spec = array();
            $temp = array();
            foreach ($attribute as $key => $row) {
                if ($row['sku']){ //是SKU属性
                    $spec[$key] = $row['spec']; //用来排序的数组
                    $temp[$key] = $row;
                    unset($attribute[$key]);
                }
            }
            array_multisort($spec, SORT_ASC, $temp); //对SKU属性数组进行排序
            $attribute = array_merge($attribute, $temp); //合并排序后的信息
        }
        return $attribute;
    }
    
    /**
     * 多个数组的笛卡儿积
     * @param $data
     * @return array
     */
    function combineDika($data)
    {
        $cnt    = count($data);
        $result = array();
        if ($cnt == 0) return $result;
        $result = $data[0];
        for ($i = 1; $i < $cnt; $i++) {
            $result = combineArray($result, $data[$i]);
        }
        return $result;
    }
    
    /**
     * 两个数组的笛卡尔积
     * @param $arr1
     * @param $arr2
     * @return array
     */
    function combineArray($arr1,$arr2) {
        $result = array();
        if (!empty($arr1)) {
            foreach ($arr1 as $item1) {
                if (!empty($arr2)) {
                    foreach ($arr2 as $item2) {
                        $result[] = $item1 . '_' . $item2;
                    }
                } else {
                    $result[] = $item1 . '_';
                }
            }
        } else {
            if (!empty($arr2)) {
                foreach ($arr2 as $item2) {
                    $result[] = '_' . $item2;
                }
            } else {
                $result[] = '_';
            }
        }
    
        return $result;
    }
    
    /**
     * SKU列表显示排序
     * @param $skus
     * @param $sortAtt
     * @param $attVal
     * @return array
     */
    function sortSkuAttr($skus, $sortAtt, $attVal){
        $newSkus = array();
        $sKarr = array(); //所有键值
        foreach ($skus as $sku){
            $aeopSKUProperty = unserialize($sku['aeopSKUProperty']);
    
            //tr class合成
            $trClassArr = array();
            foreach ($sortAtt as $k1 => $sa){
                $matchFlag = false;
                $saVal = '';
                foreach ($aeopSKUProperty as $Property){
                    if ($sa == $Property['skuPropertyId']){ //分类信息
                        $saVal = $Property['propertyValueId'];
    
                        foreach ($attVal[$sa] as $k2 => $av){
                            if ($av == $saVal){
                                $sKarr[$k1][$k2] = $saVal;
                                break;
                            }
                        }
                        $matchFlag = true;
                        break;
                    }
                }
                $trClassArr[] = $saVal;
    
                if (!$matchFlag) $sKarr[$k1] = array();
                ksort($sKarr[$k1]);
            }
            $skus[implode('_', $trClassArr)] = $sku;
        }
    
        $newTrSortArr = combineDika($sKarr); //行显示的排序数组
    
        foreach ($newTrSortArr as $ns){
            if (array_key_exists($ns, $skus)){
                $newSkus[] = $skus[$ns];
            }
        }
    
        return $newSkus; //返回排序后的新的SKU列表
    }
    
    
    /**
     * 获取速卖通销售前缀
     * @param $sku
     * @return string
     */
    function get_skucode_prefix($sku)
    {
        $len    = 0;
        $prefix = '';
        if (($len = stripos($sku, '*')) > 0) {
            $prefix = substr($sku, 0, $len);
        }
    
        return strtoupper(trim($prefix));
    }
    
    /**
     * 获取定义的平台信息
     * @param string $platType
     * @return array
     */
    function getDefinedPlatInfo($platType = 'SMT'){
        $platArray = defineProductPublishPlatArray();
        foreach ($platArray as $plat){
            if (strtoupper($plat['platType']) == strtoupper($platType)){
                return $plat;
            }
        }
        return array();
    }
    
    /**
     * 标点过滤函数 --主要是针对关键字
     * @param unknown $str
     * @return unknown
     */
    function filterForSmtProduct($str){
        $str = str_replace(';', ' ', $str);
        $str = str_replace(',', ' ', $str);
        return trim($str);
    }
    
    /**
     * 过滤速卖通SKU，去除*前的部分和#后的部分
     * @param $sku
     * @return string
     */
    function filterSmtProductSku($sku){
        $skuTemp  = trim($sku);
        $skuTempA = (strpos($skuTemp,"*") !== false) ? strpos($skuTemp,"*") : -1;
        $skuTempB = (strpos($skuTemp,"#") !== false) ? strpos($skuTemp,"#") : strlen($skuTemp);
        $skuTemp  = substr($skuTemp,$skuTempA+1,$skuTempB-$skuTempA-1);
        return trim($skuTemp);
    }
    
    /**
     * 长字符串逗号后边加空格，方便bootstrap自动分行显示
     * @param $str
     * @return mixed
     */
    function replaceDotToShow($str){
        return str_replace(',', ', ', $str);
    }
    
    /**
     * 递归解析速卖通图片银行分组成下拉列表
     * @param        $group_list
     * @param        $selected
     * @param string $indent
     * @return string
     */
    function parsePhotoGroupArray($group_list, $selected, $indent=''){
        $str = '';
        if ($group_list){
            foreach ($group_list as $group){
                $str .= '<option value="'.$group['groupId'].'" '.($group['groupId'] == $selected ? 'selected="selected"' : '').'>'.$indent.$group['groupName'].'</option>';
                if ($group['child']){
                    $newIndent = $indent.'&nbsp;&nbsp;&nbsp;&nbsp;';
                    $str .= parsePhotoGroupArray($group['child'], $selected, $newIndent);
                }
            }
        }
        return $str;
    }
    
    /**
     * 过滤输出速卖通刊登的数据,排除Notice错误
     * @param $key
     * @param $data
     * @param $returnArray:是否返回数组
     * @return string
     */
    function filterData($key, $data, $returnArray=false){
        return $data && array_key_exists($key, $data) ? $data[$key] : ($returnArray ? array() : '');
    }
    
    /**
     * 把SMT的module替换成图片，这样才能显示成图片的占位符
     * @param $detail
     * @return mixed
     */
    function replaceSmtModuleToImg($detail){
        preg_match_all('/<kse:widget.*><\/kse:widget>/i', $detail, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $m) {
                $pic    = '<img class="kse-widget" data="' . rawurlencode($m) . '" src="http://style.aliexpress.com/js/5v/lib/kseditor/plugins/widget/images/widget1.png"/>';
                $detail = str_replace($m, $pic, $detail);
                unset($pic);
            }
        }
        return $detail;
    }
    /**
     * 替换SMT描述的特殊图片成module:   ske:widget
     * @param $str
     * @return mixed
     */
    function replaceSmtImgToModule($str){
        $detail = $str;
        preg_match_all('/<img\s*[^>]*class=\s*\"\s*kse-widget\s*\"[^>]*\/>/i', $str, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                //匹配出属性的值
                preg_match('/data\s*=\s*"([^>^"]*)"/i', $match, $data);
                $widget = rawurldecode($data[1]);
                $detail = str_replace($match, $widget, $detail);
                unset($data);
            }
            unset($matches);
        }
        return $detail;
    }
    
    /**
     * 过滤速卖通产品信息模块
     * @param $str 产品详情信息
     * @return mixed
     */
    function filterSmtRelationProduct($str){
        preg_match_all('/<kse:widget.*><\/kse:widget>/i', $str, $matches);
        if (!empty($matches[0])){
            foreach($matches[0] as $widget){
                $str = str_replace($widget, '', $str);
            }
        }
        return $str;
    }
    
    function defineSuppliersDataStatus() //定义数据的状态数组
    {
        $arr = array(
                'newData' => array(
                        'text' => '待审核',
                        'canBe' => array(
                                'unPassed',
                                'currentData'
                        )
                ) ,
                'confirmModify' => array(
                        'text' => '待复审',
                        'canBe' => array(
                                'currentData',
                                'unPassed'
                        )
                ) ,
                'unPassed' => array(
                        'text' => '不通过',
                        'canBe' => array(
                                'edit'
                        )
                ) ,
                'currentData' => array(
                        'text' => '已通过',
                        'canBe' => array(
                                'everyThing',
                                'unPassed',
                                'newData',
                                'confirmModify',
                        )
                )
        );
        return $arr;
    }
    
    //供应商管理 付款方式
    function payment_method() {
        $payment[] = array(
                'key' => 1,
                'text' => '网上付款',
                'color' => '#ff0000'
        );
        $payment[] = array(
                'key' => 2,
                'text' => '银行付款',
                'color' => '#ff9900'
        );
        $payment[] = array(
                'key' => 3,
                'text' => '现金付款',
                'color' => '#000000'
        );
        $payment[] = array(
                'key' => 4,
                'text' => '其它方式',
                'color' => '#000000'
        );
        return $payment;
    }
    
    // 多维数组去空格
    function arr_trim($arr)
    {
        $data = array();
        foreach ($arr as $k => $v) {
            if (is_array($v))
            {
                $data[$k] = arr_trim($v);
            }else {
                $data[$k] = trim($v);
            }
            
        }
        return $data;
    }
    
    
    //比利时邮政中的挂号码处理方法
    function generate_shipping_code( $order_id )
        {
            //mini pack
            $str = '3320137328002';
            $str .= str_pad($order_id, 15, '0', STR_PAD_LEFT);
            $x = bcmod($str, 97);
            if ( $x == '0' ) $x = '97';
            if ( strlen($x) < 2 ) $x = str_pad($x, 2, '0', STR_PAD_LEFT);
            
            return $str.$x;             
        }
    
    //获取随机浮点数
    function random_float ($min,$max) {
       return ($min+lcg_value()*(abs($max-$min)));
    }
    
    
    
    function jatoolsPrinter_do_print(){
        
    	$funjs = $_SERVER['HTTP_HOST'] == '120.24.100.157:72'?  static_url('theme/lodop6194/LodopFuncs.js') : static_url('theme/lodop6194/LodopFuncs_v2.js');
    
        $str ='<script src="'.$funjs.'"></script>';
    
        $str .='<OBJECT  ID="jatoolsPrinter" CLASSID="CLSID:B43D3361-D075-4BE2-87FE-057188254255" codebase="'.static_url('theme/jatoolsPrinter/jatoolsPrinter.cab').'#version=8,6,0,0"></OBJECT>';
    
        $str .='<script>
                    function PrintOneURL(url){
        
                        var tof = false;
    
                        LODOP=getLodop();  
                        LODOP.PRINT_INIT();
                        LODOP.SET_PRINT_PAGESIZE();
                        LODOP.ADD_PRINT_URL(0,0,"100mm","100mm",url);
                        //LODOP.SET_PRINT_STYLEA(0,"HOrient",3);
                        //LODOP.SET_PRINT_STYLEA(0,"VOrient",3);
                    //  LODOP.SET_SHOW_MODE("MESSAGE_GETING_URL",""); //该语句隐藏进度条或修改提示信息
                    //  LODOP.SET_SHOW_MODE("MESSAGE_PARSING_URL","");//该语句隐藏进度条或修改提示信息
                        tof = LODOP.PRINT();
                        return tof;
    
                    };
    
                    function print_pdf(url,type){
                        //jatoolsPrinter.printDocument("http://120.24.100.157:72/YC440037234YW.pdf");
                        
                        if(type == 1){
                            PrintOneURL(url);
                            /*
                            var myDoc ={ 
                                //文档页可以在url指定的一个文档中,用数组指定打印文档
                                documents: [url],
                                copyrights  :    "杰创软件拥有版权  www.jatools.com"// 版权声明必须
                            };
                            jatoolsPrinter.print(myDoc ,false);
                            */
                        }else{
                            jatoolsPrinter.printDocument(url); 
                        }
                        
                    }
                </script>';
    
        echo $str;        
    }
    
    //将pdf转换为图片
    function pdf_jpg($file,$new_file){
    
        $imagick = new Imagick();
        $imagick->setResolution(500, 500);
        $imagick->readImage($file);
        //$imagick->thumbnailImage(370,370);
        //$imagick->scaleImage(370,370,true);
        $tof = $imagick->writeImages($new_file, false);
       
        return $tof;
    
    }
    
    /**
     * 显示速卖通关联产品的标题
     * @param $subject
     * @return string
     */
    function showSubject($subject){
        if (strlen($subject) > 50){
            $subject = mb_substr($subject, 0, 44, 'utf-8').'......';
        }
        return $subject;
    }
    
    /**
     * 定义速卖通允许上传的图片类型
     * @return array
     */
    function defineSmtImageExd(){
        return array(
            'gif', 'jpeg', 'jpg', 'png'
        );
    }
    
    /**
     * 定义wish允许上传的图片类型
     * @return array
     */
    function defineWishImageExd(){
        return array(
            'gif' , 'jpg', 'png'
        );
    }
    
    /**
     * 获取文件的扩展名
     * @param $file
     * @return mixed|string
     */
    function getFileExtendName($file){
        $exd = '';
        if (stripos($file, '.') !== false){
            $temp = explode('.', $file);
            $exd = array_pop($temp);
        }
        return $exd;
    }
    
    /**
     * 获取SKU属性中的海外仓发货属性ID,没有就返回0
     * @param $aeopSKUProperty
     * @return int
     */
    function checkProductSkuAttrIsOverSea($aeopSKUProperty){
        $valId = 0;
        if (!empty($aeopSKUProperty)){
    
            foreach ($aeopSKUProperty as $property){
                if ($property['skuPropertyId'] == 200007763){ //发货地的属性ID
                    $valId = $property['propertyValueId'];
                    break;
                }
            }
        }
        return $valId;
    }
    
    /**
     * 对SMTSKU进行去前后缀处理
     * @param $smtSkuCode
     * @param $erpFlag:是否解析成ERPSKU
     * @return string
     */
    function rebuildSmtSku($smtSkuCode, $erpFlag=false){
        // 去掉SKU的销售代码
        $n = strpos($smtSkuCode, '*');
        $sku_new = $n !== false ? substr($smtSkuCode, $n+1) : $smtSkuCode;
    
        // 去除sku的帐户代码
        $m = strpos($sku_new, '#');
        $sku_new = $m !== false ? substr($sku_new, 0, $m) : $sku_new;
        if ($erpFlag) {
            $sku_new = str_ireplace('{YY}', '', $sku_new);
        }
        return trim($sku_new);
    }
    
    /**
     * 解析SKU成ERP内的SKU
     * @param $skuCode
     * @param $erpFlag:解析成ERP SKu(主要是去掉海外仓的标识)
     * @return array
     */
    function buildSysSku($skuCode, $erpFlag=false){
        // 处理带销售代码的SKU：B702B#Y6 及海外仓标识
        $skus = $this->rebuildSmtSku($skuCode, $erpFlag);
    
        $sku_list = explode('+', $skus); // 处理组合的SKU：DA0090+DA0170+DA0137
        $sku_arr  = array();
        foreach ($sku_list as $value) {
            $len       = strpos($value, '('); // 处理有捆绑的SKU：MHM330(12)
            $sku_new   = $len ? substr($value, 0, $len) : $value;
            $sku_arr[] = $sku_new;
        }
        return !empty($sku_arr) ? $sku_arr : array();
    }
    
    // 解析ebaySKU信息
    function resetTransactionDetail($array) {
    	$newArray = array();
    	if ($array) {
    		foreach ($array as $row) {
    			//1.先去掉'+'
    			$tmpSkuArray = explode('+', $row['sku']);
    			$tmpCount    = count($tmpSkuArray); //SKU种类总数
    			foreach ($tmpSkuArray as $tmpSku) {
    				//先用一个数组保存最原始的一维数组信息
    				$data = $row;
    				$data['sku'] = $tmpSku; //SKU信息暂时已变更，重新赋值下就行
    				$data['price'] = round($data['price'] / $tmpCount, 2); //组合SKU的单价平均处理
    
    				//2.再去掉‘*’,可以直接取星号之后的部分
    				$tmp = explode('*', $tmpSku);
    				$tmpSku = trim(array_pop($tmp));
    
    				//3.忽略中括号内的信息
    				if (stripos($tmpSku, '[') !== false) {
    					$tmpSku = preg_replace('/\[.*\]/', '', $tmpSku);
    				}
    
    				//4.处理小括号及其单价数量
    				if (stripos($tmpSku, '(') !== false) {
    					$sku = trim(getStringBetween($tmpSku, '', '('));
    					$qty = trim(getStringBetween($tmpSku, '(', ')'));
    					$data['sku'] = $sku;
    					$data['count'] = $qty * $data['count'];
    					$data['price'] = round($data['price'] / $qty, 2);
    					$newArray[] = $data;
    				}else {
    					$data['sku'] = trim($tmpSku);
    					$newArray[] = $data;
    				}
    			}
    		}
    	}
    	return $newArray;
    }
    
    function getStringBetween($string, $start = '', $end = '') //取从某个字符首次出现的位置开始到另一字符首次出现的位置之间的字符串
    {
    	//$s = ($start != '') ? stripos($string,$start)+1 : 0 ;$e = ($end != '' ) ? stripos($string,$end) : strlen($string) ;
    	//if($s <= $e){return substr($string,$s,$e-$s);}else{return false;}
    	$s = ($start != '') ? stripos($string, $start) : 0;
    	$e = ($end != '') ? stripos($string, $end) : strlen($string);
    	if ($s <= $e) {
    		$string = substr($string, $s, $e - $s);
    		return str_replace($start, '', $string);
    	} else {
    		return false;
    	}
    }
    
    //计算2个日期相隔的天数
    function count_day_date_to_date($start,$end){
    
        $start = strtotime($start);
    
        $end = strtotime($end);
    
        $tmp = $end - $start;
    
        //转换为天
        $day = round($tmp/(24*3600),2);
    
        return $day;
    
    }
    //计算2个日期相隔的天数 小时 分钟
    function count_date_to_date($enddate,$num){
        $startdate=time();
        $enddate=strtotime($enddate)+$num*24*60*60+16*60*60;
        $date=floor((($enddate)-($startdate))/86400);
        if($date<0){
            return '需到后台处理';
        }
        if($date>= 1)
        {
            $date_mid  = floor((($enddate)-($startdate))%86400);
        }else{
            $date = 0;
            $date_mid = ($enddate)-($startdate);
        }
    
        $hour =floor($date_mid/3600);
    
        if($hour>=1)
        {
            $minute = floor($date_mid%3600);
            $minute = floor($minute/60);
    
        }else{
            $hour = 0;
            $minute = floor($date_mid/60);
        }
    
      //  $hour=floor((($enddate)-($startdate))%86400/3600);
      //  $minute=floor((($enddate)-($startdate))%86400);
    
        return $date.'天 '.$hour.'小时 '.$minute.'分';
    }
    function arrayToObject($e){
        if( gettype($e)!='array' ) return;
        foreach($e as $k=>$v){
            if( gettype($v)=='array' || getType($v)=='object' )
                $e[$k]=(object)arrayToObject($v);
        }
        return (object)$e;
    }
    
    function objectToArray($e){
        $e=(array)$e;
        foreach($e as $k=>$v){
            if( gettype($v)=='resource' ) return;
            if( gettype($v)=='object' || gettype($v)=='array' )
                $e[$k]=(array)objectToArray($v);
        }
        return $e;
    }
    
    function filterDataEbay($key, $data, $returnArray=false){
            return $data && array_key_exists($key, $data) ? $data[$key] : ($returnArray ? array() : '');
    }
}
?>