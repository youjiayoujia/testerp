<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\ProductModel;
use DB;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Spu\SpuMultiOptionModel;

class SpuModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'spus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'spu','product_require_id','status','edit_user','image_edit','developer','purchase','remark'];

    public $searchFields = ['id' =>'ID','spu'=>'spu'];

    public function values()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function products()
    {
        return $this->hasMany('App\Models\ProductModel', 'spu_id', 'id');
    }

    public function editUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'edit_user');
    }

    public function imageEdit()
    {
        return $this->belongsTo('App\Models\UserModel', 'image_edit');
    }

    public function Purchase()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase');
    }

    public function Developer()
    {
        return $this->belongsTo('App\Models\UserModel', 'developer');
    }

    public function spuMultiOption()
    {
        return $this->hasMany('App\Models\Spu\SpuMultiOptionModel', 'spu_id');
    }

    public function productRequire()
    {
        return $this->belongsTo('App\Models\Product\RequireModel', 'product_require_id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['spuMultiOption' => ['en_description', 'de_description', 'fr_description', 'it_description', 'zh_description']],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    /**
     * 更新多渠道多语言信息
     * 2016年6月3日10:43:18 YJ
     * @param array data 修改的信息
     */
    public function updateMulti($data)
    {   
        foreach ($data['info'] as $channel_id => $language) {
            $arr = [];
            $pre=[];
            $pre = $language['language'];
            foreach ($language as $prefix => $value) {
                $arr[$pre."_".$prefix] = $value;
            }
            
            $model = $this->spuMultiOption()->where("channel_id", $channel_id)->first();
            if($model){
                if($arr[$pre."_name"]!=''||$arr[$pre."_keywords"]!=''||$arr[$pre."_description"]!=''){
                    $model->update($arr);
                }   
            }
           
        }
    }

    public function insertLan()
    {
        $spus = $this->all();
        DB::table('spu_multi_options')->truncate();
        foreach ($spus as $spu) {
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>1]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>2]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>3]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>4]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>5]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>6]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>7]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>8]);
        }
    }

    public function test()
    {   
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $erp_products_data_arr = DB::select('select * from erp_products_data where spu!="" and products_id > 107842');
        foreach ($erp_products_data_arr as $key => $erp_products_data) {
            //print_r($erp_products_data);exit;
                //if($key==0)continue;
                //print_r($value);exit;
                if(count(ItemModel::where('sku',$erp_products_data->products_sku)->get()))continue;
                if($erp_products_data->products_location!=''){
                    $position['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';
                    $position['name'] = $erp_products_data->products_location;
                    $position['is_available'] = 1;
                    if(!count(PositionModel::where('name',$erp_products_data->products_location)->get())){
                        PositionModel::create($position);
                    }
                }
                
                $spuData['spu'] = $erp_products_data->spu;
                //创建spu
                if(count(SpuModel::where('spu',$erp_products_data->spu)->get())){
                    $spu_id = SpuModel::where('spu',$erp_products_data->spu)->get()->toArray()[0]['id'];
                }else{
                    $spuModel = $this->create($spuData);
                    $spu_id = $spuModel->id;
                }
                

                $productData['model'] = $erp_products_data->model;
                $productData['spu_id'] = $spu_id;
                $productData['name'] = $erp_products_data->products_name_en;
                $productData['c_name'] = $erp_products_data->products_name_cn;
                //$catalog_id = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                //print_r($catalog_id);exit;
                //$productData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $productData['supplier_id'] = $erp_products_data->products_suppliers_id;
                
                $productData['purchase_url'] = $erp_products_data->products_more_img;
                //$productData['purchase_day'] = $value['10'];
                //$productData['product_sale_url'] = $value['11'];
                $productData['notify'] = $erp_products_data->products_warring_string;
                //采购价
                $productData['purchase_price'] = $erp_products_data->products_value;
                $productData['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';

                $volume = unserialize($erp_products_data->products_volume);

                if($volume!=''){

                    if(!array_key_exists('bp', $volume)){
                        $volume['bp']['length'] = 0;
                        $volume['bp']['width'] =0;
                        $volume['bp']['height'] =0;
                    }
                    if(!array_key_exists('ap', $volume)){
                        $volume['ap']['length'] = 0;
                        $volume['ap']['width'] =0;
                        $volume['ap']['height'] =0;
                    }
                    
                    $productData['package_height'] = $volume['ap']['length'];
                    $productData['package_width'] = $volume['ap']['width'];
                    $productData['package_length'] = $volume['ap']['height'];
                    $productData['height'] = $volume['bp']['length'];
                    $productData['width'] = $volume['bp']['width'];
                    $productData['length'] = $volume['bp']['height'];
                }else{
                    $productData['package_height'] = 0;
                    $productData['package_width'] = 0;
                    $productData['package_length'] = 0;
                    $productData['height'] = 0;
                    $productData['width'] = 0;
                    $productData['length'] = 0;
                }
                
                //创建model
                if(count(ProductModel::where('model',$erp_products_data->model)->get())){
                    $product_id = ProductModel::where('model',$erp_products_data->model)->get()->toArray()[0]['id'];
                }else{
                    $productModel = ProductModel::create($productData);
                    $product_id = $productModel->id;
                    if($erp_products_data->pack_method!=''){
                        $wrr['wrap_limits_id'] = $erp_products_data->pack_method;
                        $productModel->wrapLimit()->attach($wrr); 
                    }
                }

                $skuData['product_id'] = $product_id;
                //$skuData['catalog_id'] = CatalogModel::where('c_name',$value['6'])->get(['id'])->first()->id;
                $skuData['sku'] = $erp_products_data->products_sku;
                $skuData['name'] = $erp_products_data->products_name_en;
                $skuData['c_name'] = $erp_products_data->products_name_cn;
                $skuData['weight'] = $erp_products_data->products_weight;
                $skuData['warehouse_id'] = $erp_products_data->product_warehouse_id==1000?'1':'2';
                $skuData['warehouse_position'] = $erp_products_data->products_location;
                $skuData['supplier_id'] = $erp_products_data->products_suppliers_id;
                $skuData['purchase_url'] = $erp_products_data->products_more_img;
                $skuData['purchase_price'] = $erp_products_data->products_value;
                //$skuData['purchase_adminer'] = $value['22'];
                $skuData['cost'] = $erp_products_data->products_value;

                $skuData['height'] = $productData['height'];
                $skuData['width'] = $productData['width'];
                $skuData['length'] = $productData['length'];
                $skuData['package_height'] = $productData['package_height'];
                $skuData['package_width'] = $productData['package_width'];
                $skuData['package_length'] =$productData['package_length'];
                
                $skuData['status'] =$erp_products_data->products_status_2;
                $skuData['is_available'] = $erp_products_data->productsIsActive; 
                //$skuData['remark'] = $value['41'];
                //创建sku
                $itemModel = ItemModel::create($skuData);
                foreach(explode(',',$erp_products_data->products_suppliers_ids) as $_supplier_id){
                    //print_r($itemModel->skuPrepareSupplier());exit;
                    $arr['supplier_id'] = $_supplier_id;
                    $itemModel->skuPrepareSupplier()->attach($arr);
                }

                
            }
    }

    //重新根据sku生成model和spu
    public function test1()
    {   
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        //$model = $this->all();
        $model = ItemModel::where('id','>','49976')->get();
        //$erp_products_data_arr = DB::select('select distinct products_sku,spu,model,products_warring_string from erp_products_data where spu!=""');
        foreach($model as $itemModel){
            //print_r($itemModel->sku);exit;
            $erp_data_arr = DB::select('select products_sku,products_declared_en,products_declared_cn,spu,model,products_warring_string from erp_products_data where products_sku="'.$itemModel->sku.'" and spu!=""');
            if(count($erp_data_arr)){
                $erp_data = $erp_data_arr[0];
                $spuData['spu'] = $erp_data->spu;
                //创建spu
                if(count(SpuModel::where('spu',$erp_data->spu)->get())){
                    $spu_id = SpuModel::where('spu',$erp_data->spu)->get()->toArray()[0]['id'];
                }else{
                    $spuModel = $this->create($spuData);
                    $spu_id = $spuModel->id;
                }


                $productData['model'] = $erp_data->model;
                $productData['spu_id'] = $spu_id;
                $productData['name'] = $itemModel->name;
                $productData['c_name'] = $itemModel->c_name;
                $productData['catalog_id'] = $itemModel->catalog_id;
                $productData['supplier_id'] = $itemModel->supplier_id;
                $productData['purchase_url'] = $itemModel->purchase_url;
                //$productData['purchase_day'] = $value['10'];
                //$productData['product_sale_url'] = $value['11'];
                $productData['notify'] = $erp_data->products_warring_string;
                $productData['declared_en'] = $erp_data->products_declared_en?$erp_data->products_declared_en:'';
                $productData['declared_cn'] = $erp_data->products_declared_cn?$erp_data->products_declared_cn:'';
                //采购价
                $productData['purchase_price'] = $itemModel->purchase_price;
                $productData['warehouse_id'] = $itemModel->warehouse_id;   
                $productData['package_height'] = $itemModel->package_height;
                $productData['package_width'] = $itemModel->package_width;
                $productData['package_length'] = $itemModel->package_length;
                $productData['height'] = $itemModel->height;
                $productData['width'] = $itemModel->width;
                $productData['length'] = $itemModel->length;

                //创建model
                if(count(ProductModel::where('model',$erp_data->model)->get())){
                    $product_id = ProductModel::where('model',$erp_data->model)->get()->toArray()[0]['id'];
                }else{
                    $productModel = ProductModel::create($productData);
                    $product_id = $productModel->id;
                }
                $itemModel->update(['product_id'=>$product_id]);
            }else{
                $itemModel->update(['product_id'=>0]);
            }
            
        }     
    }

    public function test2()
    { 
        $erp_products_data_arr = DB::select('select distinct products_sku,spu,model,products_declared_cn,products_declared_en 
            from erp_products_data where spu!=""');
        foreach($erp_products_data_arr as $erp_data){
            $data = [];
            $data['declared_cn'] = $erp_data->products_declared_cn;
            $data['declared_en'] = $erp_data->products_declared_en;
            $itemModel = ItemModel::where('sku',$erp_data->products_sku)->get()->first();
            if(count($itemModel)){
                $itemModel->product->update($data);
            }
        }        
    }


}