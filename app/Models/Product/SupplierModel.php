<?php

namespace App\Models\Product;

use App\Base\BaseModel;
use Tool;
use App\Models\SyncApiModel;
use App\Models\Product\SupplierAttachmentModel;

class SupplierModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'product_suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'name',
        'url',
        'company',
        'official_url',
        'contact_name',
        'email',
        'province',
        'city',
        'address',
        'type',
        'telephone',
    /*    'purchase_id',*/
        'level_id',
        'created_by',
        'purchase_time',
        'bank_account',
        'bank_code',
        'pay_type',
        'qualifications',
        'examine_status',
        'qq',
        'wangwang',
        'founder'
    ];

    //查询
    public $searchFields = ['company'=>'公司名称', 'telephone'=>'手机','contact_name'=>'联系人','qq'=>'QQ','wangwang'=>'旺旺'];

    //验证规则
    public $rules = [
        'create' => [
            'company' => 'required|unique:product_suppliers,company',
/*            'name' => 'required|max:128|unique:product_suppliers,name',*/
            /*'purchase_id' => 'required|integer',*/
            'telephone' => 'required|max:256|digits_between:8,11',
            'purchase_time' => 'required|integer',
            /*'bank_account' => 'required|string',*/
        ],
        'update' => [
           // 'company' => 'required|unique:product_suppliers,company',
/*            'name' => 'required|max:128',*/
            /*'purchase_id' => 'required|integer',*/
            'telephone' => 'required|max:256|digits_between:8,11',
            'purchase_time' => 'required|integer',
            /*'bank_account' => 'required|string',*/

        ]
    ];
    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        //dd(UserModel::all()->pluck('name','name'));
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'product_suppliers.examine_status' => config('product.supplier.examine_status'),
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function purchaseName()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_id', 'id');
    }

    //获取供应商地址
    public function getSupplierAddressAttribute()
    {
        return $this->province . $this->city . $this->address;
    }

    public function getExamineStatusNameAttribute(){
        if(isset(config('product.supplier.examine_status')[$this->examine_status])){
            return config('product.supplier.examine_status')[$this->examine_status];
        }else{
            return '无审核状态';
        }
    }
    
    public function getLevelNameAttribute(){
        return isset(config('product.supplier.level')[$this->level_id]) ? config('product.supplier.level')[$this->level_id] : '无';
    }

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function createdByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'created_by', 'id');
    }

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function levelByName()
    {
        return $this->belongsTo('App\Models\Product\SupplierLevelModel', 'level_id', 'id');
    }

    public function attachment(){
        return $this->hasMany('App\Models\Product\SupplierAttachmentModel', 'supplier_id');
    }

    public function getCreatedNameAttribute(){
        $user = $this->createdByName;
        return $user ? $user->name : '无';
    }

    /**
     * 创建新供应商
     *
     *
     */
    public function supplierCreate($data)
    {
        $files = false;
        if(!empty($data['qualifications'])) {
            $files = $data['qualifications'];
            unset($data['qualifications']);
        }
        $data['examine_status'] = 'newData'; //新创建
        $create = $this->create($data);
        $path = config('product.product_supplier.file_path');

        if($files){
            foreach ($files as $file){
                if ($file->getClientOriginalName()) {
                    //$originalExtension = $file->getClientOriginalExtension();
                    $filename = Tool::randString(16,false) . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $filename);
                    $supplier_id = $create->id;
                    SupplierAttachmentModel::create(compact('supplier_id', 'filename'));
                }
            }
        }







/*        $post = [];
        //api同步sellmore 旧系统
        if(!empty($create->id)){
            $type_config =  array_flip(config('product.sellmore.pay_type'));
            $post['type'] = 'add';
            $post['key']  = 'slme';

            $post['suppliers_id']           = $create->id;
            $post['suppliers_company']      = $create->company;
            $post['suppliers_website']      = $create->official_url;
            $post['suppliers_address']      = $create->address;
            $post['suppliers_type']         = $create->type;
            $post['supplierArrivalMinDays'] = $create->purchase_time;
            $post['suppliers_bank']         = $create->bank_account;
            $post['suppliers_card_number']  = $create->bank_code;
            $post['pay_method']             = $type_config[$create->pay_type];  //付款方式
            $post['suppliers_name']         = $create->contact_name;
            $post['suppliers_mobile']       = $create->telephone;
            $post['suppliers_wangwang']     = $create->wangwang;
            $post['suppliers_qq']           = $create->qq;

            !empty($data['qualifications']) ? $post['attachment_url'] = request()->server()['HTTP_HOST'].'/'.$path . $data['qualifications'] : '';

            $sync = new SyncApiModel;
            $sync->relations_id = $post['suppliers_id'];
            $sync->type = 'supplier';
            $sync->url  = config('product.sellmore.api_url');
            $sync->data = serialize($post);
            $sync->status = 0;
            $sync->times = 0;
            $sync->save();
           // $result = Tool::postCurlHttpsData(config('product.sellmore.api_url'),$post);
        }*/
        return $create;
    }

    /**
     * 创建新供应商
     *
     *
     */
    public function updateSupplier($id, $data, $file = null)
    {
        $files = false;
        if(!empty($data['qualifications'])) {
            $files = $data['qualifications'];
            unset($data['qualifications']);
        }
        $supplier = $this->find($id);
        $res = $supplier->update($data);
        $path = config('product.product_supplier.file_path');

        if($files){
            foreach ($files as $item){
                if ($item->getClientOriginalName()) {
                    //$originalExtension = $file->getClientOriginalExtension();
                    $filename = Tool::randString(16,false) . '.' . $item->getClientOriginalExtension();
                    $item->move($path, $filename);
                    $supplier_id = $supplier->id;
                    SupplierAttachmentModel::create(compact('supplier_id', 'filename'));
                }
            }
        }

/*        if ($data['type'] == 0 && $file != null) { //线下类型
                if ($file->getClientOriginalName()) {
                    $itemInfo = $this->where('id',$id)->first();
                    $originPath = $itemInfo['qualifications']; //原来文件路径
                    $originalExtension = $file->getClientOriginalExtension();
                    if ($originalExtension != 'php') {
                        $data['qualifications'] = Tool::randString(16,false) . '.' . $file->getClientOriginalExtension();
                        if($file->move($path, $data['qualifications']) && $originPath != ''){
                            if(file_exists('./'.$path.$originPath)){
                                unlink('./'.$path.$originPath); //删除原来的文件
                            }
                        }
                    } else {
                        return 'imageError';
                    }
                }
        }else{
            $data['qualifications'] = '';
        }*/

/*
        if($res){//api同步sellmore 旧系统
            $suplier = $this->find($id);
            $type_config =  array_flip(config('product.sellmore.pay_type'));

            $post['type'] = 'update';
            $post['key']  = 'slme';
            $post['suppliers_id']           = $suplier->id;
            $post['suppliers_company']      = $suplier->company;
            $post['suppliers_website']      = $suplier->official_url;
            $post['suppliers_address']      = $suplier->address;
            $post['supplierArrivalMinDays'] = $suplier->purchase_time;
            $post['suppliers_bank']         = $suplier->bank_account;
            $post['suppliers_card_number']  = $suplier->bank_code;
            $post['pay_method']             = $type_config[$suplier->pay_type];
            $post['suppliers_type']         = $suplier->type;
            $post['suppliers_name']         = $suplier->contact_name;
            $post['suppliers_mobile']       = $suplier->telephone;
            $post['suppliers_wangwang']     = $suplier->wangwang;
            $post['suppliers_qq']           = $suplier->qq;

            !empty($suplier->qualifications) ? $post['attachment_url'] = request()->server()['HTTP_HOST'].'/'.$path . $suplier->qualifications : '';

            $sync = new SyncApiModel;
            $sync->relations_id = $post['suppliers_id'];
            $sync->type = 'supplier';
            $sync->url  = config('product.sellmore.api_url');
            $sync->data = serialize($post);
            $sync->status = 0;
            $sync->times = 0;
            $sync->save();

            //$result = Tool::postCurlHttpsData(config('product.sellmore.api_url'),$post);
        }*/
        return $res;
    }
}
