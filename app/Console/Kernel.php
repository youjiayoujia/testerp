<?php
namespace App\Console;

use App\Models\ChannelModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\GetOrders::class,
        \App\Console\Commands\CreatePurchase::class,
        \App\Console\Commands\PurchaseStaticstics::class,
        \App\Console\Commands\Test::class,
        \App\Console\Commands\TransferProduct::class,
        \App\Console\Commands\TransferChannelAccount::class,
        \App\Console\Commands\TransferSupplier::class,
        \App\Console\Commands\TransferStock::class,
        \App\Console\Commands\UpdatedWeight::class,
        \App\Console\Commands\ImportPosition::class,
        \App\Console\Commands\ImportStock::class,
        \App\Console\Commands\TransferLogistics::class,
        \App\Console\Commands\ChannelLogistics::class,
        \App\Console\Commands\TransferUser::class,
        \App\Console\Commands\GetWishProduct::class,
        \App\Console\Commands\GetEbayProduct::class,
        \App\Console\Commands\GetAliexpressProduct::class,
        \App\Console\Commands\GetJoomProduct::class,
        \App\Console\Commands\ProductImage::class,
        \App\Console\Commands\ProductInsert::class,
        \App\Console\Commands\PickReport::class,
        \App\Console\Commands\PackReport::class,
        \App\Console\Commands\AllReport::class,
        \App\Console\Commands\GetBlacklists::class,
        \App\Console\Commands\UpdateBlacklists::class,
        \App\Console\Commands\UpdateEbaySkuSaleReport::class,
        \App\Console\Commands\UpdateEbayAmountStatistics::class,
        \App\Console\Commands\AutoRunPackages::class,
        \App\Console\Commands\ImitationOrders::class,
        \App\Console\Commands\UpdateUsers::class,
        \App\Console\Commands\ComputeCrmSatistics::class,
        \App\Console\Commands\GetMessages::class,
        \App\Console\Commands\getChannelAccountMessages::class,
        \App\Console\Commands\SendMessages::class,
        \App\Console\Commands\SetMessageRead::class,
        \App\Console\Commands\GetGmailCredentials::class,
        \App\Console\Commands\SentReturnTrack::class,
        \App\Console\Commands\MatchPaypal::class,
        \App\Console\Commands\GetLazadaPackageId::class,
        \App\Console\Commands\GetLazadaProducts::class,
        \App\Console\Commands\GetFeedBack::class,
        \App\Console\Commands\SentFeedBack::class,
        \App\Console\Commands\GetEbayCases::class,
        \App\Console\Commands\GetAliexpressIssues::class,
        \App\Console\Commands\getSellmoreSuppliers::class,
        \App\Console\Commands\SetSkuStockZero::class,
        \App\Console\Commands\SetSkuStockZeroBak::class,
        \App\Console\Commands\uploadSmtOrderOnline::class,
        \App\Console\Commands\getSmtTrackNoOnline::class,
        \App\Console\Commands\autoAddMessageForSmtOrders::class,
        \App\Console\Commands\GetAliShipmentNumber::class,
        \App\Console\Commands\AutoGetMessageAliexpress::class,
        \App\Console\Commands\AutoGetWishMessage::class,
        \App\Console\Commands\inputCrmTemplate::class, //导入CRM分类和模板
        \App\Console\Commands\inputPaypalList::class, //导入CRM分类和模板
        \App\Console\Commands\SetJoomToken::class,
        \App\Console\Commands\SetJoomToshipping::class,
        \App\Console\Commands\SetJoomShelves::class,
        \App\Console\Commands\NotWarehouseInSendEmail::class,
        \App\Console\Commands\SyncSellmoreApi::class,
        \App\Console\Commands\changeSupplierFlienameDirectory::class, //修改供应商文件目录存储
        \App\Console\Commands\AutoGetEbayMessage::class,
        \App\Console\Commands\SyncImportApi::class,
        \App\Console\Commands\AutoEbayAdd::class, //Ebay 自动补货
        \App\Console\Commands\ReduceUnuseSuppliers::class, //处理多余供货商
        \App\Console\Commands\FailMessageReplyAgain::class,
        \App\Console\Commands\AutoChangeAliOderRefund::class,
        //DHL确认发货
        \App\Console\Commands\AutoSureDHLShip::class,
        \App\Console\Commands\TemplateWriteSku::class,
        \App\Console\Commands\AutoCancelOrder::class, // 订单超过20天 自动撤单

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->command('purchase:create')->cron('20 4,12 * * *');
        $schedule->command('purchaseStaticstics:create')->cron('20 6 * * *');
        
        //黑名单定时任务
        $schedule->command('blacklists:get')->dailyAt('2:00');
        $schedule->command('blacklists:update')->dailyAt('3:00');

        //EbaySku销量报表定时任务
        $schedule->command('ebaySkuSaleReport:update')->cron('0 16 * * *');

        //EBAY销售额统计定时任务
        $schedule->command('ebayAmountStatistics:update')->cron('0 17 * * *');

        //抓单定时任务规则
        foreach (ChannelModel::all() as $channel) {
            switch ($channel->driver) {
                case 'amazon':
                    foreach ($channel->accounts->where('is_available', '1') as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'aliexpress':
                   //foreach ($channel->accounts->where('is_available', '1') as $account) {
                   //    $schedule->command('get:orders ' . $account->id)->cron('2 6,18,22 * * *');
                   //}
                   $schedule->command('sentReturnTrack:get ' . $channel->id)->cron('05 */2 * * *');
                   break;
                case 'wish':
                   //foreach ($channel->accounts->where('is_available', '1')->where('id',5) as $account) {
                   //    $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                   //}
                   $schedule->command('sentReturnTrack:get ' . $channel->id)->cron('02 * * * *');
                   break;
                case 'ebay':
                    //foreach ($channel->accounts->where('is_available', '1') as $account) {
                    //    $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    //}
                    $schedule->command('sentReturnTrack:get ' . $channel->id)->cron('02 * * * *');
                    break;
                case 'lazada':
                    foreach ($channel->accounts->where('is_available', '1') as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'cdiscount':
                    foreach ($channel->accounts->where('is_available', '1') as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
                case 'joom':
                    foreach ($channel->accounts->where('is_available', '1') as $account) {
                        $schedule->command('get:orders ' . $account->id)->everyThirtyMinutes();
                    }
                    break;
            }
        }
        //订单导入时间超过20天 系统自动撤单
        $schedule->command('autoCancelOrder:cancelOrder')->hourly();
        //包裹报表
        $schedule->command('pick:report')->hourly();
        $schedule->command('all:report')->daily();
        //CRM
        $schedule->command('import:message aliexpress')->cron('40 8,15 * * *'); //aliexpress
        $schedule->command('import:message ebay')->everyFiveMinutes(); //ebay
        $schedule->command('import:message wish')->hourly(); //wish
        $schedule->command('getEbayCases')->cron('30 8,12,13,14,16,17 * * *');
        $schedule->command('getFeedBack:account')->everyTenMinutes();
        $schedule->command('reply:again all')->everyThirtyMinutes();
        //采购
        $schedule->command('aliShipmentName:get')->hourly();
        $schedule->command('sendEmailToPurchase:notWarehouse')->cron('15 4 * * *');
        //API同步sellmore database
        $schedule->command('SyncSellmoreApi:all')->everyFiveMinutes();
        $schedule->command('SyncImportApi:all')->everyFiveMinutes();
        //半小时一次将包裹放入队列
        $schedule->command('autoRun:packages doPackages,assignStocks,assignLogistics,placeLogistics')->everyThirtyMinutes();
        //财务
        $schedule->command('aliexpressRefundStatus:change')->cron('0 21 * * *');//速卖通退款小于15美金  21：00 执行
        //DHL
        $schedule->command('dhl:sureShip')->daily();
        //匹配paypal
        $schedule->command('match:account all')->cron('*/20 * * * *');
        //邮件回复统计
        $schedule->command('compute:start')->cron('0 01 * * *'); //凌晨一点
    }
}