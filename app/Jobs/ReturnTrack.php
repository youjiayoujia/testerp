<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PackageModel;
use App\Models\Channel\AccountModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Package\ItemModel;
use App\Models\OrderModel;
use App\Models\Logistics\ChannelModel;
use App\Models\Order\OrderMarkLogicModel;
use App\Models\Package\ShippingLogModel;


use Tool;
use Channel;


class ReturnTrack extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;
    protected $orderMarkLogic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package, $orderMarkLogic)
    {
        //
        $this->orderMarkLogic = $orderMarkLogic;
        $this->package = $package;
        $this->description = ' Info:[ order_id ' . $this->package['order_id'] . ': package_id ' . $this->package['id'] . '] start mark.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        $driver = AccountModel::find($this->package->channel_account_id)->channel->driver;
        $package = $this->package;
        $package_items = $package->items;
        $remark = '';
        $is_success = true;
        $show_data = array();
        $IsUploadTrackingNumber = true;
        $logistics_channel_name = ChannelNameModel::where('channel_id', $package->channel_id)->whereHas('logistics', function ($query) use ($package) {
            $query = $query->where('logistics_id', $package->logistics_id);
        })->first()->logistics_key;

        $tracking_no = $package->tracking_no;
        if ($this->orderMarkLogic->is_upload == 2) { //标记发货但不上传跟踪号
            $tracking_no = '';
            $IsUploadTrackingNumber = false;
        }

        if ($this->orderMarkLogic->assign_shipping_logistics == 2) { //指定承运商
            $logistics_channel_name = trim($this->orderMarkLogic->shipping_logistics_name);
        }

        //查询erp_shipping_log 看是否有 记录
        $shipping_log = ShippingLogModel::where(['package_id' => $package->id])->first();
        if (empty($shipping_log)) {
            $is_modify = false;
        } else {
            $is_modify = true;
        }


        $channel_resukt = ChannelModel::where('channel_id', $package->channel_id)->where('logistics_id', $package->logistics_id)->first();
        if (!empty($channel_resukt)) {
            if ($channel_resukt->is_up == 0) { //不上传追踪号
                $tracking_no = '';
                $IsUploadTrackingNumber = false;
            }
            if (!empty($logistics_channel_name)) {
                $account = AccountModel::findOrFail($package->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                switch ($driver) {
                    case 'amazon':
                        break;
                    case 'aliexpress':
                        if ($is_modify) {  //修改追踪号
                            if (time() - strtotime($shipping_log->shipping_time) < 5 * 24 * 3600) { //五天以内
                                $tracking_info = [
                                    'oldServiceName' => $shipping_log->logistics_channel_name,
                                    'oldLogisticsNo' => $shipping_log->tracking_no,
                                    'newServiceName' => $logistics_channel_name,
                                    'newLogisticsNo' => $tracking_no,
                                    'description' => 'Tracking website: ' . $package->tracking_link,
                                    'sendType' => 'all',
                                    'outRef' => $package->order->channel_ordernum,
                                    'trackingWebsite' => $package->tracking_link,
                                ];
                                $result = $channel->getJsonDataUsePostMethod('api.sellerModifiedShipment', $tracking_info);
                                $result = json_decode($result, true);
                                if (isset($result['success']) && ($result['success'] == 'true')) {
                                    $remark = '修改追踪号成功';
                                } else {
                                    $is_success = false;
                                    $remark = '修改追踪号失败' . isset($result['error_message']) ? $result['error_message'] : "error";
                                }
                            } else {
                                $remark = '无法修改追踪号,上传时间超过5天！';
                            }
                            if ($is_success) {
                                ItemModel::where('package_id', $package->id)->update(array(
                                    'is_mark' => 1,
                                    'is_upload' => 1
                                ));

                                PackageModel::where('id', $package->id)->update(array(
                                    'is_mark' => 1,
                                    'is_upload' => 1
                                ));
                            }
                        } else { //正常上传
                            $is_pass = false;
                            $item_array = [];
                            $order = OrderModel::where('id', $package->order_id)->first();
                            if (!empty($this->orderMarkLogic->expired_time)) {
                                if (strtotime($order->orders_expired_time) - time() > $this->orderMarkLogic->expired_time * 24 * 3600) { // 需要跳过
                                    $is_success = false;
                                    $is_pass = true;//跳过标记发货
                                    $remark = '未满足最后发货期' . $this->orderMarkLogic->expired_time . '天，暂时跳过标记发货';
                                }
                            }
                            if (!$is_pass) {
                                $order_status = $channel->getOrder($order->channel_ordernum);
                                if (isset($order_status['orderStatus']) && $order_status['orderStatus'] == "WAIT_BUYER_ACCEPT_GOODS") {//已经处于买家收货状态， 不需标记发货
                                    $remark = '平台状态为等待买家收货,因此变成已标记';
                                } elseif (isset($order_status['orderStatus']) && ($order_status['orderStatus'] == "WAIT_SELLER_SEND_GOODS" || $order_status['orderStatus'] == "SELLER_PART_SEND_GOODS")) {
                                    foreach ($package_items as $item) {
                                        $item_array[$item->order_item_id] = $item->order_item_id;
                                    }
                                    $tracking_info = [
                                        'serviceName' => $logistics_channel_name,
                                        'logisticsNo' => $tracking_no,
                                        'description' => 'Tracking website: ' . $package->tracking_link,
                                        'trackingWebsite' => $package->tracking_link,
                                    ];
                                    if (count($item_array) == $order->items->count()) { //包裹item 的sku种类数目==订单item的sku种类数目  意味着没有拆分订单
                                        $tracking_info['sendType'] = 'all';
                                    } else { //数量不等
                                        if ($order_status['orderStatus'] == "WAIT_SELLER_SEND_GOODS") { //说明没有进行标记发货。 sendType = part
                                            $tracking_info['sendType'] = 'part';
                                        } else { //这个已经部分发货了。 但是要确定 这次还是部分发货  sendType = part  或者 是最后一次发货 sendType = all 那么查找这个订单 已经标记发货了的包裹 sku种类相加
                                            $is_mark_item = [];
                                            $is_mark_packages = PackageModel::where('is_mark', 1)->where('order_id', $package->order_id)->get();
                                            foreach ($is_mark_packages as $is_mark_package) {
                                                foreach ($is_mark_package->items as $item) {
                                                    $is_mark_item[$item->order_item_id] = $item->order_item_id;
                                                }
                                            }
                                            if (count($is_mark_item) + count($item_array) == $order->items->count()) { //已经标记数量+本次标记数量 = 总数量 sendType = all
                                                $tracking_info['sendType'] = 'all';
                                            } else {
                                                $tracking_info['sendType'] = 'part';
                                            }
                                        }
                                    }
                                    $tracking_info['outRef'] = $order->channel_ordernum;
                                    $result = $channel->returnTrack($tracking_info);
                                    if (!$result['status']) {
                                        $is_success = false;
                                    }
                                    $remark = $result['info'];

                                } else {
                                    $is_success = false;
                                    $remark = '未知错误' . var_export($order_status, true);
                                    $result = $order_status;
                                }
                                if ($is_success) {
                                    ItemModel::where('package_id', $package->id)->update(array(
                                        'is_mark' => 1,
                                        'is_upload' => 1
                                    ));

                                    PackageModel::where('id', $package->id)->update(array(
                                        'is_mark' => 1,
                                        'is_upload' => 1
                                    ));
                                }
                            }
                        }
                        break;
                    case 'wish':
                        foreach ($package_items as $item) {
                            $order_item = $item->orderItem;
                            $channel_order_id = $order_item->channel_order_id;
                            $tracking_info = [
                                'id' => $channel_order_id,
                                'tracking_number' => $tracking_no,
                                'tracking_provider' => $logistics_channel_name,
                                'ship_note' => '',
                            ];
                            if($is_modify){ //修改追踪号
                                $tracking_info['api'] = 'https://china-merchant.wish.com/api/v2/order/modify-tracking'; //修改追踪号
                                $mark_or_upload = array(
                                    'is_mark' => 1,
                                    'is_upload' => 1
                                );
                            }else{
                                if ($this->orderMarkLogic->wish_upload_tracking_num == 1) {
                                    $tracking_info['api'] = 'https://china-merchant.wish.com/api/v2/order/modify-tracking';
                                    $mark_or_upload = array(
                                        'is_upload' => 1,
                                    );
                                    if ($item->is_upload == '1') { //已经上传过了
                                        continue;
                                    }
                                } else {
                                    $tracking_info['api'] = 'https://china-merchant.wish.com/api/v2/order/fulfill-one';
                                    $mark_or_upload = array(
                                        'is_mark' => 1,
                                    );
                                    if ($item->is_mark == '1') { //已经标记过了
                                        continue;
                                    }
                                }
                            }
                            $result = $channel->returnTrack($tracking_info);
                            if ($result['status']) {
                                ItemModel::where('id', $item->id)->update($mark_or_upload);
                                $remark = $remark . $channel_order_id . $result['info'] . ' ';
                            } else {
                                $is_success = false;
                                $remark = $remark . $channel_order_id . $result['info'] . ' ';
                            }
                        }
                        if ($is_success) { // 所有的都成功了
                            PackageModel::where('id', $package->id)->update($mark_or_upload);
                        }
                        break;
                    case 'ebay':
                        foreach ($package_items as $item) {
                            if($is_modify){ //修改追踪号
                                $mark_or_upload = array(
                                    'is_mark' => 1,
                                    'is_upload' => 1
                                );
                            }else{
                                if ($item->is_mark == '1') { //已经标记过了
                                    continue;
                                }
                                if ($IsUploadTrackingNumber) { //上传追踪号
                                    $mark_or_upload = array(
                                        'is_mark' => 1,
                                        'is_upload' => 1
                                    );
                                } else {
                                    $mark_or_upload = array(//只标记 不上传
                                        'is_mark' => 1,
                                    );
                                }
                            }
                            $tracking_info = [
                                'IsUploadTrackingNumber' => $IsUploadTrackingNumber, //true or false
                                'ShipmentTrackingNumber' => $tracking_no, //追踪号
                                'ShippingCarrierUsed' => $logistics_channel_name,//承运商
                                'ShippedTime' => date('Y-m-d\TH:i:s\Z', time()), //发货时间 date('Y-m-d\TH:i:s\Z')
                                'ItemID' => $item->orderItem->orders_item_number, //商品id
                                'TransactionID' => !empty($item->orderItem->transaction_id) ? $item->orderItem->transaction_id : 0
                            ];
                            $result = $channel->returnTrack($tracking_info);
                            if ($result['status']) {
                                $remark = $remark . $result['info'] . ' ';
                                ItemModel::where('id', $item->id)->update($mark_or_upload);
                            } else {
                                $is_success = false;
                                $remark = $remark . $result['info'] . ' ';
                            }
                        }
                        if ($is_success) {// 所有的都成功了
                            PackageModel::where('id', $package->id)->update($mark_or_upload);
                        }
                        break;
                    case 'lazada':
                        $OrderItemIds = [];
                        foreach ($package_items as $item) {
                            $order_item = $item->orderItem;
                            $temp = $order_item->transaction_id;
                            $temp = explode(',', $temp);
                            foreach ($temp as $v) {
                                $v_temp = explode('@', $v);
                                $OrderItemIds[] = $v_temp[0];
                            }
                        }
                        $OrderItemIds = array_unique($OrderItemIds);

                        $tracking_info = [
                            'TrackingNumber' => $tracking_no,
                            'ShippingProvider' => $logistics_channel_name,
                            'OrderItemIds' => implode(',', $OrderItemIds)
                        ];
                        $result = $channel->returnTrack($tracking_info);
                        if ($result['status']) {
                            ItemModel::where('package_id', $package->id)->update(array(
                                'is_mark' => 1,
                                'is_upload' => 1
                            ));
                        } else {
                            $is_success = false;
                        }
                        $remark = $result['info'];
                        if ($is_success) {
                            PackageModel::where('id', $package->id)->update(array(
                                'is_mark' => 1,
                                'is_upload' => 1
                            ));
                        }
                        break;
                    case 'cdiscount':
                        $order = OrderModel::where('id', $package->order_id)->first();
                        $productsArr = [];
                        foreach ($package_items as $item) {
                            $order_item = $item->orderItem;
                            $productsArr[] = $order_item->channel_sku;
                        }
                        $tracking_info = [
                            'OrderNumber' => $order->channel_ordernum,
                            'TrackingNumber' => $tracking_no,
                            'TrackingUrl' => $package->tracking_link,
                            'CarrierName' => $logistics_channel_name,
                            'products_info' => $productsArr
                        ];
                        $result = $channel->returnTrack($tracking_info);
                        if ($result['status']) {

                            ItemModel::where('package_id', $package->id)->update(array(
                                'is_mark' => 1,
                                'is_upload' => 1
                            ));
                            $remark = $result['info'];

                        } else {
                            $is_success = false;
                            $remark = $result['info'];

                        }
                        if ($is_success) {
                            PackageModel::where('id', $package->id)->update(array(
                                'is_mark' => 1,
                                'is_upload' => 1
                            ));
                        }
                        break;
                    default:
                        $remark = 'Could not find Channel returnTrack';
                }
                $this->result['status'] = $is_success ? 'success' : 'fail';
                $this->result['remark'] = $remark;
                if ($is_success && !empty($tracking_no)) {
                    ShippingLogModel::create([
                        'package_id' => $package->id,
                        'tracking_no' => $tracking_no,
                        'logistics_channel_name' => $logistics_channel_name,
                        'shipping_time' => date('Y-m-d H:i:s'),
                    ]);
                }

            } else {
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'Could not find logistics_channel_name.';
            }

        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Could not find logistics setting.';
        }


        //如果存在
        if (isset($tracking_info)) {
            $show_data['data'] = $tracking_info;
        }
        //如果存在
        if (isset($result)) {
            $show_data['result'] = $result;
        }
        $this->relation_id = $this->package->order_id;
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('ReturnTrack', json_encode($show_data));


    }
}
