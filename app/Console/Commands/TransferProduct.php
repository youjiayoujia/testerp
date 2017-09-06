<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\ProductModel as smProduct;
use App\Models\Sellmore\CatalogModel as smCatalog;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;
use App\Models\Warehouse\PositionModel;
use App\Models\SpuModel;
use App\Models\ProductModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\CatalogModel;

class TransferProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (WarehouseModel::count() < 1) {
            $this->error('先导入仓库信息,深圳仓ID=1,义乌仓ID=2');
        }
        if (SupplierModel::count() < 1) {
            $this->error('先导入供货商信息');
        }
        $len = 1000;
        $start = 0;
        $createdNum[0] = 0;
        $updatedNum[0] = 0;
        $createdNum[1] = 0;
        $updatedNum[1] = 0;
        $originNum = 0;
        $createdNum[2] = 0;
        $updatedNum[2] = 0;
        $createdNum[3] = 0;
        $updatedNum[3] = 0;
        $catalogNum = 0;
        $len = 100;
        $start = 0;
        $smCatelogs = smCatalog::skip($start)->take($len)->get();
        while ($smCatelogs->count()) {
            $start += $len;
            foreach ($smCatelogs as $smCatelog) {
                $catalogNum++;
                $supplier = [
                    'id' => $smCatelog->category_id,
                    'name' => $smCatelog->category_name_en,
                    'c_name' => $smCatelog->category_name,
                ];
                $exist = CatalogModel::where(['id' => $smCatelog->category_id])->first();
                if($exist) {
                    $exist->update($supplier);
                    $updatedNum[3]++;
                } else {
                    CatalogModel::create($supplier);
                    $createdNum[3]++;
                }
            }
            $smCatelogs = smCatalog::skip($start)->take($len)->get();
        }
        $this->info('Transfer [catalog]: Origin:'.$catalogNum.' => Created:'.$createdNum[3].' Updated:'.$updatedNum[3]);

        $smProducts = smProduct::skip($start)->take($len)->get();
        while ($smProducts->count()) {
            $start += $len;
            foreach ($smProducts as $smProduct) {
                $originNum++;
                $spu = SpuModel::where(['spu' => $smProduct->products_sku])->first();
                if($spu) {
                    $updatedNum[0]++;
                    $spu->update(['spu' => $smProduct->products_sku]);
                } else {
                    $createdNum[0]++;
                    $spu = SpuModel::create(['spu' => $smProduct->products_sku]);
                }
                $arr = [];
                if ($smProduct->products_with_battery) {
                    $arr[] = 1;
                }
                if ($smProduct->products_with_adapter) {
                    $arr[] = 4;
                }
                if ($smProduct->products_with_fluid) {
                    $arr[] = 5;
                }
                if ($smProduct->products_with_powder) {
                    $arr[] = 2;
                }
                $buf = [
                    'model' => $smProduct->products_sku,
                    'parts' => $smProduct->products_parts_info ? $smProduct->products_parts_info : '',
                    'declared_cn' => $smProduct->products_declared_cn ? $smProduct->products_declared_cn : '',
                    'declared_en' => $smProduct->products_declared_en ? $smProduct->products_declared_en : '',
                    'declared_value' => $smProduct->products_declared_value ? $smProduct->products_declared_value : '',
                    'package_limit' => count($arr) ? implode(',', $arr) : '',
                    'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
                    'name' => $smProduct->products_name_en ? $smProduct->products_name_en : '',
                    'c_name' => $smProduct->products_name_cn ? $smProduct->products_name_cn : '',
                    'supplier_id' => $smProduct->products_suppliers_id ? $smProduct->products_suppliers_id : '',
                    'warehouse_id' => $smProduct->product_warehouse_id == 1000 ? 1 : 2,
                    'hs_code' => $smProduct->product_hscode ? $smProduct->product_hscode : '',
                    'spu_id' => $spu->id,
                ];
                $tmp_product = ProductModel::where(['model' => $smProduct->products_sku])->first();
                if($tmp_product) {
                    $updatedNum[1]++;
                    $tmp_product->update($buf);
                } else {
                    $createdNum[1]++;
                    $tmp_product = ProductModel::create($buf);
                }
                unset($buf);
                //体积
                $volumes = ['product_size' => '', 'package_size' => ''];
                if ($smProduct->products_volume) {
                    $volumes = unserialize($smProduct->products_volume);
                    $volumes['product_size'] = isset($volumes['bp']) ? $volumes['bp']['length'] . '*' . $volumes['bp']['width'] . '*' . $volumes['bp']['height'] : '';
                    $volumes['package_size'] = isset($volumes['ap']) ? $volumes['ap']['length'] . '*' . $volumes['ap']['width'] . '*' . $volumes['ap']['height'] : '';
                }
                //供货商
                $supplier = SupplierModel::find($smProduct->products_suppliers_id);
                $supplierId = $supplier ? $supplier->id : 0;
                $secondSupplierId = 0;
                if ($smProduct->products_suppliers_ids) {
                    $supplierIds = explode(',', $smProduct->products_suppliers_ids);
                    if (isset($supplierIds[0])) {
                        if ($supplierIds[0] != $smProduct->products_suppliers_id) {
                            $secondSupplier = SupplierModel::find($supplierIds[0]);
                            $secondSupplierId = $secondSupplier ? $secondSupplier->id : 0;
                        }
                    }
                }
                //仓库
                $warehouseId = $smProduct->product_warehouse_id == 1000 ? 1 : 2;
                $defaultPosition = '';
                //库位
                if ($smProduct->products_location) {
                    foreach(explode(',', $smProduct->products_location) as $key => $value) {
                        if($key == 0) {
                            $defaultPosition = $value;
                        }
                        $position = PositionModel::Where('name', $value)->first();
                        if (!$position) {
                            $position = PositionModel::create([
                                'name' => $value,
                                'warehouse_id' => $warehouseId
                            ]);
                        }
                    } 
                }
                $data = [
                    'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
                    'sku' => $smProduct->products_sku,
                    'name' => $smProduct->products_title,
                    'c_name' => $smProduct->products_name_cn,
                    'weight' => $smProduct->products_weight,
                    'warehouse_id' => $warehouseId,
                    'warehouse_position' => $defaultPosition,
                    'supplier_id' => $supplierId,
                    'second_supplier_id' => $secondSupplierId,
                    'purchase_url' => $smProduct->productsPhotoStandard,
                    'purchase_price' => $smProduct->products_value,
                    'purchase_carriage' => '',
                    'cost' => $smProduct->products_value,
                    'product_size' => $volumes['product_size'],
                    'package_size' => $volumes['package_size'],
                    'carriage_limit' => '',
                    'package_limit' => '',
                    'status' => $smProduct->products_status_2,
                    'is_available' => $smProduct->productsIsActive,
                    'remark' => $smProduct->products_warring_string,
                    'product_id' => $tmp_product->id,
                ];
                $exist = ItemModel::where(['sku' => $smProduct->products_sku])->first();
                if($exist) {
                    $updatedNum[2]++;
                    $exist->update($data);
                } else {
                    $createdNum[2]++;
                    $exist = ItemModel::create($data);
                }
                if ($smProduct->products_location) {
                    foreach(explode(',', $smProduct->products_location) as $key => $value) {
                        $position = PositionModel::where('name', $value)->first();
                        $stock = StockModel::where(['item_id' => $exist->id, 'warehouse_position_id' => $position->id])->first();
                        if(!$stock) {
                            StockModel::create(['item_id' => $exist->id, 'warehouse_id' => $warehouseId, 'warehouse_position_id' => $position->id]);
                        }
                    } 
                }
                unset($data);
            }
            $smProducts = smProduct::skip($start)->take($len)->get();
        }
        $this->info('Transfer [Spu]: Origin:'.$originNum.' => Created:'.$createdNum[0].' Updated:'.$updatedNum[0]);
        $this->info('Transfer [Product]: Origin:'.$originNum.' => Created:'.$createdNum[1].' Updated:'.$updatedNum[1]);
        $this->info('Transfer [Item]: Origin:'.$originNum.' => Created:'.$createdNum[2].' Updated:'.$updatedNum[2]);
    }
}
