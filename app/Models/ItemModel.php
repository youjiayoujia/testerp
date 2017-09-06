<?php
namespace App\Models;

use Tool;
use DB;
use App\Base\BaseModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchasesModel;
use App\Models\Purchase\PurchaseStaticsticsModel;
use App\Models\Order\ItemModel as OrderItemModel;
use App\Models\Package\ItemModel as PackageItemModel;
use App\Models\UserModel;
use App\Models\ChannelModel;
use App\Models\Stock\CarryOverFormsModel;
use App\Models\User\UserRoleModel;
use App\Models\Spu\SpuMultiOptionModel;
use App\Models\Product\SupplierModel;
use App\Models\Product\CatalogCategoryModel;
use Exception;

class ItemModel extends BaseModel
{
    public $table = 'items';

    protected $stock;

    public $searchFields = ['sku' => 'sku', 'c_name' => '中文名'];

    public $rules = ['update' => []];

    protected $fillable = [
        'id',
        'product_id',
        'sku',
        'weight',
        'inventory',
        'name',
        'c_name',
        'alias_name',
        'alias_cname',
        'catalog_id',
        'supplier_id',
        'supplier_sku',
        'second_supplier_id',
        'second_supplier_sku',
        'supplier_info',
        'purchase_url',
        'purchase_price',
        'sku_history_values',
        'purchase_carriage',
        'package_height',
        'package_width',
        'package_length',
        'height',
        'width',
        'length',
        'cost',
        'product_size',
        'package_size',
        'carriage_limit',
        'package_limit',
        'warehouse_id',
        'warehouse_position',
        'status',
        'is_available',
        'purchase_adminer',
        'remark',
        'cost',
        'package_weight',
        'competition_url',
        'products_history_values',
        'new_status',
        'is_oversea',
        'volumn_rate',
        'html_mod',
        'default_keywords',
        'default_name',
        'recieve_wrap_id',
        'us_rate',
        'uk_rate',
        'eu_rate',
        'declared_value',
    ];

    public function getMixedSearchAttribute()
    {
        
        return [
            'relatedSearchFields' => [],
            'filterFields' => ['html_mod'],
            'filterSelects' => [
                'status' => config('item.status'),
                'new_status' => config('item.new_status'),
                'warehouse_id' => WarehouseModel::all()->pluck('name', 'id'),
            ],
            'selectRelatedSearchs' => ['supplier' => ['name' => []],],
            'doubleRelatedSelectedFields' => [],
            'sectionSelect' => [],
            'sectionGangedDouble' => [
                'first' => ['catalog' => ['catalogCategory' => ['cn_name'=>CatalogCategoryModel::all()->pluck('cn_name', 'cn_name')]]],
                'second' => ['catalog' => ['c_name' => CatalogModel::all()->pluck('c_name', 'c_name')]]
            ],
        ];
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id');
    }

    public function recieveWrap()
    {
        return $this->belongsTo('App\Models\RecieveWrapsModel', 'recieve_wrap_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'item_id');
    }

    public function purchaseAdminer()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_adminer');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function secondSupplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'second_supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function warehousePosition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemModel', 'sku', 'sku');
    }

    public function purchases()
    {
        return $this->hasOne('App\Models\Purchase\PurchasesModel', 'item_id');
    }

    public function orderItem()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'item_id');
    }

    public function packageItems()
    {
        return $this->hasMany('App\Models\Package\ItemModel', 'item_id');
    }

    public function skuPrepareSupplier()
    {
        return $this->belongsToMany('App\Models\Product\SupplierModel', 'item_prepare_suppliers', 'item_id',
            'supplier_id')->withTimestamps();
    }

    public function getLogisticsLimitAttribute()
    {
        $product = $this->product;
        $limits = $product->logisticsLimit;
        $str = '';
        if (!empty($limits)) {
            foreach ($limits as $limit) {
                $str .= $limit->name . ' ';
            }
        }

        return $str;
    }

    /*public function getDeclaredValueAttribute()
    {
        $purchase_price = $this->purchase_price;
        if (($purchase_price / 6) < 1) {
            $value = 1;
        } elseif (($purchase_price / 6) > 25) {
            $value = 25;
        } else {
            $value = round($purchase_price / 6);
        }

        return $value;
    }*/

    public function getImageAttribute()
    {
        if ($this->product->image) {
            return $this->product->image->path . $this->product->image->name;
        }
        return '/default.jpg';
    }

    //所有库位
    public function getAllWarehousePositionAttribute()
    {
        $result = [];
        foreach ($this->stocks as $key => $stock) {
            $result[$key]['warehouse_position'] = $stock->position->name;
            $result[$key]['warehouse_position_id'] = $stock->position->id;
            $result[$key]['warehouse_name'] = $stock->warehouse->name;
        }

        return $result;
    }

    //实库存
    public function getAllQuantityAttribute()
    {
        return $this->stocks->sum('all_quantity');
    }

    //虚库存
    public function getAvailableQuantityAttribute()
    {
        return $this->stocks->sum('available_quantity');
    }
    //本地仓库实库存
    public function getAllQuantityLocalAttribute()
    {
        $warehouse = new WarehouseModel;
        return $this->stocks()->whereIn('warehouse_id',$warehouse->getLocalIds())->get()->sum('all_quantity');
    }
    //本地虚仓库虚库存
    public function getAvailableQuantityLocalAttribute()
    {
        $warehouse = new WarehouseModel;
        return$this->stocks()->whereIn('warehouse_id',$warehouse->getLocalIds())->get()->sum('available_quantity');
    }
    //普通在途库存
    public function getNormalTransitQuantityAttribute()
    {
        $zaitu_num = 0;
        foreach ($this->purchase as $purchaseItem) {
            if ($purchaseItem->status >= 0 && $purchaseItem->status < 4) {
                if (!$purchaseItem->purchaseOrder->write_off && $purchaseItem->purchaseOrder->type == 0) {
                    if ($purchaseItem->purchaseOrder->status >= 0 && $purchaseItem->purchaseOrder->status < 4) {
                        $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
                    }
                }
            }
        }

        return $zaitu_num;
    }

    //特殊在途库存
    public function getSpecialTransitQuantityAttribute()
    {
        $szaitu_num = 0;
        foreach ($this->purchase as $purchaseItem) {
            if ($purchaseItem->status >= 0 && $purchaseItem->status < 4) {
                if (!$purchaseItem->purchaseOrder->write_off && $purchaseItem->purchaseOrder->type == 1) {
                    if ($purchaseItem->purchaseOrder->status >= 0 && $purchaseItem->purchaseOrder->status < 4) {
                        $szaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty - $purchaseItem->unqualified_qty;
                    }
                }
            }
        }

        return $szaitu_num;
    }

    //分仓实库存和虚库存
    public function getWarehouseQuantityAttribute()
    {
        $data = [];
        $stockCollection = $this->stocks->groupBy('warehouse_id');
        foreach ($stockCollection as $colleciton) {
            $data[$colleciton[0]->warehouse_id]['all_quantity'] = $colleciton->sum('all_quantity');
            $data[$colleciton[0]->warehouse_id]['available_quantity'] = $colleciton->sum('available_quantity') - $this->warehouse_ouf_of_stock[$colleciton[0]->warehouse_id]['need'];
        }
        $warehouses = WarehouseModel::all();
        foreach ($warehouses as $warehouse) {
            if (!array_key_exists($warehouse->id, $data)) {
                $data[$warehouse->id]['all_quantity'] = 0;
                $data[$warehouse->id]['available_quantity'] = 0;
            }
        }

        return $data;
    }

    //分仓特采和普采在途库存
    public function getTransitQuantityAttribute()
    {
        $data = [];
        foreach ($this->purchase->groupBy('warehouse_id') as $purchaseItemCollection) {
            $warehouse_id = $purchaseItemCollection[0]->warehouse_id;
            $data[$warehouse_id]['normal'] = 0;
            $data[$warehouse_id]['special'] = 0;
            foreach ($purchaseItemCollection as $purchaseItem) {
                if ($purchaseItem->status >= 0 && $purchaseItem->status < 4) {
                    if ($purchaseItem->purchaseOrder->status >= 0 && $purchaseItem->purchaseOrder->status < 4) {
                        if ($purchaseItem->purchaseOrder->type == 0) {
                            $data[$warehouse_id]['normal'] += $purchaseItem->purchase_num;
                        } else {
                            $data[$warehouse_id]['special'] += $purchaseItem->purchase_num;
                        }
                    }
                }
            }
        }

        $warehouses = WarehouseModel::all();
        foreach ($warehouses as $warehouse) {
            if (!array_key_exists($warehouse->id, $data)) {
                $data[$warehouse->id]['normal'] = 0;
                $data[$warehouse->id]['special'] = 0;
            }
        }

        return $data;
    }

    //缺货数量
    public function getOutOfStockAttribute()
    {
        $item_id = $this->id;
        $num = DB::select('select sum(package_items.quantity) as num from packages,package_items where packages.status in ("NEED","TRACKINGFAILED","ASSIGNED","ASSIGNFAILED") and package_items.warehouse_position_id=0 and package_items.item_id = "' . $item_id . '" and
                packages.id = package_items.package_id and package_items.deleted_at is null')[0]->num;

        return $num;
    }

    //分仓欠货数量
    public function getWarehouseOutOfStockAttribute()
    {
        $item_id = $this->id;
        $num = DB::select('select packages.warehouse_id,sum(package_items.quantity) as num from packages,package_items where packages.status in ("NEED","TRACKINGFAILED","ASSIGNED","ASSIGNFAILED") and package_items.warehouse_position_id=0 and package_items.item_id = "' . $item_id . '" and
                packages.id = package_items.package_id and packages.deleted_at is null group by packages.warehouse_id');
        $data = [];
        $warehouses = WarehouseModel::all();
        foreach ($warehouses as $warehouse) {
            $data[$warehouse->id]['need'] = 0;
        }
        foreach ($num as $key => $value) {
            $data[$value->warehouse_id]['need'] += $value->num;
        }
        return $data;
    }

    //最近一次采购时间
    public function getRecentlyPurchaseTimeAttribute()
    {
        return $this->purchase->min('created_at');
    }

    //最近缺货时间
    public function getOutOfStockTimeAttribute()
    {
        $id = $this->id;
        $firstNeedItem = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
            ->whereIn('packages.status', ['NEED',"TRACKINGFAILED","ASSIGNED","ASSIGNFAILED"])
            ->where('package_items.item_id', $id)
            ->first(['packages.created_at']);

        if ($firstNeedItem) {
            $firstNeedItem = $firstNeedItem->toArray();
            $time = ceil((time() - strtotime($firstNeedItem['created_at'])) / (3600 * 24));
        } else {
            $time = 0;
        }

        return $time;
    }

    public function getStatusNameAttribute()
    {
        $config = config('item.status');
        return $config[$this->status];
    }

    public function updateItem($data)
    {
        $data['carriage_limit'] = empty($data['carriage_limit_arr']) ? '' : implode(',', $data['carriage_limit_arr']);
        $data['package_limit'] = empty($data['package_limit_arr']) ? '' : implode(',', $data['package_limit_arr']);

        $this->update($data);
    }

    public function getStockQuantity($warehouseId, $flag = 0)
    {
        $stocks = $this->stocks->where('warehouse_id', $warehouseId);
        return count($stocks) ? ($flag ? $stocks->sum('available_quantity') : $stocks->sum('all_quantity')) : 0;
    }

    //获得sku销量 period参数格式为 -7 day
    public function getsales($period)
    {
        //销量
        $sellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime($period)))
            ->where('order_items.quantity', '<', 5)
            ->where('order_items.item_id', $this->id)
            ->sum('order_items.quantity');

        return $sellNum;
    }

    //获得sku分平台销量 period参数格式为 -7 day
    public function getChannelSales($period)
    {
        //销量
        $sellNum = DB::select("select orders.channel_id,sum(`order_items`.`quantity`) as aggregate 
                    from `order_items` left join `orders` on `orders`.`id` = `order_items`.`order_id` 
                    where `order_items`.`deleted_at` is null and `orders`.`status` in ('PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL') 
                    and `orders`.`created_at` > '".date('Y-m-d H:i:s', strtotime($period))."'
                    and `order_items`.`quantity` < 5 
                    and `order_items`.`item_id` = ".$this->id." 
                    group by `orders`.`channel_id`");
        $data = [];
        foreach($sellNum as $sell){
            $data[$sell->channel_id] = $sell->aggregate;
        }

        foreach(ChannelModel::all() as $channel){
            if(!array_key_exists($channel->id,$data)){
                $data[$channel->id] = 0;
            }
        }
                
        return $data;
    }

    //计算sku采购建议数量
    public function getNeedPurchase()
    {
        //计算趋势系数
        //7天销量和14天销量
        $seven_sales = $this->getsales('-7 days');
        $fourteen_sales = $this->getsales('-14 days');
        if ($seven_sales == 0 || $fourteen_sales == 0) {
            $coefficient = 1;
        } else {
            if (($seven_sales / 7) / ($fourteen_sales / 14 * 1.1) >= 1) {
                $coefficient = 1.3;
            } elseif (($fourteen_sales / 14 * 0.9) / ($seven_sales / 7) >= 1) {
                $coefficient = 0.6;
            } else {
                $coefficient = 1;
            }
        }
        //虚库存
        $xu_kucun = $this->available_quantity;
        //普通在途库存
        $zaitu_num = $this->normal_transit_quantity;
        //预交期
        $delivery = $this->supplier ? $this->supplier->purchase_time : 7;
        //计算采购量
        //采购建议数量
        if ($this->purchase_price > 200 && $fourteen_sales < 3 || $this->status == 4) {
            $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
        } else {
            if ($this->purchase_price > 3 && $this->purchase_price <= 40) {
                $needPurchaseNum = ($fourteen_sales / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price <= 3) {
                $needPurchaseNum = ($fourteen_sales / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price > 40) {
                $needPurchaseNum = ($fourteen_sales / 14) * (5 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            }
        }

        return ceil($needPurchaseNum);
    }

    /**
     * 获取库存对象
     * @param $warehousePositionId 库位id
     *
     * @return 库存对象
     *
     */
    public function getStock($warehousePosistionId, $stock_id = 0)
    {
        $stock = '';
        if (!$stock_id) {
            $stock = $this->stocks()->where('warehouse_position_id', $warehousePosistionId)->first();
            if (!$stock) {
                $warehouse = PositionModel::where(['id' => $warehousePosistionId])->first()->warehouse_id;
                $len = StockModel::where(['item_id' => $this->id, 'warehouse_id' => $warehouse])->count();
                if ($len >= 2) {
                    throw new Exception('该sku对应的库位已经是2，且并没找到库位');
                }
                $stock = $this->stocks()->create([
                    'warehouse_position_id' => $warehousePosistionId,
                    'warehouse_id' => $warehouse
                ]);
            }
        } else {
            $stock = StockModel::find($stock_id);
        }

        return $stock;
    }

    /**
     * in api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     * $amount 金额
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function in(
        $warehousePosistionId,
        $quantity,
        $amount,
        $type = '',
        $relation_id = '',
        $remark = '',
        $flag = 1
    ) {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            $cost = $amount / $quantity;
            /*if ($flag && $this->cost && ($cost < $this->cost * 0.6 || $cost > $this->cost * 1.3)) {
                throw new Exception('入库单价不在原单价0.6-1.3范围内');
            }*/
            if ($this->all_quantity + $quantity) {
                $this->update([
                    'cost' => round((($this->all_quantity * $this->cost + $amount) / ($this->all_quantity + $quantity)),
                        3)
                ]);
                //$this->createOnePurchaseNeedData();
                return $stock->in($quantity, $amount, $type, $relation_id, $remark);
            }
        }
        return false;
    }

    /**
     * hold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function hold($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            //$this->createOnePurchaseNeedData();
            return $stock->hold($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * holdout api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function holdout($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            //$this->createOnePurchaseNeedData();
            return $stock->holdout($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * unhold api
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function unhold($warehousePosistionId, $quantity, $type = '', $relation_id = '', $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId);
        if ($quantity) {
            //$this->createOnePurchaseNeedData();
            return $stock->unhold($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    /**
     * out api
     *
     * @param
     * $warehousePoistionId 库位id
     * $quantity 数量
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function out($warehousePosistionId, $quantity, $type = '', $relation_id = '', $stock_id = 0, $remark = '')
    {
        $stock = $this->getStock($warehousePosistionId, $stock_id);
        if ($quantity) {
            //$this->createOnePurchaseNeedData();
            return $stock->out($quantity, $type, $relation_id, $remark);
        }
        return false;
    }

    //分配库存
    public function assignStock($quantity)
    {
        $stocks = $this->stocks->sortByDesc('available_quantity')->filter(function ($query) {
            return $query->warehouse->is_available == 1 && $query->warehouse->type == 'local';
        });
        if ($stocks->sum('available_quantity') >= $quantity) {
            $warehouseStocks = $stocks->groupBy('warehouse_id');
            //默认仓库
            $defaultStocks = $warehouseStocks->get($this->warehouse_id);
            if ($defaultStocks and $defaultStocks->sum('available_quantity') >= $quantity) {
                $gotStocks = $defaultStocks;
            } else {
                //其它仓库
                $otherStocks = $warehouseStocks
                    ->first(function ($key, $value) use ($quantity) {
                        return $value->sum('available_quantity') >= $quantity ? $value : false;
                    });
                $gotStocks = $otherStocks ? $otherStocks : $stocks;
            }
            $result = [];
            foreach ($gotStocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                    break;
                }
            }
            return $result;
        }

        return false;
    }

    //分配库存
    public function oversea_assignStock($quantity, $code)
    {
        $stocks = $this->stocks->sortByDesc('available_quantity')->filter(function ($query) use ($code){
            return $query->warehouse->is_available == 1 && $query->warehouse->type == 'oversea' && $query->warehouse->code == $code;
        });
        if ($stocks->sum('available_quantity') >= $quantity) {
            $warehouseStocks = $stocks->groupBy('warehouse_id');
            $otherStocks = $warehouseStocks
                ->first(function ($key, $value) use ($quantity) {
                    return $value->sum('available_quantity') >= $quantity ? $value : false;
                });
            $gotStocks = $otherStocks ? $otherStocks : $stocks;
            $result = [];
            foreach ($gotStocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                    $warehouseStock[$stock->id]['code'] = $code;
                    $warehouseStock[$stock->id]['is_oversea'] = 1;
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                    $warehouseStock[$stock->id]['code'] = $code;
                    $warehouseStock[$stock->id]['is_oversea'] = 1;
                    break;
                }
            }
            return $result;
        }

        return false;
    }

    //分配库存
    public function assignDefaultStock($quantity, $order_item_id)
    {
        $stocks = $this->stocks->groupBy('warehouse_id')->get($this->warehouse_id);
        if ($stocks->sum('available_quantity') >= $quantity) {
            $stocks = $stocks->sortByDesc('available_quantity');
            foreach ($stocks as $stock) {
                if ($stock->available_quantity < $quantity) {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                    $result[$stock->warehouse_id][$stock->id]['order_item_id'] = $order_item_id;
                    $quantity -= $stock->available_quantity;
                } else {
                    $result[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                    $result[$stock->warehouse_id][$stock->id]['order_item_id'] = $order_item_id;
                    break;
                }
            }
            return $result;
        }

        return false;
    }

    //匹配库存
    public function matchStock($quantity)
    {
        $result = [];
        $stocks = $this->stocks->sortByDesc('available_quantity')->filter(function ($query) {
            return $query->warehouse->is_available == 1 && $query->warehouse->type == 'local';
        });
        if ($stocks->sum('available_quantity') >= $quantity) {
            //单仓库
            foreach ($stocks->groupBy('warehouse_id') as $warehouseID => $warehouseStocks) {
                if ($warehouseStocks->sum('available_quantity') >= $quantity) {
                    $warehouseStock = [];
                    $matchQuantity = $quantity;
                    foreach ($warehouseStocks as $stock) {
                        if ($stock->available_quantity < $matchQuantity) {
                            $warehouseStock[$stock->id] = $this->setStockData($stock);
                            $matchQuantity -= $stock->available_quantity;
                        } else {
                            $warehouseStock[$stock->id] = $this->setStockData($stock, $matchQuantity);
                            break;
                        }
                    }
                    $result['SINGLE'][$warehouseID] = $warehouseStock;
                    continue;
                }
            }
            //多仓库
            if (!$result) {
                $warehouseStock = [];
                foreach ($stocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                        $quantity -= $stock->available_quantity;
                    } else {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                        break;
                    }
                }
                $result['MULTI'] = $warehouseStock;
            }
            return $result;
        }
        return false;
    }

    //匹配库存
    public function oversea_matchStock($quantity, $code)
    {
        $result = [];
        $stocks = $this->stocks->sortByDesc('available_quantity')->filter(function ($query) use ($code) {
            return $query->warehouse->is_available == 1 && $query->warehouse->type == 'oversea' && $query->warehouse->code == $code;
        });
        if ($stocks->sum('available_quantity') >= $quantity) {
            //单仓库
            foreach ($stocks->groupBy('warehouse_id') as $warehouseID => $warehouseStocks) {
                if ($warehouseStocks->sum('available_quantity') >= $quantity) {
                    $warehouseStock = [];
                    $matchQuantity = $quantity;
                    foreach ($warehouseStocks as $stock) {
                        if ($stock->available_quantity < $matchQuantity) {
                            $warehouseStock[$stock->id] = $this->setStockData($stock);
                            $warehouseStock[$stock->id]['code'] = $code;
                            $warehouseStock[$stock->id]['is_oversea'] = 1;
                            $matchQuantity -= $stock->available_quantity;
                        } else {
                            $warehouseStock[$stock->id] = $this->setStockData($stock, $matchQuantity);
                            $warehouseStock[$stock->id]['code'] = $code;
                            $warehouseStock[$stock->id]['is_oversea'] = 1;
                            break;
                        }
                    }
                    $result['SINGLE'][$warehouseID] = $warehouseStock;
                    continue;
                }
            }
            //多仓库
            if (!$result) {
                $warehouseStock = [];
                foreach ($stocks as $stock) {
                    if ($stock->available_quantity < $quantity) {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock);
                        $warehouseStock[$stock->id]['code'] = $code;
                        $quantity -= $stock->available_quantity;
                        $warehouseStock[$stock->id]['is_oversea'] = 1;
                    } else {
                        $warehouseStock[$stock->warehouse_id][$stock->id] = $this->setStockData($stock, $quantity);
                        $warehouseStock[$stock->id]['code'] = $code;
                        $warehouseStock[$stock->id]['is_oversea'] = 1;
                        break;
                    }
                }
                $result['MULTI'] = $warehouseStock;
            }
            return $result;
        }
        return false;
    }

    public function setStockData($stock, $quantity = null)
    {
        $quantity = $quantity ? $quantity : $stock->available_quantity;
        $stockData['item_id'] = $this->id;
        $stockData['sku'] = $stock->item ? $stock->item->sku : 'item_id对应sku不存在';
        $stockData['warehouse_id'] = $stock->warehouse_id;
        $stockData['warehouse_position_id'] = $stock->warehouse_position_id;
        $stockData['quantity'] = $quantity;
        $stockData['weight'] = $this->weight * $quantity;
        return $stockData;
    }

    public function createOnePurchaseNeedData()
    {
        $data['item_id'] = $this->id;
        $data['sku'] = $this->sku;
        $data['c_name'] = $this->c_name;
        $zaitu_num = 0;
        foreach ($this->purchase as $purchaseItem) {
            if ($purchaseItem->status >= 0 && $purchaseItem->status < 4) {
                if ($purchaseItem->purchaseOrder) {
                    if (!$purchaseItem->purchaseOrder->write_off) {
                        if ($purchaseItem->purchaseOrder->status >= 0 && $purchaseItem->purchaseOrder->status < 4) {
                            $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty;
                        }
                    }
                }
            }
        }
        //print_r($zaitu_num);exit;

        //缺货
        $data['need_total_num'] = $this->out_of_stock?$this->out_of_stock:0;

        $data['zaitu_num'] = $zaitu_num;
        //实库存
        $data['all_quantity'] = $this->all_quantity_local;
        //可用库存
        $data['available_quantity'] = $this->available_quantity_local;
        $xu_kucun = $this->available_quantity - $data['need_total_num'];
        //7天销量
        $sevenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
            ->where('order_items.quantity', '<', 5)
            ->where('order_items.item_id', $this->id)
            ->sum('order_items.quantity');
        if ($sevenDaySellNum == null) {
            $sevenDaySellNum = 0;
        }
        //7天批发订单销量
        $pifaSeven = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
            ->where('order_items.quantity', '>=', 5)
            ->where('order_items.item_id', $this->id)
            ->count('order_items.id');
        $sevenDaySellNum += $pifaSeven;

        //14天销量
        $fourteenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
            ->where('order_items.quantity', '<', 5)
            ->where('order_items.item_id', $this->id)
            ->sum('order_items.quantity');
        if ($fourteenDaySellNum == null) {
            $fourteenDaySellNum = 0;
        }
        //14天批发订单销量
        $pifaFourteen = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
            ->where('order_items.quantity', '>=', 5)
            ->where('order_items.item_id', $this->id)
            ->count('order_items.id');
        $fourteenDaySellNum += $pifaFourteen;

        //30天销量
        $thirtyDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
            ->where('order_items.quantity', '<', 5)
            ->where('order_items.item_id', $this->id)
            ->sum('order_items.quantity');
        if ($thirtyDaySellNum == null) {
            $thirtyDaySellNum = 0;
        }
        //30天批发订单销量
        $pifaThirty = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status',
                ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
            ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
            ->where('order_items.quantity', '>=', 5)
            ->where('order_items.item_id', $this->id)
            ->count('order_items.id');
        $thirtyDaySellNum += $pifaThirty;


        //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
        if ($sevenDaySellNum == 0 || $fourteenDaySellNum == 0) {
            $coefficient_status = 3;
            $coefficient = 1;
        } else {
            if (($sevenDaySellNum / 7) / ($fourteenDaySellNum / 14 * 1.1) >= 1) {
                $coefficient = 1.3;
                $coefficient_status = 1;
            } elseif (($fourteenDaySellNum / 14 * 0.9) / ($sevenDaySellNum / 7) >= 1) {
                $coefficient = 0.6;
                $coefficient_status = 2;
            } else {
                $coefficient = 1;
                $coefficient_status = 4;
            }
        }
        $data['seven_sales'] = $sevenDaySellNum;
        $data['fourteen_sales'] = $fourteenDaySellNum;
        $data['thirty_sales'] = $thirtyDaySellNum;
        $data['thrend'] = $coefficient_status;

        //预交期
        $delivery = $this->supplier ? $this->supplier->purchase_time : 7;

        //采购建议数量
        if ($this->purchase_price > 200 && $fourteenDaySellNum < 3 || $this->status == 4) {
            $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
        } else {
            if ($this->purchase_price > 3 && $this->purchase_price <= 40) {
                $needPurchaseNum = ($fourteenDaySellNum / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price <= 3) {
                $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            } elseif ($this->purchase_price > 40) {
                $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
            }
        }
        if ($this->status == 'cleaning') {
            $data['need_purchase_num'] = $data['need_total_num'];
        } else {
            $data['need_purchase_num'] = ceil($needPurchaseNum);
        }

        //退款订单数
        $refund_num = $this->orderItem->where('is_refund', '1')->count();
        $all_order_num = 0;
        $total_profit_rate = 0;
        $total_profit_num = 0;
        foreach ($this->orderItem as $o_item) {
            if ($o_item->order) {
                if (in_array($o_item->order->status, array('PACKED', 'SHIPPED', 'COMPLETE'))) {
                    $total_profit_rate += $o_item->order->profit_rate;
                    $total_profit_num++;
                }
                if (in_array($o_item->order->status,
                    array('PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'))) {
                    $all_order_num++;
                }
            }

        }


        $refund_rate = $all_order_num ? $refund_num / $all_order_num : '0';
        //退款率
        $data['refund_rate'] = $refund_rate;
        //平均利润率
        $data['profit'] = $total_profit_num ? $total_profit_rate / $total_profit_num : '0';

        $data['status'] = $this->status ? $this->status : 'saleOutStopping';
        $data['require_create'] = $needPurchaseNum > 0 ? 1 : 0;
        $thisModel = PurchasesModel::where("item_id", $data['item_id'])->get()->first();
        $data['user_id'] = $this->purchase_adminer ? $this->purchase_adminer : 0;

        $firstNeedItem = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
            ->whereIn('packages.status', ['NEED',"TRACKINGFAILED","ASSIGNED","ASSIGNFAILED"])
            ->where('package_items.item_id', $this->id)
            ->first(['packages.created_at']);

        if ($firstNeedItem) {
            $firstNeedItem = $firstNeedItem->toArray();
            $data['owe_day'] = ceil((time() - strtotime($firstNeedItem['created_at'])) / (3600 * 24));
        } else {
            $data['owe_day'] = 0;
        }

        if ($thisModel) {
            $thisModel->update($data);
        } else {
            PurchasesModel::create($data);
        }

        return $data;
        
    }

    public function createPurchaseNeedData($item_id_array = null)
    {
        ini_set('memory_limit', '2048M');
        //$item_id_array=['39547'];
        if (!$item_id_array) {
            $items = $this->all();
        } else {
            $items = $this->find($item_id_array);
        }

        foreach ($items as $item) {
            $data['item_id'] = $item->id;
            $data['sku'] = $item->sku;
            $data['c_name'] = $item->c_name;
            $zaitu_num = 0;
            foreach ($item->purchase as $purchaseItem) {
                if ($purchaseItem->status >= 0 && $purchaseItem->status < 4) {
                    if ($purchaseItem->purchaseOrder) {
                        if (!$purchaseItem->purchaseOrder->write_off) {
                            if ($purchaseItem->purchaseOrder->status >= 0 && $purchaseItem->purchaseOrder->status < 4) {
                                $zaitu_num += $purchaseItem->purchase_num - $purchaseItem->storage_qty;
                            }
                        }
                    }
                }
            }
            //print_r($zaitu_num);exit;

            //缺货
            $data['need_total_num'] = DB::select('select sum(order_items.quantity) as num from orders,order_items,purchases where orders.status= "NEED" and 
                orders.id = order_items.order_id and orders.deleted_at is null and purchases.item_id = order_items.item_id and order_items.item_id ="' . $item->id . '" ')[0]->num;
            $data['need_total_num'] = $data['need_total_num'] ? $data['need_total_num'] : 0;

            $data['zaitu_num'] = $zaitu_num;
            //实库存
            $data['all_quantity'] = $item->all_quantity_local;
            //可用库存
            $data['available_quantity'] = $item->available_quantity_local;
            //虚库存
            /*$quantity = $requireModel->where('is_require', 1)->where('item_id',
                $item->id)->get() ? $requireModel->where('is_require', 1)->where('item_id',
                $item->id)->sum('quantity') : 0;*/
            //$xu_kucun = $data['all_quantity'] - $quantity;
            $xu_kucun = $item->available_quantity - $data['need_total_num'];
            //7天销量
            $sevenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if ($sevenDaySellNum == null) {
                $sevenDaySellNum = 0;
            }
            //7天批发订单销量
            $pifaSeven = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
                ->where('order_items.quantity', '>=', 5)
                ->where('order_items.item_id', $item['id'])
                ->count('order_items.id');
            $sevenDaySellNum += $pifaSeven;

            //14天销量
            $fourteenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if ($fourteenDaySellNum == null) {
                $fourteenDaySellNum = 0;
            }
            //14天批发订单销量
            $pifaFourteen = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
                ->where('order_items.quantity', '>=', 5)
                ->where('order_items.item_id', $item['id'])
                ->count('order_items.id');
            $fourteenDaySellNum += $pifaFourteen;

            //30天销量
            $thirtyDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $item['id'])
                ->sum('order_items.quantity');
            if ($thirtyDaySellNum == null) {
                $thirtyDaySellNum = 0;
            }
            //30天批发订单销量
            $pifaThirty = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status',
                    ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
                ->where('order_items.quantity', '>=', 5)
                ->where('order_items.item_id', $item['id'])
                ->count('order_items.id');
            $thirtyDaySellNum += $pifaThirty;


            //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
            if ($sevenDaySellNum == 0 || $fourteenDaySellNum == 0) {
                $coefficient_status = 3;
                $coefficient = 1;
            } else {
                if (($sevenDaySellNum / 7) / ($fourteenDaySellNum / 14 * 1.1) >= 1) {
                    $coefficient = 1.3;
                    $coefficient_status = 1;
                } elseif (($fourteenDaySellNum / 14 * 0.9) / ($sevenDaySellNum / 7) >= 1) {
                    $coefficient = 0.6;
                    $coefficient_status = 2;
                } else {
                    $coefficient = 1;
                    $coefficient_status = 4;
                }
            }
            $data['seven_sales'] = $sevenDaySellNum;
            $data['fourteen_sales'] = $fourteenDaySellNum;
            $data['thirty_sales'] = $thirtyDaySellNum;
            $data['thrend'] = $coefficient_status;

            //预交期
            $delivery = $this->supplier ? $this->supplier->purchase_time : 7;

            //采购建议数量
            if ($this->purchase_price > 200 && $fourteenDaySellNum < 3 || $this->status == 4) {
                $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
            } else {
                if ($item->purchase_price > 3 && $item->purchase_price <= 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($item->purchase_price <= 3) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($item->purchase_price > 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                }
            }
            if ($item->status == 'cleaning') {
                $data['need_purchase_num'] = $data['need_total_num'];
            } else {
                $data['need_purchase_num'] = ceil($needPurchaseNum);
            }

            //退款订单数
            $refund_num = $item->orderItem->where('is_refund', '1')->count();
            $all_order_num = 0;
            $total_profit_rate = 0;
            $total_profit_num = 0;
            foreach ($item->orderItem as $o_item) {
                if ($o_item->order) {
                    if (in_array($o_item->order->status, array('PACKED', 'SHIPPED', 'COMPLETE'))) {
                        $total_profit_rate += $o_item->order->profit_rate;
                        $total_profit_num++;
                    }
                    if (in_array($o_item->order->status,
                        array('PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE', 'PICKING', 'PARTIAL'))) {
                        $all_order_num++;
                    }
                }

            }


            $refund_rate = $all_order_num ? $refund_num / $all_order_num : '0';
            //退款率
            $data['refund_rate'] = $refund_rate;
            //平均利润率
            $data['profit'] = $total_profit_num ? $total_profit_rate / $total_profit_num : '0';

            $data['status'] = $item->status ? $item->status : 'saleOutStopping';
            $data['require_create'] = $needPurchaseNum > 0 ? 1 : 0;
            $thisModel = PurchasesModel::where("item_id", $data['item_id'])->get()->first();
            $data['user_id'] = $item->purchase_adminer ? $item->purchase_adminer : 0;

            $firstNeedItem = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
                ->whereIn('packages.status', ['NEED'])
                ->where('package_items.item_id', $item['id'])
                ->first(['packages.created_at']);

            if ($firstNeedItem) {
                $firstNeedItem = $firstNeedItem->toArray();
                $data['owe_day'] = ceil((time() - strtotime($firstNeedItem['created_at'])) / (3600 * 24));
            } else {
                $data['owe_day'] = 0;
            }

            if ($thisModel) {
                $thisModel->update($data);
            } else {
                PurchasesModel::create($data);
            }
            if (count($item_id_array) == 1) {
                return $data;
            }
        }
    }

    public function createPurchaseStaticstics()
    {
        $users = UserRoleModel::where('role_id', '2')->get();
        
        foreach ($users as $user) {
            $data = [];
            //采购负责人
            $data['purchase_adminer'] = $user->user_id;
            //管理的SKU数
            $data['sku_num'] = $this->where('purchase_adminer', $user->user_id)->count();
            //获取时间
            $data['get_time'] = date('Y-m-d', time());
            //必须当天内下单SKU数
            $data['need_purchase_num'] = DB::select('select count(*) as num from purchases where user_id = "' . $user->user_id . '" and need_purchase_num > 0 and available_quantity+zaitu_num-seven_sales < 0 ')[0]->num;
            //15天缺货订单
            $data['fifteenday_need_order_num'] = DB::select('select sum(package_items.quantity) as num from packages,package_items where packages.status in ("NEED","TRACKINGFAILED","ASSIGNED","ASSIGNFAILED") and package_items.warehouse_position_id=0  and
                packages.id = package_items.package_id and packages.deleted_at is null and packages.created_at > "' . date('Y-m-d',
                    time() - 24 * 3600 * 15) . '" ')[0]->num;

            //15天所有订单
            $data['fifteenday_total_order_num'] = DB::select('select count(*) as num from orders,order_items,purchases where orders.status!= "CANCEL" and purchases.user_id = "' . $user->user_id . '" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "' . date('Y-m-d',
                    time() - 24 * 3600 * 15) . '" ')[0]->num;
            //订单缺货率
            $data['need_percent'] = $data['fifteenday_total_order_num'] ? round($data['fifteenday_need_order_num'] / $data['fifteenday_total_order_num'],
                4) : 0;
            //缺货总数
            $data['need_total_num'] = $this->out_of_stock?$this->out_of_stock:0;
            //平均缺货天数
            $data['avg_need_day'] = round(DB::select('select avg(' . time() . '-UNIX_TIMESTAMP(packages.created_at))/86400 as day from packages,package_items,purchases where packages.status in ("NEED","TRACKINGFAILED","ASSIGNED","ASSIGNFAILED") and purchases.user_id = "' . $user->user_id . '" and 
                packages.id = package_items.package_id and purchases.item_id = package_items.item_id  ')[0]->day, 1);
            //最长缺货天数
            $data['long_need_day'] = round(DB::select('select max(' . time() . '-UNIX_TIMESTAMP(packages.created_at))/86400 as day from packages,package_items,purchases where packages.status in ("NEED","TRACKINGFAILED","ASSIGNED","ASSIGNFAILED") and purchases.user_id = "' . $user->user_id . '" and 
                packages.id = package_items.package_id and purchases.item_id = package_items.item_id  ')[0]->day, 1);
            //采购单超期
            $data['purchase_order_exceed_time'] = DB::select('select count(*) as num from purchase_orders where user_id = "' . $user->user_id . '" and created_at < "' . date('Y-m-d H:i:s',
                    time() - 86400 * 15) . '" ')[0]->num;
            //当月累计下单数量
            $data['month_order_num'] = DB::select('select count(*) as num from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "' . $user->user_id . '" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "' . date('Y-m-01 00:00:00',
                    time()) . '" and order_items.price > 0')[0]->num;
            //当月累计下单总金额
            $data['month_order_money'] = DB::select('select sum(orders.amount*orders.rate) as total_price from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "' . $user->user_id . '" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id and orders.created_at > "' . date('Y-m-01 00:00:00',
                    time()) . '" and order_items.price > 0')[0]->total_price;
            $data['month_order_money'] = $data['month_order_money'] ? $data['month_order_money'] : 0;
            //累计运费
            $data['total_carriage'] = DB::select('select sum(total_postage) as total_postage from purchase_orders where user_id = "' . $user->user_id . '" and created_at > "' . date('Y-m-01 00:00:00',
                    time()) . '"')[0]->total_postage;
            $data['total_carriage'] = $data['total_carriage'] ? $data['total_carriage'] : 0;
            //节约成本
            $item_id_arr = DB::select('select order_items.item_id,sum(order_items.quantity) as qty from orders,order_items,purchases where orders.status!= "CANCEL" and orders.status!= "NEED" and purchases.user_id = "' . $user->user_id . '" and 
                orders.id = order_items.order_id and purchases.item_id = order_items.item_id group by order_items.item_id');
            $total_cost = 0;
            foreach ($item_id_arr as $item_id) {
                $stock_model = $this->find($item_id->item_id)->stocks;
                if (count($stock_model) > 0) {
                    $stock_id = $stock_model[0]->id;
                    $cof_model = CarryOverFormsModel::where('stock_id', $stock_id)->where('parent_id',
                        date('m', strtotime('2011-08-25')))->get()->first();
                    if ($cof_model) {
                        $total_cost += $purchase_price * $item_id->qty;
                    }
                }
            }
            $data['save_money'] = $total_cost - ($data['month_order_money'] - $data['total_carriage']);
            PurchaseStaticsticsModel::create($data);
        }
    }

    public function updateUser()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $url = "http://120.24.100.157:60/api/skuInfoApi.php";
        $itemModel = $this->where('purchase_adminer',null)->get();
        
        foreach ($itemModel as $key => $model) {
            $old_data['sku'] = $model->sku;
            //print_r($old_data);exit;
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $url);
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($c, CURLOPT_POSTFIELDS, $old_data);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
            $buf = curl_exec($c);
            $user_array = json_decode($buf);

            $dev_id = UserModel::where('name', preg_replace("/\s/", "", $user_array->dev_name))->get(['id'])->first();
            $purchase_id = UserModel::where('name',
                preg_replace("/\s/", "", $user_array->purchase_name))->get(['id'])->first();
            $arr['purchase_adminer'] = $purchase_id ? $purchase_id->id : '';
            $brr['developer'] = $dev_id ? $dev_id->id : '';
            $model->update($arr);
            $model->product->spu->update($brr);
        }

    }

    public function updateOldData()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $model = $this->all();
        //$model = $this->where('id','>','65279')->get();
        foreach ($model as $key => $itemModel) {
            $erp_products_data = DB::select('select pack_method,products_with_battery,products_with_adapter,products_with_fluid,products_with_powder 
                    from erp_products_data where products_sku =  "' . $itemModel->sku . '" ');

            $arr = [];
            if ($erp_products_data[0]->pack_method) {
                $arr[] = $erp_products_data[0]->pack_method;
                $itemModel->product->wrapLimit()->sync($arr);
            }
            $brr = [];
            if ($erp_products_data[0]->products_with_battery) {
                $brr[] = 1;
            }
            if ($erp_products_data[0]->products_with_adapter) {
                $brr[] = 4;
            }
            if ($erp_products_data[0]->products_with_fluid) {
                $brr[] = 5;
            }
            if ($erp_products_data[0]->products_with_powder) {
                $brr[] = 2;
            }
            $itemModel->product->logisticsLimit()->sync($brr);
        }
    }

    public function updateBasicData()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $model = $this->all();
        //$model = $this->where('id','>','65279')->get();
        foreach ($model as $key => $itemModel) {
            $erp_products_data = DB::select('select products_name_en,products_name_cn,products_declared_en,products_declared_cn,
                    products_declared_value,products_weight,products_value,products_suppliers_id,products_suppliers_ids,products_check_standard,weightWithPacket,
                    product_warehouse_id,products_location,products_more_img,productsPhotoStandard,products_remark_2
                    from erp_products_data where products_sku =  "' . $itemModel->sku . '" ');

            $old_data['name'] = $erp_products_data[0]->products_name_en;
            $old_data['c_name'] = $erp_products_data[0]->products_name_cn;
            //$old_data['products_sku'] = $model->sku;
            //$old_data['products_sort'] = $model->product->catalog?$model->product->catalog->name:'异常';
            $old_data['declared_en'] = $erp_products_data[0]->products_declared_en;
            $old_data['declared_cn'] = $erp_products_data[0]->products_declared_cn;
            $old_data['purchase_price'] = $erp_products_data[0]->products_value;
            $old_data['weight'] = $erp_products_data[0]->products_weight;
            $old_data['package_weight'] = $erp_products_data[0]->weightWithPacket;
            $old_data['supplier_id'] = $erp_products_data[0]->products_suppliers_id;
            $old_data['quality_standard'] = $erp_products_data[0]->products_check_standard;
            $old_data['warehouse_id'] = $erp_products_data[0]->product_warehouse_id == 1000 ? 1 : 2;
            $old_data['warehouse_position'] = $erp_products_data[0]->products_location;
            $old_data['purchase_url'] = $erp_products_data[0]->products_more_img;
            $old_data['competition_url'] = $erp_products_data[0]->productsPhotoStandard;
            $old_data['notify'] = $erp_products_data[0]->products_warring_string;
            $arr = [];
            $arr = explode(',', $erp_products_data[0]->products_suppliers_ids);

            $itemModel->update($old_data);
            $itemModel->product->update($old_data);
            $itemModel->skuPrepareSupplier()->sync($arr);
        }
    }

    public function updateWarehouse()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $model = $this->all();
        //$model = $this->where('id','<','3333')->get();
        foreach ($model as $key => $itemModel) {
            $old_data = [];
            $old_data['warehouse_id'] = $itemModel->purchaseAdminer ? $itemModel->purchaseAdminer->warehouse_id : '3';
            $itemModel->update($old_data);
            /*$erp_products_data = DB::select('select product_warehouse_id,products_sku,products_location
                    from erp_products_data where products_sku =  "'.$itemModel->sku.'" ');
            
            if(count($erp_products_data)){
                if($erp_products_data[0]->product_warehouse_id==1025){
                    $warehouse_id = 2;
                }else{
                    $warehouse_id = 1;
                }
                $old_data['warehouse_id'] = $warehouse_id;
                $old_data['warehouse_position'] = $erp_products_data[0]->products_location;
                //print_r($old_data);exit;
                $itemModel->update($old_data);
            }*/

        }
    }

    public function updateCatalog()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $model = $this->where('is_available',1)->get();
        //$model = $this->where('id','<','3333')->get();
        foreach ($model as $key => $itemModel) {
            $result = DB::select('select catalogs.id from catalogs,sku_catalog where catalogs.name = sku_catalog.catalog_enname and sku = "'.$itemModel->sku.'"');
            if(count($result)){
                //print_r($result);exit;
                $itemModel->update(['catalog_id'=>$result[0]->id]);
                $itemModel->product->update(['catalog_id'=>$result[0]->id]);
            }
        }
    }

    public function updateWeight()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $model = $this->where('weight', null)->get();

        foreach ($model as $key => $itemModel) {
            $old_data = [];
            $erp_products_data = DB::select('select products_weight
                    from erp_products_data where products_sku =  "' . $itemModel->sku . '" ');
            //print_r(count($erp_products_data));exit;
            if (count($erp_products_data)) {
                $old_data['weight'] = $erp_products_data[0]->products_weight;
                //print_r($old_data);exit;
                $itemModel->update($old_data);
            }

        }
    }

    public function insertWarehousePosition()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $erp_products_data = DB::select('select product_warehouse_id,products_sku,products_location
                    from erp_products_data where product_warehouse_id = 1025');

        foreach ($erp_products_data as $data) {
            $arr = [];
            $arr['warehouse_id'] = 2;
            $arr['name'] = $data->products_location;
            PositionModel::create($arr);
        }

    }

    public function oneKeyUpdateSku()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $erp_products_data = DB::select('select distinct(products_sku),products_id,products_html_mod,pack_method,spu,products_warring_string,model,products_history_values,products_with_battery,products_with_adapter,products_with_fluid,products_with_powder,
                                        product_warehouse_id,products_location,products_name_en,products_name_cn,products_declared_en,products_declared_cn,
                                        products_declared_value,products_weight,products_value,products_suppliers_id,products_suppliers_ids,products_check_standard,weightWithPacket,
                                        products_more_img,productsPhotoStandard,products_remark_2,products_volume,products_status_2,productsIsActive
                                        from erp_products_data where productsIsActive = 1 and spu!="" order by products_id desc');
        foreach ($erp_products_data as $data) {
            $itemModel = $this->where('sku', $data->products_sku)->get()->first();
            if (count($itemModel)) {
                //存在sku,更新操作
                //更新物流包装限制
                $arr = [];
                if ($data->pack_method) {
                    $arr[] = $data->pack_method;
                    $itemModel->product->wrapLimit()->sync($arr);
                }
                $brr = [];
                if ($data->products_with_battery) {
                    $brr[] = 1;
                }
                if ($data->products_with_adapter) {
                    $brr[] = 4;
                }
                if ($data->products_with_fluid) {
                    $brr[] = 5;
                }
                if ($data->products_with_powder) {
                    $brr[] = 2;
                }
                $itemModel->product->logisticsLimit()->sync($brr);
                //更新数据
                $old_data['notify'] = $data->products_warring_string;
                $old_data['name'] = $data->products_name_en;
                $old_data['c_name'] = $data->products_name_cn;
                $old_data['declared_en'] = $data->products_declared_en;
                $old_data['declared_cn'] = $data->products_declared_cn;
                $old_data['declared_value'] = $data->products_declared_value;
                $old_data['purchase_price'] = $data->products_value;
                $old_data['weight'] = $data->products_weight;
                $old_data['package_weight'] = $data->weightWithPacket;
                //公共描述
                $old_data['html_mod'] = $data->products_html_mod;
                //供应商
                $supp_name = DB::select('select suppliers_id,suppliers_company
                                        from erp_suppliers where suppliers_id = "' . $data->products_suppliers_id . '"');

                if (count($supp_name)) {
                    $my_supplier_id = SupplierModel::where('company',
                        trim($supp_name[0]->suppliers_company))->get()->first();
                    if (count($my_supplier_id)) {
                        $old_data['supplier_id'] = $my_supplier_id->id;
                    } else {
                        $old_data['supplier_id'] = 0;
                    }
                } else {
                    $old_data['supplier_id'] = 0;
                }

                $old_data['quality_standard'] = $data->products_check_standard;
                //$old_data['warehouse_id'] = $data->product_warehouse_id==1000?1:2;
                $old_data['warehouse_id'] = $itemModel->purchaseAdminer ? $itemModel->purchaseAdminer->warehouse_id : '3';
                $old_data['warehouse_position'] = $data->products_location;
                $old_data['purchase_url'] = $data->products_more_img;
                $old_data['competition_url'] = $data->productsPhotoStandard;
                $old_data['notify'] = $data->products_warring_string;
                $old_data['is_available'] = $data->productsIsActive;
                $old_data['status'] = $data->products_status_2;
                $volume = unserialize($data->products_volume);
                //长宽高
                if ($volume != '') {
                    if (!array_key_exists('bp', $volume)) {
                        $volume['bp']['length'] = 0;
                        $volume['bp']['width'] = 0;
                        $volume['bp']['height'] = 0;
                    }
                    if (!array_key_exists('ap', $volume)) {
                        $volume['ap']['length'] = 0;
                        $volume['ap']['width'] = 0;
                        $volume['ap']['height'] = 0;
                    }
                    $old_data['package_height'] = $volume['ap']['length'];
                    $old_data['package_width'] = $volume['ap']['width'];
                    $old_data['package_length'] = $volume['ap']['height'];
                    $old_data['height'] = $volume['bp']['length'];
                    $old_data['width'] = $volume['bp']['width'];
                    $old_data['length'] = $volume['bp']['height'];
                } else {
                    $old_data['package_height'] = 0;
                    $old_data['package_width'] = 0;
                    $old_data['package_length'] = 0;
                    $old_data['height'] = 0;
                    $old_data['width'] = 0;
                    $old_data['length'] = 0;
                }
                //采购历史
                $old_data['sku_history_values'] = $data->products_history_values;

                $crr = [];
                //多对多供应商转换id
                $crr = explode(',', $erp_products_data[0]->products_suppliers_ids);
                if (substr($data->products_suppliers_ids, 0, 1) == ',') {
                    $data->products_suppliers_ids = substr($data->products_suppliers_ids, 1);
                }
                
                if(!$data->products_suppliers_ids){
                    $data->products_suppliers_ids = 0;
                }
                $supp_name = DB::select('select suppliers_id,suppliers_company
                                        from erp_suppliers where suppliers_id in(' . $data->products_suppliers_ids . ')');
                if (count($supp_name)) {
                    $my_suppliers_id_arr = [];
                    $supp_name_arr = [];
                    foreach ($supp_name as $_supp_name) {
                        $supp_name_arr[] = trim($_supp_name->suppliers_company);
                    }
                    $my_suppliers_id_two = SupplierModel::whereIn('company', $supp_name_arr)->get(['id'])->toArray();
                    if (count($my_suppliers_id_two)) {
                        foreach ($my_suppliers_id_two as $_my_suppliers_id_two) {
                            $my_suppliers_id_arr[] = $_my_suppliers_id_two['id'];
                        }
                        $itemModel->skuPrepareSupplier()->sync($my_suppliers_id_arr);
                    }
                }

                //echo '<pre>';
                //print_r($my_suppliers_id_arr);exit;
                $itemModel->update($old_data);
                $itemModel->product->update($old_data);
                //$itemModel->skuPrepareSupplier()->sync($my_suppliers_id_arr);
            } else {
                //新增
                //添加库位
                if ($data->products_location != '') {
                    $position['warehouse_id'] = $data->product_warehouse_id == 1000 ? '1' : '2';
                    $position['name'] = $data->products_location;
                    $position['is_available'] = 1;
                    if (!count(PositionModel::where('name', $data->products_location)->get())) {
                        PositionModel::create($position);
                    }
                }
                //供应商
                $supp_name = DB::select('select suppliers_id,suppliers_company
                                        from erp_suppliers where suppliers_id = "' . $data->products_suppliers_id . '"');
                if (count($supp_name)) {
                    $my_supplier_id = SupplierModel::where('company',
                        trim($supp_name[0]->suppliers_company))->get()->first();
                    if (count($my_supplier_id)) {
                        $productData['supplier_id'] = $my_supplier_id->id;
                        $skuData['supplier_id'] = $my_supplier_id->id;
                    } else {
                        $productData['supplier_id'] = 0;
                        $skuData['supplier_id'] = 0;
                    }

                } else {
                    $productData['supplier_id'] = 0;
                    $skuData['supplier_id'] = 0;
                }


                //创建spu
                $spuData['spu'] = $data->spu;
                if (count(SpuModel::where('spu', $data->spu)->get())) {
                    $spu_id = SpuModel::where('spu', $data->spu)->get()->toArray()[0]['id'];
                } else {
                    $spuModel = SpuModel::create($spuData);
                    $spu_id = $spuModel->id;
                }

                //model数据
                $productData['model'] = $data->model;
                $productData['spu_id'] = $spu_id;
                $productData['name'] = $data->products_name_en;
                $productData['c_name'] = $data->products_name_cn;

                $productData['purchase_url'] = $data->products_more_img;
                $productData['notify'] = $data->products_warring_string;
                //采购价
                $productData['purchase_price'] = $data->products_value;
                $productData['warehouse_id'] = $data->product_warehouse_id == 1000 ? '1' : '2';
                //$productData['warehouse_id'] =$itemModel->purchaseAdminer->warehouse_id;
                $volume = unserialize($data->products_volume);
                //长宽高
                if ($volume != '') {
                    if (!array_key_exists('bp', $volume)) {
                        $volume['bp']['length'] = 0;
                        $volume['bp']['width'] = 0;
                        $volume['bp']['height'] = 0;
                    }
                    if (!array_key_exists('ap', $volume)) {
                        $volume['ap']['length'] = 0;
                        $volume['ap']['width'] = 0;
                        $volume['ap']['height'] = 0;
                    }
                    $productData['package_height'] = $volume['ap']['length'];
                    $productData['package_width'] = $volume['ap']['width'];
                    $productData['package_length'] = $volume['ap']['height'];
                    $productData['height'] = $volume['bp']['length'];
                    $productData['width'] = $volume['bp']['width'];
                    $productData['length'] = $volume['bp']['height'];
                } else {
                    $productData['package_height'] = 0;
                    $productData['package_width'] = 0;
                    $productData['package_length'] = 0;
                    $productData['height'] = 0;
                    $productData['width'] = 0;
                    $productData['length'] = 0;
                }
                //创建model
                if (count(ProductModel::where('model', $data->model)->get())) {
                    $product_id = ProductModel::where('model', $data->model)->get()->toArray()[0]['id'];
                } else {
                    $productModel = ProductModel::create($productData);
                    $product_id = $productModel->id;
                    //包装限制
                    if ($data->pack_method != '') {
                        $wrr['wrap_limits_id'] = $data->pack_method;
                        $productModel->wrapLimit()->sync($wrr);
                    }
                    //物流限制
                    $brr = [];
                    if ($data->products_with_battery) {
                        $brr[] = 1;
                    }
                    if ($data->products_with_adapter) {
                        $brr[] = 4;
                    }
                    if ($data->products_with_fluid) {
                        $brr[] = 5;
                    }
                    if ($data->products_with_powder) {
                        $brr[] = 2;
                    }
                    $productModel->logisticsLimit()->sync($brr);

                }

                //数据
                $skuData['product_id'] = $product_id;
                $skuData['sku'] = $data->products_sku;
                $skuData['name'] = $data->products_name_en;
                $skuData['c_name'] = $data->products_name_cn;
                $skuData['weight'] = $data->products_weight;
                $skuData['warehouse_id'] = $data->product_warehouse_id == 1000 ? '1' : '2';
                //$skuData['warehouse_id'] = $itemModel->purchaseAdminer->warehouse_id;
                $skuData['warehouse_position'] = $data->products_location;


                $skuData['purchase_url'] = $data->products_more_img;
                $skuData['purchase_price'] = $data->products_value;
                $skuData['cost'] = $data->products_value;
                $skuData['height'] = $productData['height'];
                $skuData['width'] = $productData['width'];
                $skuData['length'] = $productData['length'];
                $skuData['package_height'] = $productData['package_height'];
                $skuData['package_width'] = $productData['package_width'];
                $skuData['package_length'] = $productData['package_length'];
                //
                $skuData['html_mod'] = $data->products_html_mod;
                //采购历史
                $skuData['sku_history_values'] = $data->products_history_values;
                $skuData['status'] = $data->products_status_2;
                $skuData['is_available'] = $data->productsIsActive;
                //创建sku
                $itemModel = ItemModel::create($skuData);

                //多对多供应商转换id
                $crr = explode(',', $erp_products_data[0]->products_suppliers_ids);
                if (substr($data->products_suppliers_ids, 0, 1) == ',') {
                    $data->products_suppliers_ids = substr($data->products_suppliers_ids, 1);
                }
                
                if(!$data->products_suppliers_ids){
                    $data->products_suppliers_ids = 0;
                }
                $supp_name = DB::select('select suppliers_id,suppliers_company
                                        from erp_suppliers where suppliers_id in(' . $data->products_suppliers_ids . ')');
                if (count($supp_name)) {
                    $my_suppliers_id_arr = [];
                    $supp_name_arr = [];
                    foreach ($supp_name as $_supp_name) {
                        $supp_name_arr[] = trim($_supp_name->suppliers_company);
                    }
                    $my_suppliers_id_two = SupplierModel::whereIn('company', $supp_name_arr)->get(['id'])->toArray();
                    if (count($my_suppliers_id_two)) {
                        foreach ($my_suppliers_id_two as $_my_suppliers_id_two) {
                            $my_suppliers_id_arr[] = $_my_suppliers_id_two['id'];
                        }

                        foreach (explode(',', $data->products_suppliers_ids) as $_supplier_id) {
                            $arr['supplier_id'] = $_supplier_id;
                            $itemModel->skuPrepareSupplier()->attach($my_suppliers_id_arr);
                        }
                    }
                }

            }
        }
        $last_id = SpuMultiOptionModel::all()->last()->spu_id;
        $spus = SpuModel::where('id', '>', $last_id)->get();
        foreach ($spus as $spu) {
            $channels = ChannelModel::all();
            foreach ($channels as $channel) {
                SpuMultiOptionModel::create(['spu_id' => $spu->id, 'channel_id' => $channel->id]);
            }
        }

    }

    //
    public function revertTo()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $items = $this->all();
        foreach ($items as $item) {
            
            $item->update(['declared_value'=>$item->product->declared_value]);
        }
    }

}