<?php
/** 自动补货
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-10-25
 * Time: 14:30
 */
namespace App\Jobs;

use Tool;
use Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\ItemModel;
use App\Models\Order\ItemModel as OrderItemModel;

class AutoAddProduct extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order_items;
    protected $ordernum;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($order_items, $ordernum)
    {
        //

        /*if($order->channel_id ==$channel->id ){ //ebay 订单自动补货
            foreach($order->items as $item){
                $job = new AutoAddProduct($item,$order['ordernum']);
                $job->onQueue('autoAddProduct');
                $this->dispatch($job);
            }
        }*/
        $this->order_items = $order_items;
        $this->ordernum = $ordernum;
        $this->description = 'Order:' . $ordernum . ' ItemID:' . $this->order_items['orders_item_number'] . ' SKU:' . $this->order_items['channel_sku'] . ' add product .';

    }

    public function handle()
    {
        $start = microtime(true);
        $status = false;
        $this->relation_id = (int)$this->order_items['orders_item_number'];
        $orderItems = OrderItemModel::where('id', $this->order_items['id'])->first();
        if (isset($orderItems->item)) {
            if ($orderItems->item->status == 'selling') {
                $add_num = $this->order_items['quantity']; //订单sku 数量
                $active_num = 0;
                $account = AccountModel::find($orderItems->order->channel_account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $result = $channel->getProductDetail($this->order_items['orders_item_number']);
                if ($result) {
                    foreach ($result['sku_info'] as $ebaySku) {
                        if ($ebaySku['sku'] == $orderItems->channel_sku) {
                            $active_num = $ebaySku['quantity'];
                            break;
                        }
                    }
                    if ($active_num != 0) {
                        $add_data[$orderItems->channel_sku] = $add_num + $active_num;
                        $is_mul = count($result) > 1 ? true : false;
                        $add_result = $channel->changeQuantity($this->order_items['orders_item_number'], $add_data,
                            $is_mul, $result['list_info']['site']);
                        if ($add_result['status']) {
                            $status = true;
                            //改变erp的值
                            EbayPublishProductDetailModel::where([
                                'item_id' => $this->order_items['orders_item_number'],
                                'sku' => $orderItems->channel_sku
                            ])->update(['quantity' => $add_num + $active_num]);
                        }
                        $remark = $add_result['info'];

                    } else {
                        $remark = '与在线广告SKU匹配不成功';
                    }
                } else {
                    $remark = '同步广告详情失败,跳过补货';
                }
            } else {
                $remark = 'SKU状态不为在售';
            }
        } else {
            $remark = '未找到对应的sku';
        }


        $this->result['status'] = $status;
        $this->result['remark'] = $remark;
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('autoAddProduct', isset($result) ? json_encode($add_result) : json_encode(array($remark)));
    }
}