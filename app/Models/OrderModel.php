<?php
/**
 * 订单模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/18
 * Time: 下午5:57
 */

namespace App\Models;

use Queue;
use App\Jobs\DoPackages;
use App\Jobs\AssignStocks;
use App\Jobs\AssignLogistics;
use App\Jobs\PlaceLogistics;
use Tool;
use Exception;
use Storage;
use App\Models\CurrencyModel;
use App\Base\BaseModel;
use App\Models\ItemModel;
use App\Models\Order\RefundModel;
use App\Models\Channel\ProductModel as ChannelProduct;
use App\Models\Order\BlacklistModel;
use Illuminate\Support\Facades\DB;
use App\Models\Oversea\ChannelSaleModel;
use App\Models\WarehouseModel;
use App\Models\Oversea\ItemCostModel;
use Session;

class OrderModel extends BaseModel
{
    public $table = 'orders';

    public $guarded = ['items', 'remark'];

    public $fillable = [
        'id',
        'channel_id',
        'channel_account_id',
        'ordernum',
        'channel_ordernum',
        'channel_listnum',
        'by_id',
        'email',
        'status',
        'is_review',
        'active',
        'order_is_alert',
        'amount',
        'gross_margin',
        'profit',
        'profit_rate',
        'channel_fee',
        'amount_product',
        'amount_shipping',
        'amount_coupon',
        'transaction_number',
        'customer_service',
        'operator',
        'payment',
        'currency',
        'rate',
        'address_confirm',
        'shipping',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_address1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'billing_firstname',
        'billing_lastname',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zipcode',
        'billing_phone',
        'customer_remark',
        'withdraw_reason',
        'withdraw',
        'cele_admin',
        'priority',
        'package_times',
        'split_times',
        'split_quantity',
        'fulfill_by',
        'blacklist',
        'platform',
        'aliexpress_loginId',
        'payment_date',
        'create_time',
        'is_chinese',
        'orders_expired_time',
        'created_at',
        'is_oversea',
        'operator_id',
        'fee_amt',
        'is_send_ebay_msg',
    ];

    private $canPackageStatus = ['PREPARED'];
    private $canCancelStatus = ['SHIPPED', 'COMPLETE'];

    public $searchFields = ['id' => '内单号'];

    //退款rules
    public $rules = [
        'create' => [
            'refund_amount' => 'required',
            'price' => 'required',
            'refund_currency' => 'required',
            'refund' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'image' => 'required',
        ],
    ];

    //添加rules
    public function rule($request)
    {
        $arr = [
            'channel_id' => 'required',
            'channel_account_id' => 'required',
            'ordernum' => 'required',
            'channel_ordernum' => 'required',
            'status' => 'required',
            'active' => 'required',
            'customer_service' => 'required',
            'operator' => 'required',
            'address_confirm' => 'required',
            'create_time' => 'required',
            'currency' => 'required',
            'transaction_number' => 'required',
            'amount' => 'required',
            'amount_product' => 'required',
            'amount_coupon' => 'required',
            'shipping_firstname' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'shipping_state' => 'required',
            'shipping_country' => 'required',
            'shipping_zipcode' => 'required',
            'shipping_phone' => 'required',
            'payment' => 'required',
            'payment_date' => 'required',
        ];

        $buf = $request->all();
        $buf = $buf['arr'];
        foreach ($buf as $key => $val) {
            if ($key == 'sku') {
                foreach ($val as $k => $v) {
                    $arr['arr.sku.' . $k] = 'required';
                }
            }
            if ($key == 'quantity') {
                foreach ($val as $k => $v) {
                    $arr['arr.quantity.' . $k] = 'required';
                }
            }
            if ($key == 'price') {
                foreach ($val as $k => $v) {
                    $arr['arr.price.' . $k] = 'required';
                }
            }
            if ($key == 'status') {
                foreach ($val as $k => $v) {
                    $arr['arr.status.' . $k] = 'required';
                }
            }
            if ($key == 'ship_status') {
                foreach ($val as $k => $v) {
                    $arr['arr.ship_status.' . $k] = 'required';
                }
            }
            if ($key == 'is_gift') {
                foreach ($val as $k => $v) {
                    $arr['arr.is_gift.' . $k] = 'required';
                }
            }
        }

        return $arr;
    }

    public function getOverseaCostAttribute()
    {
        $num = 0;
        foreach($this->packages as $package) {
            foreach($package->items as $packageItem) {
                $buf = ItemCostModel::where(['item_id' => $packageItem->item_id, 'code' => $package->warehouse->code])->first();
                if($buf) {
                    $num += $buf->cost * $packageItem->quantity;
                }
            }
        }

        return $num;
    }

    //更新rules
    public function updateRule($request)
    {
        $arr = [
            'shipping_firstname' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'shipping_state' => 'required',
            'shipping_country' => 'required',
            'shipping_zipcode' => 'required',
            'shipping_phone' => 'required',
        ];

        $buf = $request->all();
        $buf = $buf['arr'];
        foreach ($buf as $key => $val) {
            if ($key == 'sku') {
                foreach ($val as $k => $v) {
                    $arr['arr.sku.' . $k] = 'required';
                }
            }
            if ($key == 'quantity') {
                foreach ($val as $k => $v) {
                    $arr['arr.quantity.' . $k] = 'required';
                }
            }
            if ($key == 'price') {
                foreach ($val as $k => $v) {
                    $arr['arr.price.' . $k] = 'required';
                }
            }
            if ($key == 'status') {
                foreach ($val as $k => $v) {
                    $arr['arr.status.' . $k] = 'required';
                }
            }
            if ($key == 'ship_status') {
                foreach ($val as $k => $v) {
                    $arr['arr.ship_status.' . $k] = 'required';
                }
            }
            if ($key == 'is_gift') {
                foreach ($val as $k => $v) {
                    $arr['arr.is_gift.' . $k] = 'required';
                }
            }
        }

        return $arr;
    }

    //未付款订单
    public function unpaidOrder()
    {
        return $this->belongsTo('App\Models\Order\UnpaidOrderModel', 'by_id', 'ordernum');
    }

    //订单产品
    public function items()
    {
        return $this->hasMany('App\Models\Order\ItemModel', 'order_id', 'id');
    }

    //订单包裹
    public function packages()
    {
        return $this->hasMany('App\Models\PackageModel', 'order_id', 'id');
    }

    //订单渠道
    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    //订单渠道账号
    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id', 'id');
    }

    //订单国家
    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
    }

    //订单币种
    public function currency()
    {
        return $this->belongsTo('App\Models\CurrencyModel', 'currency', 'code');
    }

    //运营人员
    public function userAffairer()
    {
        return $this->belongsTo('App\Models\UserModel', 'affairer', 'id');
    }

    //客服人员
    public function userService()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service', 'id');
    }

    //运营人员
    public function userOperator()
    {
        return $this->belongsTo('App\Models\UserModel', 'operator', 'id');
    }

    //订单备注
    public function remarks()
    {
        return $this->hasMany('App\Models\Order\RemarkModel', 'order_id', 'id');
    }

    //退款记录
    public function refunds()
    {
        return $this->hasMany('App\Models\Order\RefundModel', 'order_id', 'id');
    }

    //订单需求
    public function requires()
    {
        return $this->hasMany('App\Models\RequireModel', 'order_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message\MessageModel', 'channel_order_number', 'channel_ordernum');
    }

    //ebay消息记录
    public function ebayMessageList()
    {
        return $this->hasMany('App\Models\Message\SendEbayMessageListModel', 'order_id', 'id');
    }

    //ebay手续费
    public function orderPaypal()
    {
        return $this->belongsTo('App\Models\Order\OrderPaypalDetailModel', 'order_id', 'id');
    }

    //订单重量
    public function getOrderWeightAttribute()
    {
        $items = $this->items;
        $weight = 0;
        foreach ($items as $item) {
            $weight += $item->item->weight * $item->quantity;
        }

        return $weight;
    }

    public function getOverseaDeclaredAttribute()
    {
        $num = 0;
        foreach($this->items as $single) {
            $num += $single->declared_value * $single->quantity;
        }

        return $num;
    }

    //多重查询
    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => [
                'channel_ordernum',
                'email',
                'by_id',
                'shipping_firstname',
                'currency',
                'transaction_number',
            ],
            'filterSelects' => [
                'is_oversea' => config('order.whether'),
                'status' => config('order.status'),
                'active' => config('order.active'),
                'is_chinese' => config('order.is_chinese')
            ],
            'sectionSelect' => [
                'price' => ['amount', 'profit', 'profit_rate'],
                'time' => ['created_at'],
            ],
            'relatedSearchFields' => [
                'country' => ['code'],
                'items' => ['sku'],
                'channelAccount' => ['alias'],
                'userOperator' => ['name'],
                'packages' => ['tracking_no'],
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => ChannelModel::get(['name'])->pluck('name', 'name')],
                'items' => ['item_status' => config('item.status')],
                'remarks' => ['type' => config('order.review_type')],
                'packages' => ['is_mark' => config('order.is_mark'), 'status' => config('package')],
            ],
            'doubleRelatedSearchFields' => [],
            'doubleRelatedSelectedFields' => [
                'packages' => ['logistics' => ['code' => LogisticsModel::where('is_enable', '1')->get(['code'])->pluck('code', 'code')]],
            ],
        ];
    }

    public function getRelationArrAttribute()
    {
        return [
            'country' => ['countries', 'code' , 'shipping_country'],
            'items' => ['order_items', 'order_id', 'id'],
            'channelAccount' => ['channel_accounts', 'id' , 'channel_account_id'],
            'userOperator' => ['users', 'id' , 'operator'],
            'packages' => ['packages', 'order_id', 'id'],
            'channel' => ['channels', 'id', 'channel_id'],
            'remarks' => ['order_remarks', 'order_id', 'id'],
        ];
    }

    public function clearSession()
    {
        $arr = $this->relation_arr;
        foreach($arr as $key => $single) {
            Session::forget($this->table.'.'.$key);
        }
    }

    //状态名称
    public function getStatusNameAttribute()
    {
        $config = config('order.status');
        return isset($config[$this->status]) ? $config[$this->status] : '';
    }

    //状态颜色
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'REVIEW':
                $color = 'danger';
                break;
            case 'CANCEL':
                $color = 'active';
                break;
            case 'NEED':
                $color = 'warning';
                break;
            case 'COMPLETE':
                $color = 'success';
                break;
            case 'SHIPPED':
                $color = 'success';
                break;
            case 'UNPAID':
                $color = '';
                break;
            default:
                $color = 'info';
                break;
        }
        return $color;
    }

    //激活名称
    public function getActiveNameAttribute()
    {
        $arr = config('order.active');
        return $arr[$this->active];
    }

    //是否部分发货
    public function getIsPartialNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_partial];
    }

    //是否手工发货
    public function getByHandNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->by_hand];
    }

    public function getIsAffairNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_affair];
    }

    //地址是否验证
    public function getAddressConfirmNameAttribute()
    {
        $arr = config('order.address');
        return $arr[$this->address_confirm];
    }

    //撤销原因
    public function getWithdrawNameAttribute()
    {
        $arr = config('order.withdraw');
        return $arr[$this->withdraw];
    }

    //物流方式
    public function getLogisticsAttribute()
    {
        $logistics = '';
        foreach ($this->packages as $package) {
            $logisticsName = $package->logistics ? $package->logistics->code : '';
            $logistics .= $logisticsName . ' ';
        }

        return $logistics;
    }

    //追踪号
    public function getCodeAttribute()
    {
        $code = '';
        foreach ($this->packages as $package) {
            $trackingNo = $package->tracking_no;
            $code .= $trackingNo . ' ';
        }

        return $code;
    }

    //订单成本
    public function getAllItemCostAttribute()
    {
        $total = 0;
        foreach ($this->items()->with('item')->get() as $item) {
            if ($item->item) {
                if ($this->items->count() > 1) {
                    if ($item->item->status != 'cleaning') {
                        $total += $item->item->cost * $item->quantity;
                    }
                } else {
                    $total += $item->item->cost * $item->quantity;
                }
            }
        }
        return $total;
    }

    public function getPartialOverAttribute()
    {
        foreach ($this->items as $item) {
            if ($item->split_quantity != $item->quantity) {
                return false;
            }
        }
        return true;
    }

    //订单产品数量
    public function getOrderQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    //物流成本
    public function getLogisticsFeeAttribute()
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total += $package->calculateLogisticsFee();
        }
        return $total;
    }

    public function packagesToQueue()
    {
        if (!$this->packages->count()) {
            $job = new DoPackages($this);
            Queue::pushOn('doPackages', $job);
        }
        foreach ($this->packages as $package) {
            switch ($package->status) {
                case 'NEW':
                    $package->update(['queue_name' => 'assignStocks']);
                    $job = new AssignStocks($package);
                    Queue::pushOn('assignStocks', $job);
                    break;
                case 'WAITASSIGN':
                    $package->update(['queue_name' => 'assignLogistics']);
                    $job = new AssignLogistics($package);
                    Queue::pushOn('assignLogistics', $job);
                    break;
                case 'ASSIGNED':
                    $package->update(['queue_name' => 'placeLogistics']);
                    $job = new PlaceLogistics($package);
                    Queue::pushOn('placeLogistics', $job);
                    break;
                case 'NEED':
                    $package->update(['queue_name' => 'assignStocks']);
                    $job = new AssignStocks($package);
                    Queue::pushOn('assignStocks', $job);
                    break;
            }
        }
    }

    //订单可用状态
    public function getActiveItemsAttribute()
    {
        return $this->items->where('is_active', '1');
    }

    //订单状态
    public function getStatusTextAttribute()
    {
        return config('order.status.' . $this->status);
    }

    //售后状态
    public function getActiveTextAttribute()
    {
        return config('order.active.' . $this->active);
    }

    //ebay订单历史
    public function getSendEbayMessageHistoryAttribute()
    {
        if (!$this->ebayMessageList->isEmpty()) {
            return $this->ebayMessageList;
        } else {
            return false;
        }
    }

    //ebay订单差评
    public function getEbayFeedbackCommentAttribute(){
        $items = $this->items;
        $comment = '';
        if(!$this->items->isEmpty()){
            foreach($items as $item){
                if(! empty($item->ebayFeedback)){
                    if($item->ebayFeedback->comment_type == 'Negative'){
                        $comment = '差评';
                    }
                    break;
                }
            }

        }
        return $comment;
    }

    //订单备注
    public function getOrderReamrksAttribute()
    {
        $remarks = '';
        if (!$this->remarks->isEmpty()) {
            foreach ($this->remarks as $remark) {
                $remarks .= empty($remarks) ? $remark->remark : $remark->remark . ';';

            }
        }
        return $remarks;
    }

    /**
     * 根据单号取订单记录
     * @param $query
     * @param $ordernum
     * @return mixed
     */
    public function scopeOfOrdernum($query, $ordernum)
    {
        return $query->where('ordernum', $ordernum);
    }

    //退款
    public function refundCreate($data, $file = null)
    {
        $data['process_status'] = 'PENDING';
        $path = 'uploads/refund' . '/' . $data['order_id'] . '/';
        if ($file != '' && $file->getClientOriginalName()) {
            $data['image'] = $path . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['image'], file_get_contents($file->getRealPath()));
        } else {
            $data['image'] = '';
        }
        if ($data['type'] == 'FULL') {
/*            $total = 0;
            foreach ($this->items as $orderItem) {
                $orderItem->update(['is_refund' => 1]);
                $total = $orderItem['price'] * $orderItem['quantity'] + $total;
            }*/
            $data['refund_amount'] = $this->amount;
            $data['price'] = $this->amount;
        }
        if ($data['type'] == 'PARTIAL') {
            foreach ($data['tribute_id'] as $id) {
                $orderItem = $this->items->find($id);
                $orderItem->update(['is_refund' => 1]);
            }
        }
        $data['customer_id'] = request()->user()->id;
        $refund = new RefundModel();
        $refund_new = $refund->create($data);
        if ($data['type'] == 'FULL') {
            foreach ($this->items as $orderItem) {
                $orderItem->update(['refund_id' => $refund_new->id]);
            }
        } else {
            foreach ($data['tribute_id'] as $partid) {
                $orderItem = $this->items->find($partid);
                $orderItem->update(['refund_id' => $refund_new->id]);
            }
        }

        return 1;
    }

    //创建订单
    public function createOrder($data)
    {
        DB::beginTransaction();
        $data['ordernum'] = str_replace('.', '', microtime(true));
        $currency = CurrencyModel::where('code', $data['currency'])->first();
        if ($currency) {
            $data['rate'] = $currency->rate;
        }
        if ($data['shipping_country'] == 'PR') {
            $data['shipping_country'] = 'US';
        }
        if ($data['shipping_country'] == 'UK') {
            $data['shipping_country'] = 'GB';
        }
        if ($data['shipping_country'] == 'FR' && substr($data['shipping_zipcode'], 0, 3) == '974') {
            $data['shipping_country'] = 'RE';
        }
        //判断是否有订单产品
        if (!isset($data['items']) or empty($data['items'])) {
            DB::rollBack();
            return false;
        }
        $order = $this->create($data);
        //判断订单头是否创建成功
        if (!$order) {
            DB::rollBack();
            return false;
        }
        //插入订单产品
        foreach ($data['items'] as $orderItem) {
            if ($orderItem['sku']) {
                $item = ItemModel::where('sku', $orderItem['sku'])->first();
                if ($item) {
                    $orderItem['item_id'] = $item->id;
                    $orderItem['item_status'] = $item->status;
                } else {
                    $stock = StockModel::where('oversea_sku', $orderItem['sku'])->first();
                    if ($stock) {
                        $orderItem['item_id'] = $stock->item_id;
                        $orderItem['item_status'] = $stock->item->status;
                        $orderItem['is_oversea'] = 1;
                        $orderItem['code'] = $stock->warehouse->code;
                    }
                }
            }
            if ($orderItem['channel_sku']) {
                $channelSku = explode('*', $orderItem['channel_sku']);
                // $user = UserModel::where('code', $channelSku[0])->first();
                $orderItem['operator_id'] = $channelSku[0];
            }
            if (!isset($orderItem['item_id'])) {
                $orderItem['item_id'] = 0;
                if ($order->status == 'PAID') {
                    $order->update(['status' => 'REVIEW']);
                }
                $order->remark($orderItem['channel_sku'] . '找不到对应产品.', 'ITEM');
            }
            $orderItem['channel_id'] = $order->channel_id;
            $order->items()->create($orderItem);
        }
        foreach ($order->items as $key => $single) {
            if (!$key) {
                if ($single->is_oversea) {
                    $order->update(['is_oversea' => '1']);
                }
            }
            if (!WarehouseModel::where('code', $single->code)->first()) {
                $order->update(['status' => 'REVIEW']);
                break;
            }
        }
        if ($order->status == 'PAID') {
            $order->update(['status' => 'PREPARED']);
        }
        DB::commit();
        return $order;
    }

    //更新订单
    public function updateOrder($data)
    {
        $this->update($data);
        foreach ($this->items as $item) {
            if ($item->item_id == 0) {
                $this->update(['status' => 'REVIEW']);
                $this->remark($item->channel_sku . '找不到对应产品.', 'ITEM');
            }
        }
        if ($this->status == 'PAID') {
            $this->update(['status' => 'PREPARED']);
        }
        return $this;
    }

    //添加订单备注
    public function remark($remark, $type = 'DEFAULT', $user_id = 0)
    {
        return $this->remarks()->create(['type' => $type, 'remark' => $remark, 'user_id' => $user_id]);
    }

    //判断是否可打包
    public function canPackage()
    {
        //判断订单ACTIVE状态
        if ($this->active != 'NORMAL') {
            return false;
        }
        //判断订单状态
        if (!in_array($this->status, $this->canPackageStatus)) {
            return false;
        }
        //订单是否包含正常产品
        if ($this->active_items->count() < 1) {
            $this->status = 'REVIEW';
            $this->save();
            return false;
        }

        return true;
    }

    //创建包裹
    public function createPackage()
    {
        if ($this->canPackage()) {
            return $this->createVirtualPackage();
        }
        return false;
    }

    //创建虚拟包裹
    public function createVirtualPackage()
    {
        $package = [];
        //channel
        $package['channel_id'] = $this->channel_id ? $this->channel_id : '';
        $package['channel_account_id'] = $this->channel_account_id ? $this->channel_account_id : '';
        //type
        $package['type'] = $this->items->count() > 1 ? 'MULTI' : ($this->items->first()['quantity'] > 1 ? 'SINGLEMULTI' : 'SINGLE');
        $package['weight'] = $this->order_weight;
        $package['email'] = $this->email ? $this->email : '';
        $package['shipping_firstname'] = $this->shipping_firstname ? $this->shipping_firstname : '';
        $package['shipping_lastname'] = $this->shipping_lastname ? $this->shipping_lastname : '';
        $package['shipping_address'] = $this->shipping_address ? $this->shipping_address : '';
        $package['shipping_address1'] = $this->shipping_address1 ? $this->shipping_address1 : '';
        $package['shipping_city'] = $this->shipping_city ? $this->shipping_city : '';
        $package['shipping_state'] = $this->shipping_state ? $this->shipping_state : '';
        $package['shipping_country'] = $this->shipping_country ? $this->shipping_country : '';
        $package['shipping_zipcode'] = $this->shipping_zipcode ? $this->shipping_zipcode : '';
        $package['shipping_phone'] = $this->shipping_phone ? $this->shipping_phone : '';
        $package['status'] = 'NEW';
        $package['is_oversea'] = $this->is_oversea;
        $package['queue_name'] = 'assignStocks';
        $package = $this->packages()->create($package);
        if ($package) {
            foreach ($this->items->toArray() as $packageItem) {
                if (!$packageItem['remark']) {
                    $packageItem['remark'] = 'REMARK';
                }
                $packageItem['order_item_id'] = $packageItem['id'];
                $item = ItemModel::find($packageItem['item_id']);
                if($item) {
                    $packageItem['sku'] = $item->sku;
                }
                if ($packageItem['is_active']) {
                    $newPackageItem = $package->items()->create($packageItem);
                }
            }
        }

        return $package;
    }

    //计算利润率
    public function calculateProfitProcess()
    {
        $currencyArr = CurrencyModel::whereIn('code', [$this->currency, 'RMB'])->get()->pluck('rate', 'code');
        $rate = $currencyArr[$this->currency];
        $rmbRate = $currencyArr['RMB'];
        $orderAmount = $this->amount * $rate;
        $itemCost = $this->all_item_cost * $rmbRate;
        $logisticsCost = $this->logistics_fee * $rmbRate;
        $orderChannelFee = $this->calculateOrderChannelFee();
        $orderProfit = round($orderAmount - $itemCost - $logisticsCost - $orderChannelFee, 4);
        $orderProfitRate = $orderProfit / $orderAmount;
        $this->update(['profit' => $orderProfit, 'profit_rate' => $orderProfitRate]);
        return $orderProfitRate;
    }

    public function overseaCalculateProfit()
    {
        $currencyArr = CurrencyModel::whereIn('code', [$this->currency, 'RMB'])->get()->pluck('rate', 'code');
        $rate = $currencyArr[$this->currency];
        $rmbRate = $currencyArr['RMB'];
        $orderAmount = $this->amount * $rate;
        $itemCost = $this->oversea_cost * $rmbRate;
        $logisticsCost = $this->logistics_fee * $rmbRate;
        $orderProfit = round($orderAmount - $itemCost - $logisticsCost - $this->oversea_declared * 0.25, 4);
        $orderProfitRate = $orderProfit / $orderAmount;
        $this->update(['profit' => $orderProfit, 'profit_rate' => $orderProfitRate]);
        return $orderProfitRate;
    }

    //ebay成交费
    public function getDealFeeAttribute()
    {
        $dealFee = 0;
        if ($this->items) {
            foreach ($this->items as $item) {
                $rate = CurrencyModel::where('code', $item->currency)->first();
                if ($rate) {
                    $dealFee += $item->final_value_fee * $rate->rate;
                }
            }
        }

        return $dealFee;
    }

    //计算平台费
    public function calculateOrderChannelFee()
    {
        $sum = 0;
        switch ($this->channel->driver) {
            case 'wish':
                $sum = $this->amount * 0.15;
                $sum = $sum * $this->rate;
                break;
            case 'ebay':
                $counterFee = $this->fee_amt * $this->rate;
                $dealFee = 0;
                if ($this->items) {
                    foreach ($this->items as $item) {
                        $rate = CurrencyModel::where('code', $item->currency)->first();
                        if ($rate) {
                            $dealFee += $item->final_value_fee * $rate->rate;
                        }
                    }
                }
                $sum = $counterFee + $dealFee;
                break;
            default:
                foreach ($this->items()->with('item.catalog.channels')->get() as $item) {
                    if ($item->item and $item->item->catalog) {
                        $channelRate = $item->item->catalog->channels->find($this->channelAccount->catalog_rates_channel_id);
                        if ($channelRate) {
                            $sum += ($item->price * $item->quantity) * ($channelRate->pivot->rate / 100) + $channelRate->pivot->flat_rate;
                            $sum = $sum * $this->rate;
                        }
                    }
                }
                break;
        }

        $this->update(['channel_fee' => $sum]);
        return $sum;
    }

    //黑名单验证
    public function checkBlack()
    {
        $channel = ChannelModel::find($this->channel_id);
        $count = 0;
        $blackList = BlacklistModel::whereIN('type', ['CONFIRMED', 'SUSPECTED']);
        if ($channel) {
            switch ($channel->driver) {
                case 'wish':
                    $name = trim($this->shipping_lastname . ' ' . $this->shipping_firstname);
                    $count = $blackList->where('zipcode', $this->shipping_zipcode)
                        ->where('name', $name)->count();
                    break;
                case 'aliexpress':
                    if ($this->by_id) {
                        $count = $blackList->where('by_id', $this->by_id)->count();
                    }
                    break;
                default:
                    if ($this->email) {
                        $count = $blackList->where('email', $this->email)->count();;
                    }
                    break;
            }
            if ($count > 0) {
                $this->update(['blacklist' => '0']);
                return true;
            }
        }
        return false;
    }

    /**
     * 订单撤销
     *
     * @return boolean
     */
    public function cancelOrder($type, $reason = '')
    {
        if ($this->status != 'CANCEL') {
            if (!in_array($this->status, $this->canCancelStatus)) {
                //取消包裹
                foreach ($this->packages as $package) {
                    $package->cancelPackage();
                }
                //撤销订单
                $this->update([
                    'status' => 'CANCEL',
                    'withdraw' => $type,
                    'withdraw_reason' => $reason
                ]);
            } else {
                return false;
            }
        }
        return true;
    }
}