<?php
/**
 * item控制器
 *
 * User: youjia
 * Date: 16/1/18
 * Time: 09:32:00
 */

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\ProductModel;
use App\Models\SpuModel;
use App\Models\Product\ImageModel;
use App\Models\Product\SupplierModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\LimitsModel;
use App\Models\WrapLimitsModel;
use App\Models\RecieveWrapsModel;
use App\Models\CatalogModel;
use App\Models\UserModel;
use App\Models\Warehouse\PositionModel;
use Excel;
use App\Models\ChannelModel;
use App\Models\Item\SkuMessageModel;
use App\Models\SyncApiModel;
use App\Models\Channel\CatalogRatesModel;
use App\Models\product\CatalogCategoryModel;

class ItemController extends Controller
{
    public function __construct(ItemModel $item,SupplierModel $supplier,ProductModel $product,WarehouseModel $warehouse,LimitsModel $limitsModel,WrapLimitsModel $wrapLimitsModel, SkuMessageModel $message, ImageModel $imageModel)
    {
        $this->model     = $item;
        $this->supplier  = $supplier;
        $this->product   = $product;
        $this->warehouse = $warehouse;
        $this->message = $message;
        $this->image = $imageModel;
        $this->logisticsLimit = $limitsModel;
        $this->wrapLimit = $wrapLimitsModel;
        $this->mainIndex = route('item.index');
        $this->mainTitle = '产品SKU';
        $this->viewPath  = 'item.';
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $hideUrl = $_SERVER['HTTP_REFERER'];
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logisticsLimit_arr = [];
        foreach($model->product->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['pivot']['logistics_limits_id'];              
        }
        $wrapLimit_arr = [];
        foreach($model->product->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['pivot']['wrap_limits_id'];               
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            //'suppliers' => $this->supplier->all(),
            'warehouses' => $this->warehouse->where('type','local')->get(),
            'wrapLimit' => $this->wrapLimit->all(),
            'logisticsLimit' => $this->logisticsLimit->all(),
            'wrapLimit_arr' => $wrapLimit_arr,
            'logisticsLimit_arr' => $logisticsLimit_arr,
            'hideUrl' => $hideUrl,
            'recieveWraps' => RecieveWrapsModel::all(),

        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $data = request()->all();
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data['sku_history_values'] = $model->sku_history_values;
        $data['sku_history_values'] .= ','.$data['purchase_price'];
        
        $model->updateItem($data);

        $data['products_with_battery'] = 0;
        $data['products_with_adapter'] = 0;
        $data['products_with_fluid'] = 0;
        $data['products_with_powder'] = 0;
        if(array_key_exists('carriage_limit_arr', $data)){
            foreach($data['carriage_limit_arr'] as $logistics_limits_id){
                $brr[] = $logistics_limits_id;         
            }
            $model->product->logisticsLimit()->sync($brr);
            //回传旧系统
            if(in_array('1', $data['carriage_limit_arr']))$data['products_with_battery'] = 1;
            if(in_array('4', $data['carriage_limit_arr']))$data['products_with_adapter'] = 1;
            if(in_array('5', $data['carriage_limit_arr']))$data['products_with_fluid'] = 1;
            if(in_array('2', $data['carriage_limit_arr']))$data['products_with_powder'] = 1;
        }

        $arr = [];
        if(array_key_exists('package_limit_arr', $data)){
            foreach($data['package_limit_arr'] as $wrap_limits_id){
                $arr[] = $wrap_limits_id;         
            }
            $model->product->wrapLimit()->sync($arr);
        }

        //回传老系统
        $old_data['pack_method'] = serialize($arr);
        $old_data['products_name_en'] = $model->name;
        $old_data['products_name_cn'] = $model->c_name;
        $old_data['products_sku'] = $model->sku;
        $old_data['products_sort'] = $model->product->catalog?$model->product->catalog->name:'异常';
        $old_data['products_declared_en'] = $model->product->declared_en;
        $old_data['products_declared_cn'] = $model->product->declared_cn;
        $old_data['products_value'] = $model->purchase_price;
        $old_data['products_weight'] = $model->weight;
        $old_data['weightWithPacket'] = $model->package_weight;
        $old_data['products_suppliers_id'] = $model->supplier_id;
        $old_data['products_check_standard'] = $model->quality_standard;

        $old_data['product_warehouse_id'] = $model->warehouse_id;
        $old_data['products_location'] = $model->warehouse_position;
        $old_data['products_more_img'] = $model->purchase_url;

        $old_data['products_with_battery'] = $data['products_with_battery'];
        $old_data['products_with_adapter'] = $data['products_with_adapter'];
        $old_data['products_with_fluid'] = $data['products_with_fluid'];
        $old_data['products_with_powder'] = $data['products_with_powder'];
        $old_data['productsPhotoStandard'] = $model->product->competition_url;
        $old_data['products_remark_2'] = $model->product->notify;
        $old_data['productsLastModify '] = $model->updated_at->format('Y-m-d H:i:s');
        $old_data['dev_uid'] = $model->product->spu->developer;
        $old_data['type'] = 'edit';

        /*$url="http://120.24.100.157:60/api/products.php";
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $old_data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60); 
        $buf = curl_exec($c);*/

        $sync = new SyncApiModel;
        $sync->relations_id = $model->id;
        $sync->type = 'product';
        $sync->url  = 'http://120.24.100.157:60/api/products.php';
        $sync->data = serialize($old_data);
        $sync->status = 0;
        $sync->times = 0;
        $sync->save();

        $to = json_encode($model);
        $this->eventLog($userName->name, 'item信息更新,id='.$model->id, $to, $from);
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', 'sku ' . $model->sku . '编辑成功.'));
    }

    public function sectionGangedDouble()
    {
        $val = trim(request('val'));
        $model = CatalogCategoryModel::where('cn_name', $val)->first();
        if (!$model) {
            return false;
        }
        $str = "<option value=''>二级分类</option>";
        foreach ($model->catalogs as $catalog) {
            $str .= "<option value='" . $catalog->id . "'>" . $catalog->c_name . "</option>";
        }
        return $str;
    }

    public function skuHandleApi()
    {
        $data = request()->all();
        if($data['type']=='edit'){
            $arr = [];
            $brr = [];
            $data['purchase_adminer'] = UserModel::where('name',$data['purchase_name'])->get()->first()['id'];
            $data['developer'] = UserModel::where('name',$data['dev_name'])->get()->first()['id'];
            $skuModel = $this->model->where('sku',$data['sku'])->get()->first();
            if(count($skuModel)==0){
                echo json_encode('no sku');exit;
            }
            $skuModel->update($data);
            foreach(unserialize($data['carriage_limit_arr']) as $logistics_limits_id){
                $brr[] = $logistics_limits_id;         
            }
            $skuModel->product->logisticsLimit()->sync($brr);
            foreach(unserialize($data['package_limit_arr']) as $wrap_limits_id){
                $arr[] = $wrap_limits_id;         
            }
            $skuModel->product->wrapLimit()->sync($arr);
            $skuModel->product->update($data);
            $data['status'] = 0;
            $skuModel->product->spu->update($data);
        }else{
            $arr = [];
            $brr = [];
            $data['purchase_adminer'] = UserModel::where('name',$data['purchase_name'])->get()->first()['id'];
            $data['developer'] = UserModel::where('name',$data['dev_name'])->get()->first()['id'];
            $spuModel = SpuModel::create($data);
            $data['spu_id'] = $spuModel->id;
            $productModel = ProductModel::create($data);
            $data['product_id'] = $productModel->id; 
            $skuModel = $this->model->create($data);
            foreach(unserialize($data['carriage_limit_arr']) as $logistics_limits_id){
                $brr[] = $logistics_limits_id;         
            }
            $skuModel->product->logisticsLimit()->attach($brr);
            foreach(unserialize($data['package_limit_arr']) as $wrap_limits_id){
                $arr[] = $wrap_limits_id;         
            }
            $skuModel->product->wrapLimit()->attach($arr);
            $data['status'] = 0;
            $skuModel->product->spu->update($data);
        }
        echo json_encode('success');exit;

    }

    public function skuSupplierApi()
    {
        $data = request()->all();
        $itemModel = $this->where('sku',$data['sku'])->get()->first();
        if(count($itemModel)){
            $itemModel->skuPrepareSupplier()->sync($data['supplier_ids']);
            echo json_encode('success');
        }else{
            echo json_encode('sku不存在');
        }
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logisticsLimit_arr = [];
        foreach($model->product->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['ico'];              
        }
        
        $wrapLimit_arr = [];
        foreach($model->product->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['name'];               
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'warehouse' => $this->warehouse->find($model->warehouse_id),
            'logisticsLimit_arr' => $logisticsLimit_arr,
            'wrapLimit_arr' => $wrapLimit_arr,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 获取供应商信息
     */
    public function ajaxSupplierUser()
    {
        $item_id = request()->input('item_id');
        $model = $this->model->find($item_id);
        $user_array = ItemModel::where('supplier_id',$model->supplier_id)->distinct()->get();
        $in = [];
        foreach ($user_array as $array) {
            $in[] = $array->purchase_adminer;
        }

        if(request()->ajax()) {
            $user = trim(request()->input('user'));
            $buf = UserModel::where('name', 'like', '%'.$user.'%')->whereIn('id',$in)->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }
        return json_encode(false);
    }

    /**
     * 更新采购员
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePurchaseAdmin($item_id)
    {
        $user_name = request()->input('manual_name');
        $user_id = request()->input('purchase_adminer');
        $model = $this->model->find($item_id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if($user_id){
            $model->update(['purchase_adminer'=>$user_id]);
            $to = json_encode($model);
            $this->eventLog($userName->name, '采购人员更新,id='.$model->id, $to, $from);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
        }else{
            $userModel = UserModel::where('name',$user_name)->first();
            if($userModel){
                $model->update(['purchase_adminer'=>$userModel->id]);
                $to = json_encode($model);
                $this->eventLog($userName->name, '采购人员更新,id='.$model->id, $to, $from);
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
            }else{
                $to = json_encode($model);
                $this->eventLog($userName->name, '采购人员更新,id='.$model->id, $to, $from);
                return redirect($this->mainIndex)->with('alert', $this->alert('danger','该用户不存在.'));
            }
        }

    }

    /**
     * 批量更新界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batchEdit()
    {
        $item_ids = request()->input("item_ids");
        $arr = explode(',', $item_ids);
        $param = request()->input('param');
        
        $skus = $this->model->whereIn("id",$arr)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'skus' => $skus,
            'item_ids'=>$item_ids,
            'param'  =>$param,
            'catalogs'=>CatalogModel::all(),
        ];
        return view($this->viewPath . 'batchEdit', $response);
    }

    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batchUpdate()
    {
        $item_ids = request()->input("item_ids");
        $arr = explode(',', $item_ids);
        $skus = $this->model->whereIn("id",$arr)->get();
        $data = request()->all();
        if(!array_key_exists('productsVolume',$data)){
            foreach($skus as $itemModel){
                $itemModel->update($data);
            }       
            return redirect($this->mainIndex);
        }else{
            $data['length'] = $data['productsVolume']['bp']['length'];
            $data['width'] = $data['productsVolume']['bp']['width'];
            $data['height'] = $data['productsVolume']['bp']['height'];
            $data['package_length'] = $data['productsVolume']['ap']['length'];
            $data['package_width'] = $data['productsVolume']['ap']['width'];
            $data['package_height'] = $data['productsVolume']['ap']['height'];
            $data['weight'] = $data['products_weight2'];
            //echo '<pre>';
            //print_r($data);exit;
            foreach($skus as $itemModel){
                $itemModel->update($data);
            }       
            return redirect($this->mainIndex);
        }    
    }


    public function getImage()
    {
        $item = $this->model->where('sku',trim(request('sku')))->first();
        if(!$item) {
            return json_encode(false);
        }
        if($item)
            return ('/'.$item->product->dimage);
        else 
            return json_encode(false);
    }

    public function getModel()
    {
        $sku = trim(request('sku'));
        $model = $this->model->where('sku', $sku)->first();
        return json_encode($model);
    }

    /**
     * 打印产品
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printsku()
    {
        $item_id = request()->input("id");
        $model = $this->model->find($item_id);
        $response['model']= $model;
        $response['from'] = 'sku';
        return view($this->viewPath . 'printsku', $response);
    }

    /**
     * 上传表格修改sku状态
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadSku()
    {
        $status = request()->input('spu_status'); 
        if(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.')+1)!='csv'){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请上传csv表格!'));
        }
        if(empty($_FILES['upload']['tmp_name'])) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请上传表格!'));
        }
        $csv = Excel::load($_FILES['upload']['tmp_name'])->noHeading()->toArray();
        $result = '';

        foreach ($csv as $key => $spu) {
            $sku_array = $this->model->where('sku', 'like', '%'.$spu['1'].'%')->get();

            if(count($sku_array)){
                foreach ($sku_array as $sku) {
                    $sku->update(['status'=>$status]);
                }
            }else{
                $result .= ($key+1).',';
            }
           
        }

        if(!$result){
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '状态修改为.'.config('item.status')[$status]));   
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '第'.substr($result,0,strlen($result)-1)."行SPU不存在，请重新上传"));
        }
        
    }
    //批量删除sku
    public function batchDelete()
    {
        $item_ids = request()->input('item_ids');
        $item_ids = explode(',', $item_ids);
        foreach ($item_ids as $key => $item_id) {
            $model = $this->model->find($item_id);
            if (!$model) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
            }
            $model->destroy($item_id);
        }

        return 1;
    }
    public function index()
    {
        request()->flash();
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $itemUrl = route('item.index');
        if($url == $itemUrl){
            $item = $this->model->where('id',0);
        }else{
            $item = $this->model->with('catalog','warehouse','supplier','product','product.spu','purchaseAdminer','warehousePosition','product.wrapLimit');
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$item),
            'mixedSearchFields' => $this->model->mixed_search,
            'warehouses' => $this->warehouse->all(),
            'channels' => ChannelModel::all(),
            'Compute_channels' => CatalogRatesModel::all(),

        ];
        return view($this->viewPath . 'index', $response);
    }

    public function question($item_id)
    {
        $content = request()->input('question_content');
        $question_group = request()->input('question_group');
        $data['sku_id'] = $item_id;
        $data['question_group'] = $question_group;
        $data['question'] = $content;
        $data['question_time'] = date('Y-m-d H:i:s',time());
        $data['question_user'] = request()->user()->id;
        $data['status'] = 'pending';
        $data['image'] = $this->image->skuMessageImage(request()->file('uploadImage'));
        $messageModel = $this->message->create($data);
        return redirect($this->mainIndex);
    }

    public function extraQuestion()
    {
        $content = request()->input('extra_content');
        $data['extra_question'] = $content;
        $id = request()->input('id');
        $sku_message = $this->message->find($id);
        $sku_message->update($data);
        return redirect(route('item.questionIndex'));
    }

    public function questionIndex()
    {
        request()->flash();
        $this->mainIndex = route('item.questionIndex');
        $this->mainTitle = '产品留言板';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->message),
            'mixedSearchFields' => $this->message->mixed_search,
        ];
        return view($this->viewPath . 'questionIndex', $response);
    }

    public function questionStatus()
    {
        $question_ids = request()->input('question_ids');
        $status = request()->input('status');
        $arr = explode(',', $question_ids); 
        
        foreach ($arr as $id) {
            $sku_message = $this->message->find($id);
            $sku_message->update(['status'=>$status]);
        }
        
        return 1;
    }

    public function answer()
    {
        $content = request()->input('answer_content');
        $id = request()->input('id');
        $sku_message = $this->message->find($id);
        $data['answer'] = $content;
        $data['answer_date'] = date('Y-m-d H:i:s',time());
        $data['answer_user'] = request()->user()->id;
        $data['status'] = 'close';
        $sku_message->update($data);
        return redirect(route('item.questionIndex'));
    }

    //添加供应商
    public function addSupplier($item_id)
    {
        $supplier_id = request()->input('supplier_id');
        $model = $this->model->with('skuPrepareSupplier')->find($item_id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        $arr['supplier_id'] = $supplier_id;
        $model->skuPrepareSupplier()->attach($arr);
        $to = json_encode($model);
        $this->eventLog($userName->name, '添加供应商,id='.$model->id, $to, $from);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '备选供应商添加成功.'));
    }

    public function curlApiChangeWarehousePositon()
    {
        $data = request()->all();
        $positionModel = PositionModel::where('name',$data['products_location'])->get()->first();
        if(!$positionModel){
            echo json_encode(['msg'=>'库位不存在']);exit;
        }
        $itemModel = $this->model->where('sku',$data['products_sku'])->get()->first();
        if(!$itemModel){
            echo json_encode(['msg'=>'sku不存在']);exit;
        }
        $warehouse_position_id = $positionModel->id;
        $warehouse_id = $data['product_warehouse_id']==1000?1:2;
        $result = $itemModel->update(['warehouse_id'=>$warehouse_id,'warehouse_position'=>$warehouse_position_id]);
        echo json_encode(['msg'=>'修改库位成功']);exit;
    }

    public function oneKeyUpdateSku()
    {
        $this->model->oneKeyUpdateSku();

    }

    //修改转新品状态
    public function changeNewSku($id)
    {
        $url = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $new_status = request()->input('new_status');
        $model->update(['new_status'=>$new_status]);
        $remark = '已取消转新品';
        if($new_status){
            $remark = '已转新品';
        }
        
        return redirect($url)->with('alert', $this->alert('success', $model->sku.$remark));
        //print_r($new_status);exit;
    }

}