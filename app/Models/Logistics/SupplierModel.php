<?php
/**
 * 物流商模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:11
 */

namespace App\Models\Logistics;

use Storage;
use App\Base\BaseModel;

class SupplierModel extends BaseModel
{
    public $table = 'logistics_suppliers';

    public $searchFields = ['name' => '物流商名称', 'customer_id' => '客户ID'];

    public $fillable = [
        'id',
        'name',
        'customer_id',
        'secret_key',
        'is_api',
        'client_manager',
        'manager_tel',
        'technician',
        'technician_tel',
        'remark',
        'bank',
        'card_number',
        'url',
        'password',
        'customer_service_name',
        'customer_service_qq',
        'customer_service_tel',
        'finance_name',
        'finance_qq',
        'finance_tel',
        'driver',
        'driver_tel',
        'logistics_collection_info_id',
        'credentials',
    ];

    public $rules = [
        'create' => [
            'name' => 'required',
            'customer_id' => 'required',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required',
            'technician_tel' => 'required',
            'url' => 'required',
            'password' => 'required',
            'customer_service_name' => 'required',
            'customer_service_qq' => 'required',
            'customer_service_tel' => 'required',
            'finance_name' => 'required',
            'finance_qq' => 'required',
            'finance_tel' => 'required',
            'driver' => 'required',
            'driver_tel' => 'required',
            'logistics_collection_info_id' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'customer_id' => 'required',
            'secret_key' => 'required',
            'is_api' => 'required',
            'client_manager' => 'required',
            'technician' => 'required',
            'manager_tel' => 'required',
            'technician_tel' => 'required',
            'url' => 'required',
            'password' => 'required',
            'customer_service_name' => 'required',
            'customer_service_qq' => 'required',
            'customer_service_tel' => 'required',
            'finance_name' => 'required',
            'finance_qq' => 'required',
            'finance_tel' => 'required',
            'driver' => 'required',
            'driver_tel' => 'required',
            'logistics_collection_info_id' => 'required',
        ],
    ];

    public function collectionInfo()
    {
        return $this->belongsTo('App\Models\Logistics\CollectionInfoModel', 'logistics_collection_info_id', 'id');
    }

    public function createSupplier($data, $file = null)
    {
        $path = 'uploads/supplier' . '/';
        if ($file != '' && $file->getClientOriginalName()) {
            $data['credentials'] = $path . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['credentials'], file_get_contents($file->getRealPath()));
        }
        return $this->create($data);
    }

    public function updateSupplier($id, $data, $file = null)
    {
        $path = 'uploads/supplier' . '/';
        if ($file != '' && $file->getClientOriginalName()) {
            $supplier = $this->where('id', $id)->first();
            $supplierPath = $supplier['credentials'];
            if($file->getClientOriginalExtension() != 'php') {
                $data['credentials'] = $path . time() . '.' . $file->getClientOriginalExtension();
                if($file->move($path, $data['credentials']) && $supplierPath != ''){
                    if(file_exists('./' . $path . $supplierPath)){
                        unlink('./' . $path . $supplierPath);
                    }
                }
            }
        }
        return $this->find($id)->update($data);
    }

}