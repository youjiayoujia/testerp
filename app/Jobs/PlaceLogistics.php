<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\AssignStocks;

class PlaceLogistics extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $package;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($package, $type = null)
    {
        $this->package = $package;
        $this->type = $type;
        $this->relation_id = $this->package->id;
        $this->description = 'Package:' . $this->package->id . ' place logistics order.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if (!$this->package->is_oversea && $this->package->order->status != 'REVIEW' && in_array($this->package->status,
                ['ASSIGNED', 'TRACKINGFAILED'])
        ) {
            $result = $this->package->placeLogistics($this->type);
            if ($result['status'] == 'success') {
                $this->package->update(['queue_name' => '']);
                $this->result['status'] = 'success';
                $this->result['remark'] = 'packages tracking_no:' . $result['tracking_no'];
                $this->package->eventLog('队列', 'packages tracking_no:' . $result['tracking_no'],
                    json_encode($this->package));
            } elseif ($result['status'] == 'again') {
                $this->result['status'] = 'success';
                $this->result['remark'] = 'packages logistics_order_number:' . $result['logistics_order_number'] . ' need  get tracking_no ';
                $job = new PlaceLogistics($this->package, $this->type);
                $job = $job->onQueue('placeLogistics')->delay(600); //暂设10分钟
                $this->dispatch($job);
                $this->package->eventLog('队列',
                    'packages logistics_order_number:' . $result['logistics_order_number'] . ' need  get tracking_no ',
                    json_encode($this->package));
            } else {
                $this->package->update(['queue_name' => '']);
                $this->release();
                $this->result['status'] = 'fail';
                $this->result['remark'] = $result['tracking_no'];
                $this->package->eventLog('队列', '下单失败' . $result['tracking_no'], json_encode($this->package));
            }
        } else {
            $this->package->update(['queue_name' => '']);
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('PlaceLogistics');
    }

    public function failed()
    {
        $this->package->update(['queue_name' => '']);
        $this->result['status'] = 'fail';
        $this->result['remark'] = '队列执行失败，程序错误或响应超时.';
        $this->log('PlaceLogistics');
    }
}