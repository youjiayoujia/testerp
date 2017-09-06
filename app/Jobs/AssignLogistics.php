<?php

namespace App\Jobs;

use Cache;
use Exception;
use App\Jobs\Job;
use App\Jobs\PlaceLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignLogistics extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package)
    {
        $this->package = $package;
        $this->relation_id = $this->package->id;
        $this->description = 'Package:' . $this->package->id . ' assign logistics.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if (in_array($this->package->status, ['WAITASSIGN', 'ASSIGNFAILED'])) {
            $order = $this->package->order;
            $this->package->assignLogistics();
            if (!$order->is_review) { //审核通过的订单无需再审核
                //验证黑名单
                if ($order->checkBlack()) {
                    $order->update(['status' => 'REVIEW']);
                    $order->remark('黑名单需审核.', 'BLACK');
                }
                //特殊需求
                if (!empty($order->customer_remark)) {
                    $order->update(['status' => 'REVIEW']);
                    $order->remark('特殊需求需审核.', 'REQUIRE');
                }
                //订单留言
                if ($order->messages->count() == 1 and $order->messages->first()->replies->count() == 0) {
                    $order->update(['status' => 'REVIEW']);
                    $order->remark('客户有订单留言.', 'MESSAGE');
                }
                //包裹重量大于2kg
                if ($this->package->weight >= 2) {
                    $order->update(['status' => 'REVIEW']);
                    $order->remark('包裹重量大于2kg.', 'WEIGHT');
                }
                //分渠道审核
                if($order->is_oversea) {
                    $profitRate = $order->overseaCalculateProfit();
                } else {
                    $profitRate = $order->calculateProfitProcess();
                }
                switch ($order->channel->driver) {
                    case 'amazon':
                        break;
                    case 'aliexpress':
                        if ($profitRate <= 0 or $profitRate >= 0.4) {
                            $order->update(['status' => 'REVIEW']);
                            $order->remark('速卖通订单利润率小于0或大于40%.', 'PROFIT');
                        }
                        break;
                    case 'wish':
                        if ($profitRate < 0.08) {
                            $order->update(['status' => 'REVIEW']);
                            $order->remark('WISH订单利润率小于8%.', 'PROFIT');
                        }
                        break;
                    case 'ebay':
                        if ($profitRate <= 0.05) {
                            $order->update(['status' => 'REVIEW']);
                            $order->remark('EBAY订单利润率小于或等于5%.', 'PROFIT');
                        }
                        break;
                    case 'lazada':
                        break;
                    case 'cdiscount':
                        break;
                    case 'joom':
                        break;
                }
            }
            if ($this->package->order->status != 'REVIEW') {
                if ($this->package->status == 'ASSIGNED') {
                    $this->package->update(['queue_name' => 'placeLogistics']);
                    $job = new PlaceLogistics($this->package);
                    $job = $job->onQueue('placeLogistics');
                    $this->dispatch($job);
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success.';
                    $this->package->eventLog('队列', '已匹配物流，加入下单队列', json_encode($this->package));
                } elseif ($this->package->status == 'ASSIGNFAILED') {
                    $this->package->update(['queue_name' => '']);
                    $this->result['status'] = 'success';
                    $this->result['remark'] = '未匹配到物流.';
                    $this->package->eventLog('队列', '匹配失败,未匹配到物流', json_encode($this->package));
                } elseif ($this->package->status == 'NEED') {
                    $this->package->update(['queue_name' => '']);
                    $this->result['status'] = 'success';
                    $this->result['remark'] = '已匹配到物流,缺货中，不需要提前标记发货.';
                    $this->package->eventLog('队列', '已匹配到物流,缺货中,不需要提前标记发货.', json_encode($this->package));
                }
            } else {
                $this->package->update(['queue_name' => '']);
                $this->result['status'] = 'fail';
                $this->result['remark'] = '订单需审核.';
                $this->package->eventLog('队列', '订单需审核.', json_encode($this->package));
            }
        } else {
            $this->package->update(['queue_name' => '']);
        }
        $this->lasting = round(microtime(true) - $start, 2);
        $this->log('AssignLogistics');
    }

    public function failed()
    {
        $this->package->update(['queue_name' => '']);
        $this->result['status'] = 'fail';
        $this->result['remark'] = '队列执行失败，程序错误或响应超时.';
        $this->log('AssignLogistics');
    }
}