<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-23
 * Time: 17:23
 */

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;


class AutoPublish extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $ebayPublishProductId;

    public function __construct($ebayPublishProductId)
    {
        $this->ebayPublishProductId = $ebayPublishProductId;
        $this->description = ' Info:[ 列表id ' . $ebayPublishProductId . '] 开始自动上架.';

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $start = microtime(true);
        //echo $this->ebayPublishProductId;
        $ebayPublishProduct = new EbayPublishProductModel();
        $result = $ebayPublishProduct->publish($this->ebayPublishProductId, 'Add', true);
        if ($result['is_success']) {
            EbayPublishProductModel::where('id', $this->ebayPublishProductId)->update([
                'status' => '2',
                'item_id' => $result['info'],
                'start_time' => date('Y-m-d H:i:s')
            ]);

            EbayPublishProductDetailModel::where('publish_id', $this->ebayPublishProductId)->update([
                'item_id' => $result['info'],
                'status' => '1',
                'start_time' => date('Y-m-d H:i:s'),
            ]);

            $this->result['status'] = 'success';
            $this->result['remark'] = '刊登成功 Item：' . $result['info'];
        } else {
            EbayPublishProductModel::where('id', $this->ebayPublishProductId)->update([
                'status' => '0',
                'note' => $result['info'],
            ]);

            $this->result['status'] = 'fail';

        }
        $this->relation_id = $this->ebayPublishProductId;
        $this->result['remark'] = $result['info'];

        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('AutoPublish', json_encode(array($result['data'])));

    }
}
