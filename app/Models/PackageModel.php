<?php
namespace App\Models;

use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\ErpEubModel;
use App\Models\Logistics\ErpRussiaModel;
use App\Models\Logistics\ErpShunFenModel;
use Excel;
use DB;
use App\Jobs\AssignStocks;
use App\Jobs\AssignLogistics;
use App\Jobs\PlaceLogistics;
use App\Base\BaseModel;
use App\Models\RequireModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\SupplierModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\ZoneModel;
use App\Models\Channel\AccountModel;
use App\Models\LogisticsModel;
use App\Models\Logistics\LimitsModel;
use App\Models\Product\ProductLogisticsLimitModel;
use App\Models\Logistics\ChannelModel as LogisticChannel;
use App\Models\ChannelModel;
use Queue;
use Cache;
use Session;

class PackageModel extends BaseModel
{
    public $table = 'packages';

    public $rules = [
        'create' => ['ordernum' => 'required'],
        'update' => [],
    ];

    // 用于查询
    public $searchFields = ['id' => 'ID'];

    public $fillable = [
        'channel_id',
        'channel_account_id',
        'order_id',
        'warehouse_id',
        'logistics_id',
        'picklist_id',
        'assigner_id',
        'shipper_id',
        'type',
        'status',
        'cost',
        'cost1',
        'weight',
        'actual_weight',
        'length',
        'width',
        'height',
        'tracking_no',
        'logistics_order_number',
        'tracking_link',
        'email',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_address1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'is_auto',
        'remark',
        'logistics_assigned_at',
        'printed_at',
        'shipped_at',
        'delivered_at',
        'created_at',
        'is_tonanjing',
        'is_over',
        'lazada_package_id',
        'is_oversea',
        'queue_name'
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
                'order' => ['id', 'channel_ordernum'],
                'items' => ['sku']
            ],
            'filterFields' => ['tracking_no', 'shipping_firstname', 'shipping_country'],
            'filterSelects' => [
                'is_oversea' => config('order.whether'),
                'status' => config('package'),
                'warehouse_id' => WarehouseModel::where('is_available', '1')->get(['id', 'name'])->pluck('name', 'id'),
            ],
            'selectRelatedSearchs' => [
                'order' => ['status' => config('order.status'), 'active' => config('order.active')],
                'channel' => ['name' => ChannelModel::get(['name'])->pluck('name', 'name')],
                'channelAccount' => ['account' => AccountModel::get(['account'])->pluck('account', 'account')]
            ],
            'sectionSelect' => ['time' => ['created_at', 'shipped_at']],
            'doubleRelatedSearchFields' => [
            ],
            'doubleRelatedSelectedFields' => [
            ],
            'sectionGanged' => [
                'first' => ['logistics' => ['catalog' => ['name' => CatalogModel::get(['name'])->pluck('name', 'name')]]],
                'second' => ['logistics_id' => LogisticsModel::where('is_enable', '1')->get(['id', 'code'])->pluck('code', 'id')]
            ],
        ];
    }

    public function getRelationArrAttribute()
    {
        return [
            'order' => ['orders', 'id', 'order_id'],
            'items' => ['package_items', 'package_id', 'id'],
            'channel' => ['channels', 'id', 'channel_id'],
            'channelAccount' => ['channel_accounts', 'id', 'channel_account_id'],
        ];
    }

    public function clearSession()
    {
        $arr = $this->relation_arr;
        foreach($arr as $key => $single) {
            Session::forget($this->table.'.'.$key);
        }
    }

    public function getPackageInfoAttribute()
    {
        $count = 0;
        $skuString = '';
        foreach ($this->items as $packageItem) {
            if ($count > 5) {
                $skuString .= ',***';
            } else {
                $skuString .= ',' . ($packageItem->item ? $packageItem->item->sku : '') . '*' . ($packageItem->item ? $packageItem->item->cost : '') . '' . ($packageItem->warehousePosition ? $packageItem->warehousePosition->name : '');
            }
            $count++;
        }
        $skuString = substr($skuString, 1);
        return $skuString;
    }

    public function getDeclareNameAttribute()
    {
        $skuString = '';
        foreach ($this->items as $packageItem) {
            $skuString .= ($packageItem->item ? $packageItem->item->name : '') . '*';
        }
        $skuString = substr($skuString, 1);
        return $skuString;
    }

    public function shipperName()
    {
        return $this->belongsTo('App\Models\UserModel', 'shipper_id', 'id');
    }

    public function getSelfValueAttribute()
    {
        $value = 0;
        foreach ($this->items as $packageItem) {
            $value += $packageItem->quantity * ($packageItem->item ? $packageItem->item->cost : 0);
        }

        return $value;
    }

    public function getLogisticsZoneAttribute()
    {
        $logisticsZone = 0;
        if ($this->logistics) {
            foreach ($this->logistics->zones as $zone) {
                foreach ($zone->logistics_zone_countries as $country) {
                    if ($this->shipping_country == $country->code) {
                        $logisticsZone = $zone->zone;
                    }
                }
            }
        }
        return $logisticsZone;
    }

    //获取申报信息
    public function getDeclaredInfo($isAll = false)
    {
        $data = [];
        if ($isAll) {
            $items = $this->items ? $this->items->item : false;
            if ($items) {
                foreach ($items as $item) {
                    $data['declared_value'] = $item->declared_value;
                    $data['weight'] = $item->weight;
                    $data['declared_en'] = $item->product ? $item->product->declared_en : '';
                    $data['declared_cn'] = $item->product ? $item->product->declared_cn : '';
                }
            }
        } else {
            $items = $this->items ? $this->items->first()->item : false;
            if ($items) {
                $data['declared_value'] = $items->declared_value;
                $data['weight'] = $items->weight;
                $data['declared_en'] = $items->product ? $items->product->declared_en : '';
                $data['declared_cn'] = $items->product ? $items->product->declared_cn : '';
            }
        }

        return $data;
    }

    //获取流向代码
    public function getExpressCodeAttribute()
    {
        $logistics = LogisticsModel::where('id', $this->logistics_id)->get();
        $name = '';
        foreach ($logistics as $value) {
            $name = $value->template->name;
        }
        $expressCode = '';
        if ($name == '顺丰俄罗斯挂号面单') {
            $erpRussia = ErpRussiaModel::where('country_code', $this->shipping_country)->where('type', 'g')->get();
            foreach ($erpRussia as $value) {
                $expressCode = $value->express_code;
            }
        }

        return $expressCode;
    }

    //获取分拣码
    public function getFenJianAttribute()
    {
        $code = '';
        $zip = substr($this->shipping_zipcode, 0, 3);
        $erpEubs = ErpEubModel::where('zip', $zip)->get();
        foreach ($erpEubs as $erpEub) {
            $code = $erpEub->code;
        }

        return $code;
    }

    //顺分荷兰面单分拣码
    public function getShunFenAttribute()
    {
        $fjm = '';
        if ($this->logistics) {
            if ($this->logistics->name == '【挂号】SF荷兰挂号') {
                $fjm = ErpShunFenModel::where('code', $this->shipping_country)->first()->gh;
            }
        }

        return $fjm;
    }

    //包裹sku信息
    public function getSkuInfoAttribute()
    {
        $skuString = '';
        foreach ($this->items as $key => $packageItem) {
            if ($key <= 2) {
                $skuString .= ',' . ($packageItem->item ? $packageItem->item->sku : '') . '*' . $packageItem->quantity . '【' . ($packageItem->warehousePosition ? $packageItem->warehousePosition->name : '') . '】';
            }
        }
        $skuString = substr($skuString, 1);

        return $skuString;
    }

    //sku申报名
    public function getDeclaredEnAttribute()
    {
        $declared_en = $this->items ? ($this->items->first()->item ? ($this->items->first()->item->product ? $this->items->first()->item->product->declared_en : '') : '') : '';

        return $declared_en;
    }

    //包裹总重量
    public function getTotalWeightAttribute()
    {
        $weight = 0;
        foreach ($this->items as $packageItem) {
            $weight += $packageItem->quantity * ($packageItem->item ? $packageItem->item->weight : 0);
        }

        return $weight;
    }

    //包裹单个sku重量
    public function getSignalWeightAttribute()
    {
        $weight = ($this->items ? $this->items->first()->quantity : 0) * ($this->items ? ($this->items->first()->item ? $this->items->first()->item->weight : 0) : 0);

        return $weight;
    }

    //包裹单个sku价格
    public function getSignalPriceAttribute()
    {
        $price = 0;
        if ($this->order->rate) {
            $price = ($this->items ? $this->items->first()->quantity : 0) * ($this->items ? ($this->items->first()->orderItem ? $this->items->first()->orderItem->price : 0) : 0);
            $price = $price / $this->order->rate;
        }

        return $price;
    }

    //包裹总价格
    public function getTotalPriceAttribute()
    {
        $price = 0;
        if ($this->order->rate) {
            foreach ($this->items as $packageItem) {
                $price += $packageItem->quantity * ($packageItem->item ? $packageItem->item->declared_value : 0);
            }
            $price = $price / $this->order->rate;
        }

        return $price;
    }

    //包裹是否含电池
    public function getIsBatteryAttribute()
    {
        $flag = false;
        foreach ($this->items as $packageItem) {
            if ($packageItem->item ? ($packageItem->item->product ? $packageItem->item->product : null) : null) {
                $productLogisticsLimits = ProductLogisticsLimitModel::where('product_id',
                    $packageItem->item->product->id)->get();
                foreach ($productLogisticsLimits as $productLogisticsLimit) {
                    $name = LimitsModel::where('id', $productLogisticsLimit->logistics_limits_id)->get(['name']);
                    if ($name == '含电池') {
                        $flag = true;
                    }
                }
            }
        }

        return $flag;
    }

    /**
     * 申报中文名
     * @return string
     */
    public function getDeclearedCnameAttribute()
    {
        $declared_cn = '';
        foreach ($this->items as $packageItem) {
            if ($packageItem->item) {
                $declared_cn = $packageItem->item->product ? $packageItem->item->product->declared_cn : '连衣裙';
            }
        }
        return $declared_cn;
    }

    /**
     * 申报英文名
     * @return string
     */
    public function getDeclearedEnameAttribute()
    {
        $declared_en = '';
        foreach ($this->items as $packageItem) {
            if ($packageItem->item) {
                $declared_en = $packageItem->item->product ? $packageItem->item->product->declared_en : 'dress';
            }
        }
        return $declared_en;
    }

    /**
     * 获取申报价值
     */
    public function getDeclearedValueAttribute()
    {
        $declared_val = 0;
        foreach ($this->items as $packageItem) {
            if ($packageItem->item) {
                $declared_val = $packageItem->item ? $packageItem->item->declared_value : 0;
            }
        }
        return $declared_val;
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'ASSIGNFAILED':
                $color = 'danger';
                break;
            case 'TRACKINGFAILED':
                $color = 'danger';
                break;
            case 'NEW':
                $color = 'info';
                break;
            case 'NEED':
                $color = 'warning';
                break;
            case 'ASSIGNED':
                $color = 'info';
                break;
            case 'PROCESSING':
                $color = 'info';
                break;
            case 'PICKING':
                $color = 'info';
                break;
            case 'PACKED':
                $color = 'info';
                break;
            case 'SHIPPED':
                $color = 'success';
                break;
            default:
                $color = 'info';
                break;
        }
        if ($this->order->status == 'REVIEW') {
            $color = 'danger';
        }

        return $color;
    }

    public function requires()
    {
        return $this->hasMany('App\Models\RequireModel', 'package_id', 'id');
    }

    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assigner_id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function picklist()
    {
        return $this->belongsTo('App\Models\PickListModel', 'picklist_id');
    }

    public function picklistItems()
    {
        return $this->belongsToMany('App\Models\Pick\ListItemModel', 'picklistitem_packages', 'package_id',
            'picklist_item_id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Package\ItemModel', 'package_id');
    }

    public function listItemPackages()
    {
        return $this->hasMany('App\Models\Pick\ListItemPackageModel', 'package_id', 'id');
    }

    public function manualLogistics()
    {
        return $this->hasMany('App\Models\Package\LogisticModel', 'package_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
    }

    public function russiaPYCode()
    {
        return $this->hasMany('App\Models\Logistics\Zone\RussiaPingCodeModel', 'country_code', 'shipping_country');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('package');
        return isset($arr[$this->status]) ? $arr[$this->status] : '';
    }

    public function shunyou()
    {
        return $this->belongsTo('App\Models\ShunyouModel', 'shipping_country', 'country_code');
    }

    public function fourpx()
    {
        return $this->belongsTo('App\Models\FourModel', 'shipping_country', 'fourpx_country_code');
    }

    public function Fourcode()
    {
        return $this->belongsTo('App\Models\FourcodeModel', 'shipping_country', 'code');
    }

    public function postconfig()
    {
        return $this->belongsTo('App\Models\PostpacketModel', 'logistics_id', 'shipment_id_string');
    }

    public function catelog()
    {
        return $this->belongsTo('App\Models\CatalogModel', $this->items->first()->item->product->catalog_id);
    }

    public function LogisticsChannel()
    {
        return $this->hasMany('App\Models\Logistics\ChannelModel', 'logistics_id', 'logistics_id');
    }

    //物流网址
    public function getThisPackageLogisticAttribute()
    {
        $url = $this->LogisticsChannel()->where('channel_id', '=', $this->channel_id)->select('url')->first();
        return $url['url'];
    }


    public function processGoods($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $path = $path . 'excelProcess.xls';
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        foreach ($arr as $key => $value) {
            $arr[$key] = $value[0];
        }

        return $arr;
    }

    public function cancelPackage()
    {
        if ($this->status == 'SHIPPED') {
            return false;
        }
        $item = $this->items()->first();
        if ($item->warehouse_position_id) {
            foreach ($this->items as $packageItem) {
                $packageItem->item->unhold($packageItem->warehouse_position_id, $packageItem->quantity, 'PACKAGE',
                    $this->id);
                $packageItem->forceDelete();
            }
        } else {
            foreach ($this->items as $packageItem) {
                $packageItem->forceDelete();
            }
        }
        return $this->forceDelete();
    }

    public function forceCancelPackage()
    {
        if (in_array($this->status, ['PACKED', 'SHIPPED'])) {
            return false;
        }
        $item = $this->items()->first();
        if ($item->warehouse_position_id) {
            foreach ($this->items as $packageItem) {
                $packageItem->item->unhold($packageItem->warehouse_position_id, $packageItem->quantity, 'PACKAGE',
                    $packageItem->id);
                $packageItem->forceDelete();
            }
        } else {
            foreach ($this->items as $packageItem) {
                $packageItem->forceDelete();
            }
        }
        return $this->forceDelete();
    }

    public function reCreatePackage()
    {
        $arr = [];
        $singleWarehouseId = 0;
        foreach ($this->items as $key => $packageItem) {
            $item = $packageItem->item;
            $warehouse_id = 0;
            $buf = $item->transit_quantity;
            foreach ($buf as $key1 => $value) {
                $flag = 0;
                foreach ($value as $k => $v) {
                    if ($v) {
                        $flag = 1;
                    }
                }
                if (!$flag) {
                    unset($buf[$key1]);
                }
            }
            if (count($buf) > 1 || count($buf) == 0) {
                $warehouse_id = $this->items->first()->item->warehouse_id ? $this->items->first()->item->warehouse_id : '3';
            } else {
                foreach ($buf as $key1 => $value) {
                    $warehouse_id = $key1;
                    break;
                }
            }
            if(!($key)) {
                $singleWarehouseId = $warehouse_id;
            }
            $arr[$warehouse_id][$key]['item_id'] = $packageItem->item_id;
            $arr[$warehouse_id][$key]['quantity'] = $packageItem->quantity;
            $arr[$warehouse_id][$key]['order_item_id'] = $packageItem->order_item_id;
        }
        if (count($arr) > 1) {
            foreach ($this->items as $bufItem) {
                $bufItem->forceDelete();
            }
            $j = 0;
            foreach ($arr as $k => $v) {
                if ($j == 0) {
                    foreach ($v as $k1 => $v1) {
                        $this->items()->create($v1);
                    }
                    $flag = 1;
                    if (count($v) > 1) {
                        $flag = 3;
                    } elseif (count($v) == 1) {
                        if ($v['0']['quantity'] > 1) {
                            $flag = 2;
                        } else {
                            $flag = 1;
                        }
                    }
                    $this->update([
                        'warehouse_id' => $k,
                        'type' => ($flag == 1 ? 'SINGLE' : ($flag == 2 ? 'SINGLEMULTI' : 'MULTI'))
                    ]);
                } else {
                    $package = $this->create($this->toArray());
                    foreach ($v as $k2 => $v2) {
                        $package->items()->create($v2);
                    }
                    $flag = 1;
                    if (count($v) > 1) {
                        $flag = 3;
                    } elseif (count($v) == 1) {
                        if (array_values($v)['0']['quantity'] > 1) {
                            $flag = 2;
                        } else {
                            $flag = 1;
                        }
                    }
                    $package->update([
                        'warehouse_id' => $k,
                        'type' => ($flag == 1 ? 'SINGLE' : ($flag == 2 ? 'SINGLEMULTI' : 'MULTI'))
                    ]);
                }
                $j++;
            }
            return $this->order_id;
        } else {
            $this->update(['warehouse_id' => $singleWarehouseId]);
        }

        return false;
    }

    public function canAssignStocks()
    {
        if (!in_array($this->status, ['NEW', 'NEED'])) {
            return false;
        }
        if ($this->order->status == 'REVIEW') {
            return false;
        }
        return true;
    }

    public function createPackageItems()
    {
        if ($this->canAssignStocks()) {
            $items = $this->setPackageItems();
            if ($items) {
                $channel = ChannelModel::find($this->channel_id);
                if($channel->name == 'Wish') {
                    if(count($items) > 1) {
                        return false;
                    }
                }
                return $this->createPackageDetail($items);
            } else {
                if ($this->status == 'NEW') {
                    if ($this->type == 'MULTI') {
                        $orderId = $this->reCreatePackage();
                        if ($orderId) {
                            $order = OrderModel::find($orderId);
                            foreach ($order->packages as $package) {
                                $job = new AssignStocks($package);
                                Queue::pushOn('assignStocks', $job);
                            }
                            return false;
                        } else {
                            foreach ($this->items()->with('item')->get() as $item) {
                                $require = [];
                                $require['item_id'] = $item->item_id;
                                $require['warehouse_id'] = $item->item->warehouse_id;
                                $require['order_item_id'] = $item->order_item_id;
                                $require['sku'] = $item->item->sku;
                                $require['quantity'] = $item->quantity;
                                $this->requires()->create($require);
                            }
                            //todo v3测试，正式上线删除
                            $this->update([
                                'status' => 'WAITASSIGN',
                                'queue_name' => 'assignLogistics'
                            ]);
                            $job = new AssignLogistics($this);
                            Queue::pushOn('assignLogistics', $job);
                            $this->order->update(['status' => 'NEED']);
                            return false;
                        }
                    } else {
                        foreach ($this->items()->with('item')->get() as $item) {
                            $require = [];
                            $require['item_id'] = $item->item_id;
                            $require['warehouse_id'] = $item->item->warehouse_id;
                            $require['order_item_id'] = $item->order_item_id;
                            $require['sku'] = $item->item->sku;
                            $require['quantity'] = $item->quantity;
                            $this->requires()->create($require);
                        }
                        //todo v3测试，正式上线删除
                        $fItem = $this->items()->first();
                        $arr = $fItem->item->transit_quantity;
                        foreach ($arr as $key => $value) {
                            $flag = 0;
                            foreach ($value as $k => $v) {
                                if ($v) {
                                    $flag = 1;
                                }
                            }
                            if (!$flag) {
                                unset($arr[$key]);
                            }
                        }
                        if (count($arr) > 1 || count($arr) == 0) {
                            $warehouse_id = $fItem->item->warehouse_id ? ($fItem->item->warehouse_id == '1' ? '3' : $fItem->item->warehouse_id) : '3';
                        } else {
                            foreach ($arr as $key => $value) {
                                $warehouse_id = $key;
                                break;
                            }
                        }
                        $this->update([
                            'status' => 'WAITASSIGN',
                            'warehouse_id' => $warehouse_id,
                            'queue_name' => 'assignLogistics'
                        ]);
                        $job = new AssignLogistics($this);
                        Queue::pushOn('assignLogistics', $job);
                        $this->order->update(['status' => 'NEED']);
                        return false;
                    }
                } else {
                    if (strtotime($this->created_at) < strtotime('-3 days')) {
                        $arr = $this->explodePackage();
                        if ($arr) {
                            $this->createChildPackage($arr);
                        }
                    }
                    $this->update(['queue_name' => '']);
                    return false;
                }
            }
        }

        return false;
    }

    public function createChildPackage($arr)
    {
        $items = $this->items;
        if ($this->order->status == 'NEED') {
            $this->order->update(['status' => 'PARTIAL']);
        }
        foreach ($arr as $warehouseId => $stockInfo) {
            $weight = 0;
            $newPackage = $this->create($this->toArray());
            foreach ($stockInfo as $stockId => $info) {
                $item = $items->where('item_id', $info['item_id'])->first();
                if ($item->quantity <= $info['quantity']) {
                    $item->delete();
                } else {
                    $weight += $item->item->weight * $info['quantity'];
                    $item->update(['quantity' => ($item->quantity - $info['quantity'])]);
                }
                $newPackage->items()->create($info);
            }
            foreach ($newPackage->items as $single) {
                $single->item->hold($single->warehouse_position_id, $single->quantity, 'PACKAGE', $newPackage->id);
            }
            $newPackage->update([
                'status' => 'WAITASSIGN',
                'warehouse_id' => $info['warehouse_id'],
                'weight' => $weight,
                'logistics_id' => '',
                'tracking_no' => '',
            ]);
            //加入订单状态部分发货
        }
        $oldWeight = 0;
        foreach ($this->items as $item) {
            $oldWeight += $item->item->weight * $item->quantity;
        }
        $this->update(['weight' => $oldWeight, 'logistics_id' => '', 'tracking_no' => '']);
        $this->eventLog(UserModel::find(request()->user()->id)->name, '自动拆分订单', json_encode($this));
    }

    public function atLeastTimes($arr)
    {
        $sum = 0;
        foreach ($arr as $key => $value) {
            if ($value['sum'] == $value['allocateSum'] || $value['allocateSum'] == 0) {
                $sum += 1;
            } else {
                $sum += 2;
            }
        }

        return $sum;
    }

    public function explodePackage()
    {
        $arr = $this->packageStockDiff($this->packageNeedArray());
        $sum = $this->atLeastTimes($arr);
        if ($this->order->split_times > (4 - $sum)) {
            return false;
        }
        $stocks = [];
        foreach ($arr as $key => $value) {
            if (!($value['allocateSum'] >= 5 && $value['allocateSum'] / $value['sum'] >= 0.5 || $value['allocateSum'] < 5 && $value['allocateSum'] == $value['sum'])) {
                continue;
            }
            foreach ($value as $k => $v) {
                if (!is_array($v)) {
                    continue;
                }
                if ($v['allocateQuantity']) {
                    $defaultStocks = ItemModel::find($k)->assignDefaultStock($v['allocateQuantity'],
                        $v['order_item_id']);
                    if (array_key_exists($key, $stocks)) {
                        $stocks[$key] += $defaultStocks[$key];
                    } else {
                        $stocks += $defaultStocks;
                    }
                }
            }
        }

        return $stocks;
    }


    public function PackageNeedArray()
    {
        $arr = [];
        foreach ($this->items as $packageItem) {
            $item = $packageItem->item;
            $needQuantity = $packageItem->quantity;
            if ($needQuantity) {
                if (!array_key_exists($item->warehouse_id, $arr)) {
                    $arr[$item->warehouse_id] = [];
                    $arr[$item->warehouse_id]['sum'] = 0;
                    if (!array_key_exists($packageItem->item_id, $arr[$item->warehouse_id])) {
                        $arr[$item->warehouse_id][$packageItem->item_id]['quantity'] = $needQuantity;
                        $arr[$item->warehouse_id][$packageItem->item_id]['order_item_id'] = $packageItem->order_item_id;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    } else {
                        $arr[$item->warehouse_id][$packageItem->item_id]['quantity'] += $needQuantity;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    }
                } else {
                    if (!array_key_exists($packageItem->item_id, $arr[$item->warehouse_id])) {
                        $arr[$item->warehouse_id][$packageItem->item_id]['quantity'] = $needQuantity;
                        $arr[$item->warehouse_id][$packageItem->item_id]['order_item_id'] = $packageItem->order_item_id;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    } else {
                        $arr[$item->warehouse_id][$packageItem->item_id]['quantity'] += $needQuantity;
                        $arr[$item->warehouse_id]['sum'] += $needQuantity;
                    }
                }
            }
        }
        return $arr;
    }

    public function packageStockDiff($arr)
    {
        foreach ($arr as $warehouseId => $singleWarehouseInfo) {
            $arr[$warehouseId]['allocateSum'] = 0;
            foreach ($singleWarehouseInfo as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                foreach ($value as $k => $v) {
                    $stocks = StockModel::where(['item_id' => $key, 'warehouse_id' => $warehouseId])->get();
                    if (!count($stocks)) {
                        $arr[$warehouseId][$key]['allocateQuantity'] = 0;
                    } else {
                        $stock_sum = $stocks->sum('available_quantity');
                        $arr[$warehouseId][$key]['allocateQuantity'] = ($stock_sum <= $arr[$warehouseId][$key]['quantity']) ? $stock_sum : $arr[$warehouseId][$key]['quantity'];
                        $arr[$warehouseId]['allocateSum'] += $arr[$warehouseId][$key]['allocateQuantity'];
                    }
                    continue 2;
                }
            }
        }

        return $arr;
    }

    /**
     * @param array $items
     * @return array|bool
     */
    public function setPackageItems()
    {
        if ($this->items->count() > 1) { //多产品
            if (empty($this->warehouse_id)) {
                $packageItem = $this->setMultiPackageItem();
            } else {
                $packageItem = $this->setPackageItemFb();
            }
        } else { //单产品
            if (empty($this->warehouse_id)) {
                $packageItem = $this->setSinglePackageItem();
            } else {
                $packageItem = $this->setPackageItemFb();
            }
        }
        return $packageItem;
    }

    //设置单产品订单包裹产品
    public function setSinglePackageItem()
    {
        $packageItem = [];
        $originPackageItem = $this->items()->with('item')->first();
        $quantity = $originPackageItem->quantity;
        if (!$quantity) {
            return false;
        }
        $stocks = $originPackageItem->item->assignStock($quantity);
        if ($stocks) {
            foreach ($stocks as $warehouseId => $stock) {
                foreach ($stock as $key => $value) {
                    $packageItem[$warehouseId][$key] = $value;
                    $packageItem[$warehouseId][$key]['order_item_id'] = $originPackageItem->order_item_id;
                    $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                }
            }
        } else {
            return false;
        }

        return $packageItem;
    }

    //设置多产品订单包裹产品
    public function setMultiPackageItem()
    {
        $packageItem = [];
        $stocks = [];
        //根据仓库满足库存数量进行排序
        $warehouses = [];
        foreach ($this->items()->with('item')->get() as $originPackageItem) {
            $quantity = $originPackageItem->quantity;
            if (!$quantity) {
                continue;
            }
            $itemStocks = $originPackageItem->item->matchStock($quantity);
            if ($itemStocks) {
                foreach ($itemStocks as $itemStock) {
                    foreach ($itemStock as $warehouseId => $stock) {
                        if (isset($warehouses[$warehouseId])) {
                            $warehouses[$warehouseId] += 1;
                        } else {
                            $warehouses[$warehouseId] = 1;
                        }
                    }
                }
                $stocks[$originPackageItem->order_item_id] = $itemStocks;
            } else {
                return false;
            }
        }
        krsort($warehouses);
        //set package item
        foreach ($stocks as $orderItemId => $itemStocks) {
            foreach ($itemStocks as $type => $itemStock) {
                if ($type == 'SINGLE') {
                    $stock = collect($itemStock)->sortByDesc(function ($value, $key) use ($warehouses) {
                        return $warehouses[$key];
                    })->first();
                    foreach ($stock as $key => $value) {
                        $packageItem[$value['warehouse_id']][$key] = $value;
                        $packageItem[$value['warehouse_id']][$key]['order_item_id'] = $orderItemId;
                        $packageItem[$value['warehouse_id']][$key]['remark'] = 'REMARK';
                    }
                } else {
                    foreach ($itemStock as $warehouseId => $warehouseStock) {
                        foreach ($warehouseStock as $key => $value) {
                            $packageItem[$warehouseId][$key] = $value;
                            $packageItem[$warehouseId][$key]['order_item_id'] = $orderItemId;
                            $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                        }
                    }
                }
            }
        }

        return $packageItem;
    }

    //设置多产品订单包裹产品
    public function setPackageItemFb()
    {
        $warehouses = WarehouseModel::where(['type' => 'local', 'is_available' => '1'])->get();
        foreach ($warehouses as $key => $warehouse) {
            $warehouseId = $warehouse->id;
            $buf = [];
            $i = 0;
            foreach ($this->items as $packageItem) {
                $pquantity = $packageItem->quantity;
                $stocks = StockModel::where([
                    'item_id' => $packageItem->item_id,
                    'warehouse_id' => $warehouseId
                ])->get()->sortByDesc('available_quantity');
                if ($stocks->sum('available_quantity') < $pquantity) {
                    unset($stocks);
                    continue 2;
                }
                foreach ($stocks as $key => $stock) {
                    if ($stock->available_quantity < $pquantity) {
                        $buf[$warehouseId][$i]['item_id'] = $packageItem->item_id;
                        $buf[$warehouseId][$i]['warehouse_position_id'] = $stock->warehouse_position_id;
                        $buf[$warehouseId][$i]['order_item_id'] = $packageItem->order_item_id;
                        $buf[$warehouseId][$i]['quantity'] = $stock->available_quantity;
                        $pquantity -= $stock->available_quantity;
                        $i++;
                    } else {
                        $buf[$warehouseId][$i]['item_id'] = $packageItem->item_id;
                        $buf[$warehouseId][$i]['warehouse_position_id'] = $stock->warehouse_position_id;
                        $buf[$warehouseId][$i]['order_item_id'] = $packageItem->order_item_id;
                        $buf[$warehouseId][$i]['quantity'] = $pquantity;
                        $i++;
                        continue 2;
                    }
                }
            }
            if (!empty($buf)) {
                return $buf;
            }
        }

        return false;
    }

    /******************************************************************************/
    public function oversea_createPackageItems()
    {
        $arr = [];
        foreach ($this->items as $key => $single) {
            $arr[$single->code][$key]['item_id'] = $single->item_id;
            $arr[$single->code][$key]['quantity'] = $single->quantity;
            $arr[$single->code][$key]['order_item_id'] = $single->order_item_id;
            $arr[$single->code][$key]['remark'] = $single->remark;
            $arr[$single->code][$key]['is_oversea'] = $single->is_oversea;
            $arr[$single->code][$key]['code'] = $single->code;
        }
        foreach ($arr as $code => $value) {
            $newPackage = $this->create($this->toarray());
            $warehouse = WarehouseModel::where('code', $code)->first();
            if (!$warehouse) {
                return false;
            }
            foreach ($value as $k => $v) {
                $newPackage->items()->create($v);
            }
            $arr = '';
            if ($newPackage->items->count() == 1) {
                $arr = $newPackage->oversea_setSinglePackageItem($code);
            } elseif ($newPackage->items->count() > 1) {
                $arr = $newPackage->oversea_setMultiPackageItem($code);
            }
            if ($arr) {
                $newPackage->oversea_createPackageDetail($arr);
            } else {
                $newPackage->update(['status' => 'NEED', 'queue_name' => '']);
            }
        }
        $this->cancelPackage();
    }

    public function oversea_createPackageDetail($items)
    {
        foreach ($this->items as $packageItem) {
            $packageItem->forceDelete();
        }
        $this->order->update(['status' => 'PACKED']);
        $i = true;
        foreach ($items as $warehouseId => $packageItems) {
            if ($i) {
                $i = false;
                $weight = 0;
                foreach ($packageItems as $key => $packageItem) {
                    $newPackageItem = $this->items()->create($packageItem);
                    $weight += $newPackageItem->item->weight * $newPackageItem->quantity;
                    DB::beginTransaction();
                    try {
                        $newPackageItem->item->hold(
                            $packageItem['warehouse_position_id'],
                            $packageItem['quantity'],
                            'PACKAGE',
                            $newPackageItem->id);
                    } catch (Exception $e) {
                        DB::rollBack();
                    }
                    DB::commit();
                }
                $this->update([
                    'warehouse_id' => $warehouseId,
                    'status' => 'WAITASSIGN',
                    'weight' => $weight,
                    'queue_name' => 'assignLogistics',
                    'type' => $this->items()->count() > 1 ? 'MULTI' : ($this->items()->first()->quantity > 1 ? 'SINGLEMULTI' : 'SINGLE'),
                ]);
                $job = new AssignLogistics($this);
                Queue::pushOn('assignLogistics', $job);
            } else {
                $newPackage = $this->create($this->toArray());
                $weight = 0;
                if ($newPackage) {
                    foreach ($packageItems as $key => $packageItem) {
                        $newPackageItem = $newPackage->items()->create($packageItem);
                        $weight += $newPackageItem->item->weight * $newPackageItem->quantity;
                        DB::beginTransaction();
                        try {
                            $newPackageItem->item->hold(
                                $packageItem['warehouse_position_id'],
                                $packageItem['quantity'],
                                'PACKAGE',
                                $newPackageItem->id);
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                        DB::commit();
                    }
                    $newPackage->update([
                        'warehouse_id' => $warehouseId,
                        'status' => 'WAITASSIGN',
                        'weight' => $weight,
                        'queue_name' => 'assignLogistics',
                        'type' => $this->items()->count() > 1 ? 'MULTI' : ($this->items()->first()->quantity > 1 ? 'SINGLEMULTI' : 'SINGLE'),
                    ]);
                    $job = new AssignLogistics($newPackage);
                    Queue::pushOn('assignLogistics', $job);
                }
            }
        }

        return true;
    }


    //设置单产品订单包裹产品
    public function oversea_setSinglePackageItem($code)
    {
        $packageItem = [];
        $originPackageItem = $this->items->first();
        $quantity = $originPackageItem->quantity;
        if (!$quantity) {
            return false;
        }
        $stocks = $originPackageItem->item->oversea_assignStock($quantity, $code);
        if ($stocks) {
            foreach ($stocks as $warehouseId => $stock) {
                foreach ($stock as $key => $value) {
                    $packageItem[$warehouseId][$key] = $value;
                    $packageItem[$warehouseId][$key]['order_item_id'] = $originPackageItem->order_item_id;
                    $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                }
            }
        } else {
            return false;
        }

        return $packageItem;
    }

    //设置多产品订单包裹产品
    public function oversea_setMultiPackageItem($code)
    {
        $packageItem = [];
        $stocks = [];
        //根据仓库满足库存数量进行排序
        $warehouses = [];
        foreach ($this->items as $originPackageItem) {
            $quantity = $originPackageItem->quantity;
            if (!$quantity) {
                continue;
            }
            $itemStocks = $originPackageItem->item->oversea_matchStock($quantity, $code);
            if ($itemStocks) {
                foreach ($itemStocks as $itemStock) {
                    foreach ($itemStock as $warehouseId => $stock) {
                        if (isset($warehouses[$warehouseId])) {
                            $warehouses[$warehouseId] += 1;
                        } else {
                            $warehouses[$warehouseId] = 1;
                        }
                    }
                }
                $stocks[$originPackageItem->order_item_id] = $itemStocks;
            } else {
                return false;
            }
        }
        krsort($warehouses);
        //set package item
        foreach ($stocks as $orderItemId => $itemStocks) {
            foreach ($itemStocks as $type => $itemStock) {
                if ($type == 'SINGLE') {
                    $stock = collect($itemStock)->sortByDesc(function ($value, $key) use ($warehouses) {
                        return $warehouses[$key];
                    })->first();
                    foreach ($stock as $key => $value) {
                        $packageItem[$value['warehouse_id']][$key] = $value;
                        $packageItem[$value['warehouse_id']][$key]['order_item_id'] = $orderItemId;
                        $packageItem[$value['warehouse_id']][$key]['remark'] = 'REMARK';
                    }
                } else {
                    foreach ($itemStock as $warehouseId => $warehouseStock) {
                        foreach ($warehouseStock as $key => $value) {
                            $packageItem[$warehouseId][$key] = $value;
                            $packageItem[$warehouseId][$key]['order_item_id'] = $orderItemId;
                            $packageItem[$warehouseId][$key]['remark'] = 'REMARK';
                        }
                    }
                }
            }
        }

        return $packageItem;
    }

    /*********************************************************************************/

    public function createPackageDetail($items)
    {
        $oldStatus = $this->status;
        $oldWarehouseId = $this->warehouse_id;
        $oldLogisticsId = $this->logistics_id;
        $oldTrackingNo = $this->tracking_no;
        $oldWeight = $this->weight;
        foreach ($this->items as $packageItem) {
            $packageItem->forceDelete();
        }
        $i = true;
        foreach ($items as $warehouseId => $packageItems) {
            if ($i) {
                $i = false;
                $weight = 0;
                foreach ($packageItems as $key => $packageItem) {
                    $newPackageItem = $this->items()->create($packageItem);
                    $weight += $newPackageItem->item->weight * $newPackageItem->quantity;
                    DB::beginTransaction();
                    try {
                        $newPackageItem->item->hold(
                            $packageItem['warehouse_position_id'],
                            $packageItem['quantity'],
                            'PACKAGE',
                            $newPackageItem->id);
                    } catch (Exception $e) {
                        DB::rollBack();
                    }
                    DB::commit();
                }
                if (empty($oldWarehouseId)) {
                    $this->update([
                        'warehouse_id' => $warehouseId,
                        'status' => 'WAITASSIGN',
                        'weight' => $weight,
                        'queue_name' => 'assignLogistics'
                    ]);
                    $job = new AssignLogistics($this);
                    Queue::pushOn('assignLogistics', $job);
                    $this->eventLog('队列', '已匹配到库存,待分配', json_encode($this));
                } else {
                    if ($oldWarehouseId != $warehouseId) {
                        $this->update([
                            'warehouse_id' => $warehouseId,
                            'status' => 'WAITASSIGN',
                            'weight' => $weight,
                            'logistics_id' => '',
                            'logistics_order_number' => '',
                            'tracking_no' => '',
                            'queue_name' => 'assignLogistics'
                        ]);
                        $job = new AssignLogistics($this);
                        Queue::pushOn('assignLogistics', $job);
                        $this->eventLog('队列', '已匹配到库存,待分配', json_encode($this));
                    } else {
                        if (floatval($weight) - floatval($oldWeight) < 0.00000000001) {
                            if (!empty($oldLogisticsId) && !empty($oldTrackingNo)) {
                                $this->update(['status' => 'PROCESSING', 'queue_name' => '']);
                                $this->eventLog('队列', '已匹配到库存,待拣货', json_encode($this));
                                continue;
                            }
                            if (!empty($oldLogisticsId) && empty($oldTrackingNo)) {
                                $this->update(['status' => 'ASSIGNED', 'queue_name' => 'placeLogistics']);
                                $job = new PlaceLogistics($this);
                                Queue::pushOn('placeLogistics', $job);
                                $this->eventLog('队列', '已匹配到库存,待下单', json_encode($this));
                                continue;
                            }
                            $this->update(['status' => 'WAITASSIGN', 'queue_name' => 'assignLogistics']);
                            $job = new AssignLogistics($this);
                            Queue::pushOn('assignLogistics', $job);
                            $this->eventLog('队列', '已匹配到库存,待分配', json_encode($this));
                        } else {
                            $this->update([
                                'status' => 'WAITASSIGN',
                                'weight' => $weight,
                                'logistics_id' => '',
                                'logistics_order_number' => '',
                                'tracking_no' => '',
                                'queue_name' => 'assignLogistics',
                            ]);
                            $job = new AssignLogistics($this);
                            Queue::pushOn('assignLogistics', $job);
                            $this->eventLog('队列', '已匹配到库存,待分配', json_encode($this));
                        }
                    }
                }
            } else {
                $newPackage = $this->create($this->toArray());
                $weight = 0;
                if ($newPackage) {
                    foreach ($packageItems as $key => $packageItem) {
                        $newPackageItem = $newPackage->items()->create($packageItem);
                        $weight += $newPackageItem->item->weight * $newPackageItem->quantity;
                        DB::beginTransaction();
                        try {
                            $newPackageItem->item->hold(
                                $packageItem['warehouse_position_id'],
                                $packageItem['quantity'],
                                'PACKAGE',
                                $newPackageItem->id);
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                        DB::commit();
                    }
                    $newPackage->update([
                        'warehouse_id' => $warehouseId,
                        'status' => 'WAITASSIGN',
                        'weight' => $weight
                    ]);
                    if (!empty($oldWarehouseId)) {
                        if ($oldWarehouseId != $warehouseId) {
                            $newPackage->update([
                                'warehouse_id' => $warehouseId,
                                'status' => 'WAITASSIGN',
                                'weight' => $weight,
                                'logistics_id' => '',
                                'tracking_no' => '',
                                'logistics_order_number' => '',
                                'queue_name' => 'assignLogistics',
                            ]);
                            $job = new AssignLogistics($newPackage);
                            Queue::pushOn('assignLogistics', $job);
                            $newPackage->eventLog('队列', '已匹配到库存,待分配', json_encode($newPackage));
                        } else {
                            if (floatval($weight) - floatval($oldWeight) < 0.00000000001) {
                                if (!empty($oldLogisticsId) && !empty($oldTrackingNo)) {
                                    $newPackage->update(['status' => 'PROCESSING', 'queue_name' => '']);
                                    $newPackage->eventLog('队列', '已匹配到库存,待拣货', json_encode($newPackage));
                                    continue;
                                }
                                if (!empty($oldLogisticsId) && empty($oldTrackingNo)) {
                                    $newPackage->update(['status' => 'ASSIGNED', 'queue_name' => 'placeLogistics']);
                                    $job = new PlaceLogistics($newPackage);
                                    Queue::pushOn('placeLogistics', $job);
                                    $newPackage->eventLog('队列', '已匹配到库存,待下单', json_encode($newPackage));
                                    continue;
                                }
                                $newPackage->update(['status' => 'WAITASSIGN', 'queue_name' => 'assignLogistics']);
                                $job = new AssignLogistics($newPackage);
                                Queue::pushOn('assignLogistics', $job);
                                $newPackage->eventLog('队列', '已匹配到库存,待分配', json_encode($newPackage));
                            } else {
                                $newPackage->update([
                                    'status' => 'WAITASSIGN',
                                    'weight' => $weight,
                                    'queue_name' => 'assignLogistics'
                                ]);
                                $job = new AssignLogistics($newPackage);
                                Queue::pushOn('assignLogistics', $job);
                                $newPackage->eventLog('队列', '已匹配到库存,待分配', json_encode($newPackage));
                            }
                        }
                    } else {
                        $newPackage->update(['queue_name' => 'assignLogistics']);
                        $job = new AssignLogistics($newPackage);
                        Queue::pushOn('assignLogistics', $job);
                        $newPackage->eventLog('队列', '已匹配到库存,待分配', json_encode($newPackage));
                    }
                }
            }
        }

        return true;
    }

    public function getShippingLimitsAttribute()
    {
        $packageLimits = collect();
        foreach ($this->items()->with('item.product')->get() as $packageItem) {
            $all = $packageItem->item->product->logisticsLimit;
            foreach ($all as $key => $packageLimit) {
                if ($packageLimit) {
                    $packageLimits = $packageLimits->merge(explode(",", $packageLimit->pivot->logistics_limits_id));
                }
            }
        }

        return $packageLimits->unique();
    }

    public function getHasPickAttribute()
    {
        $items = $this->items;
        foreach ($items as $item) {
            if ($item->picked_quantity) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断包裹是否能分配物流
     */
    public function canAssignLogistics()
    {
        //判断包裹状态
        if (!in_array($this->status, ['WAITASSIGN', 'ASSIGNFAILED', 'NEED'])) {
            return false;
        }

        //判断是否自动发货
        if (!$this->is_auto) {
            return false;
        }
        return true;
    }

    public function calculateLogisticsFee()
    {
        if (!empty($this->logistics_id)) {
            $logisticsId = $this->logistics_id;
        } else {
            if ($this->realTimeLogistics()) {
                $logisticsId = $this->realTimeLogistics()->logistics->id;
            } else {
                return false;
            }
        }
        $zones = ZoneModel::where('logistics_id', $logisticsId)->get();
        foreach ($zones as $zone) {
            $country = CountriesModel::where('code', $this->shipping_country)->first();
            if ($country) {
                $code = $this->shipping_country;
            } else {
                $countryChange = CountriesChangeModel::where('country_from', $this->shipping_country)->first();
                if ($countryChange) {
                    $code = $countryChange->country_to;
                } else {
                    $code = '';
                }
            }
            if ($zone->inZone($code)) {
                if ($zone->type == 'first') {
                    if ($this->weight <= $zone->fixed_weight) {
                        $fee = $zone->fixed_price;
                    } else {
                        $fee = $zone->fixed_price;
                        $weight = $this->weight - $zone->fixed_weight;
                        if ($zone->continued_weight) {
                            $fee += ceil($weight / $zone->continued_weight) * $zone->continued_price;
                        } else {
                            return false;
                        }
                    }
                    if ($zone->discount_weather_all) {
                        $fee = ($fee + $zone->other_fixed_price) * $zone->discount;
                    } else {
                        $fee = $fee * $zone->discount + $zone->other_fixed_price;
                    }
                    return $fee;
                } else {
                    $sectionPrices = $zone->zone_section_prices;
                    foreach ($sectionPrices as $sectionPrice) {
                        if ($this->weight >= $sectionPrice->weight_from && $this->weight < $sectionPrice->weight_to) {
                            return $sectionPrice->price;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * 自动分配物流方式
     */
    public function realTimeLogistics()
    {
        $weight = $this->weight; //包裹重量
        $amount = $this->order ? $this->order->amount : ''; //订单金额
//        $amountShipping = $this->order ? $this->order->amount_shipping : ''; //订单运费
//        $celeAdmin = $this->order ? $this->order->cele_admin : '';
        //是否通关
//        if ($amount > $amountShipping && $amount > 0.1 && $celeAdmin == null) {
//            $isClearance = 1;
//        } else {
//            $isClearance = 0;
//        }
        if ($this->warehouse) {
            $rules = $this->warehouse->logisticsRules()
                ->where(function ($query) use ($weight) {
                    $query->where('weight_from', '<=', $weight)
                        ->where('weight_to', '>=', $weight)->orwhere('weight_section', '0');
                })
                ->where(function ($query) use ($amount) {
                    $query->where('order_amount_from', '<=', $amount)
                        ->where('order_amount_to', '>=', $amount)->orwhere('order_amount_section', '0');
                })
//                ->where(['is_clearance' => $isClearance])
                ->with([
                    'logistics',
                    'rule_catalogs',
                    'rule_channels',
                    'rule_countries_through',
                    'rule_accounts',
                    'rule_transports_through',
                    'rule_limits',
                    'logistics.logisticsChannels'
                ])
                ->get()
                ->sortBy(function ($single, $key) {
                    return $single->logistics ? $single->logistics->priority : 1;
                });
            foreach ($rules as $rule) {
                if ($rule->catalog_section) {
                    $catalogs = $rule->rule_catalogs->pluck('catalog_id');
                    foreach ($this->items as $item) {
                        if (!in_array($item->catalog_id, $catalogs->toArray())) {
                            continue 2;
                        }
                    }
                }
                if ($rule->channel_section) {
                    $channel = $rule->rule_channels
                        ->where('channel_id', $this->channel_id)
                        ->first();
                    if (!$channel) {
                        continue;
                    }
                }
                if ($rule->country_section) {
                    $country = $rule->rule_countries_through
                        ->where('code', $this->shipping_country)
                        ->first();
                    if (!$country) {
                        continue;
                    }
                }
                if ($rule->account_section) {
                    $channel_account = $rule->rule_accounts
                        ->where('account_id', $this->channel_account_id)
                        ->first();
                    if (!$channel_account) {
                        continue;
                    }
                }
                if ($rule->transport_section) {
                    if ($this->order->shipping) {
                        $transport = $rule->rule_transports_through
                            ->where('name', $this->order->shipping)
                            ->first();
                        if (!$transport) {
                            continue;
                        }
                    }
                }
                if ($rule->limit_section) {
                    $shippingLimits = $this->shipping_limits;
                    $mustLimits = $rule->rule_limits->where('type', '0')->pluck('logistics_limit_id');
                    $noLimits = $rule->rule_limits->where('type', '1')->pluck('logistics_limit_id');
                    if ($mustLimits->count() > 0 and $shippingLimits->count() < 1) {
                        continue;
                    }
                    foreach ($shippingLimits as $shippingLimit) {
                        if (in_array($shippingLimit, $noLimits->toArray())) {
                            continue 2;
                        }
                    }
                }
                return $rule;
            }
        }
        return false;
    }

    public function assignLogistics()
    {
        if ($this->canAssignLogistics()) {
            $rule = $this->realTimeLogistics();
            if ($rule) {
                //物流查询链接
                $logistics = $rule->logistics;
                $object = $logistics->logisticsChannels()->where('channel_id', $this->channel_id)->first();
                $trackingUrl = $object ? $object->url : '';
                $is_auto = ($rule->logistics->docking == 'MANUAL' ? '0' : '1');
                if (Cache::has('package' . $this->id . 'logisticsId') && Cache::get('package' . $this->id . 'logisticsId') == $rule->logistics->id) {
                    $item = $this->items->first();
                    if (empty($item->warehouse_position_id)) {
                        return $this->update([
                            'logistics_id' => $rule->logistics->id,
                            'tracking_link' => $trackingUrl,
                            'logistics_assigned_at' => date('Y-m-d H:i:s'),
                            'is_auto' => $is_auto,
                            'status' => 'NEED',
                            'tracking_no' => Cache::get('package' . $this->id . 'trackingNo'),
                        ]);
                    } else {
                        return $this->update([
                            'logistics_id' => $rule->logistics->id,
                            'tracking_link' => $trackingUrl,
                            'logistics_assigned_at' => date('Y-m-d H:i:s'),
                            'is_auto' => $is_auto,
                            'status' => 'PROCESSING',
                            'tracking_no' => Cache::get('package' . $this->id . 'trackingNo'),
                        ]);
                    }
                } else {
                    $item = $this->items->first();
                    if (empty($item->warehouse_position_id) && $object && !$object->delivery) {
                        return $this->update([
                            'status' => 'NEED',
                            'logistics_id' => $rule->logistics->id,
                            'tracking_link' => $trackingUrl,
                            'logistics_assigned_at' => date('Y-m-d H:i:s'),
                            'logistics_order_number' => '',
                            'tracking_no' => '',
                            'is_auto' => $is_auto,
                        ]);
                    } else {
                        return $this->update([
                            'status' => 'ASSIGNED',
                            'logistics_id' => $rule->logistics->id,
                            'tracking_link' => $trackingUrl,
                            'logistics_assigned_at' => date('Y-m-d H:i:s'),
                            'logistics_order_number' => '',
                            'tracking_no' => '',
                            'is_auto' => $is_auto,
                        ]);
                    }
                }
            }
            return $this->update([
                'status' => 'ASSIGNFAILED',
                'logistics_assigned_at' => date('Y-m-d H:i:s')
            ]);
        }

        return false;
    }

    /**
     * 判断包裹是否能物流下单
     */
    public function canplaceLogistics($type)
    {
        if ($type == 'UPDATE') {
            //判断订单状态
            if (!in_array($this->status, ['NEED', 'PROCESSING', 'PICKING', 'PACKED', 'SHIPPED'])) {
                return false;
            }
        } else {
            //判断订单状态
            if (!in_array($this->status, ['ASSIGNED', 'TRACKINGFAILED'])) {
                return false;
            }
        }

        //判断是否自动发货
        if (!$this->is_auto) {
            return false;
        }

        return true;
    }

    public function placeLogistics($type = null)
    {
        if ($this->canPlaceLogistics($type)) {
            $result = $this->logistics->placeOrder($this->id);
            if ($result['status'] == 'success') {
                if ($type == 'UPDATE') {//如果是更新追踪号，则不修改包裹状态
                    $item = $this->items->first();
                    if (in_array($this->channel->name, ['Wish', 'Ebay', 'Aliexpress']) && $this->is_upload) {
                        $this->is_mark = 0;
                        $this->save();
                    }
                    if($this->status == 'TRACKINGFAILED') {
                        if (empty($item->warehouse_position_id)) {
                            $this->update([
                                'status' => 'NEED',
                                'tracking_no' => $result['tracking_no'],
                                'logistics_order_number' => $result['logistics_order_number'],
                                'logistics_order_at' => date('Y-m-d H:i:s'),
                            ]);
                        } else {
                            $this->update([
                                'status' => 'PROCESSING',
                                'tracking_no' => $result['tracking_no'],
                                'logistics_order_number' => $result['logistics_order_number'],
                                'logistics_order_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    } else {
                        $this->update([
                            'tracking_no' => $result['tracking_no'],
                            'logistics_order_number' => $result['logistics_order_number'],
                            'logistics_order_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                } else {
                    $item = $this->items->first();
                    if (in_array($this->channel->name, ['Wish', 'Ebay', 'Aliexpress']) && $this->is_upload) {
                        $this->is_mark = 0;
                        $this->save();
                    }
                    if (empty($item->warehouse_position_id)) {
                        $this->update([
                            'status' => 'NEED',
                            'tracking_no' => $result['tracking_no'],
                            'logistics_order_number' => $result['logistics_order_number'],
                            'logistics_order_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $this->update([
                            'status' => 'PROCESSING',
                            'tracking_no' => $result['tracking_no'],
                            'logistics_order_number' => $result['logistics_order_number'],
                            'logistics_order_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
            if ($result['status'] == 'again') {
                $this->update([
                    'tracking_no' => $result['tracking_no'],
                    'logistics_order_number' => $result['logistics_order_number'],
                    'logistics_order_at' => date('Y-m-d H:i:s'),
                ]);
            }
            if ($result['status'] == 'error' or $result['status'] == '') {
                $this->update([
                    'status' => 'TRACKINGFAILED',
                ]);
            }
            return $result;
        }
        return ['status' => false, 'tracking_no' => 'Fail to place logistics order', 'logistics_order_number' => ''];
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针
     *
     */
    public function excelProcess($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path . 'excelProcess.xls');
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach ($arr as $key => $content) {
            $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
            $content['logistics_id'] = iconv('gb2312', 'utf-8', trim($content['logistics_id']));
            $content['tracking_no'] = iconv('gb2312', 'utf-8', trim($content['tracking_no']));
            if (!LogisticsModel::where(['name' => $content['logistics_id']])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_logistics = LogisticsModel::where(['name' => $content['logistics_id']])->first();
            $tmp_package = $this->where('id', $content['package_id'])->first();
            if (!$tmp_package || $tmp_package->is_auto || $tmp_package->status != 'PROCESSING') {
                $error[] = $key;
                continue;
            }
            $this->find($content['package_id'])->update([
                'logistics_id' => $tmp_logistics->id,
                'tracking_no' => $content['tracking_no'],
                'status' => 'SHIPPED',
                'shipped_at' => date('Y - m - d G:i:s', time()),
            ]);
            foreach ($this->find($content['package_id'])->items as $packageitem) {
                $packageitem->orderItem->update(['status' => 'SHIPPED']);
            }
        }

        return $error;
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针
     *
     */
    public function excelProcessFee($file, $type)
    {
        $path = config('setting.excelPath');
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcessFee($path . 'excelProcess.xls', $type);
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcessFee($path, $type)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach ($arr as $key => $content) {
            switch ($type) {
                case '1':
                case '2':
                    $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
                    $content['cost'] = iconv('gb2312', 'utf-8', trim($content['cost']));
                    $tmp_package = $this->where('id', $content['package_id'])->first();
                    if (!$tmp_package || $tmp_package->status != 'SHIPPED') {
                        $error[] = $key;
                        continue;
                    }
                    $model = $this->find($content['package_id']);
                    if ($type == 1) {
                        $model->update(['cost' => $content['cost']]);
                        $model->eventLog('系统', '回传物流费' . $content['cost'], json_encode($model));
                    } else {
                        $model->update(['cost1' => $content['cost']]);
                        $model->eventLog('系统', '回传物流费' . $content['cost'], json_encode($model));
                    }
                    break;
                case '3':
                    $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
                    $content['tracking_no'] = iconv('gb2312', 'utf-8', trim($content['tracking_no']));
                    $tmp_package = $this->where('id', $content['package_id'])->first();
                    if (!$tmp_package) {
                        $error[] = $key;
                        continue;
                    }
                    $model = $this->find($content['package_id']);
                    if ($model->is_oversea) {
                        $model->update(['tracking_no' => $content['tracking_no'], 'status' => 'SHIPPED']);
                        $model->order->update(['status' => 'SHIPPED']);
                        foreach ($model->items as $packageItem) {
                            $packageItem->item->holdout($packageItem->warehouse_position_id, $packageItem->quantity,
                                'PACKAGE', $model->id);
                        }
                    } else {
                        $model->update(['tracking_no' => $content['tracking_no']]);
                    }
                    $model->eventLog('系统', '回传追踪号' . $content['tracking_no'], json_encode($model));
                    break;
                case '4':
                    $content['package_id'] = iconv('gb2312', 'utf-8', trim($content['package_id']));
                    $content['tracking_no'] = iconv('gb2312', 'utf-8', trim($content['tracking_no']));
                    $content['logistics_id'] = iconv('gb2312', 'utf-8', trim($content['logistics_id']));
                    $tmp_package = $this->where('id', $content['package_id'])->first();
                    if (!$tmp_package) {
                        $error[] = $key;
                        continue;
                    }
                    if (!in_array($tmp_package->status, ['PACKED', 'SHIPPED'])) {
                        $error[] = $key;
                        continue;
                    }
                    $package = $this->find($content['package_id']);
                    if (in_array($package->channel->name, ['Wish', 'Ebay', 'Aliexpress']) && $package->is_upload) {
                        $package->is_mark = 0;
                        $package->save();
                    }
                    $package->update([
                        'tracking_no' => $content['tracking_no'],
                        'logistics_id' => $content['logistics_id']
                    ]);
                    $package->eventLog('系统', '修改物流方式id' . $content['logistics_id'] . '+追踪号' . $content['tracking_no'],
                        json_encode($package));
                    break;
            }
        }

        return $error;
    }

    /**
     * 将arr转换成相应的格式
     *
     * @param $arr type:array
     * @return array
     *
     */
    public function transfer_arr($arr)
    {
        $buf = [];
        foreach ($arr as $key => $value) {
            $tmp = [];
            if ($key != 0) {
                foreach ($value as $k => $v) {
                    $tmp[$arr[0][$k]] = $v;
                }
                $buf[] = $tmp;
            }
        }

        return $buf;
    }

    /**
     * 根据包裹封装数组
     *
     * @param $packages 包裹
     * @return none
     *
     */
    public function exportData($packages)
    {
        $arr = [];
        foreach ($packages as $package) {
            if (!array_key_exists($package->logistic->logistics_supplier_id, $arr)) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            if (!array_key_exists($package->logistic_id, $arr[$package->logistic->logistics_supplier_id])) {
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'] = [$package->id];
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] = 1;
                $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] = $package->weight;
                continue;
            }
            array_push($arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['package_id'],
                $package->id);
            $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['quantity'] += 1;
            $arr[$package->logistic->logistics_supplier_id][$package->logistic_id]['weight'] += $package->weight;
        }
        $this->loadExcel($arr);
    }

    /**
     * 根据封装好的数组，生成excel
     *
     * @param $arr array
     * @return none
     *
     */
    public function loadExcel($arr)
    {
        if (count($arr)) {
            $j = 0;
            $k = 0;
            foreach ($arr as $key1 => $value1) {
                $i = 0;
                $k++;
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2['package_id'] as $key3 => $value3) {
                        $i++;
                        if ($i == 1 && $k != 1) {
                            $rows[] = [
                                '供货商' => '',
                                '物流方式' => '',
                                '发货日期' => '',
                                '运单号' => '',
                                '重量' => '',
                                '总包裹数' => '',
                                '总重量' => '',
                            ];
                            $j++;
                        }
                        $rows[] = [
                            '供货商' => SupplierModel::find($key1)->name,
                            '物流方式' => LogisticsModel::find($key2)->name,
                            '发货日期' => iconv('utf-8', 'gb2312', PackageModel::find($value3)->shipped_at),
                            '运单号' => PackageModel::find($value3)->tracking_no,
                            '重量' => PackageModel::find($value3)->weight,
                        ];
                        if ($i == 1) {
                            $rows[$j] += ['总包裹数' => $value2['quantity']];
                            $rows[$j] += ['总重量' => $value2['weight']];
                        }
                        $j++;
                    }
                }
            }
        } else {
            $rows[] = [
                '供货商' => '',
                '物流方式' => '',
                '发货日期' => '',
                '运单号' => '',
                '重量' => '',
            ];
        }
        $name = '发货复查';
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '发货复查';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function scopeOfTrackingNo($query, $trackingNo)
    {
        return $query->where('tracking_no', $trackingNo);
    }

    public function getStatusTextAttribute()
    {
        return !empty(config('package')[$this->status]) ? config('package')[$this->status] : '';
    }

    public function shipping()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }
}