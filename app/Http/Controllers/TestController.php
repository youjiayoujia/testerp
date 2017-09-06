<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */
namespace App\Http\Controllers;
header('Content-type: text/html; charset=UTF-8');
use Session;
use App\Models\ChannelModel;
use App\Models\Message\MessageModel;
use Test;
use App\Models\Purchase\PurchaseOrderModel;
use Tool;
use Channel;
use Logistics;
use App\Models\Channel\AccountModel;
use App\Models\OrderModel;
use App\Modules\Paypal\PaypalApi;
use App\Models\Order\OrderPaypalDetailModel;
use App\Models\Publish\Ebay\EbayFeedBackModel;
use App\Models\Publish\Ebay\EbaySpecificsModel;
use App\Models\PackageModel;
use App\Models\ItemModel;
use App\Models\Oversea\Box\BoxModel;
use App\Models\LogisticsModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use App\Models\Publish\Joom\JoomPublishProductModel;
use App\Models\Publish\Joom\JoomPublishProductDetailModel;
use App\Models\Publish\Joom\JoomShippingModel;
use App\Modules\Channel\ChannelModule;
use App\Jobs\Job;
use App\Jobs\DoPackage;
use App\Jobs\SendMessages;
use App\Models\PickListModel;
use App\Models\WarehouseModel;
use DNS1D;
use App\Models\Channel\ChannelsModel;
use App\Models\Sellmore\ShipmentModel;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\CatalogModel;
use DB;
use Excel;
use App\Models\Message\ReplyModel;
use App\Jobs\Inorders;
use App\Modules\Channel\Adapter\AmazonAdapter;
use App\Models\Oversea\StockModel as fbaStock;
use App\Models\Order\ItemModel as orderItemss;
use App\Models\Message\Issues\AliexpressIssueListModel;
use App\Models\Message\Issues\AliexpressIssuesDetailModel;
use App\Models\Order\RefundModel;
use App\Models\Product\SupplierModel;
use Illuminate\Support\Facades\Storage;
use BarcodeGen;
use App\Models\ProductModel;
use Cache;
use Queue;
use App\Models\StockModel;
use App\Jobs\AssignStocks;
use App\Jobs\DoPackages;
use App\Jobs\PlaceLogistics;


use Crypt;
use factory;
use App\Models\Item\ItemPrepareSupplierModel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\MatchPaypal as MatchPaypalJob ;


class TestController extends Controller
{
    private $itemModel;
    private $orderModel;
    public function __construct(OrderModel $orderModel, ItemModel $itemModel)
    {
        $this->itemModel = $itemModel;
        $this->orderModel = $orderModel;
    }
    //测试包裹站台是否满足 物流下单状态；
    //进行物流下单


    public function tryGetLogtisticsNo($id){

        $package = PackageModel::where('id', $id)->first();
        if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED'])) {
            $result = $package->placeLogistics('UPDATE');
        } else {
            $result = $package->placeLogistics();
        }
        dd($result);
    }
    public function test_3(){
        $id = request()->get('id');
        $package = PackageModel::where('id', $id)->first();
        if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED'])) {
            $result = $package->placeLogistics('UPDATE');
        } else {
            $result = $package->placeLogistics();
        }
        dd($result);
    }

    // public function test2()
    // {
    //     $order['channel_id'] = 2;
    //     $order['channel_account_id'] = 1; 
    //     $order['channel_ordernum'] = 12345632;
    //     $order['status'] = 'PAID';
    //     $order['currency'] = 'USD';
    //     $order['shipping_country'] = 'US';
    //     $order['items'][0]['quantity'] = 2;
    //     $order['items'][0]['sku'] = 'SS1197W';
    //     $order['items'][0]['channel_sku'] = '353*SS1197W';
    //     $job = new InOrders($order);
    //     $job = $job->onQueue('inOrders');
    //     $this->dispatch($job);
    //     var_dump('ok');
    // }

    // public function test2()
    // {
    //     $lo = LogisticsModel::find(10);
    //     var_dump($lo->belongsToWarehouse('3'));
//    $id = request()->get('id');
//    $package = PackageModel::where('id', $id)->first();
//    if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED'])) {
//    $result = $package->placeLogistics('UPDATE');
//    } else {
//        $result = $package->placeLogistics();
//    }
//    dd($result);
    // }

    // public function test2()
    // {
    //     $package = PackageModel::find('42990');
    //     if(in_array($package->status, ['WAITASSIGN', 'ASSIGNFAILED'])) {
    //         $order = $package->order;
    //         $package->assignLogistics();
    //         if (!$order->is_review) { //审核通过的订单无需再审核
    //             //验证黑名单
    //             if ($order->checkBlack()) {
    //                 $order->update(['status' => 'REVIEW']);
    //                 $order->remark('黑名单需审核.', 'BLACK');
    //             }
    //             //特殊需求
    //             if (!empty($order->customer_remark)) {
    //                 $order->update(['status' => 'REVIEW']);
    //                 $order->remark('特殊需求需审核.', 'REQUIRE');
    //             }
    //             //订单留言
    //             if ($order->messages->count() == 1 and $order->messages->first()->replies->count() == 0) {
    //                 $order->update(['status' => 'REVIEW']);
    //                 $order->remark('客户有订单留言.', 'MESSAGE');
    //             }
    //             //包裹重量大于2kg
    //             if ($package->weight >= 2) {
    //                 $order->update(['status' => 'REVIEW']);
    //                 $order->remark('包裹重量大于2kg.', 'WEIGHT');
    //             }
    //             //分渠道审核
    //             $profitRate = $order->calculateProfitProcess();
    //             switch ($order->channel->driver) {
    //                 case 'amazon':
    //                     break;
    //                 case 'aliexpress':
    //                     if ($profitRate <= 0 or $profitRate >= 0.4) {
    //                         $order->update(['status' => 'REVIEW']);
    //                         $order->remark('速卖通订单利润率小于0或大于40%.', 'PROFIT');
    //                     }
    //                     break;
    //                 case 'wish':
    //                     if ($profitRate < 0.08) {
    //                         $order->update(['status' => 'REVIEW']);
    //                         $order->remark('WISH订单利润率小于8%.', 'PROFIT');
    //                     }
    //                     break;
    //                 case 'ebay':
    //                     if ($profitRate <= 0.05) {
    //                         $order->update(['status' => 'REVIEW']);
    //                         $order->remark('EBAY订单利润率小于或等于5%.', 'PROFIT');
    //                     }
    //                     break;
    //                 case 'lazada':
    //                     break;
    //                 case 'cdiscount':
    //                     break;
    //                 case 'joom':
    //                     break;
    //             }
    //         }
    //         if($package->order->status != 'REVIEW') {
    //             if ($package->status == 'ASSIGNED') {
    //                 $package->update(['queue_name' => 'placeLogistics']);
    //                 $job = new PlaceLogistics($package);
    //                 $job = $job->onQueue('placeLogistics');
    //                 $this->dispatch($job);
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = 'Success.';
    //                 $package->eventLog('队列', '已匹配物流，加入下单队列', json_encode($package));
    //             } elseif ($package->status == 'ASSIGNFAILED') {
    //                 $package->update(['queue_name' => '']);
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = '未匹配到物流.';
    //                 $package->eventLog('队列', '匹配失败,未匹配到物流', json_encode($package));
    //             } elseif ($package->status == 'NEED') {
    //                 $package->update(['queue_name' => '']);
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = '已匹配到物流,缺货中，不需要提前标记发货.';
    //                 $package->eventLog('队列', '已匹配到物流,缺货中,不需要提前标记发货.', json_encode($package));
    //             }
    //         } else {
    //             $package->update(['queue_name' => '']);
    //             $this->result['status'] = 'fail';
    //             $this->result['remark'] = '订单需审核.';
    //             $package->eventLog('队列', '订单需审核.', json_encode($package));
    //         }
    //     } else {
    //         $package->update(['queue_name' => '']);
    //     }
    //     var_dump('123');
    // }

    // public function test2()
    // {  
    //     $package = BoxModel::find(25);
    //     $test = $package->expected_fee;
    //     var_dump($test);
    // }

    // public function test2()
    // {
    //     $model = PackageModel::find(10);
    //     $buf = $model->relatedGet($model, 'order', 'id', '1');
    // }

    // public function test2()
    // {
        
    // }

    // public function test2()
    // {
    //     $package = PackageModel::where('id', '40893')->first(['warehouse_id', 'channel_id']);
    //     var_dump($package);exit;
    // }

    // public function test2()
    // {
    //     $package = PackageModel::find('31828');

    //     if($package->order->status != 'REVIEW' && in_array($package->status, ['NEW', 'NEED'])) {
    //         if($package->is_oversea) {
    //             $flag = $package->oversea_createPackageItems();
    //         } else {
    //             $flag = $package->createPackageItems();
    //         }
    //         if ($flag) {
    //             if ($package->status == 'WAITASSIGN') {
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = 'Success to assign stock.';
    //                 $package->eventLog('队列', '已匹配到库存,待分配', json_encode($package));
    //             } elseif ($package->status == 'PROCESSING') { //todo:如果缺货订单匹配到了库存，不是原匹配仓库，需要匹配物流下单
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = 'Success to assign stock.';
    //                 $package->eventLog('队列', '已匹配到库存,待拣货', json_encode($package));
    //             } elseif ($package->status == 'ASSIGNED') {
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = 'Success to assign stock.';
    //                 $package->eventLog('队列', '已匹配到库存,待下单', json_encode($package));
    //             }
    //         } else {
    //             $this->result['status'] = 'success';
    //             $this->result['remark'] = 'have no enough stocks or can\'t assign stocks.';
    //             $package->eventLog('队列', 'have no enough stocks or can\'t assign stocks.',
    //                 json_encode($package));
    //         }
    //     } else {
    //         $package->update(['queue_name' => '']);
    //     }

    //     var_dump('123');
    // }

    public function test2()
    {
        $packages = PackageModel::all()->sortByDesc('id')->take('2');
        var_dump($packages->toarray());exit;
    }

    // public function test2()
    // {

    // }

    // public function test2()
    // {
    //     $id = request()->get('id');
    //     $package = PackageModel::where('id', $id)->first();
    //     if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED'])) {
    //         $result = $package->placeLogistics('UPDATE');
    //     } else {
    //         $result = $package->placeLogistics();
    //     }
    //     dd($result);
    // }

    /*public function test2()
    {
        $id = request()->get('id');
        $package = PackageModel::where('id', $id)->first();
        if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED'])) {
            $result = $package->placeLogistics('UPDATE');
        } else {
            $result = $package->placeLogistics();
        }
        dd($result);
    }*/

    // public function test2()
    // {
    //     $orders_arr = OrderModel::all()->chunk(200);
    //     foreach($orders_arr as $orders) {
    //         var_dump($orders->toarray());exit;
    //     }
    // }

    // public function test2()
    // {
    //     $order = OrderModel::find(347935);

    //     if ($order && $order->status != 'REVIEW') {
    //         if ($order->status == 'PREPARED') {
    //             if ($order->channel->driver == 'ebay' and $order->order_is_alert != 2) {
    //                 if ($order->order_is_alert == 1) {
    //                     $order->update(['status' => 'REVIEW']);
    //                     $order->remark('EBAY订单匹配PAYPAL失败.', 'PAYPAL');
    //                 }
    //                 $order->eventLog('队列', 'EBAY订单需要匹配PAYPAL.');
    //                 $this->relation_id = $order->id;
    //                 $this->result['status'] = 'success';
    //                 $this->result['remark'] = 'EBAY订单需要匹配PAYPAL.';
    //             } else {
    //                 $package = $order->createPackage();
    //                 if ($package) {
    //                     $package->update(['queue_name' => 'assignStocks']);
    //                     $job = new AssignStocks($package);
    //                     $job->onQueue('assignStocks');
    //                     $this->dispatch($job);
    //                     $order->update(['status' => 'PACKED']);
    //                     $this->relation_id = $order->id;
    //                     $this->result['status'] = 'success';
    //                     $this->result['remark'] = 'Success.';
    //                     $package->eventLog('队列', '已生成空包裹，加入匹配库存队列', json_encode($package));
    //                 } else {
    //                     $this->relation_id = 0;
    //                     $this->result['status'] = 'fail';
    //                     $this->result['remark'] = 'Fail to create virtual package.';
    //                 }
    //             }
    //         } else {
    //             $this->relation_id = 0;
    //             $this->result['status'] = 'success';
    //             $this->result['remark'] = 'Order status is not PREPARED. Can not create package';
    //             $order->eventLog('队列', 'Order status is not PREPARED. Can not create package',
    //                 json_encode($order));
    //         }
    //     }

    //     var_dump('123');
    // }

    public function test1()
    {
        $orders = OrderModel::find(3977);
        $orders->calculateProfitProcess();
        return 1;
    }

    public function test3()
    {
        $orders = OrderModel::whereBetween('id', [3081, 3189])->get();
        foreach ($orders as $order) {
            $order->calculateProfitProcess();
        }
        return 1;
    }

    public function test4()
    {
        $orders = OrderModel::where('channel_id', 4)->get();
        foreach ($orders as $order) {
            if ($order->items) {
                foreach ($order->items as $item) {
                    $item->update(['channel_id' => 4]);
                }
            }
        }
        return 1;
    }



//    public function test2()
//    {
//        $package = PackageModel::find(3081);
//        $package->realTimeLogistics();
//    }

    //模拟数据
    // public function test2()
    // {
    //     $user = factory(\App\Models\OrderModel::class,10)->create(['status' => 'PREPARED','customer_service' => '63', 'operator' => '195', 'payment' => 'MIXEDCARD', 'currency' => 'USD', 'rate' => '1'])
    //     ->each(function($single){
    //         $i = 0;
    //         $range = mt_rand(1,3);
    //         while($i<$range) {
    //             $single->items()->save(factory(\App\Models\Order\ItemModel::class)->make(['currency' => 'USD', 'is_active' => '1', 'status' => 'NEW', 'item_status' => 'selling']));
    //             $i++;
    //         }
            
    //     });
    // }
//    public function test2()
//    {
//        foreach (\App\Models\Order\ItemModel::all() as $item) {
//            $status = ItemModel::where('sku', $item->sku)->first()->status;
//            $item->update(['item_status' => $status]);
//        }
//    }
//    public function test2()
//    {
//        $item = ItemModel::find(23767);
//        var_dump($item->getStockQuantity(4,1));
//    }
    // public function test2()
    // {
    //     $a = ['a' => 'b', 'c' => 'e', 'f' => ['f','g','i']];
    //     $b = ['b' => 'c', 'a' => 'b', 'f' => ['f','k']];
    //     $arr = $this->calctowarr($a,$b);
    //     var_dump($a);
    //     var_dump($b);
    // }
    public function calcTwoArr(&$a, &$b)
    {
        foreach ($a as $key => $value) {
            if (array_key_exists($key, $b)) {
                if ($this->valueequal($value, $b[$key])) {
                    unset($a[$key]);
                    unset($b[$key]);
                } else {
                    if (getType($value) == getType($b[$key]) && is_array($value)) {
                        $this->calcTwoArr($a[$key], $b[$key]);
                    }
                }
            }
        }
    }
    public function valueEqual($c, $d)
    {
        if (getType($c) == getType($d)) {
            if (!is_array($c)) {
                if ($c == $d) {
                    return true;
                } else {
                    return false;
                }
            } else {
                foreach ($c as $key => $value) {
                    if (array_key_exists($key, $d)) {
                        if (!$this->valueEqual($value, $d[$key])) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
                return true;
            }
        } else {
            return false;
        }
    }

    // public function test2()
    // {
    //     $package = PackageModel::find('17');
    //     $package->realTimeLogistics();
    // }
    // public function test2()
    // {
    //     $data['channel_ordernum'] = '1111';
    //     $data['ordernum'] = '3000';
    //     $data['channel_account_id'] = '365';
    //     $data['channel_id'] = '2';
    //     $data['status'] = 'PAID';
    //     $data['active'] = 'NORMAL';
    //     $data['items'][0]['sku'] = 'MPJ845D';
    //     $data['items'][0]['quantity'] = 1;
    //     $job = new Inorders($data);
    //     $job->onQueue('Inorders');
    //     $this->dispatch($job);
    // }
    // public function test2()
    // {
    //     $account = AccountModel::find(1);
    //     $single = new AmazonAdapter($account->api_config);
    //     // var_dump($single->requestReport());exit;
    //     // var_dump($single->getReportRequestList('53034017045'));exit;
    //     // $buf = $single->getReport('2724553088017044');
    //     var_dump(empty($single->listInShipment('FBA3VX2RL1')));
    //     // $arr = explode("\n", $buf);
    //     // $keys = explode("\t", $arr[0]);
    //     // $vals = [];
    //     // foreach($arr as $key => $value) {
    //     //     if(!$key) {
    //     //         continue;
    //     //     }
    //     //     $buf = explode("\t", $value);
    //     //     foreach($buf as $k => $v) {
    //     //         $vals[$keys[$k]] = $v;
    //     //     }
    //     //     var_dump($vals);
    //     //     var_dump($vals['afn-inbound-receiving-quantity']);exit;
    //         // var_dump($vals);exit;
    //         // $tmp = Tool::filter_sku($vals['sku']);
    //         // if(count($tmp)) {
    //         //     $item = ItemModel::where('sku', $tmp['0']['erpSku'])->first()
    //         //     if($item) {
    //         //         $vals['item_id'] = $item->id;
    //         //     }
    //         // }
    //         // $vals['title'] = $vals['product-name'];
    //         // $vals['channel_sku'] = $vals['sku'];
    //         // $vals['mfn_fulfillable_quantity'] = $vals['mfn-fulfillable-quantity'];
    //         // $vals['afn_warehouse_quantity'] = $vals['afn-warehouse-quantity'];
    //         // $vals['afn_fulfillable_quantity'] = $vals['afn-fulfillable-quantity'];
    //         // $vals['afn_unsellable_quantity'] = $vals['afn-unsellable-quantity'];
    //         // $vals['afn_reserved_quantity'] = $vals['afn-reserved-quantity'];
    //         // $vals['afn_total_quantity'] = $vals['afn-total-quantity'];
    //         // $vals['per_unit_volume'] = $vals['per-unit-volume'];
    //         // $vals['afn_inbound_working_quantity'] = $vals['afn-inbound-working-quantity'];
    //         // $vals['afn_inbound_shipped_quantity'] = $vals['afn-inbound-shipped-quantity'];
    //         // $vals['afn_inbound_receiving_quantity'] = $vals['afn-inbound-shipped-quantity'];
    //         // $vals['account_id'] = '1';
    //         // fbaStock::create($vals);
    //     // }exit;
    // }

    public function index()
    {
        echo "<pre>";
        $package = PackageModel::find(62);
        var_dump($package->placeLogistics());
        exit;
        set_time_limit(0);
        $account = AccountModel::find(28);
        if ($account) {
            //初始化
            $i = 1;
            $startDate = date("Y-m-d H:i:s", strtotime('-' . $account->sync_days . ' days'));
            $endDate = date("Y-m-d H:i:s", time() - 300);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $nextToken = '';
            do {
                $start = microtime(true);
                $total = 0;
                $orderList = $channel->listOrders(
                    $startDate, //开始日期
                    $endDate, //截止日期
                    $account->api_status, //订单状态
                    $account->sync_pages, //每页数量
                    $nextToken //下一页TOKEN
                );
                foreach ($orderList['orders'] as $order) {
                    $order['channel_id'] = $account->channel->id;
                    $order['channel_account_id'] = $account->id;
                    $order['customer_service'] = $account->customer_service ? $account->customer_service->id : 0;
                    $order['operator'] = $account->operator ? $account->operator->id : 0;
                    $job = new InOrders($order);
                    $job = $job->onQueue('inOrders');
                    $this->dispatch($job);
                    $total++;
                }
                $nextToken = $orderList['nextToken'];
                //todo::Adapter->error()
                $result['status'] = 'success';
                $result['remark'] = 'Success.';
                $end = microtime(true);
                $lasting = round($end - $start, 3);
                echo $account->alias . ':' . $account->id . ' 抓取取第 ' . $i . ' 页, 耗时 ' . $lasting . ' 秒';
                $i++;
            } while ($nextToken);
        } else {
            echo 'Account is not exist.';
        }
    }
    public function testChinaPost()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }
    public function testWinit()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }
    public function test4px()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }
    public function testSmt()
    {
        $package = PackageModel::findOrFail(2);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->createWarehouseOrder($package);
        exit;
    }
    public function testYw()
    {
        $id = request()->get('id');
        $package = PackageModel::findOrFail($id);
        Logistics::driver($package->logistics->driver, $package->logistics->api_config)
            ->getTracking($package);
        exit;
    }
    public function aliexpressOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(2);
        $startDate = date("Y-m-d H:i:s", strtotime('-30 day'));
        $endDate = date("Y-m-d H:i:s", strtotime('-12 hours'));
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        foreach ($status as $orderStatus) {
            $pageTotalNum = 1;
            $pageSize = 50;
            for ($i = 1; $i <= $pageTotalNum; $i++) {
                $orderList = $channel->listOrdersOther($startDate, $endDate, $orderStatus, $i, $pageSize);
                if (isset($orderList['orderList'])) {
                    if ($i == 1) {
                        $pageTotalNum = ceil($orderList['totalItem'] / $pageSize); //重新生成总页数
                    }
                    foreach ($orderList['orderList'] as $list) {
                        $thisOrder = $this->orderModel->where('channel_ordernum', $list['orderId'])->first();
                        if ($thisOrder) {
                            continue;
                        }
                        $orderDetail = $channel->getOrder($list['orderId']);
                        if (isset($orderDetail['orderStatus'])) {
                            $order = $channel->parseOrder($list, $orderDetail);
                            if ($order) {
                                $thisOrder = $this->orderModel->where('channel_ordernum',
                                    $order['channel_ordernum'])->first();
                                $order['channel_id'] = $account->channel->id;
                                $order['channel_account_id'] = $account->id;
                                if ($thisOrder) {
                                    $thisOrder->updateOrder($order);
                                } else {
                                    $this->orderModel->createOrder($order);
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                } else {
                    break;
                }
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    public function lazadaOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(4);
        $startDate = date("Y-m-d H:i:s", strtotime('-1 day'));
        $endDate = date("Y-m-d H:i:s");
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $orderList = $channel->listOrders($startDate, $endDate, $status, 10);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            $order['channel_id'] = $account->channel->id;
            $order['channel_account_id'] = $account->id;
            if ($thisOrder) {
                //$thisOrder->updateOrder($order);
            } else {
                $this->orderModel->createOrder($order);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    public function cdiscountOrdersList()
    {
        $begin = microtime(true);
        $account = AccountModel::findOrFail(10);
        $startDate = date("Y-m-d H:i:s", strtotime('-1 day'));
        $endDate = date("Y-m-d H:i:s");
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $orderList = $channel->listOrders($startDate, $endDate, $status);
        foreach ($orderList as $order) {
            $thisOrder = $this->orderModel->where('channel_ordernum', $order['channel_ordernum'])->first();
            $order['channel_id'] = $account->channel->id;
            $order['channel_account_id'] = $account->id;
            if ($thisOrder) {
                //$thisOrder->updateOrder($order);
            } else {
                $this->orderModel->createOrder($order);
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    public function test()
    {
        $datas = DB::table('test')->get();
        foreach ($datas as $data) {
            $spu_id = DB::table('spus')->insertGetId(
                array(
                    'spu' => $data->sku,
                    'status' => 0,
                    'created_at' => '2015-10-16 16:33:00',
                    'updated_at' => '2015-10-16 16:33:00'
                )
            );
            $product_id = DB::table('products')->insertGetId(
                array(
                    'spu_id' => $spu_id,
                    'name' => $data->title,
                    'c_name' => $data->c_name,
                    'model' => $data->sku,
                    'weight' => $data->weight,
                    'catalog_id' => 1,
                    'supplier_id' => 1,
                    'warehouse_id' => 1,
                    'default_image' => 0,
                    'status' => 1
                )
            );
            $sku_id = DB::table('items')->insertGetId(
                array(
                    'product_id' => $product_id,
                    'name' => $data->title,
                    'c_name' => $data->c_name,
                    'sku' => $data->sku,
                    'weight' => $data->weight,
                    'purchase_price' => $data->value,
                    'warehouse_position' => $data->location,
                    'warehouse_id' => 1,
                    'catalog_id' => 1,
                    'supplier_id' => 1,
                    'status' => 1
                )
            );
        }
    }
    public function getWishProduct()
    {
        $accountID = request()->get('id');
        $begin = microtime(true);
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $hasProduct = true;
        $start = 0;
        while ($hasProduct) {
            $productList = $channel->getOnlineProduct($start, 500);
            if ($productList) {
                foreach ($productList as $product) {
                    $is_add = true;
                    $productInfo = $product['productInfo'];
                    $variants = $product['variants'];
                    foreach ($variants as $key => $variant) {
                        $productInfo['sellerID'] = $variant['sellerID']; //这个随便保存一个就好
                        $variants[$key]['account_id'] = $accountID;
                    }
                    $productInfo['account_id'] = $accountID;
                    $thisProduct = WishPublishProductModel::where('productID', $productInfo['productID'])->first();
                    if ($thisProduct) {
                        $is_add = false;
                        $mark_id = $thisProduct->id;
                    }
                    if ($is_add) {
                        $wish = WishPublishProductModel::create($productInfo);
                        foreach ($variants as $detail) {
                            $detail['product_id'] = $wish->id;
                            $wishDetail = WishPublishProductDetailModel::create($detail);
                        }
                    } else {
                        WishPublishProductModel::where('productID', $productInfo['productID'])->update($productInfo);
                        foreach ($variants as $key1 => $item) {
                            $productDetail = WishPublishProductModel::find($mark_id)->details;
                            if (count($variants) == count($productDetail)) {
                                foreach ($productDetail as $key2 => $productItem) {
                                    if ($key1 == $key2) {
                                        $productItem->update($item);
                                    }
                                }
                            } else {
                                foreach ($productDetail as $key2 => $orderItem) {
                                    $orderItem->delete($item);
                                }
                                foreach ($variants as $value) {
                                    $value['product_id'] = $mark_id;
                                    WishPublishProductDetailModel::create($value);
                                }
                            }
                        }
                    }
                }
                $start++;
            } else {
                $hasProduct = false;
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    public function getEbayInfo()
    {
        $accountID = request()->get('id');
        $begin = microtime(true);
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbaySite();
    }
    public function testLazada()
    {
        $accountId = 201;
        $account = AccountModel::findOrFail($accountId);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $startDate = date('Y-m-d', strtotime('-2 day'));
        $page = 0;
        $is_do = true;
        do {
            $result = $channel->getChangedOrders($startDate, $page, $pageSize = 500);
            if ($result) {
                $page++;
                foreach ($result as $re) {
                    if ($re['Order']['state'] == 'REFUNDED') { //退款状态
                        var_dump($re);
                    }
                }
            } else {
                $is_do = false;
            }
        } while ($is_do);
        /*  $result = $channel->GetFeedback();
          foreach($result as $re){
              $re['channel_account_id'] = $accountId;
              $feedback = EbayFeedBackModel::where(['feedback_id'=>$re['feedback_id'],'channel_account_id'=>$accountId])->first();
              if(empty($feedback)){
                  echo 11;
                  EbayFeedBackModel::create($re);
              }
          }*/
        $packages = PackageModel::where('order_id', 12914)->get();
        foreach ($packages as $package) {
            $OrderItemIds = [];
            foreach ($package->items as $item) {
                $temp = $item->orderItem->transaction_id;
                $temp = explode(',', $temp);
                foreach ($temp as $v) {
                    $v_temp = explode('@', $v);
                    $OrderItemIds[] = $v_temp[0];
                }
            }
            /* $OrderItemIds = [
                 9047009, 9047011
             ];*/
            $channel_listnum[] = $package->order->channel_listnum;
            $account = AccountModel::findOrFail($package->channel_account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->getPackageId(implode(',', $channel_listnum));
            if ($result) {
                if (isset($result[$OrderItemIds[0]])) { // 获取到了 最踪号 和 PackageId
                    $update_info = [
                        'tracking_no' => $result[$OrderItemIds[0]]['TrackingCode'],
                        'lazada_package_id' => $result[$OrderItemIds[0]]['PackageId'],
                    ];
                    $package->update($update_info);
                } else { //特殊情况数据记录
                }
            } else { //api调用失败
            }
            var_dump($OrderItemIds);
            var_dump($result);
        }
        exit;
    }
    public function testPaypal()
    {
        $id = request()->get('id');
        $orders = OrderModel::where('id', $id)->get();
        foreach ($orders as $order) {
            $is_paypals = false;
            $paypals = $order->channelAccount->paypal;
            foreach ($paypals as $paypal) {
                $api = new  PaypalApi($paypal);
                $result = $api->apiRequest('gettransactionDetails', $order->transaction_number);
                $transactionInfo = $api->httpResponse;
                var_dump($transactionInfo);
                var_dump($result);
                if ($result && $transactionInfo != null && (strtoupper($transactionInfo ['ACK']) == 'SUCCESS' || strtoupper($transactionInfo ['ACK']) == 'SUCCESSWITHWARNING')) {
                    $is_paypals = true;
                    $tInfo = $transactionInfo;
                    $paypal_account=isset($tInfo ['EMAIL'])?$tInfo ['EMAIL']:'';
                    $paypal_buyer_name = trim($tInfo ['SHIPTONAME']);
                    $paypal_country_code = trim($tInfo['SHIPTOCOUNTRYCODE']); //国家简称
                    $paypal_country = trim($tInfo['SHIPTOCOUNTRYNAME']); //国家
                    $paypal_city = trim($tInfo['SHIPTOCITY']);        //城市
                    $paypal_state = trim($tInfo['SHIPTOSTATE']);       //州
                    $paypal_street = trim($tInfo['SHIPTOSTREET']);      //街道1
                    $paypal_street2 = isset($tInfo['SHIPTOSTREET2'])?trim($tInfo['SHIPTOSTREET2']):'';     //街道2
                    $paypal_zip = trim($tInfo['SHIPTOZIP']);         //邮编
                    $paypal_phone = isset($tInfo['SHIPTOPHONENUM']) ? trim($tInfo['SHIPTOPHONENUM']) : '';    //电话
                    $paypalAddress = $paypal_street . ' ' . $paypal_street2 . ' ' . $paypal_city . ' ' . $paypal_state . ' ' . $paypal_country . '(' . $paypal_country_code . ') ' . $paypal_zip;
                    $feeAmt  = $tInfo['FEEAMT'];
                    $currencyCode  = $tInfo['CURRENCYCODE'];
                    //把paypal的信息记录

                    $is_exist = OrderPaypalDetailModel::where('order_id',$order->id)->first();
                    if (empty($is_exist)) {
                        $add = [
                            'order_id' => $this->order->id,
                            'paypal_id' =>$paypal->id,
                            'paypal_account' => $paypal_account,
                            'paypal_buyer_name'=>$paypal_buyer_name,
                            'paypal_address'=>$paypalAddress,
                            'paypal_country'=>$paypal_country_code,
                            'feeAmt'=>$feeAmt,
                            'currencyCode'=>$currencyCode
                        ];
                        OrderPaypalDetailModel::create($add);
                    }
                    if (!empty($error)) { //设置为匹配失败
                        $order->update(['order_is_alert'=>'1']);
                        echo 'false';
                    } else { //设置为匹配成功
                        $order->update(['order_is_alert'=>'2','fee_amt'=>$feeAmt]);
                        echo 'success';
                    }
                }
            }
            if (!$is_paypals) { //说明对应的paypal 都没有找到信息
                $order->update(['order_is_alert'=>'1']);
                echo 'false2';
            }
        }
    }

    public function testAutoReply()
    {
        $accounts = AccountModel::where('is_available','1')->where('channel_id',3)->get();
        foreach($accounts as  $account){
            if($account->id !=6)
                continue;
            //获取此账号的自动规则
            $rules = $account->AutoReplyRules;

            $messages =MessageModel::where('account_id', $account->id)->orderBy('id', 'DESC')->get();
            foreach($messages as $message){


                ////////测试块//////////

               /* if($message->id != 621)
                    continue;*/
                /////////测试块//////////


                //step1: 关联消息订单
                $message->findOrderWithMessage();
                if(! $rules->isEmpty()){ //存在规则
                    $rule = $this->checkAutomaticReply($message, $rules);
                    dd($rule);
                    if(! empty($rule->template)){ //符合发送消息的条件

                        /**
                         * 创建reply记录
                         * 塞入发送队列
                         *
                         */
                        $new_reply = [
                            'message_id' => $message->id,
                            'to' => $message->from_name,
                            'to_email' => $message->from,
                            'title' => $rule->name . '(自动回复)',
                            'content' => $rule->template,
                            'status' => 'NEW',
                        ];
                        $reply = ReplyModel::firstOrNew($new_reply);
                        $reply->save();

                        $job = new SendMessages($reply);
                        $job = $job->onQueue('SendMessages');
                        $this->dispatch($job);

                        $message->status = 'COMPLETE';
                        $message->type_id = 0;
                        $message->end_at = date('Y-m-d H:i:s', time());
                        $message->is_auto_reply = 1;
                        $message->save();

                    }}

            }
        }
    }

    /**
     * 基础验证消息关联订单 包裹 物流
     * @param $message
     * @return object | bool
     */
    public function basicVerification($message)
    {

        $order = $message->relatedOrders()->orderBy('id', 'DESC')->first();
        if(empty($order)){
            return -1;
        }
        $packages = OrderModel::find($order->order_id)->packages;
        if($packages->isEmpty()){ //存在包裹
            return -2;
        }
        if($packages->count() != 1){//只存在一个包裹
            return -3;
        }
        if($packages->first()->status != 'SHIPPED'){ //包裹状态为已发货
            return -4;
        }
        //检查 消息关联的订单物流方式必须是平邮
        if(! $message->MsgOrderIsExpress()){
            return -5;
        }
        //验证是否为平台第一条消息
        if(! $message->IsFristMsgForOrder()){
            return -6;
        }

        return $packages->first();
    }

    public function checkAutomaticReply($message, $rules)
    {
        $result = false;

        $package = $this->basicVerification($message);

        if(! is_object($package)){ //验证失败
            return $result;
        }
        $send_time = Carbon::parse($message->date);
        $shipped_at = Carbon::parse($package->shipped_at);
        $diff_day = $send_time->diffInDays($shipped_at);  // 相差天数

        foreach($rules as $rule){
            if($rule->status == 'ON'){
                switch ($rule->ChannelName){
                    case 'Wish':
                        $check_wish = true;
                        if( ! empty($rule->label_keywords)){//主题关键字
                            if(! strstr($message->labels, $rule->label_keywords)){
                                //主题匹配
                                $check_wish = -1;
                            }
                        }

                        if(! empty($rule->message_keywords)){//用户消息中同时包含关键字
                            $check_wish = false;
                            foreach (explode(',', $rule->message_keywords) as $keyword){
                                if(! strstr($message->UserMsgInfo, trim($keyword))){
                                    $check_wish = true;
                                }
                            }
                        }

                        if($rule->type_shipping_fifty_day == 'ON'){ //50天按钮开
                            if($diff_day < 50){
                                $check_wish = -3;
                            }
                        }

                        if($rule->type_within_tuotou == 'ON'){  //在wish平台妥投时间之内
                            if($diff_day > 19){
                                $check_wish = -4;
                            }
                        }

                        if($check_wish == true)
                            $result = $rule;
                        break;
                    case 'Aliexpress':
                    //检查关键词
                    if(! empty($rule->message_keywords)){
                        $check_aliexpress = true;
                        foreach (explode(',', $rule->message_keywords) as $keyword){
                            $check_aliexpress = false;
                            if(! strstr($message->UserMsgInfo, $keyword)){
                                $check_aliexpress = true;
                            }
                        }

                            if($rule->type_shipping_one_month == 'ON'){//SMT: 平邮已发货订单，据发货时间一个月之内
                                if($diff_day > 30){
                                    $check_aliexpress = false;
                                }
                            }

                            if($rule->type_shipping_one_two_month == 'ON'){//SMT 据发货时间  1～2个月没有
                                if(($diff_day < 30) || ($diff_day > 60 )){
                                    $check_aliexpress = false;
                                }
                            }

                            if($check_aliexpress)
                                $result = $rule;

                    }
                    break;
                    case 'Ebay':
                        if(! empty($rule->message_keywords)){//用户消息中同时包含关键字
                            $check_ebay = false;
                            foreach (explode(',', $rule->message_keywords) as $keyword){
                                if(! strstr($message->UserMsgInfo, trim($keyword))){
                                    $check_wish = -2;
                                }
                            }
                        }




                    break;
                    default:
                        break;
                }
            }
        }

        return $result;
    }
    //type_shipping_one_month
    public function AliexpressFilter($message,$type=1){

        $result = false;

        $order = $message->relatedOrders()->orderBy('id', 'DESC')->first();
        $packages = OrderModel::find($order->order_id)->packages;
        if(! $packages->isEmpty()){
            if($packages->count() == 1){  //只存在一个包裹
                $package = $packages->first();
                $send_time = Carbon::parse($message->date);
                $shipped_at = Carbon::parse($package->shipped_at);

                if($type == 1){//发信时间和发货时间的时间差 小于 30天  判断

                    if($send_time->diffInDays($shipped_at) <= 30){
                        //是否第一次发信 或者 第二次发信其第一封为自动回复的
                        if($message->IsFristMsgForOrder()){
                            $result = true;
                        }

                    }
                }else{ //发信时间和发货时间的时间差  1-2 月之间     判断
                    if(($send_time->diffInDays($shipped_at) > 30) && ($send_time->diffInDays($shipped_at) < 60 )){
                        //是否第一次发信 或者 第二次发信其第一封为自动回复的
                        if($message->IsFristMsgForOrder()){
                            $result = true;
                        }
                    }
                }

            }
        }
        return $result;
    }

    public function jdtestCrm()
    {

/*        $tmp ="[u'https://s3-us-west-1.amazonaws.com/sweeper-production-ticket-image-uploads/cbc4768ccda711e6bcf102c49158406c.jpg', u'https://s3-us-west-1.amazonaws.com/sweeper-production-ticket-image-uploads/dd5f9e6ccda711e6bfeb02465360c040.jpg', u'https://s3-us-west-1.amazonaws.com/sweeper-production-ticket-image-uploads/e490e222cda711e6ae1c02762614c162.jpg']";

        $tmp = str_replace('[', '', $tmp);
        $tmp = str_replace(']', '', $tmp);
        $tmp_array = explode(',', $tmp);
        $urls = [];
        foreach($tmp_array as $url){
            $tmp_url = explode('\'', $url);
            $urls[] = $tmp_url[1];

        }
        dd($urls);

        dd('end');*/
        foreach (AccountModel::all() as $account) {
            if ($account->account == 'Coolcoola04@126.com') { //测试diver

                //$reply = ReplyModel::find(13);
                dd($account->api_config);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                dd($channel);

                $data = $channel->getMessages();
                dd(json_decode($data,true));
                exit;
                $messageList = $channel->getMessages();
                print_r($messageList);
                exit;
            }
        }



        dd('exit');
        $groups = SupplierModel::all()->groupBy('company');

        foreach ($groups as $group_key => $group){
            if($group->count() > 1){   //如果有多个相同的供货商
                foreach($group as $key => $supplier){
                    if($key == 0){ //保留id最小的供货商，然后把其他的 sku 关联到 最小的供货商id  其余的全删除

                        $correct_supplier_id = $supplier->id;

                    }else{

                         $sql = 'update items set supplier_id = ' . $correct_supplier_id . ' where supplier_id = ' . $supplier->id;
                         DB::update($sql);
                         //更换备选
                         DB::update('update item_prepare_suppliers set supplier_id = ' . $correct_supplier_id . ' where supplier_id = ' . $supplier->id);

                         $supplier->delete(); //删除多余
                    }
                }

            }

        }
        dd('结束');
        /*
         * 写入队列
         */
       $replys = ReplyModel::where('status','FAIL')->get();
        foreach($replys as $reply){
            $job = new SendMessages($reply);
            $job = $job->onQueue('SendMessages');
            $this->dispatch($job);
        }
        dd('已执行！ fight 3333！！！');

        foreach (AccountModel::all() as $account) {
            if ($account->account == 'Coolcoola04@126.com') { //测试diver

                $reply = ReplyModel::find(13);
                $channel = Channel::driver($account->channel->driver, $account->api_config);

                $channel->sendMessages($reply);
                exit;
                $messageList = $channel->getMessages();
                print_r($messageList);
                exit;
            }
        }
        dd('end');
        $ali = new Alibaba(); //初始化阿里账号
        $ali_accounts = AlibabaSupliersAccountModel::all();
        $purchase_orders =  PurchaseOrderModel::whereIn('status',[1,2,3])->whereNotNull('post_coding')->get();
        foreach ($purchase_orders as $purchase_order){
            if(!empty($purchase_order->post_coding)){
                foreach ($ali_accounts as $account){
                    if(empty($account->access_token)){
                        continue;
                    }
                    if($account->resource_owner != 'slme18'){
                        continue;
                    }
                    //根据采购人 获取对应的阿里账号
                    $curl_params['access_token']  =$account->access_token;
                    //$curl_params['buyerMemberId'] =$account->memberId;
                    $curl_params['id']    = $purchase_order->post_coding;
                    //$param['buyerMemberId'] = $curl_params['buyerMemberId'];
                    $param['access_token']  = $curl_params['access_token'];
                    $param['id']    = $curl_params['id'];
                    $curl_params['_aop_signature'] = $ali->getSignature($param, $ali->order_list_api_url.'/'.$ali->app_key);
                    $crul_url = $ali->ali_url .'/openapi/'.$ali->order_list_api_url.'/'.$ali->app_key;
                    $order_detail = json_decode($ali->get($crul_url,$curl_params),true);
                    if(!empty($order_detail['orderModel']['logisticsOrderList'])){
                        foreach ($order_detail['orderModel']['logisticsOrderList'] as $item_logistics){
                            if(!empty($item_logistics['logisticsBillNo'])) {
                            }
                        }
                    }
                }
            }
        }
        $orderids = '';
        $orderids_ary = [];
        $count = 0;
        /**
         * 获取所有缺失物流单号的采购单中的单号
         *
         */
        $purchasePostages = PurchasePostageModel::where('post_coding',Null)->get();
        if(!$purchasePostages->isEmpty()){
            foreach ($purchasePostages as $purchasePostage){
                if(!empty($purchasePostage->purchaseOrder->post_coding)){ //外部单号不为空
                    $orderids_ary[$purchasePostage->purchaseOrder->post_coding] = $purchasePostage->purchaseOrder->post_coding;
                }
            }
        }
        //dd($orderids_ary);
        $ali_accounts = AlibabaSupliersAccountModel::all();
        foreach($orderids_ary as $ali_order_id){
            foreach ($ali_accounts as $account){
                if(empty($account->access_token)){
                    continue;
                }
                if($account->resource_owner != 'slme18'){
                    continue;
                }
                //根据采购人 获取对应的阿里账号
                $curl_params['access_token']  =$account->access_token;
                //$curl_params['buyerMemberId'] =$account->memberId;
                $curl_params['id']    = $ali_order_id;
                //$param['buyerMemberId'] = $curl_params['buyerMemberId'];
                $param['access_token']  = $curl_params['access_token'];
                $param['id']    = $curl_params['id'];
                $curl_params['_aop_signature'] = $ali->getSignature($param, $ali->order_list_api_url.'/'.$ali->app_key);
                $crul_url = $ali->ali_url .'/openapi/'.$ali->order_list_api_url.'/'.$ali->app_key;
                $order_detail = json_decode($ali->get($crul_url,$curl_params),true);
                if(!empty($order_detail['orderModel']['logisticsOrderList'])){
                    foreach ($order_detail['orderModel']['logisticsOrderList'] as $item_logistics){
                        if(!empty($item_logistics['logisticsBillNo'])) {
                        }
                    }
                }
                //dd($orderList);
                if(isset($orderList['orderListResult']['modelList'])){
                    if(count($orderList['orderListResult']['modelList']) != 0){
                        foreach ($orderList['orderListResult']['modelList'] as $modellist){
                            if(!empty($modellist['logisticsOrderList']) && is_array($modellist['logisticsOrderList'])){ //如果存在物流列表
                                foreach ($modellist['logisticsOrderList'] as $logistic){
                                    if(!empty($logistic['logisticsBillNo'])){
                                        dd($logistic['logisticsOrderNo']);
                                        /*                                    $postage = PurchasePostageModel::where('post_coding','=',$logistic['logisticsOrderNo'])->first();
                                                                            if(empty($postage)){
                                                                                $new_postage = new PurchasePostageModel;
                                                                                $new_postage->purchase_order_id = $modellist['id'];
                                                                                $new_postage->post_coding       = $logistic['logisticsOrderNo'];
                                                                                $new_postage->user_id           = $user_id;
                                                                                $new_postage->save();
                                                                                $this->info('#Order:'.$modellist['id'].' add logisticsOrderNo :'. $logistic['logisticsOrderNo'].' insert success');
                                                                            }*/
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        /*        $message_obj = MessageModel::find(36336);
                //$tt = $message_obj->ChannelMessageFields();
                dd($message_obj->MessageFields);exit;
                exit;*/
        //渠道测试块
        /*        $message_obj = MessageModel::find(36259);
                $fields = unserialize(base64_decode($message_obj->channel_message_fields));
                dd($fields);exit;*/
        /*
                 $reply_obj = ReplyModel::find(28569);
                  foreach (AccountModel::all() as $account) {
                    if( $account->account == 'wintrade9'){ //测试diver
                        $channel = Channel::driver($account->channel->driver, $account->api_config);
                        $messageList = $channel->sendMessages($reply_obj);
                        print_r($messageList);exit;
                    }
                }*/
        /*
         *
         *
         */
        foreach (AccountModel::all() as $account) {
            if ($account->account == 'darli04@126.com') { //测试diver
                //dd($account);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $messageList = $channel->getMessages();
                print_r($messageList);
                exit;
            }
        }
        /*        $userId =  request()->user()->id;
                $accounts = AccountModel::where('customer_service_id','=',$userId)->get();
                if(count($accounts) <> 0){
                    foreach ($accounts as $key => $account){
                        $ids_ary[] = $account->id;
                    }
                    return $ids_ary;
                }
                exit;*/
    }

    public function testReply($id){


        //测试单个塞入队列
        /*
 * 写入队列
 */
/*        $reply = ReplyModel::find($id);
            $job = new SendMessages($reply);
            $job = $job->onQueue('SendMessages');
            $this->dispatch($job);
        dd($reply);*/


/*        foreach (AccountModel::all() as $account) {
            if ($account->account == 'Coolcoola04@126.com') { //测试diver
                $replys = ReplyModel::where('status','FAIL')->get();
                foreach ($replys as $reply){
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $channel->sendMessages($reply);
                }

                dd('已经操作233');

            }
        }*/

    }
    /**
     * Curl Post JSON 数据
     */
    public function postCurlHttpsData($url, $data)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        /*        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data))
                );*/
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            // $this->setCurlErrorLog(curl_error ( $curl ));
            die(curl_error($curl)); //异常错误
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
    public function testEbayCases()
    {
        $url = 'jiangdi.zserp.com/api/SyncSellmoreData';
        $data = [
            'secretKey' => 'VSxtAts2fQlTLc1KCLaM',
            'type' => 'update',
            //'id'                     => 30000,
            'suppliers_id' => 40110,
            'suppliers_company' => '',
            'suppliers_website' => '',
            'suppliers_address' => '',
            'suppliers_type' => '0',
            'supplierArrivalMinDays' => '',
            'suppliers_bank' => '招商',
            'suppliers_card_number' => '23543456345',
            'suppliers_name' => '160000',
            'suppliers_mobile' => '13016937924',
            'suppliers_wangwang' => '23456edg',
            'suppliers_qq' => '3563456',
            'pay_method' => '1',
            'attachment_url' => 'http://erp.moonarstore.com/upload/suppliers/2014/0410/13971223945484.jpg',
        ];
        $result = $this->postCurlHttpsData($url, $data);
        echo($result);
        exit;
        /*        'sellmore' => [
                    'pay_type' => [
                        1 => 'ONLINE',
                        2 => 'BANK_PAY',
                        3 => 'CASH_PAY',
                        4 => 'OTHER_PAY',
                    ],
                    'api_url' => 'http://120.24.100.157:60/api/api_suppliers.php',
                ],*/
        foreach (AccountModel::all() as $account) {
            if ($account->account == 'ebay@licn2011') { //测试diver
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $messageList = $channel->getCases();
                print_r($messageList);
                exit;
            }
        }
    }
    /*
     * 同步ebay信息
     * 测试自动补货功能
     */
    public function getEbayProduct()
    {
        //$package = PackageModel::findOrFail(3113);
        $id = request()->get('id');
        $package = PackageModel::where('id', $id)->first();
        if (in_array($package->status, ['PROCESSING', 'PICKING', 'PACKED','SHIPPED'])) {
            $result = $package->placeLogistics('UPDATE');
        } else {
            $result = $package->placeLogistics();
        }
        var_dump($result);        
        exit;
        $page = 2;
        $pageSize = 2000;
        $status = ['saleOutStopping', 'stopping', 'cleaning'];
        $product_data = ItemModel::where(['is_available' => 1,])->whereIn('status',
            $status)->Offset($pageSize * $page)->limit($pageSize)->get();
        foreach ($product_data as $product) {
            if ($product->AvailableQuantity + $product->NormalTransitQuantity > 0) {
                continue;
            }
            $ebay_sku_arr = EbayPublishProductDetailModel::where([
                'erp_sku' => trim($product->sku),
                'status' => '1'
            ])->get();
            foreach ($ebay_sku_arr as $ebay_sku) {
                if ($ebay_sku->ebayProduct->multi_attribute == 0) { //直接下架
                    echo 'single  down';
                } else {
                    $all_ebay_sku = EbayPublishProductDetailModel::where(['item_id' => $ebay_sku->item_id,])->where('id',
                        '!=', $ebay_sku->id)->get(); //这个广告下的全部sku 不包括这个
                    $is_down = true; //下架改广告
                    foreach ($all_ebay_sku as $ebay_sku_item) {
                        if ($ebay_sku_item->status == 1) { //sku 在线
                            echo $ebay_sku_item->erp_sku;
                            if (in_array($ebay_sku_item->erpProduct->status, $status)) { //其他sku  不满足状态
                                if ($ebay_sku_item->erpProduct->AvailableQuantity + $ebay_sku_item->erpProduct->NormalTransitQuantity > 0) { //其他sku 虚库存+在途 > 0
                                    $is_down = false;
                                }
                            } else {
                                $is_down = false;
                            }
                        }
                    }
                    if ($is_down) { //下架这个listting
                        echo 'mul  down';
                    } else { //将这个sku的在线数量调成0
                        echo 'set zero';
                    }
                }
            }
        }
        //货源待定SKU 在线数量设置为0
        $page = 2;
        $pageSize = 2000;
        $status = ['unSellTemp'];
        $product_data = ItemModel::where(['is_available' => 1,])->whereIn('status',
            $status)->Offset($pageSize * $page)->limit($pageSize)->get();
        foreach ($product_data as $product) {
            if ($product->AvailableQuantity + $product->NormalTransitQuantity > 0) {
                continue;
            }
            $ebay_sku_arr = EbayPublishProductDetailModel::where([
                'erp_sku' => trim($product->sku),
                'status' => '1'
            ])->get();
            foreach ($ebay_sku_arr as $ebay_sku) { //设置在线数量为0
                echo 'set zero';
            }
        }
        /* $account = AccountModel::find(378);
         if ($account) {
             $channel = Channel::driver($account->channel->driver, $account->api_config);
             $channel->testBuHuo();*/
//            $is_do =true;
//            $i=1;
//            while($is_do) {
//                $productList = $channel->getSellerEvents($i);
//                if ($productList) {
//                    foreach($productList as $key=> $itemId){
//                        $channel->getProductDetail($itemId);
//                        if($key==10){
//                            exit;
//                        }
//                    }
//                    $i++;
//                }else{
//                    $is_do=false;
//                }
//            }
        //}
    }
    public function getCurlData($remote_server)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $output;
    }
    public function getSmtIssue()
    {
       $messgaes = MessageModel::limit(1000)->WithOnly('account', ['account'])->get();
       foreach ($messgaes as $message){
           $message->account;
           //dd($message->account);
       }
       dd($messgaes);


        $account_name = 'Coolcoola04@126.com';  //渠道名称

        $account = AccountModel::where('account',$account_name)->first();
        if(! empty($account)){
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $getIssueLists = $channel->getIssues();
            //dd($getIssueLists);
            if(!empty($getIssueLists)){
                foreach($getIssueLists as $issue){
                    $issue_list = AliexpressIssueListModel::firstOrNew(['issue_id' => $issue['issue_id']]);
                    if(empty($issue_list->id)){
                        $issue_list->issue_id      = $issue['issue_id'];
                        $issue_list->account_id    = $account->id;
                        $issue_list->gmtModified   = $issue['gmtModified'];
                        $issue_list->issueStatus   = $issue['issueStatus'];
                        $issue_list->gmtCreate     = $issue['gmtCreate'];
                        $issue_list->reasonChinese = $issue['reasonChinese'];
                        $issue_list->orderId       = $issue['orderId'];
                        $issue_list->reasonEnglish = $issue['reasonEnglish'];
                        $issue_list->issueType     = $issue['issueType'];
                        $issue_list->save();

                        //$this->info('issue #' .$issue['issue_id']. ' Received.');

                        if(!empty($issue['issue_detail'])){
                            $issue_detail = AliexpressIssuesDetailModel::firstOrNew(['issue_list_id' => $issue_list->id]);
                            if(empty($issue_detail->id)){
                                $issue_detail->issue_list_id = $issue_list->id;
                                $issue_detail->resultMemo    = $issue['issue_detail']->resultMemo;
                                $issue_detail->orderId       = $issue['issue_detail']->resultObject->orderId;
                                $issue_detail->gmtCreate     = date('Y-m-d H:i:s', substr($issue['issue_detail']->resultObject->gmtCreate, 0, 10));
                                $issue_detail->issueReasonId = $issue['issue_detail']->resultObject->issueReasonId;
                                $issue_detail->buyerAliid    = $issue['issue_detail']->resultObject->buyerAliid;
                                $issue_detail->issueStatus   = $issue['issue_detail']->resultObject->issueStatus;
                                $issue_detail->issueReason   = $issue['issue_detail']->resultObject->issueReason;
                                $issue_detail->productName   = $issue['issue_detail']->resultObject->productName;

                                //序列化对象
                                $issue_detail->productPrice         = base64_encode(serialize($issue['issue_detail']->resultObject->productPrice));
                                $issue_detail->buyerSolutionList    = base64_encode(serialize($issue['issue_detail']->resultObject->buyerSolutionList));
                                $issue_detail->sellerSolutionList   = base64_encode(serialize($issue['issue_detail']->resultObject->sellerSolutionList));
                                $issue_detail->platformSolutionList = base64_encode(serialize($issue['issue_detail']->resultObject->platformSolutionList));
                                $issue_detail->refundMoneyMax       = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMax));
                                $issue_detail->refundMoneyMaxLocal  = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMaxLocal));

                                $issue_detail->save();
                            }
                        }
                    }
                }
            }else{
                dd(' hasnot this time OR token is timeout');

            }
        }else{
            //$this->comment('account num maybe worng.');
            dd('account num maybe worng.');

        }
        //$this->info('finsh.');
        dd('finsh');

    }
    public function oneSku()
    {
        ini_set('memory_limit', '2048M');
        $model = ProductModel::all();
        foreach ($model as $_item) {
            $sku = $_item->item[0]->sku;
            $url = 'http://erp.moonarstore.com/getSkuImageInfo/getSkuImageInfo.php?distinct=true&include_sub=true&sku=' . $sku;
            $contents = json_decode(file_get_contents($url));
            if (count($contents)) {
                foreach ($contents as $image) {
                    $data['spu_id'] = $_item->item[0]->product->spu_id;
                    $data['product_id'] = $_item->item[0]->product_id;
                    $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/';
                    $data['name'] = $image->filename;
                    $arr = (array)$image->fileId;
                    $image_url = 'http://erp.moonarstore.com/getSkuImageInfo/getSkuImage.php?id=' . $arr['$id'];
                    $disk = Storage::disk('product');
                    Storage::disk('product')->put($data['path'] . $data['name'], file_get_contents($image_url));
                }
            }
        }
    }
    /*Synchronize joom platform data
     *@model:joom
     *@param $account_ids
     */
    public function getJoomProduct()
    {
        //$account_ids = 412;
        $account_ids = request()->get('accountIDs');
        $account_arr = explode(',', $account_ids);
        foreach ($account_arr as $account_id) {
            $account = AccountModel::find($account_id);
            $begin = microtime(true);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $hasProduct = true;
            $start = 0;
            while ($hasProduct) {
                $productList = $channel->getOnlineProduct($start, 50);
                if ($productList) {
                    foreach ($productList as $product) {
                        $is_add = true;
                        $productInfo = $product['productInfo'];
                        $variants = $product['variants'];
                        foreach ($variants as $key => $variant) {
                            $productInfo['sellerID'] = $variant['sellerID'];
                            $variants[$key]['account_id'] = $account_id;     //request account id
                        }
                        $productInfo['account_id'] = $account_id;  //request account id
                        $thisProduct = JoomPublishProductModel::where('productID', $productInfo['productID'])->first();
                        if ($thisProduct) {
                            $is_add = false;
                            $mark_id = $thisProduct->id;
                        }
                        if ($is_add) {    //not data create
                            $joom = JoomPublishProductModel::create($productInfo);
                            foreach ($variants as $detail) {
                                $detail['product_id'] = $joom->id;
                                $joomDetail = JoomPublishProductDetailModel::create($detail);
                            }
                        } else {         //exist update data
                            JoomPublishProductModel::where('productID',
                                $productInfo['productID'])->update($productInfo);
                            foreach ($variants as $key1 => $item) {
                                $productDetail = JoomPublishProductModel::find($mark_id)->details;
                                if (count($variants) == count($productDetail)) {
                                    foreach ($productDetail as $key2 => $productItem) {
                                        if ($key1 == $key2) {
                                            $productItem->update($item);
                                        }
                                    }
                                } else {
                                    foreach ($productDetail as $key2 => $orderItem) {
                                        $orderItem->delete($item);
                                    }
                                    foreach ($variants as $value) {
                                        $value['product_id'] = $mark_id;
                                        JoomPublishProductDetailModel::create($value);
                                    }
                                }
                            }
                        }
                    }
                    $start++;
                } else {
                    $hasProduct = false;
                }
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    /*Time:2016-10-7
     *get joom order
     *@param $account_ids
     */
    public function joomOrdersList()
    {
        $begin = microtime(true);
        $account_ids = request()->get('accountIDs');
        $account = AccountModel::find($account_ids);
        $startDate = date("Y-m-d", strtotime('-3 day'));
        $endDate = date("Y-m-d H:i:s");
        $status = $account->api_status;
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        for ($i = 0; $i >= 0; $i++) {
            $pagesize = 50;
            $orderList = $channel->listOrders($startDate, $endDate, $status, $i, $pagesize);
            if (isset($orderList['orders'])) {
                foreach ($orderList['orders'] as $order) {
                    $thisOrder = OrderModel::where('channel_ordernum', $order['channel_ordernum'])->first();
                    $order['channel_id'] = $account->channel->id;
                    $order['channel_account_id'] = $account->id;
                    if ($thisOrder) {
                        //$thisOrder->updateOrder($order);
                    } else {
                        $this->orderModel->createOrder($order);
                    }
                }
            } else {   //over
                break;
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    /*Time:2016-10-14
         *joom_OrdersToShipping 标记发货
         *@param $order_id $id
         */
    public function joomToShipping()
    {
        $begin = microtime(true);
        $id = request()->get('id');
        $orders = OrderModel::where('id', $id)->first();
        if (!$orders) {
            echo "Parameter error！";
            exit;
        }
        if ($orders->channel_id !== 9) {
            echo "Parameter error！";
            exit;
        }
        $account = AccountModel::where('channel_id', $orders->channel_id)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $channel_arr = explode("+", $orders->channel_ordernum);
        $provider = '';   //joom承运商承认物流
        if (!$provider) {
            $provider = 'SFExpress';
        }
        foreach ($channel_arr as $item) {
            foreach ($orders->packages as $track) {
                $order_shipping = JoomShippingModel::where([
                    'joomID' => $item,
                    'shipping_code' => $track->tracking_no
                ])->get();
                $orderList = $channel->joomApiOrdersToShipping($item, $provider, $track->tracking_no, $orders->status);
                if ($orderList['code'] == 0 || isset($orderList['data']['success'])) {
                    if ($orders->status == 'SHIPPED') {
                    }
                } else {
                    if (strpos($orderList['message'], "has been shipped already")) {
                        echo "" . $orderList->id . "在joom平台上已经标记发货成功！不需重新发货！api返回信息为" . $orderList['message'] . "<br>";
                    } else {
                        echo "发生错误，错误信息为" . $orderList["message"] . "<br>";
                    }
                }
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
    /*
     * Time:2016-10-17
     * joomordersshelves  refure token
     * @param $order_id $id
     */
    public function joomrefreshtoken()
    {
        $begin = microtime(true);
        $account = request()->get('account');
        if (!$account) {
            echo "Parameter error！";
            exit;  //参数不能为空
        }
        $account = AccountModel::where('account', $account)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $url = "https://api-merchant.joom.it/api/v2/oauth/refresh_token";
        $post_data = "client_id=" . $account->client_id . "&client_secret=" . $account->client_secret . "&refresh_token=" . $account->refresh_token . "&grant_type=refresh_token";
        //$json_data = $channel->postCurlHttpsData($url,$post_data);   //刷新token返回的信息
        if (isset($json_data)) {
            $ret = DB::table('channel_accounts')->where('id', $account->id)->update([
                'joom_access_token' => $json_data->data->access_token,
                'joom_refresh_token' => $json_data->data->refresh_token,
                'joom_expiry_time' => $json_data->data->expiry_time
            ]);
            if ($ret) {
                echo "系统刷新token成功!请检查！刷新后token信息为" . $json_data->data->access_token . "<br>";
            } else {
                echo "系统刷新token失败!请检查！<br>";
            }
        } else {
            echo "系统刷新token失败!请检查！<br>";
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }


    public function  testAutoCancelOrder(){
        $id = request()->get('id');
        if(!empty($id)){
            $list  = OrderModel::whereIn('status', ['UNPAID','PAID','PREPARED','NEED','PACKED','REVIEW'])->where('created_at','<',date('Y-m-d H:i:s',strtotime("-20 days")))->where('id',$id);
        }else{
            $list  = OrderModel::whereIn('status', ['UNPAID','PAID','PREPARED','NEED','PACKED','REVIEW'])->where('created_at','<',date('Y-m-d H:i:s',strtotime("-20 days")));
        }
        $orders = $list->get();
        if($orders->count()){
            foreach($orders as $order){
                echo $order->id.'\n';
                $result = $order->cancelOrder(10);//撤单，4为客户撤单类型
                if($result){
                    $order->eventLog('队列', '订单导入超过20天，系统自动撤单.');
                }else{
                    $order->eventLog('队列', '系统自动撤单失败.');
                }
            }
        }
    }




}