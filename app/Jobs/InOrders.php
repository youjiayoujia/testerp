<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\DoPackages;
use App\Jobs\AutoAddProduct;
use App\Models\OrderModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ChannelModel;

class InOrders extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->description = 'Put Order:[' . $this->order['channel_account_id'] . ':' . $this->order['channel_ordernum'] . '] in SYS.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderModel $orderModel)
    {
        $start = microtime(true);
        if ($this->order['status'] != 'CANCEL') { //录入新订单
            $oldOrder = $orderModel->where('channel_ordernum', $this->order['channel_ordernum'])->first();
            if (!$oldOrder) {
                $order = $orderModel->createOrder($this->order);
                if ($order) {
                    if ($order->status == 'PREPARED') {
                        if ($order->channel->driver == 'ebay' and $order->order_is_alert != 2) {
                            $order->eventLog('队列', 'EBAY订单需要匹配PAYPAL.');
                            $this->relation_id = $order->id;
                            $this->result['status'] = 'success';
                            $this->result['remark'] = 'EBAY订单需要匹配PAYPAL.';
                        } else {
                            $job = new DoPackages($order);
                            $job = $job->onQueue('doPackages');
                            $this->dispatch($job);
                            $order->eventLog('队列', '订单已加入处理队列');
                            $this->relation_id = $order->id;
                            $this->result['status'] = 'success';
                            $this->result['remark'] = 'Success.';
                        }
                    } else {
                        $this->relation_id = 0;
                        $this->result['status'] = 'success';
                        $this->result['remark'] = '订单状态不是准备发货，无法加入处理队列.';
                        $order->eventLog('队列', '订单状态不是准备发货，无法加入处理队列.');
                    }
                } else {
                    $this->relation_id = 0;
                    $this->result['status'] = 'fail';
                    $this->result['remark'] = '插入订单失败.';
                    $order->eventLog('队列', '插入订单失败.');
                }
            } else { //ebay  以前是UNPAID  现在是PAID 需要更新
                $channel = ChannelModel::where('name', 'Ebay')->first();
                if ($oldOrder->channel_id == $channel->id && $oldOrder->status == 'UNPAID' && $this->order['status'] == 'PAID') {
                    $this->order['id'] = $oldOrder->id;
                    $order = $oldOrder->updateOrder($this->order);
                    if ($order and $order->status == 'PREPARED') {
                        $job = new DoPackages($order);
                        $job = $job->onQueue('doPackages');
                        $this->dispatch($job);
                        $this->relation_id = $oldOrder->id;
                        $this->result['status'] = 'success';
                        $this->result['remark'] = '更新为已支付成功, 并加入处理队列.';
                        $order->eventLog('队列', '更新为已支付成功, 并加入处理队列.');
                    } else {
                        $this->relation_id = $oldOrder->id;
                        $this->result['status'] = 'fail';
                        $this->result['remark'] = '内部错误, 无法更新为已支付.';
                        $order->eventLog('队列', '内部错误, 无法更新为已支付.');
                    }
                } else {
                    $this->result['status'] = 'success';
                    $this->result['remark'] = '订单已存在，无需插入.';
                    $oldOrder->eventLog('队列', '订单已存在，无需插入.');
                }
            }
        } else { //客户撤单
            $order = $orderModel->where('channel_ordernum', $this->order['channel_ordernum'])->first();
            if ($order) {
                $order->cancelOrder(4);//撤单，4为客户撤单类型
                $order->eventLog('队列', '客户撤单.');
            }
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('InOrders', json_encode($this->order));
    }

    public function failed()
    {
        $this->result['status'] = 'fail';
        $this->result['remark'] = '队列执行失败，程序错误或响应超时.';
        $this->log('InOrders', json_encode($this->order));
    }
}