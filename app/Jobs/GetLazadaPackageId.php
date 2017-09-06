<?php

namespace App\Jobs;

use Tool;
use Channel;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PackageModel;
use App\Models\Channel\AccountModel;


class GetLazadaPackageId extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $package;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PackageModel $package)
    {
        //
        $this->package = $package;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $start = microtime(true);

        $OrderItemIds = [];
        foreach ($this->package->items as $item) {
            $temp = $item->orderItem->transaction_id;
            $temp = explode(',', $temp);
            foreach ($temp as $v) {
                $v_temp = explode('@', $v);
                $OrderItemIds[] = $v_temp[0];
            }
        }

        $channel_listnum[] = $this->package->order->channel_listnum;
        $account = AccountModel::findOrFail($this->package->channel_account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getPackageId(implode(',', $channel_listnum));
        if ($result) {
            if (isset($result[$OrderItemIds[0]])) { // 获取到了 最踪号 和 PackageId
                $update_info = [
                    'tracking_no' => $result[$OrderItemIds[0]]['TrackingCode'],
                    'lazada_package_id' => $result[$OrderItemIds[0]]['PackageId'],
                ];
                $this->package->update($update_info);

                $this->relation_id = $this->package->id;
                $this->result['status'] = 'success';
                $this->result['remark'] = 'Success.';

            } else { //特殊情况数据记录
                $this->relation_id = $this->package->id;
                $this->result['status'] = 'fail';
                $this->result['remark'] = '数据未匹配上';
            }
        } else { //api调用失败
            $this->relation_id = $this->package->id;
            $this->result['status'] = 'fail';
            $this->result['remark'] = '调用API失败';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('GetLazadaPackageId',
            isset($channel->apiResponse) ? json_encode($channel->apiResponse) : json_encode(array('调用API失败')));

    }
}
