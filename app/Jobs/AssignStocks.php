<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use App\Jobs\AssignLogistics;
use App\Jobs\PlaceLogistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignStocks extends Job implements SelfHandling, ShouldQueue
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
        $this->description = 'Package:' . $this->package->id . ' do package.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    /**
     *
     *  因为可能遇到匹配到了多个仓库，所以不能再这边放入队列
     *  所以在createPackageItems中放入队列
     */
    public function handle()
    {
        $start = microtime(true);
        if($this->package->order->status != 'REVIEW' && in_array($this->package->status, ['NEW', 'NEED'])) {
            if($this->package->is_oversea) {
                $flag = $this->package->oversea_createPackageItems();
            } else {
                $flag = $this->package->createPackageItems();
            }
            if ($flag) {
                if ($this->package->status == 'WAITASSIGN') {
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success to assign stock.';
                } elseif ($this->package->status == 'PROCESSING') { //todo:如果缺货订单匹配到了库存，不是原匹配仓库，需要匹配物流下单
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success to assign stock.';
                } elseif ($this->package->status == 'ASSIGNED') {
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'Success to assign stock.';
                }
            } else {
                $this->result['status'] = 'success';
                $this->result['remark'] = 'have no enough stocks or can\'t assign stocks.';
                $this->package->eventLog('队列', 'have no enough stocks or can\'t assign stocks.',
                    json_encode($this->package));
            }
        } else {
            $this->package->update(['queue_name' => '']);
        }

        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('assignStocks');
    }

    public function failed()
    {
        $this->package->update(['queue_name' => '']);
        $this->result['status'] = 'fail';
        $this->result['remark'] = '队列执行失败，程序错误或响应超时.';
        $this->log('assignStocks');
    }
}