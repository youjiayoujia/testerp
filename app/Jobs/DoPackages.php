<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use App\Jobs\AssignStocks;
use App\Models\OrderModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoPackages extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'DoPackages';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->order && $this->order->status != 'REVIEW') {
            if ($this->order->status == 'PREPARED') {
                if ($this->order->channel->driver == 'ebay' and $this->order->order_is_alert != 2) {
                    if ($this->order->order_is_alert == 1) {
                        $this->order->update(['status' => 'REVIEW']);
                        $this->order->remark('EBAY订单匹配PAYPAL失败.', 'PAYPAL');
                    }
                    $this->order->eventLog('队列', 'EBAY订单需要匹配PAYPAL.');
                    $this->relation_id = $this->order->id;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'EBAY订单需要匹配PAYPAL.';
                } else {
                    $package = $this->order->createPackage();
                    if ($package) {
                        $package->update(['queue_name' => 'assignStocks']);
                        $job = new AssignStocks($package);
                        $job->onQueue('assignStocks');
                        $this->dispatch($job);
                        $this->order->update(['status' => 'PACKED']);
                        $this->relation_id = $this->order->id;
                        $this->result['status'] = 'success';
                        $this->result['remark'] = 'Success.';
                        $package->eventLog('队列', '已生成空包裹，加入匹配库存队列', json_encode($package));
                    } else {
                        $this->relation_id = 0;
                        $this->result['status'] = 'fail';
                        $this->result['remark'] = 'Fail to create virtual package.';
                    }
                }
            } else {
                $this->relation_id = 0;
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Order status is not PREPARED. Can not create package';
                $this->order->eventLog('队列', 'Order status is not PREPARED. Can not create package',
                    json_encode($this->order));
            }
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('DoPackages', json_encode($this->order));
    }

    public function failed()
    {
        $this->result['status'] = 'fail';
        $this->result['remark'] = '队列执行失败，程序错误或响应超时.';
        $this->log('DoPackages', json_encode($this->order));
    }
}