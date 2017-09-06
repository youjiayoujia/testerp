<?php
namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtCategoryModel;
use App\Models\Publish\Smt\smtProductList;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use Illuminate\Support\Facades\Input;

use App\Models\Publish\Smt\smtServiceTemplate;
use App\Models\Publish\Smt\smtProductGroup;
use App\Models\Publish\Smt\smtProductUnit;
use App\Models\Publish\Smt\smtProductModule;
use App\Models\Publish\Smt\afterSalesService;
use App\Models\Publish\Smt\smtFreightTemplate;
use App\Models\Publish\Smt\smtCategoryAttribute;
use App\Models\Publish\Smt\smtTemplates;
use App\Models\Publish\Smt\smtUserSaleCode;
use Illuminate\Support\Facades\DB;
use App\Models\Publish\Smt\smtProductDetail;
use App\Models\Publish\Smt\smtProductSku;
use App\Models\Publish\Smt\smtCopyright;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use App\Models\ProductModel;
use App\Modules\Common\common_helper;
use App\Models\SkuPublishRecords;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemModel;
use App\Models\LogisticsModel;
use App\Models\Publish\Smt\smtPriceTaskMain;



class SmtController extends Controller{
   public function __construct(smtProductList $smtProductList,smtProductUnit $smtProductUnit){ 
       $this->viewPath = "publish.smt.";
       $this->model = $smtProductList;
       $this->smtProductDetailModel = new smtProductDetail();
       $this->smtProductSkuModel = new smtProductSku();
       $this->smtProductUnitModel = $smtProductUnit;
       $this->channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
       $this->mainIndex = route('smt.index');
       $this->mainOnlineIndex = route('smt.onlineProductIndex');
   }
   
   /**
    * 草稿列表
    */
   public function index(){
       request()->flash();
       $this->mainTitle='SMT产品草稿';
       $list = $this->model->where('productStatusType','newData');
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'data' => $this->autoList($this->model,$list),
           'mixedSearchFields' => $this->model->mixed_search,
           'type' => 'newData',
           'token'=> $this->model->account_info,
       ];
       return view($this->viewPath . 'index', $response);
   }
   
   /**
    * 待发布产品列表
    * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
    */
   public function waitPostList(){
       request()->flash();
       $this->mainTitle='SMT待发布产品';
       $list = $this->model->where('productStatusType','waitPost');
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'data' => $this->autoList($this->model,$list),
           'mixedSearchFields' => $this->model->mixed_search,
           'type' => 'waitPost',
       ];
       return view($this->viewPath . 'index', $response);
   }
   
   /**
    * 在线产品列表
    */
   public function onlineProductIndex(){
       request()->flash();
       $this->mainTitle='SMT在线产品';
       $list = $this->model->whereIn('productStatusType',['onSelling','offline','auditing','editingRequired']);
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'data' => $this->autoList($this->model,$list),
           'mixedSearchFields' => $this->model->mixed_search,
           'accountList' => AccountModel::where('channel_id',$this->channel_id)->get(),
           'categoryList' => smtCategoryModel::where('pid',0)->get(),
       ];
       $response['mixedSearchFields']
       ['filterSelects'] = [
           'token_id' => $this->model->getAccountNumber('App\Models\Channel\AccountModel','alias'),
           'productStatusType' => config('smt_product.productStatusType'),
       ];
      
       return view($this->viewPath . 'onlinIndex', $response);
   }
      
   public function create(){   
       $this->mainTitle='SMT产品';
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'account'=>AccountModel::where('channel_id',$this->channel_id)->get(),
           'category_info' => smtCategoryModel::where('pid',0)->get(),
       ];
       return view($this->viewPath . 'create',$response);            
   }
   
   /**
    * 新增产品基础数据渲染
    */
   public function addProduct(){
        $this->mainTitle='SMT产品新增';
        $smtCategoryModel = new smtCategoryModel;
        $category_id = Input::get('category_id');
        $token_id = Input::get('token_id');
        
        $category_info = $smtCategoryModel->getCateroryAndParentName($category_id);
        
        //查询选择的分类的属性
        $attributes = smtCategoryAttribute::where('category_id',$category_id)->first();
        $category_attributes = array();
        $account = AccountModel::findOrFail($token_id);
        $smtApi = Channel::driver($account->channel->driver, $account->api_config);
        if (!$attributes){ //属性直接不存在
            $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id,$category_id);
            if ($return)
                $category_attributes = $return;
        }else { //属性存在但不是最新的         
            $category_attributes = unserialize($attributes->attribute);
            
            //这个属性今天还没更新呢，更新下吧
            if (!$attributes->last_update_time || date('Y-m-d') != date('Y-m-d', strtotime($attributes->last_update_time))) {
                $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id, $category_id);
                if ($return)
                    $category_attributes = $return;
            }                                
        }
    
        //对属性进行排序处理
        $category_attributes = $smtApi->sortAttribute($category_attributes);
        
        //获取运费模版
        $freight = smtFreightTemplate::where('token_id',$token_id)->get();
       
        //服务模板
        $service = smtServiceTemplate::where('token_id',$token_id)->get();
        
        //产品分组
        $product_group = new SmtProductController(); 
        $group = $product_group->getLocalProductGroupList($token_id); 
        
        //单位列表
        $unit = $this->smtProductUnitModel->getAllUnit();
        
        //产品模板
        $module = smtProductModule::where('token_id',$token_id)->get();
        
        //速脉通模板及售后模板
        $plat = 6;    
        $template_list = smtTemplates::where('plat',$plat)->get();
        $shouhou_list  = afterSalesService::where(['plat'=>$plat,'token_id'=>$token_id])->get();
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'category_info' => $category_info,
            'attributes' => $category_attributes,
            'freight' => $freight,
            'service' => $service,
            'group' => $group,
            'productUnit' => $unit,
            'module' => $module,
            'productTemplate' => array(),
            'draft_info'      => array(),
            'draft_skus'      => array(),
            'draft_detail'    => array(),
            'shouhou_list' => $shouhou_list,
            'template_list'   => $template_list,
            'token_id' => $token_id,
            'category_id' => $category_id,
            'action' => 'add',
            'api' => 'post',
        
        ];
        return view($this->viewPath . 'addProduct',$response);
        
   }
      
   public function doAction(){
       $action = Input::get('action');
  
        if ($action == 'save') { //保存
            return $this->save($action,true);
        } elseif ($action == 'post') { //发布     
            return $this->post();
        } elseif ($action == 'saveToPost') { //保存为待发布
            return  $this->save($action,true);
        }elseif ($action == 'editAndPost'){ //编辑并发布        	
            return $this->editAndPost(); //修改在线广告
        } else { //都不是以上操作
           return $this->ajax_return('非法操作', 'false');
        }
   }
   
   /**
    * 保存 及 保存为待发布
    * @param string $action:操作选择
    * @param bool $exit:是否退出执行,即exit
    * @return array
    */
   public function save($action='save', $exit=false){
       header('Content-Type: text/html; Charset=utf-8');
       //提及数据
       $posts = Input::get();  
       //草稿主表数据信息
       $draft_product['token_id']      = $posts['token_id'];
       //$draft_product['user_id']       = request()->user()->id;
       $draft_product['subject']       = trim($posts['subject']);
       $draft_product['groupId']       = $posts['groupId'];
       $draft_product['categoryId']    = $posts['categoryId'];
       $draft_product['packageLength'] = $posts['packageLength'];
       $draft_product['packageWidth']  = $posts['packageWidth'];
       $draft_product['packageHeight'] = $posts['packageHeight'];
       $draft_product['grossWeight']   = $posts['grossWeight'];
       $draft_product['deliveryTime']  = $posts['deliveryTime'];
       $draft_product['wsValidNum']    = $posts['wsValidNum'];
       
       if ($action == 'saveToPost') { //保存为待发布
           $draft_product['productStatusType'] = 'waitPost';
       }
       /*******************产品属性封装开始***************************/
       $aeopAeProductPropertys = array();
       //select和checkbox类型
       $sysAttrValueIdAndValue = array_key_exists('sysAttrValueIdAndValue', $posts) ? $posts['sysAttrValueIdAndValue'] : array();
       $otherAttributeTxt      = array_key_exists('otherAttributeTxt', $posts) ? $posts['otherAttributeTxt'] : array();
       if ($sysAttrValueIdAndValue){
           foreach ($sysAttrValueIdAndValue as $attrId => $value) {
               if (!empty($value)) {
                   if (is_array($value)){
                       foreach ($value as $v){ //checkbox类型的会写成数组
                           list($attrValueId,) = explode('-', $v);
                           $aeopAeProductPropertys[] = array(
                               'attrNameId'  => $attrId,
                               'attrValueId' => $attrValueId
                           );
                       }
                   }else {
                       list($attrValueId,) = explode('-', $value);
                       $aeopAeProductPropertys[] = array(
                           'attrNameId'  => $attrId,
                           'attrValueId' => $attrValueId
                       );
                   }
                   if ($otherAttributeTxt && array_key_exists($attrId, $otherAttributeTxt)) { //其它属性
                       $aeopAeProductPropertys[] = array(
                           'attrNameId' => $attrId,
                           'attrValue'  => $otherAttributeTxt[$attrId]
                       );
                   }
               }
           }
       }
       
       //input类型的
       $sysAttrIdAndValueName = array_key_exists('sysAttrIdAndValueName', $posts) ? $posts['sysAttrIdAndValueName'] : array();
       //input类型的要考虑选择的单位
       $sysAttrIdAndUnit = array_key_exists('sysAttrIdAndUnit', $posts) ? $posts['sysAttrIdAndUnit'] : array();
       if ($sysAttrIdAndValueName){
           // var_dump($sysAttrIdAndValueName);exit;
           foreach ($sysAttrIdAndValueName as $attrId => $value) {
               if ($value!=='') {
                   //有输入值，并且有单位，把单位组合进去吧
                   $value = !empty($sysAttrIdAndUnit[$attrId]) ? trim($value).' '.$sysAttrIdAndUnit[$attrId] : trim($value);
                   $aeopAeProductPropertys[] = array(
                       'attrNameId' => $attrId,
                       'attrValue'  => $value,
                   );
               }
           }
       }
       
       
       //自定义属性
       $custom = array_key_exists('custom', $posts) ? $posts['custom'] : array();
       if ($custom){
           foreach ($custom['attrName'] as $k => $attrName) {
               $aeopAeProductPropertys[] = array(
                   'attrName'  => $attrName,
                   'attrValue' => $custom['attrValue'][$k]
               );
           }
       }
       
       /***********************产品属性封装结束**********************/
       
       //详情表数据
       $draft_detail['aeopAeProductPropertys'] = serialize($aeopAeProductPropertys);
       
       
       //图片
       $imageURLs = array_key_exists('imgLists', $posts) ? $posts['imgLists'] : array();
       if (!$imageURLs){
           $this->ajax_return('保存失败，主图信息不存在,请先上传', false);
       }
       if (count($imageURLs) > 6){
           $this->ajax_return('主图不能超过6张', false);
       }
  
       $draft_detail['imageURLs']              = implode(';', $imageURLs);   //图片
       $draft_detail['isImageDynamic']         = count($imageURLs) > 1 ? 1 : 0; //是否动态图      
      
     
       //自定义关联产品
       /*
       $relationProductArr = array_key_exists('relationProduct', $posts) ? $posts['relationProduct'] : array();
       $relationProductIds = '';
       if ($relationProductArr) {
           $relationProductIds = implode(';', $relationProductArr);
           //$relationStr = $this->createRelationTemplate($relationProductIds);
       }
       //关联产品ID字符串
       $draft_detail['relationProductIds'] = $relationProductIds;
       
       //关联产品的位置
       $relationLocation = $posts['relation_loction'];
       $draft_detail['relationLocation'] = $posts['relation_loction'];
       */   
       
       
       //这个账号的token信息
       $token_id = $posts['token_id'];
       $token_info = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($token_info->channel->driver, $token_info->api_config);

       $detail_str = trim($posts['detail']);
       $draft_detail['detail']                 = htmlspecialchars($detail_str); //详情
       $draft_detail['detailLocal']            = $draft_detail['detail'];
       $draft_detail['keyword']                = $smtApi->filterForSmtProduct($posts['keyword']);      //关键字
       $draft_detail['productMoreKeywords1']   = $smtApi->filterForSmtProduct($posts['productMoreKeywords1']);
       $draft_detail['productMoreKeywords2']   = $smtApi->filterForSmtProduct($posts['productMoreKeywords2']);
       
       if ((strlen($draft_detail['keyword']) + strlen($draft_detail['productMoreKeywords1']) + strlen($draft_detail['productMoreKeywords2'])) > 255){
           $this->ajax_return('三个关键字加起来长度不能超过255个字符', false);
       }
       
       $draft_detail['productUnit']            = $posts['productUnit'];
       $draft_detail['freightTemplateId']      = $posts['freightTemplateId'];
       $draft_detail['isImageWatermark']       = 0;
       $draft_detail['packageType']            = array_key_exists('packageType', $posts) ? 1 : 0; //是否打包
       $draft_detail['lotNum']                 = $draft_detail['packageType'] ? $posts['lotNum'] : 1; //打包数量
       if (array_key_exists('isPackSell', $posts)) {
           $draft_detail['isPackSell'] = $posts['isPackSell']; //自定义记重
       }
       //批发订单
       if (array_key_exists('wholesale', $posts)) {
           $draft_detail['bulkOrder']    = $posts['bulkOrder'];
           $draft_detail['bulkDiscount'] = $posts['bulkDiscount'];
       }else { //没提交的话，直接赋值为0，不然的话还是会提交的
           $draft_detail['bulkOrder']    = 0;
           $draft_detail['bulkDiscount'] = 0;
       }
       $draft_detail['promiseTemplateId'] = $posts['promiseTemplateId']; //服务模板
       
       //自定义刊登模板详情
       $draft_detail['templateId']   = $posts['templateId'];
       $draft_detail['shouhouId']    = $posts['shouhouId'];
       $draft_detail['detail_title'] = htmlspecialchars(trim($posts['detail_title']));
       if (array_key_exists('detailPicList', $posts)){ //有描述图片详情
           $draft_detail['detailPicList'] = implode(';', $posts['detailPicList']);
       }
       
       
       /***************多属性SKU组装信息--组装开始*******************/
       $aeopAeProductSKUs = array(); //需要组装下SKU信息
       $skuPrice          = array_key_exists('skuPrice', $posts) ? $posts['skuPrice'] : array();
       $ipmSkuStock       = array_key_exists('skuStock', $posts) ? $posts['skuStock'] : array();
       $skuCode           = array_key_exists('skuCode', $posts) ? $posts['skuCode'] : array();
       //自定义SKU属性名称
       $customizedName = array_key_exists('customizedName', $posts) ? $posts['customizedName'] : array();
       //自定义SKU图片信息
       $customizedPic = array_key_exists('customizedPic', $posts) ? $posts['customizedPic'] : array();
       //最小价格
       $productMinPrice = 0;
       //最大价格
       $productMaxPrice = 0;
       if ($skuPrice && $ipmSkuStock) { //单价和库存都存在，应该会存在SKU来着
           foreach ($skuPrice as $key => $price) {
               if (!trim($skuCode[$key])) { //sku不存在的话直接pass掉
                   continue;
               }
       
               $attList         = explode('-', $key);
               $aeopSKUProperty = array();
               foreach ($attList as $at) { //处理下属性，找下自定义的属性信息
                   list($skuPropertyId, $propertyValueId) = explode('_', $at);
                   if ($propertyValueId) { //有属性值才行，没属性值不管
                       $array = array(
                           'skuPropertyId'   => $skuPropertyId,
                           'propertyValueId' => $propertyValueId,
                       );
                       if (array_key_exists($at, $customizedName) && $customizedName[$at]) { //有自定义属性
                           $array = array_merge($array, array('propertyValueDefinitionName' => $customizedName[$at]));
                       }
                       if (array_key_exists($at, $customizedPic) && $customizedPic[$at]) { //有自定义图片
                           $array = array_merge($array, array('skuImage' => $customizedPic[$at]));
                       }
                       $aeopSKUProperty[] = $array;
                       unset($array);
                   }
               }
               $aeopAeProductSKUs[] = array(
                   'skuPrice'        => $price,
                   'skuCode'         => $skuCode[$key],
                   'ipmSkuStock'     => $ipmSkuStock[$key],
                   'aeopSKUProperty' => $aeopSKUProperty
               );
               //最小单价
               $productMinPrice = $productMinPrice == 0 ? $price : ($productMinPrice < $price ? $price : $productMinPrice);
               //最大单价
               $productMaxPrice = $productMaxPrice > $price ? $productMaxPrice : $price;
           }
       
       } else {
           //单属性产品组装--和多属性互斥
           $productPrice        = $posts['productPrice'];
           $productStock        = $posts['productStock'];
           $productCode         = $posts['productCode'];
           $aeopAeProductSKUs[] = array(
               'skuPrice'        => $productPrice,
               'skuCode'         => trim($productCode),
               'ipmSkuStock'     => $productStock,
               'aeopSKUProperty' => array(), //这个字段必需
           );
           //最小单价
           $productMinPrice = $productPrice;
           $productMaxPrice = $productPrice; ///最大单价
       }
       
       //单价或者一口价
       $draft_product['productPrice'] = $aeopAeProductSKUs[0]['skuPrice'];
       $draft_product['productMinPrice'] = $productMinPrice;
       $draft_product['productMaxPrice'] = $productMaxPrice;
       /****************多属性SKU组装信息--组装结束*******************/
       $result_flag = true;
       $info        = '';       
       $used_id = request()->user()->id;
       $code = ''; //账号前缀
       if ($used_id){
           $saleCode = smtUserSaleCode::where('user_id',$used_id)->first();          
           $code = $saleCode? $saleCode->sale_code : '';      
       }     
       
       if ($posts['id']) { //草稿ID存在，还是直接更新       
           DB::beginTransaction();
           try{
               smtProductList::where('productId',$posts['id'])->update($draft_product);          
               smtProductDetail::where('productId',$posts['id'])->update($draft_detail);
              
               $exist_skuList = smtProductSku::where('productId',$posts['id'])->get();

               $exist_skuLists = array();
               //存在就更新，不存在就insert
               if($exist_skuList){
                   foreach ($exist_skuList as $row) {
                       $exist_skuLists[] = $row['skuMark'];
                   }
               }
               $smtProductSkuModel = new smtProductSku();
               foreach ($aeopAeProductSKUs as $per_sku) {                    
                   $valId                      = $smtApi->checkProductSkuAttrIsOverSea($per_sku['aeopSKUProperty']); //海外仓属性ID
                   $per_sku['aeopSKUProperty'] = $per_sku['aeopSKUProperty'] ? serialize($per_sku['aeopSKUProperty']) : '';
                   $per_sku['skuStock']        = $per_sku['ipmSkuStock'] > 0 ? 1 : 0;
                   //$per_sku['smtSkuCode']      = ($code ? $code . '*' : '') .(($valId > 0 && $valId != 201336100) ? '{YY}' : ''). $per_sku['skuCode'] . ($token_info['accountSuffix'] ? '#' . $token_info['accountSuffix'] : '');
                   $per_sku['smtSkuCode']      = ($code ? $code . '*' : '') .(($valId > 0 && $valId != 201336100) ? '{YY}' : ''). $per_sku['skuCode'] ;
                   $per_sku['updated']         = 1; //这些都是修改过的
                   $per_sku['isRemove']        = 0; //未被删除的
                   $per_sku['overSeaValId']    = $valId;
                    
                   $newSkus = $smtApi->buildSysSku($per_sku['skuCode']);
                   $withErr = false; //循环中是否出错
                   foreach ($newSkus as $sku){
                       $per_sku['skuCode'] = (($valId > 0 && $valId != 201336100) ? '{YY}' : '').$sku;                     
                       $isSkuExists = smtProductSku::where(['productId'=>$posts['id'],
                                                            'smtSkuCode'=>$per_sku['smtSkuCode'],
                                                            'skuCode'=>$per_sku['skuCode'],
                                                            'overSeaValId'=>$valId])->first();
                       if ($isSkuExists['id']) {//更新
                           $where = array();
                           $where['productId']    = $posts['id'];
                           $where['smtSkuCode']   = $per_sku['smtSkuCode'];
                           $where['skuCode']      = $per_sku['skuCode'];
                           $where['overSeaValId'] = $valId;                          
                           smtProductSku::where($where)->update($per_sku);                    
                       } else { //增加                           
                           $per_sku['productId'] = $posts['id'];
                           $per_sku['skuMark']   = $posts['id'] . ':' . $per_sku['skuCode'];
                           $smtProductSkuModel->create($per_sku);                         
                       }
                   }
                   unset($newSkus);
                   if ($withErr) break;                   
               }                   
               //没有变更的数据直接删除吧，说明已经变了，只使用最新的就好了
               $smtProductSkuModel->delete(array('productId' => $posts['id'], 'updated' => 0));
                
               $newData            = array();
               $newData['updated'] = 0;
               $smtProductSkuModel->where('productId','=',$posts['id'])->update($newData);
               DB::commit();
               if ($exit){                     
                    $this->ajax_return('保存'.($result_flag ? '成功' : '失败').$info, $result_flag);
                }else {
                	return array('status' => $result_flag, 'info' => '保存'.($result_flag ? '成功' : '失败').$info, 'id' => $posts['id']);
                }                        
               
           }catch(\Exception $e){
               DB::rollback();
               throw $e;
           }                            
        } else { //add
           DB::beginTransaction();
           $productId = date('ymdHis').rand(1000, 9999).'-'.$posts['token_id'];     //临时的产品ID           	
           $draft_product['productId'] = $productId;          
           $draft_product['productStatusType'] = ($action == 'saveToPost') ? 'waitPost' : 'newData';  
           $result = $this->model->create($draft_product);  
                  
           //保存到详情表中
           $draft_detail['productId'] = $productId;
           $detail_result = $this->smtProductDetailModel->create($draft_detail);          
       
           $sku_flag = true;
           $withErr = false;      
           foreach ($aeopAeProductSKUs as $per_sku) {
               $valId                      = $smtApi->checkProductSkuAttrIsOverSea($per_sku['aeopSKUProperty']); //发货地属性ID 值ID：201336100为中国
               $per_sku['overSeaValId']    = $valId;
               $per_sku['aeopSKUProperty'] = $per_sku['aeopSKUProperty'] ? serialize($per_sku['aeopSKUProperty']) : '';
               $per_sku['productId']       = $productId;
               $per_sku['skuStock']        = $per_sku['ipmSkuStock'] > 0 ? 1 : 0;     
               $per_sku['smtSkuCode'] = ($code ? $code . '*' : '') .(($valId > 0 && $valId != 201336100) ? '{YY}' : '').$per_sku['skuCode'] ;
               $newSkus = $smtApi->buildSysSku($per_sku['skuCode']);
               foreach($newSkus as $sku){
                   $per_sku['skuCode'] = (($valId > 0 && $valId != 201336100) ? '{YY}' : '').$sku;
                   $per_sku['skuMark'] = $productId . ':' . $per_sku['skuCode']; 
                   $sku_res = $this->smtProductSkuModel->create($per_sku);
                   if (!$sku_res->id){
                       $sku_flag = false;
                       $withErr = true;
                       break;
                   }
               }
               if ($withErr) break;
           }
           if ($sku_flag) { //都插入成功了才进行提交
               DB::commit();
                if ($exit){                   
                	$this->ajax_return('保存成功', true, array('id' => $productId)); //这个productId还是返回吧
                }else {
                	return array('status' => true, 'info' => '保存成功', 'id' => $productId);
                }
           } else {
               DB::rollback();
               $this->ajax_return('保存到SKU列表出错', false);
           }
       }
   }
   
   public function post(){
       $return = $this->save('saveToPost', false);
       if ($return && $return['status']){                    
           $main_id = $return['id'];         
           $this->postAeProduct($main_id, true);       //发布
       }else {
           $this->ajax_return('保存失败,未上传', false);
       }
   }
   
   /**
    * 编辑并上传
    */
   public function editAndPost(){
       $return = $this->save('save', false);
       if ($return && $return['status']){   
           $productId = $return['id'];   
           return $this->postAeProduct($productId, false);
       }else {
           $this->ajax_return('编辑时保存失败', false);
       }
   }
   
   /**
    *批量发布
    */
   public function batchPost(){
       $productIds = Input::get('productIds');   
       if (!$productIds){
           $return[] = array('status' => false, 'info' => '请传入产品数据');
       }else {
           $products = explode(',', $productIds);
           $return   = array();
          
           foreach ($products as $productId) {
               $return[] = $this->postAeProduct($productId, true, true);
           }
       }
       return $return;
   }
   
    /**
     * 发布产品
     * @return array
     * @param $id:产品ID
     * @param bool $isAdd:是否新添加
     * @param bool $auto:为false的时候，不管草稿的状态;只有待发布的才自动发布
     * @return array
     */
    public function postAeProduct($id, $isAdd = true, $auto = false)
    {
        if ($id) {
            $product = array();
            $draft_info = $this->model->where('productId',$id)->first();                   
            $draft_detail = $draft_info->details;      //读取待发布产品详情信息                      
            $draft_skus = $draft_info->productSku;     //读取待发布产品SKU信息
            
            $token_id = $draft_info->token_id;
            $account = AccountModel::findOrFail($token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            $firstSku = $smtApi->rebuildSmtSku($draft_skus[0]['smtSkuCode']); //简单解析下第一个SKU
                   
            if (!$token_id) {
                if ($auto) {
                    return array('status' => false, 'info' => '产品:' . $id . '刊登账号不存在');
                }else {
                    return json_encode(array('status' => false, 'info' => '产品:' . $id . '刊登账号不存在'));
                }
            }

	        $skudatastr =array();
	        $skuCode = array();//此ID的所有SKU
	        foreach($draft_skus as $v){
	        	$skus =unserialize($v->aeopSKUProperty);	        	
	        	$skudatastr[] = $skus[0]['propertyValueDefinitionName'];
	        	$skuCode[] = $v->skuCode;
	        }
	        $skudatastr = implode( $skudatastr,',');//价格属性字符串
	     
            $checkeinfo['token_id'] = $token_id;   
            $checkeinfo['categoryId']=$draft_info->categoryId;                           //获取分类ID
            $checkeinfo['subject']=$draft_info->subject;                                 //获取标题
            $checkeinfo['aeopAeProductPropertys']=$draft_detail->aeopAeProductPropertys; //获取属性
            $checkeinfo['keyword']=$draft_detail->keyword;                               //获取关键字1
            $checkeinfo['productMoreKeywords1']=$draft_detail->productMoreKeywords1;     //获取关键字2
            $checkeinfo['productMoreKeywords2']=$draft_detail->productMoreKeywords2;     //获取关键字3
            $checkeinfo['detail']=$draft_detail->detail;                                 //获取详情

            //侵权验证
            $re = $this->findAeProductProhibitedWords($checkeinfo);  
            if($re!='success'){
                $re =   str_replace("FORBIDEN_TYPE", "禁用", $re);
                $re =   str_replace("RESTRICT_TYPE", "限定", $re);
                $re =   str_replace("BRAND_TYPE", "品牌", $re);
                $re =   str_replace("TORT_TYPE", "侵权", $re);
                $re =   str_replace("titleProhibitedWords", "商品的标题", $re);
                $re =   str_replace("keywordsProhibitedWords", "商品的关键字列表", $re);
                $re =   str_replace("productPropertiesProhibitedWords", "商品的属性", $re);
                $re =   str_replace("detailProhibitedWords", "商品的详细描述", $re);
                if ($auto) {
                    return array('status' => false, 'info' => '产品:' . $id . $re);
                }else {
                    return $this->ajax_return( '产品:' . $id.$re,false);
                }
            }

            //对价格属性进行检测
            $checkeinfoss = $checkeinfo;
            $checkeinfoss['detail'] = $skudatastr;
            $re = $this->findAeProductProhibitedWords($checkeinfoss);            
            if($re!='success'){
                $re =   str_replace("FORBIDEN_TYPE", "禁用", $re);
                $re =   str_replace("RESTRICT_TYPE", "限定", $re);
                $re =   str_replace("BRAND_TYPE", "品牌", $re);
                $re =   str_replace("TORT_TYPE", "侵权", $re);
                $re =   str_replace("titleProhibitedWords", "商品的标题", $re);
                $re =   str_replace("keywordsProhibitedWords", "商品的关键字列表", $re);
                $re =   str_replace("productPropertiesProhibitedWords", "商品的属性", $re);
                $re =   str_replace("detailProhibitedWords", "商品的价格属性", $re);
                if ($auto) {
                    return array('status' => false, 'info' => '产品:' . $id . $re);
                }else {
                    return $this->ajax_return( '产品:' . $id.$re,false);
                }
            }                     
            
            //ERP违禁商标名检测
            $copyworld = array();
            $copyworld[] = $draft_info->subject;                  //获取标题
            $copyworld[] = $draft_detail->detail;                 //获取详情
            $copyworld[] = $draft_detail->aeopAeProductPropertys; //获取属性
            $copyworld[] = $skudatastr;                           //价格属性
            $copyworld['skuCode'] = $skuCode;                     //对SKU检测
            $checkres = $this->copycheck($copyworld);
            $copysure = Input::get('copysure');                   //是否忽略ERP违禁商标名检测
            if($checkres != 'success'){
                $resmsg = '的数据含有违禁的'.$checkres;
                if($copysure != 'yes'){
                    //检查结果含有ERP违禁商标名,但是操作者选择不忽略，禁止发布提示发布失败
                    if ($auto) {
                        return array('status' => 'copyright', 'info' => '产品:' . $id .$resmsg );
                    }else {
                        return $this->ajax_return( '产品:' . $id.$resmsg,'copyright');
                    }                    
                }
            }
 
            $replace_flag = false; //是否替换图片的标识
            if ($isAdd) { //新增的话，该替换的还是要替换
                //旧账号ID存在，同时(2个账号不一致或者原产品ID存在)
                if ($draft_info->old_token_id && ($draft_info->old_token_id <> $draft_info->token_id || $draft_info->old_productId)) {
                    $replace_flag = true;
                }
            }

            $product_arr = array(); //要上传的产品信息
            //组装产品信息

            /**************产品详情信息开始**************/

            //看是否有关联产品的图片，有的话，直接上传并替换到里边去
            /*
            $relationFlag = false; //关联产品标识         
            if (!empty($draft_detail->relationProductIds)){
                $relationFlag = true;
                $relationHtml = $this->createRelationTemplate($draft_detail->relationProductIds);
                $top_pic = site_url("attachments/images/relation_header.jpg");
                if (strstr($relationHtml, $top_pic) !== false){
                    $res1 = $smtApi->uploadBankImage('api.uploadImage', $top_pic, 'relation_banner');
                    if ($res1['status'] == 'SUCCESS' || $res1['status'] == 'DUPLICATE') {
                        $url1 = $res1['photobankUrl']; //返回的url链接
                        $relationHtml = str_replace($top_pic, $url1, $relationHtml);
                    }else {
                        $relationHtml = str_replace('<img src="'.$top_pic.'" style="width: 100.0%;">', '', $relationHtml);
                    }
                }
            }*/

            $detail = htmlspecialchars_decode($draft_detail->detail);          
            $detail = $smtApi->replaceSmtImgToModule($detail);    //替换产品模型
            if ($replace_flag) {
                $detail = $this->replaceDetailPics($detail, $firstSku, $draft_info->productId);
            }
            $product['detail'] = $detail; //用来更新本地数据

            //把模板，标题，售后模板等套进来          
            $templateId = $draft_detail->templateId;         
            $templateInfo = smtTemplates::where('id',$templateId)->first();
            if ($templateInfo && $templateInfo->id) {
                //位置调整下，没模板的话就不要传了，不然也是浪费
                $picStr = '';
                if ($draft_detail->detailPicList) { //描述图片信息
                    $tempPicList = explode(';', $draft_detail->detailPicList);
                    foreach ($tempPicList as $imgPath) {
                        if ($replace_flag) { //需要替换才进行替换，不然才不管，都是上传到图片银行的
                            $newPath = $this->uploadOnePicToBank($imgPath, $firstSku, $draft_info->productId);
                            if ($newPath) {
                                $picStr .= '<img src="' . $newPath . '" alt="aeProduct.getSubject()" title="aeProduct.getSubject()" />';
                                $product['detailPicList'][] = $newPath;
                            }
                        } else {
                            $picStr .= '<img src="'.$imgPath.'" alt="aeProduct.getSubject()" title="aeProduct.getSubject()" />';
                        }
                    }
                }

                $layout = htmlspecialchars_decode($templateInfo->content);             
                $layout = str_replace('{my_template_id}', $templateId, $layout);       //替换模板ID                
                $detail_title = $draft_detail->detail_title;                
                $layout = str_replace('{my_layout_title}', $detail_title, $layout);    //替换标题                
                $shouhouId    = $draft_detail->shouhouId;                              //售后模板
                $shouhouInfo  = afterSalesService::where('id',$shouhouId)->first();                
                $layout       = str_replace('{my_shouhou_id}', $shouhouId, $layout);   //替换售后模板                
                $shouhou_html = $shouhouInfo ? htmlspecialchars_decode($shouhouInfo->content) : '';
                $layout       = str_replace('{my_layout_shouhou}', $shouhou_html, $layout);
                
                $layout = str_replace('{my_layout_detail}', $detail, $layout);         //替换描述                
                $layout = str_replace('{my_layout_pic}', $picStr, $layout);            //替换描述图片
                $layout = str_replace('{my_layout_relation}', '', $layout);              
                $html = $layout;
                unset($detail);
                unset($layout);
            } else {                           
                $html = $detail;    //这是没有使用自定义售后模板的情况    
                unset($detail);
            }
            unset($relationHtml);
            $product_arr['detail'] = $html;
            unset($html);
            /**************产品详情信息结束**************/

            /*************产品主图信息开始**************/
            if ($replace_flag) { //需要上传主图
                $imgLists    = explode(';', $draft_detail->imageURLs);
                $newImgLists = array();
                foreach ($imgLists as $k => $img) {
                    //$newImgLists[] = $this->replaceTempPics($img, $draft_skus[0]['skuCode'] . '-logo' . ($k + 1), $draft_info['productId']);
                    //现在上传到图片银行
                    $flag = $this->checkURLResourceTypeIsExit($img);
                    if($flag === false){
                        $img = "http:".$img;
                    }
                    $newImgLists[] = $this->uploadOnePicToBank($img, $firstSku.'-logo' . ($k + 1), $draft_info->productId);
                }
                unset($imgLists);
                $product_arr['imageURLs']      = implode(';', $newImgLists);
                $product_arr['isImageDynamic'] = count($newImgLists) > 1 ? 'true' : 'false';
                $product['imageURLs']          = $product_arr['imageURLs'];
                $product['isImageDynamic']     = $product_arr['isImageDynamic'];
            } else {                
                $product_arr['imageURLs'] = $draft_detail['imageURLs'];                
                $product_arr['isImageDynamic'] = stripos($draft_detail['imageURLs'], ';') !== false ? 'true' : 'false'; //商品主图类型 --多图用动态，单图静态
            }
            /*************产品主图组装结束*************/

            /*************产品SKU属性组装开始***********/
            $aeopAeProductSKUs = array(); //需要组装下SKU信息
            foreach ($draft_skus as $sku) {
                $temp_property = array();
                if ($sku['aeopSKUProperty']) {
                    $temp = unserialize($sku['aeopSKUProperty']);
                    if ($replace_flag && $temp) { //图片等自定义信息存在，同时需要替换
                        foreach ($temp as $j => $t) {
                            if (array_key_exists('skuImage', $t) && $t['skuImage']) {
                                //上传图片处理 --还是上传到临时图片
                                $pic = $this->replaceTempPics($t['skuImage'], $firstSku . '-cust'.$j, $id);
                                $t['skuImage'] = $pic;
                            }
                            $temp_property[$j] = $t;
                        }
                        unset($temp);
                    } else {
                        $temp_property = $temp;
                    }
                }

                $aeopAeProductSKUs[] = array(
                    'skuPrice'        => $sku['skuPrice'],
                    'skuCode'         => $sku['smtSkuCode'],
                    'ipmSkuStock'     => $sku['ipmSkuStock'],
                    'aeopSKUProperty' => $temp_property
                );
                unset($temp_property);
            }

            $product_arr['aeopAeProductSKUs'] = json_encode($aeopAeProductSKUs);
            $product['aeopAeProductSKUs']     = $aeopAeProductSKUs; 

            if (count($aeopAeProductSKUs) == 1) { //只有一个是要传一口价的
                $product_arr['productPrice'] = $aeopAeProductSKUs[0]['skuPrice'];
            }
            unset($aeopAeProductSKUs);
            /*************产品SKU属性组装结束***********/
               
            $product_arr['categoryId'] = $draft_info->categoryId;
            $product_arr['deliveryTime'] = $draft_info->deliveryTime;   //备货期
            if ($draft_detail->promiseTemplateId) {
                $product_arr['promiseTemplateId'] = $draft_detail->promiseTemplateId; //服务模板ID
            }            
            $product_arr['subject'] = $draft_info->subject;            
            /*$product_arr['keyword'] = $smtApi->filterForSmtProduct($draft_detail->keyword); //关键词 --过滤下';'和','           
            $productMoreKeywords1 = $smtApi->filterForSmtProduct($draft_detail->productMoreKeywords1);   //更多关键词
            if ($productMoreKeywords1) {
                $product_arr['productMoreKeywords1'] = $productMoreKeywords1;
            }
            $productMoreKeywords2 = $smtApi->filterForSmtProduct($draft_detail->productMoreKeywords2);
            if ($productMoreKeywords2) {
                $product_arr['productMoreKeywords2'] = $productMoreKeywords2;
            }*/

            if ($draft_info->groupId) {              
                $product_arr['groupId'] = $draft_info->groupId;                    //产品组ID
            }
            $product_arr['freightTemplateId'] = $draft_detail->freightTemplateId;  //运费模板ID            
            $product_arr['isImageWatermark'] = 'false';                            //是否添加水印            
            $product_arr['productUnit'] = $draft_detail->productUnit;              //单位         
            if ($draft_detail->packageType) {                
                $lotNum                = $draft_detail->lotNum;                    //每包件数
                $product_arr['lotNum'] = intval($lotNum) > 1 ? intval($lotNum) : 2;   
            }
            $product_arr['packageType'] = $draft_detail->packageType ? 'true' : 'false'; //是否打包

            //包装长宽高
            $product_arr['packageLength'] = (int)$draft_info->packageLength;
            $product_arr['packageWidth']  = (int)$draft_info->packageWidth;
            $product_arr['packageHeight'] = (int)$draft_info->packageHeight;            
            $product_arr['grossWeight'] = $draft_info->grossWeight;                //商品毛重           
            $isPackSell = $draft_detail->isPackSell;                               //是否自定义记重 -- 自定义记重暂时未作
            $isPackSell = $isPackSell == '1' ? 'true' : 'false';
            $baseUnit   = '';
            $addUnit    = '';
            $addWeight  = '';
            $product_arr['isPackSell'] = false;                                    // api变动 暂时都设置成false            
            $product_arr['wsValidNum'] = $draft_info->wsValidNum;                  //有效期
            if ($isAdd) {                
                $api                = 'api.postAeProduct';  
                $product_arr['src'] = 'isv';                                       //商品来源 --固定死
            } else {                
                $api                      = 'api.editAeProduct';
                $product_arr['productId'] = $id;
                if ($draft_detail->src) {                    
                    $product_arr['src'] = $draft_detail->src;                      //修改用原样的
                }
            }

            /*******************产品属性封装开始***************************/
            $aeopAeProductPropertys                = unserialize($draft_detail['aeopAeProductPropertys']);
            $product_arr['aeopAeProductPropertys'] = json_encode($aeopAeProductPropertys);
            unset($aeopAeProductPropertys);
            /***********************产品属性封装结束**********************/

            if ($draft_detail->bulkOrder && $draft_detail->bulkDiscount) {                
                $product_arr['bulkOrder'] = (int)$draft_detail->bulkOrder;          //最小批发数量                
                $product_arr['bulkDiscount'] = (int)$draft_detail->bulkDiscount;    //批发折扣
            }            
            if (!empty($draft_detail->sizechartId) && $draft_detail->sizechartId > 0) {
                $product_arr['sizechartId'] = $draft_detail->sizechartId;           //尺码表模板ID
            }           
            $result = $smtApi->getJsonDataUsePostMethod($api, $product_arr);        //发布或者修改
            $data   = json_decode($result, true);          
           
            $product_arr['productId'] = $draft_info->productId;
            $product['productId']     = $draft_info->productId;
            $return                   = $this->hanleProductData($product);          //不管成功还是失败，都把数据保存下来
            if (!$return['status']) {   //写错误日志 
            }              
            unset($draft_info);
            unset($draft_detail);
            if (array_key_exists('success', $data) && $data['success']) {
                if ($isAdd) { //是新刊登的产品            
                    $realProductId = $data['productId'];                           //速卖通平台返回的产品ID        
                    $newListData['productId']         = $realProductId;
                    $newListData['productStatusType'] = 'onSelling';
                    $newListData['old_token_id']      = 0;
                    $newListData['old_productId']     = '';
                    $newListData['product_url']       = 'http://www.aliexpress.com/item/-/'.$realProductId.'.html';
                    //$newListData['ownerMemberId']     = $smtApi->_aliexpress_member_id;                    

                    DB::beginTransaction();
                    $newData['productId'] = $realProductId;
                    $this->smtProductDetailModel->where('productId',$id)->update($newData);
                    
                    $plat_info = $smtApi->getDefinedPlatInfo(); //SMT平台信息                  

                    //SMT销售前缀列表
                    $sale_code = smtUserSaleCode::all();
                   /* $res = array();
                    foreach($sale_code as $r){
                        $rs[$r->user_id]   = $r->user_id;
                        $rs[$r->sale_code] = $r->sale_code;
                    }*/
                    $user_id = 0; //解析前缀获取广告对应的用户
                    //更新SKU列表信息
                    $draft_skus = $draft_skus->toArray();
                    $productId = $id;
                    foreach ($draft_skus as $sku) {
                        $skus = $smtApi->buildSysSku($sku['smtSkuCode']); //还是会带{YY}                        
                        foreach ($skus as $skuCode) {                     //发布成功了，更新现有的数据
                            $newData['skuMark'] = $realProductId . ':' . $skuCode;
                            $oldMark            = $productId . ':' . $skuCode;                             
                            $this->smtProductSkuModel->where(['productId'=>$productId,'overSeaValId'=>$sku['overSeaValId'],'skuMark'=>$oldMark])->update($newData);
                            $common = new common_helper();
                            $prefix = $common->get_skucode_prefix($sku['smtSkuCode']); //产品的前缀
                            if ($prefix) {
                                $user_id = $user_id ? $user_id : (array_key_exists($prefix, $sale_code) ? $sale_code[$prefix]['user_id'] : $user_id); //对应的账号ID
                            }

                            /****插入一条记录到刊登记录内开始****/
                            $publishRecord = array(
                                'SKU'            => $smtApi->rebuildSmtSku($skuCode, true), //到这就是ERP内的SKU
                                'userID'         => $user_id,
                                'publishTime'    => date('Y-m-d H:i:s'),
                                'platTypeID'     => $plat_info['platTypeID'],
                                'publishPlat'    => $plat_info['platID'],
                                'sellerAccount'  => $account->account,  //账号，通过tokenid来吧
                                'itemNumber'     => $realProductId,
                                'publishViewUrl' => 'http://www.aliexpress.com/item/-/' . $realProductId . '.html' //链接，处理下吧
                            );
                         
                            SkuPublishRecords::create($publishRecord);
                            /****插入一条记录到刊登记录内结束****/
                        }        
                        //更新列表页的产品信息
                        $newListData['user_id'] = $user_id; //用老账号的用户id --不要通过session处理，怕以后会跑计划任务
                        $this->model->where('productId',$productId)->update($newListData);                       
                    }
                    DB::commit();
                    unset($newListData);
                    unset($newData);
                }
                unset($product_arr);
                unset($draft_skus);
                if ($auto) {
                    return array('status' => true, 'info' => '产品:' . $id . ($isAdd ? '发布成功，新产品ID为:' . $realProductId : '修改成功'));
                }else {                
                    $this->ajax_return('产品:' . $id . ($isAdd ? '发布成功，新产品ID为:' . $realProductId : '修改成功'), true);
                }
            } else {
                unset($product_arr);
                unset($draft_skus);                
                if ($auto){
                    return array('info' => '产品:' . $id . ($isAdd ? '发布' : '修改') . '失败,'.(isset($data['error_code']) ? $data['error_code'] : '').$data['error_message'], 'status' => false);
                }else {
                    $this->ajax_return('产品:' . $id . ($isAdd ? '发布' : '修改') . '失败,'.(isset($data['error_code']) ? $data['error_code'] : '') . $data['error_message'], false);
                }
            }
        } else {
            if ($auto){
                return array('status' => false, 'info' => '产品:' . $id . '不存在');
            }else {
                $this->ajax_return('产品:' . $id . '不存在', false);
            }
        }
    }
    
    /**
     * 编辑草稿详情信息
     */
    public function edit($id)
    {     
        $this->mainTitle='SMT待发布产品';
        if ($id) {    
            //查询草稿数据
            $draft_info = $this->model->where('productId',$id)->first();
            if (in_array($draft_info['productStatusType'], array('newData', 'waitPost', 'failure'))) { //可以直接编辑的状态
                //会调用刊登API
                $api = 'post';
            } else {
                //调用修改API
                $api = 'edit';
            }
    
            //查询草稿SKU信息
            $draft_skus = $this->smtProductSkuModel->where('productId',$id)->get();
            if($draft_skus){
                $draft_skus = $draft_skus->toArray();
            }
           
            //查询草稿详情
            $draft_detail = $draft_info->details;
            //已选择的分类
            $smtCategoryModel = new smtCategoryModel;
            $category_id = $draft_info->categoryId;
            $category_info = $smtCategoryModel->getCateroryAndParentName($category_id);
    
            $token_id = $draft_info->token_id;
             //查询选择的分类的属性
            
            $attributes = smtCategoryAttribute::where('category_id',$category_id)->first();
            $category_attributes = array();
            $account = AccountModel::findOrFail($token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            if (!$attributes){ //属性直接不存在
                $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id,$category_id);
                if ($return)
                    $category_attributes = $return;
            }else { //属性存在但不是最新的           
                $category_attributes = unserialize($attributes->attribute);
                 
                //这个属性今天还没更新呢，更新下吧
                if (!$attributes->last_update_time || date('Y-m-d') != date('Y-m-d', strtotime($attributes->last_update_time))) {
                    $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id, $category_id);
                    if ($return)
                        $category_attributes = $return;
                }                                
            }
            //对属性进行排序处理
            $category_attributes = $smtApi->sortAttribute($category_attributes);
            
            //获取运费模版
            $freight = smtFreightTemplate::where('token_id',$token_id)->get();
           
            //服务模板
            $service = smtServiceTemplate::where('token_id',$token_id)->get();
            
            //产品分组
            $product_group = new SmtProductController(); 
            $group = $product_group->getLocalProductGroupList($token_id);            
            //单位列表
            $unit = $this->smtProductUnitModel->getAllUnit();

            //产品模板
            $module = smtProductModule::where('token_id',$token_id)->get();
            
            //速脉通模板及售后模板
            $plat = 6;    
            $template_list = smtTemplates::where('plat',$plat)->get();
            $shouhou_list  = afterSalesService::where(['plat'=>$plat,'token_id'=>$token_id])->get();      
            //产品信息不组合成一条数据了
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'draft_info'    => $draft_info,
                'draft_skus'    => $draft_skus,
                'draft_detail'  => $draft_detail,
                'category_info' => $category_info,
                'freight'       => $freight,
                'service'       => $service,
                'group'         => $group,
                'module'        => $module,
                'productUnit'   => $unit,
                'action'        => 'edit',
                'attributes'    => $category_attributes,
                'token_id'      => $draft_info['token_id'],
                'template_list' => $template_list,
                'shouhou_list'  => $shouhou_list,
                'api'           => $api,
                'token_id' => $token_id,
                'category_id' => $category_id,
            ];
            return view($this->viewPath . 'addProduct',$response);
        } else { //没有ID--直接跳转到列表页吧
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
    }
    
    public function editOnlineProduct(){ 
        $this->mainTitle='SMT在线数据修改';
        $this->mainIndex = route('smt.onlineProductIndex');
        $id = Input::get('id');
        if ($id) {
            //查询草稿数据
            $draft_info = $this->model->where('productId',$id)->first();
        
            //查询草稿SKU信息
            $draft_skus = $this->smtProductSkuModel->where(['productId'=>$id,'isRemove'=>0])->get();
            if($draft_skus){
                $draft_skus = $draft_skus->toArray();
            }
            //查询草稿详情
            $draft_detail = $draft_info->details;
            
            //已选择的分类
            $smtCategoryModel = new smtCategoryModel;
            $category_id = $draft_info->categoryId;
            $category_info = $smtCategoryModel->getCateroryAndParentName($category_id);
        
            $token_id = $draft_info->token_id;
            //查询选择的分类的属性
        
            $attributes = smtCategoryAttribute::where('category_id',$category_id)->first();
            $category_attributes = array();
            $account = AccountModel::findOrFail($token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            if (!$attributes){ //属性直接不存在
                $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id,$category_id);
                if ($return)
                    $category_attributes = $return;
            }else { //属性存在但不是最新的
                //$data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $data);
                $category_attributes = unserialize($attributes->attribute);
                 
                //这个属性今天还没更新呢，更新下吧
                if (!$attributes->last_update_time || date('Y-m-d') != date('Y-m-d', strtotime($attributes->last_update_time))) {
                    $return = $smtApi->getChildAttributesResultByPostCateIdAndPath($token_id, $category_id);
                    if ($return)
                        $category_attributes = $return;
                }
            }
            //对属性进行排序处理
            $category_attributes = $smtApi->sortAttribute($category_attributes);
            //获取运费模版
            $freight = smtFreightTemplate::where('token_id',$token_id)->get();
             
            //服务模板
            $service = smtServiceTemplate::where('token_id',$token_id)->get();
        
            //产品分组
            $product_group = new SmtProductController();
            $group = $product_group->getLocalProductGroupList($token_id);
            //单位列表
            $unit = $this->smtProductUnitModel->getAllUnit();
        
            //产品模板
            $module = smtProductModule::where('token_id',$token_id)->get();
        
            //速脉通模板及售后模板
            $plat = 6;
            $template_list = smtTemplates::where('plat',$plat)->get();
            $shouhou_list  = afterSalesService::where(['plat'=>$plat,'token_id'=>$token_id])->get();
            //产品信息不组合成一条数据了
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'draft_info'    => $draft_info,
                'draft_skus'    => $draft_skus,
                'draft_detail'  => $draft_detail,
                'category_info' => $category_info,
                'freight'       => $freight,
                'service'       => $service,
                'group'         => $group,
                'module'        => $module,
                'productUnit'   => $unit,
                'action'        => 'edit',
                'attributes'    => $category_attributes,
                'token_id'      => $draft_info['token_id'],
                'template_list' => $template_list,
                'shouhou_list'  => $shouhou_list,
                'token_id' => $token_id,
                'category_id' => $category_id,
            ];
            return view($this->viewPath . 'editOnlineProduct',$response);
        } else { //没有ID--直接跳转到列表页吧
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
    }
    
    public function ajaxOperateOnlineProduct(){
        $get = Input::get();
        $type = $get['type'];
        $product =[];
        $smtProduct = $this->model->where('productId',$get['id'])->first();
        $account = AccountModel::findOrFail($smtProduct->token_id);
        $smtApi = Channel::driver($account->channel->driver, $account->api_config); 
        if($type == 'online'){
            $api = 'api.offlineAeProduct';
        }elseif($type == 'offline'){
            $api = 'api.onlineAeProduct';
        }else{
            $this->ajax_return('操作失败!',0);
        }     

        $productId = $smtProduct->productId;
        $result= $smtApi->updateProductPublishState($api,$productId);

        if(array_key_exists('success',$result) && $result['success']){
            if($type == 'online'){
                $data['productStatusType'] = 'offline';
            }else{
                $data['productStatusType'] = 'onSelling';
            }            
            $this->model->where('productId',$productId)->update($data);
            $this->ajax_return('操作成功!',true);
        }else{
            $this->ajax_return('操作失败!',false);
        }
    }
  
    /**
     * 删除草稿数据
     * @param int $id
     */
    public function destroy($id){
        if($id){
            $result = $this->draftDel($id);
            if($result['status']){
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '删除成功.'));
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '删除失败.'));
            }
            
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '没有找到数据或数据状态错误.'));
        }
    }
    
    /**
     *删除草稿数据
     */
    public function draftDel($id){
        $info = $this->model->where('productId',$id)->first();
        if ($info && in_array($info['productStatusType'], array('newData', 'failure', 'waitPost'))){
            DB::beginTransaction();
            $info->delete();
            $this->smtProductDetailModel->where('productId',$id)->delete();
            $this->smtProductSkuModel->where('productId',$id)->delete();
            DB::commit();
            return array('status' => true, 'msg' => '删除成功');
           
        }else {
            return array('status' => false, 'msg' => '没有找到数据或数据状态错误');            
        }
    }
    
    /**
     * 批量删除草稿数据
     */
    public function batchDel(){
        $productIds = input::get('productIds');
        if (!empty($productIds)){
            $productIdArr = explode(',', $productIds);  
            foreach ($productIdArr as $id){
                $rs = $this->draftDel($id);
                if ($rs['status']){
                        $success[] = "草稿$id 删除成功";
                    }else{
                        $error[] = "草稿$id 删除失败，".$rs['msg'];
                    }
            }
            $msg = !empty($success) ? implode(';', $success) : '';
            $msg .= !empty($error) ? implode(';', $error) : '';
            echo json_encode(array('status' => true, 'msg' => $msg));
        }else {
            echo json_encode(array('status' => false, 'msg' => '非法操作'));
        }
        exit;
    }
   //json返回数据结构
   function ajax_return($info='', $status=1, $data='') {
       $result = array('data' => $data, 'info' => $info, 'status' => $status);
       exit( json_encode($result) );
   }
   
   //检测相关词汇是否违规
   public function findAeProductProhibitedWords($checkeinfo)
   {
   
       $productProperties =array();
       if(!empty($checkeinfo['aeopAeProductPropertys']))
       {
           $productpropertys = unserialize($checkeinfo['aeopAeProductPropertys']); 
           foreach($productpropertys as $pro)
           {      
               if(array_key_exists('attrValueId', $pro))
               {
                   $productProperties[]=$pro;
               }
               
           }
       }

       $keyword[]=$checkeinfo['keyword'];
       $keyword[]= $checkeinfo['productMoreKeywords1'];
       $keyword[]= $checkeinfo['productMoreKeywords2'];
   
       $categoryId = $checkeinfo['categoryId'];   
   
       $title =  $checkeinfo['subject'];
   
       $detail =htmlspecialchars_decode($checkeinfo['detail']);
       $detail = trim(strip_tags($detail));   
   
       $productProperties = json_encode($productProperties);
   
       $keywords=json_encode($keyword);
       
       //获取账号的信息
       $account = AccountModel::findOrFail($checkeinfo['token_id']);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);    
       $api='api.findAeProductProhibitedWords';   
       $parameters ='categoryId='.rawurlencode($categoryId).'&title='.rawurlencode($title).'&keywords='.rawurlencode($keywords).'&productProperties='.rawurlencode($productProperties).'&detail='.rawurlencode($detail);
   
       $result = $smtApi->getJsonData($api,$parameters); 
       $rs = json_decode($result, true);   
       if(isset($rs['productPropertiesProhibitedWords']))
       {
           $noproblem = true;
           $stringinfo = '';
           foreach($rs as $k=> $v)
           {
               if(!empty($v))
               {
                   foreach($v as $key=>$value)
                   {
                       $stringinfo=$stringinfo.'--'.$k.':'.$value['primaryWord'];
                       foreach($value['types'] as $v2)
                       {
                           $stringinfo=$stringinfo.':'.$v2;
                       }
                   }
                   $noproblem =false;
               }
           }
           if($noproblem)
           {
               return 'success';
           }
           else
           {
               return $stringinfo;
           }
       }
       else
       {
           if(isset($rs['error_message']))
           {
               return $rs['error_message'];
           }
           else
           {
               return '检查违禁词查失败';
           }
       }
   }
   
   public function copycheck($copyworld){
       $copyright= smtCopyright::where('is_del',1)->get();
       $copuSku = array();//侵权的SKU
       $copyTra = array();//侵权的品牌名
       foreach($copyright as $v){
           $world = trim($v->trademark);
           $reg = "/\b".$world."\b/i";
           $skuworld = trim($v->sku);
           $skureg = "/".$skuworld."/i";
           if(is_array($copyworld)){
               foreach($copyworld as $key=>$vx){
                   if($key === "skuCode"){
                       foreach($vx as $sku){
                           if(preg_match($skureg,$sku)){
                               if(!$skuworld){
                                   continue;
                               }
                               //return '('.$skuworld.')';
                               $copuSku[] = $skuworld;
                     		    }
                       }
                   }else{
                       if(preg_match($reg,$vx)){
                           if(!$world){
                               continue;
                           }
                           //return '('.$v['trademark'].')';
                           $copyTra[] = $v->trademark;
                       }
                   }    
               }
           }
       }
       $copuSku = implode(',', array_unique($copuSku));
       $skuMsg = 'sku:'.$copuSku;
       $copyTra = implode(',', array_unique($copyTra));
       $traMsg = '商标名:'.$copyTra;
       if($copuSku && !$copyTra){
           //只有侵权的SKU
           return $skuMsg;
       }elseif(!$copuSku && $copyTra){
           //只有侵权的品牌名
           return $traMsg;
       }elseif($copuSku && $copyTra){
           //都有
           return $skuMsg.'和'.$traMsg;
       }
       return 'success';
   } 
   
   /**
    * 创建关联产品模板
    * @param $relationIds 关联的产品id
    * @return string
    */
   private function createRelationTemplate($relationIds){
       $html = '';
   
       //获取产品单价，标题，图片信息
       if ($relationIds){
           $productIds = explode(';', $relationIds);
           //标题
           $product_list = $this->model->where('productId',$productIds)->lists('productId, subject');
           if ($product_list){
               //单价
               $price_list = $this->model->where('productId',$productIds)->groupBy('productId')->productSku()->lists('productId','price');;
   
               //图片
               $detail_list = $this->smtProductDetailModel->where('productId',$productIds)->lists('productId,imageURLs');
   
               //注意变更下里边的图片链接地址
               $pic_top = site_url("attachments/images/relation_header.jpg");
               $html_header = <<<html
<div style="background: #d00000;width: 775.0px;margin: 10.0px auto;">
    <img src="$pic_top" style="width: 100.0%;">
    <div style="background: #d00000;padding: 0px;font-size: 0.0px;">
html;
               $html_footer = <<<html
    </div>
</div>
html;
               $html_body = '';
               //先格式化下产品列表
               $productsArr = array();
               foreach ($product_list as $row){
                   $productsArr[$row['productId']] = $row;
               }
               unset($product_list);
               //按照传过来的产品id顺序进行排序
               foreach ($productIds as $productId){
                   if (!empty($productsArr[$productId])){
                       $row       = $productsArr[$productId]; //产品信息
                       $imageURLs = $detail_list[$productId]['imageURLs']; //图片链接
                       $imgList   = explode(';', $imageURLs); //图片列表
                       $firstImg  = array_shift($imgList); //要显示的第一张图片
                       $html_body .= '<div style="display: inline-block;width: 187.5px;background: #ffffff;margin: 0 0px 5.0px 5.0px;text-align: center;">
                                <table title="'.$row['subject'].'" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div style="width: 187.0px;height: 187.0px;border-bottom: 1.0px solid #cccccc;">
                                                    <a href="http://www.aliexpress.com/item/xxx/'.$productId.'.html" target="_blank">
                                                        <img alt="'.$row['subject'].'" src="'.$firstImg.'" height="100%" width="100%"></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="height: 107.0px;">
                                    <h5 style="height: 50.0px;font-size: 16.0px;">'.showSubject($row['subject']).'</h5>
                                    <h5 style="display: inline;font-size: 16.0px;">Price:</h5>
                                    <h5 style="font-size: 20.0px;display: inline;color: #ff6800;"> <b>$'.(isset($price_list[$productId]) ? $price_list[$productId] : 0).'</b>
                                    </h5>
                                </div>
                                <a href="http://www.aliexpress.com/item/xxx/'.$productId.'.html" target="_blank">
                                    <img src="http://g02.a.alicdn.com/kf/HTB14_BtHpXXXXaXXpXXq6xXFXXXC/222299605/HTB14_BtHpXXXXaXXpXXq6xXFXXXC.jpg">
                                </a>
                            </div>';
                   }
               }
               $html = $html_header.$html_body.$html_footer;
           }
       }
   
       return $html;
   }
   
   /**
    * 替换详情中的图片为相应图片银行的图片
    * @param $detail
    * @param $skuCode
    * @param int $id
    * @return mixed
    */
   public function replaceDetailPics($detail, $skuCode, $id=0){
       $api2   = 'api.uploadImage';
       preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/', $detail, $matches);
       
       $productData = $this->model->where('productId',$id)->first();
       $token_id = $productData->token_id;
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);
       if ($matches[2]) {
           foreach ($matches[2] as $k => $src) {
               if (!$src){
                   continue;
               }
               $return_data = $smtApi->uploadBankImage($api2, $src, $skuCode . '-' . $k);
               if ($return_data['success']) {
                   $detail = str_replace($src, $return_data['photobankUrl'], $detail);
               } else { //替换失败的话，看是否需要写点日志
   
               }
           }
       }
       return $detail;
   }
   
   /**
    * 上传一张图片到图片银行
    * @param unknown $src
    * @param unknown $skuCode
    * @param number $id
    * @return Ambigous <string, unknown>
    */
   public function uploadOnePicToBank($src, $skuCode, $id=0){
       $api2   = 'api.uploadImage';
       
       $productData = $this->model->where('productId',$id)->first();
       $token_id = $productData->token_id;
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);
       
       $return_data = $this->smt->uploadBankImage($api2, $src, $skuCode .rand(1000, 9999));
       if (!$return_data['success']) {//没上传成功，写下错误日志
   
       }
       return $return_data['success'] ? $return_data['photobankUrl'] : '';
   }
   
   /**
    * 调用上传临时图片接口替换图片
    * @param $img
    * @param $skuCode
    * @param $id:错误日志时需要用到
    * @return string
    */
   public function replaceTempPics($img, $skuCode, $id=0){
       $api      = 'api.uploadTempImage';
       $new_pic = '';
       //循环替换图片吧
       $productData = $this->model->where('productId',$id)->first();
       $token_id = $productData->token_id;
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);
       
       $img_return = $smtApi->uploadBankImage($api, $img, $skuCode);
       if ($img_return && $img_return['success']) {
           $new_pic = $img_return['url'];
       } else { //失败了，写下日志吧
   
       }
       return $new_pic;
   }
   
   /**
    * 异步显示本地子分类信息
    */
   public function showChildCategory()
   {
       $category_id = Input::get('category_id');
       $category_list = smtCategoryModel::where('pid',$category_id)->get();
       echo json_encode($category_list);
   }
   
   /**
    * 获取关键字推荐类目的分类名称信息--父分类也显示出去吧
    */
   public function showCommandCategoryList()
   {   
       $keyword  = trim(Input::get('keyword'));
       $token_id = trim(Input::get('token_id'));
       $category_list = $this->getOnlineCategoryId($token_id, $keyword);
       $this->smt_category = new smtCategoryModel();
       $rs = array();
       if ($category_list) {
           foreach ($category_list as $category_id) {
               //显示推荐的类目信息
               $rs[] = array('id' => $category_id, 'name' => $this->smt_category->getCateroryAndParentName($category_id));
           }
       }
       echo json_encode($rs);
   }
   
   /**
    * 根据关键词返回推荐子叶类目信息--在线获取的
    * @param $token_id
    * @param $keyword
    * @return array
    */
    public function getOnlineCategoryId($token_id, $keyword)
    {
        $result = '';
        $rs     = array(); //返回的数组
        $api    = 'api.recommendCategoryByKeyword';
        
        if ($token_id && $keyword) {
            $account = AccountModel::findOrFail($token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);   
            $result = $smtApi->getJsonData($api, 'keyword=' . rawurlencode($keyword));
        }
        $data = json_decode($result, true);
        if (isset($data['success']) && $data['total'] > 0) {
           $rs = $data['cateogryIds'];
        }
        
        return $rs;
    }
   
   /**
    * 根据SKU模糊查询并推荐产品列表
    */
   public function recommendProductList(){
       $sku = trim(Input::get('sku'));
       $skuArr = ProductModel::where('model','like','%'.$sku.'%')->groupBy('model')->lists('model');              
       $result = array('data' => $skuArr, 'status' => true);
       echo  json_encode($result);       
   }
   
   /**
    * 解析并保存产品数据 --只要解析变更的那部分，其他的基本不用管
    * @param $product
    * @return array
    */
   protected function hanleProductData($product){   
       //产品ID
       $productId = $product['productId'];
       $product_list_data                        = array();
       //$product_list_data['gmtCreate']           = date('Y-m-d H:i:s');
       $aeopAeProductSKUs                        = $product['aeopAeProductSKUs'];
       $product_list_data['multiattribute']      = (count($aeopAeProductSKUs) > 1 ? 1 : 0);
       $product_list_data['isRemove']            = '0';
   
       //处理广告详情信息
       $detail_data = array();
       if (array_key_exists('imageURLs', $product)){
           $detail_data['imageURLs']              = $product['imageURLs'];
       }
       if (array_key_exists('detailPicList', $product)){
           $detail_data['detailPicList']          = implode(';', $product['detailPicList']);
       }
       if (array_key_exists('detail', $product)){
           $detail_data['detail']                 = htmlspecialchars($product['detail']);
           $detail_data['detailLocal']            = htmlspecialchars($product['detail']);
       }
       if (array_key_exists('isImageDynamic', $product)){
           $detail_data['isImageDynamic'] = $product['isImageDynamic'];
       }
   
   
       DB::beginTransaction();
       //直接更新
       $this->smtProductDetailModel->where('productId',$productId)->update($detail_data);       
   
       $smtSkuCodeArr = array();
       $common = new common_helper();
       //处理广告SKU信息 --这要处理下
       foreach ($aeopAeProductSKUs as $sku){
           $smtSkuCodeArr[] = strtoupper(trim($sku['skuCode']));
           $sku_data['aeopSKUProperty'] = $sku['aeopSKUProperty'] ? serialize($sku['aeopSKUProperty']) : '';
           $skuMark = $productId.':'.$common->filterSmtProductSku($sku['skuCode']);
           $this->smtProductSkuModel->where('skuMark',$skuMark)->update($sku_data);           
       }
   
       $smtSkuCodeArr = array_unique($smtSkuCodeArr);
       if ($product_list_data['multiattribute'] == 1 && count($smtSkuCodeArr) == 1){
           $product_list_data['multiattribute'] = 0; //单属性设置
       }
       unset($smtSkuCodeArr);
   
       $this->model->where('productId',$productId)->update($product_list_data);
       DB::commit();
       return array('status' => true);
   }
   
   /**
    * 异步上传SKU目录
    */
   public function ajaxUploadDirImage(){      
       $token_id = Input::get('token_id');
       $dirName  = trim(Input::get('dirName'));
       $opt      = trim(Input::get('opt'));
   
       if (empty($token_id) || empty($dirName)) {
           $this->ajax_return('账号或者SKU不能为空', false);
       } 
       //本程序的上级目录
       $topDir = str_replace('\\', '/', dirname($_SERVER['DOCUMENT_ROOT']));
       //图片库中ebay图片的位置
       $ebayPicDir = $topDir . '/erp/imgServer/upload/SMT';
       $skuDir = $ebayPicDir . '/' . $dirName;
       if (strtoupper($opt) == 'SP'){
           $spArray = array('SP', 'sp', 'Sp', 'sP');
           $hasFlag = false;
           foreach ($spArray as $sp){
               $skuDir = $ebayPicDir . '/' . $dirName.'/'.$sp;
               if (file_exists($skuDir)){ //文件夹还是存在的
                   $hasFlag = true;
                   break;
               }
           }
           if (!$hasFlag){
               $this->ajax_return('SKU对应的文件夹不存在(若发现该SKU目录的名称含有小写，请让修改)', false);
           }
       }else {
           if (!file_exists($skuDir)) { //SKU对应的文件夹不存在
               $this->ajax_return('SKU对应的文件夹不存在(若发现该SKU目录的名称含有小写，请让修改)', false);
           }
       }
   
       if (!is_dir($skuDir)) {
           $this->ajax_return('SKU对应的信息不是文件夹，请检查路径', false);
       }
   
       $handle = opendir($skuDir);
   
       //图片扩展列表
       $common = new common_helper();
       $imageExt = $common->defineSmtImageExd();
   
       $api = 'api.uploadImage'; //上传到哪个图片接口
   
       $success = array();
       $error   = array();
   
       //获取要上传到的账号信息
       //获取账号的信息
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);    
       if (empty($account)) {
           $this->ajax_return('没有查找到账号信息', false);
       }
       while ($file = readdir($handle)) {
           if ($file != '.' && $file != '..' && !is_dir($skuDir . '/' . $file)) {
               $exd = strtolower($common->getFileExtendName($file));
               if (in_array($exd, $imageExt)) { //是速卖通的图片
                   $temp      = explode('.', $file);
                   $fileName  = array_shift($temp);
                   $imagePath = $skuDir . '/' . $file; //真实的图片路径
                   $result    = $smtApi->uploadBankImage($api, $imagePath, $fileName); //返回的图片结果
                   //print_r($result);exit;
                   //$result = array();
                   if (array_key_exists('status', $result) && ($result['status'] == 'SUCCESS' || $result['status'] == 'DUPLICATE')) {
                       $url       = $result['photobankUrl']; //返回的url链接
                       $success[] = $url;
                   } else {
                       $msg = $file;
                       if (array_key_exists('error_code', $result)){
                           $msg .= ',error_code:'.$result['error_code'].','.$result['error_message'];
                       }
                       $error[] = $msg; //失败的图片名称
                   }
               }
           }
       }
       closedir($handle);
   
       $this->ajax_return($error, true, $success);
   }
   
   /**
    * 异步上传一张图片到临时文件以添加自定义属性图片
    */
   public function ajaxUploadOneCustomPic(){
       $token_id = Input::get('token_id');
       $oldImg   = Input::get('img');
       if (empty($token_id)) {
           $this->ajax_return('上传失败，账号为空', false);
       }
   
       if (empty($oldImg)) {
           $this->ajax_return('上传失败，需要上传的图片链接为空', false);
       }
   
       //获取账号的信息
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);    
       if (empty($account)) {
           $this->ajax_return('上传失败,没有查到对应的账号信息', false);
       }   
       
       $api = 'api.uploadTempImage';  
       //上传图片到临时目录
       $result = $smtApi->uploadBankImage($api, $oldImg);
       if ($result && array_key_exists('success', $result) && $result['success']) {
           $this->ajax_return('', true, $result['url']);
       } else { //失败了，写下日志吧
           $this->ajax_return('error_code:' . $result['error_code'] . ',' . $result['error_message'], false);
       }
   }
   
   public function ajaxUploadDirImageByNewSys(){
       $token_id = request()->input('token_id');
       $dirName  = trim(request()->input('dirName'));
       $type = $_GET['type']; 
       if (empty($token_id) || empty($dirName)) {
           $this->ajax_return('账号或者SKU不能为空', false);
       }
   
       $result =  $this->uploadBankImageNewAll($dirName,$type);
       if(empty($result))
       {
           $this->ajax_return('未找到改SKU图片信息', false);
       }
       
       $error   = array();   
       $account = AccountModel::findOrFail($token_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);
       $api = 'api.uploadImage'; 
       $last_array = array();
       foreach($result as $re)
       {
           $res = $smtApi->uploadBankImage($api,$re['url'],$re['name']);
           if (array_key_exists('success',$res)&& $res['success']=='true') {
               $new_pic = $res['photobankUrl'];
               
               if(!empty($new_pic))
               {
                   $tmpImage = array();
                   $tmpImage['resize'] = str_replace('getSkuImageInfo-800resize', 'getSkuImageInfo-resize', $re['url']);
                   $tmpImage['remote'] = $new_pic;               
                   $last_array[] = $tmpImage;
               }
               else
               {
                   $this->ajax_return('检查账号图片银行空间是否还有空余', false, $last_array);
               }
           } else {
               $error = array('图片上传是失败'.$res['error_message']);
               $this->ajax_return($error, false, '');
              
           }          
       }
       $this->ajax_return($error, true, $last_array);
   }
   
   // $type= 1 取实拍图片 $type=2  取链接图
   public function uploadBankImageNewAll($dirName,$type)
   {
       $url='';
       if($type==1)
       {
           //$url ='http://120.24.100.157:3000/api/sku/'.$dirName.'?include-sub=true&distinct=true&tags=photo';
           $url = 'http://120.24.100.157:70/getSkuImageInfo/getSkuImageInfo.php?tags=photo&distinct=true&include_sub=true&sku='.$dirName;
       }
       if($type==2)
       {
           //$url ='http://120.24.100.157:3000/api/sku/'.$dirName.'?include-sub=true&distinct=true&tags=link';
           $url = 'http://120.24.100.157:70/getSkuImageInfo/getSkuImageInfo.php?tags=link&distinct=true&include_sub=true&sku='.$dirName;
   
       }   
         
       $result =$this->picCurl($url);

        $result = json_decode($result,true);

		$return_pic_array=array();
		
        if(!empty($result)){
        	foreach($result as $ke => $v){
        		//added by andy.
	          	$photo_name = $v['filename'];
	          	//$s_url = 'http://120.24.100.157:70/getSkuImageInfo/getSkuImage.php?id='.$photo_name;
	          	$s_url = 'http://imgurl.moonarstore.com/getSkuImageInfo-800resize/sku/'.$photo_name;
	            $return_pic_array[$ke]['url'] = $s_url;
	            $return_pic_array[$ke]['name'] = $s_url;
        	}
        }      
        
        return $return_pic_array;
   }
   
   public function picCurl($url)
   {
       $curl = curl_init();
   
       curl_setopt($curl, CURLOPT_URL, $url);
   
       curl_setopt($curl, CURLOPT_HEADER, 0);
   
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
   
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   
       $result = curl_exec($curl);
   
       curl_close($curl);
       return $result;
   
   }
   
   /**
    *  循环上传SKU图片
    * @param $img
    * @param $skuCode
    * @param $id:错误日志时需要用到
    * @return string
    */
   public function uploadBankImageNew($api,$img,$skuCode){
   
       $img_return = $this->smt->uploadBankImage($api, $img, $skuCode);
       $new_pic='';
       if (isset($img_return['success'])&& $img_return['success']=='true') {
           $new_pic = $img_return['photobankUrl'];
       } else { //失败了，写下日志吧
   
       }
       return $new_pic;
   }
   
   public function getskuinfo()
   {
       $sku = trim(Input::get('sku'));
       $suk_info = ItemModel::where('is_available',1)->where('sku','like','%'.$sku.'%')->first();
       $returnarr = array();
       $returnarr['weight'] = 0;
       $returnarr['length'] = 0;
       $returnarr['width'] = 0;
       $returnarr['height'] = 0;
       if($suk_info){
           $returnarr['weight'] = $suk_info->weight;
           $returnarr['length'] = $suk_info->length;
           $returnarr['width'] = $suk_info->width;
           $returnarr['height'] = $suk_info->height;
       }       
      
       $this->ajax_return('',1,$returnarr);   
   }
   
   /**
    * 批量保存为待发布状态
    */
   public function changeStatusToWait(){
       $product_ids = request()->input('product_ids');
      
       $product_ids_arr = explode(',', $product_ids);
       foreach($product_ids_arr as $product_id) {      
           $this->model->where('productId',$product_id)->update(['productStatusType' => 'waitPost']);
       }
       return 1;
   }
   
   /**
    * 批量修改待发布产品
    */
   public function batchModify(){
       $productIds = request()->input('productIds');
       $productInfo = request()->input('products');
       $productIdArr = explode(',', $productIds);
       $string = '';
       foreach($productIdArr as $productId){
           if(array_key_exists($productId, $productInfo)){
               $tmp = array();
               $tmp = $productInfo[$productId];
               $product = array();
               $detail = array();
               $product['grossWeight']= $tmp['grossWeight'];
               $product['productPrice'] = $tmp['productPrice'];
               $product['packageLength'] = $tmp['packageLength'];
               $product['packageWidth'] = $tmp['packageWidth'];
               $product['packageHeight'] = $tmp['packageHeight'];
               $this->model->where('productId',$productId)->update($product);

               $detail['keyword'] = $tmp['keyword'];
               $detail['productMoreKeywords1'] = $tmp['productMoreKeywords1'];
               $detail['productMoreKeywords2'] = $tmp['productMoreKeywords2'];
               $detail['productUnit'] = $tmp['productUnit'];
               $detail['promiseTemplateId'] = $tmp['promiseTemplateId'];
               $detail['freightTemplateId'] = $tmp['freightTemplateId'];
               $this->smtProductDetailModel->where('productId',$productId)->update($detail);
               $string .= $productId. '更新成功!';
           }
       }       
       return redirect($this->mainIndex)->with('alert', $this->alert('success', $string));
   }
   
   public function checkURLResourceTypeIsExit($url){
       $arr = parse_url($url);
       if(array_key_exists('scheme', $arr)){
           return true;
       }else{
           return false;
       }
   }
   
}

?>