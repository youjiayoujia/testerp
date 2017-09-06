<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/9
 * Time: 下午3:10
 */

namespace App\Console\Commands;

use App\Models\ItemModel;
use App\Models\ChannelModel;
use App\Models\Order\EbaySkuSaleReportModel;
use App\Models\Order\ItemModel as orderItem;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use Illuminate\Console\Command;

class UpdateEbaySkuSaleReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebaySkuSaleReport:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update EbaySkuSaleReport';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $begin = microtime(true);

        //EbaySku销量报表
        $channelId = ChannelModel::where('driver', 'ebay')->first()->id;
        $ebayPublishProducts = EbayPublishProductModel::all();
        foreach ($ebayPublishProducts as $ebayPublishProduct) {
            $data['sku'] = substr(strstr(strstr($ebayPublishProduct->sku, '*'), '[', true), 1);
            $data['channel_name'] = 'Ebay';
            $data['site'] = $ebayPublishProduct->site_name;
            $data['one_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))) . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['seven_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['fourteen_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-14 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['sale_different'] = $data['seven_sale'] - ($data['fourteen_sale'] - $data['seven_sale']);
            if ($data['fourteen_sale'] - $data['seven_sale'] == 0) {
                $data['sale_different_proportion'] = 0;
            } else {
                $data['sale_different_proportion'] = $data['sale_different'] / ($data['fourteen_sale'] - $data['seven_sale']);
            }
            $data['thirty_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['ninety_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-90 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['created_time'] = null;
            $data['status'] = null;
            $item = ItemModel::where('sku', $data['sku'])->first();
            if ($item) {
                $data['created_time'] = $item->created_at;
                $data['status'] = $item->status;
            }
            $data['is_warning'] = '1';
            if ($data['status'] == 'stopping') {
                $data['is_warning'] = '0';
            }
            $ebaySkuSaleReports = EbaySkuSaleReportModel::where('sku', $data['sku'])->where('site', $data['site']);
            if ($ebaySkuSaleReports->count()) {
                $ebaySkuSaleReports->update([
                    'sale_different' => $data['sale_different'],
                    'sale_different_proportion' => $data['sale_different_proportion'],
                    'one_sale' => $data['one_sale'],
                    'seven_sale' => $data['seven_sale'],
                    'fourteen_sale' => $data['fourteen_sale'],
                    'thirty_sale' => $data['thirty_sale'],
                    'ninety_sale' => $data['ninety_sale'],
                    'created_time' => $data['created_time'],
                    'status' => $data['status'],
                    'is_warning' => $data['is_warning']
                ]);
            } else {
                EbaySkuSaleReportModel::create($data);
            }
        }
        $end = microtime(true);
        echo 'EbaySku销量报表更新耗时' . round($end - $begin, 3) . '秒';
    }
}