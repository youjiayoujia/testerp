<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/19
 * Time: 下午4:20
 */

namespace App\Console\Commands;

use App\Models\RoleModel;
use App\Models\User\UserRoleModel;
use App\Models\Order\EbayAmountStatisticsModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use Illuminate\Console\Command;

class UpdateEbayAmountStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebayAmountStatistics:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update EbayAmountStatistics';

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

        //EBAY销售额统计
        $roleId = RoleModel::where('role', 'ebay_staff')->first()->id;
        $userRoles = UserRoleModel::where('role_id', $roleId)->get();
        $data['channel_name'] = 'Ebay';
        foreach ($userRoles as $userRole) {
            $data['user_id'] = $userRole->user_id;
            $data['prefix'] = 0;
            $ebayPublishProducts = EbayPublishProductModel::where('seller_id', $data['user_id']);
            if ($ebayPublishProducts->count()) {
                $data['prefix'] = explode('*', $ebayPublishProducts->first()->sku)[0];
            }
            foreach ($ebayPublishProducts->get() as $ebayPublishProduct) {

            }
            $data['january_publish'] = EbayPublishProductModel::where('seller_id', $data['user_id'])
                ->whereBetween('created_at', [date('Y-m-01', strtotime(date('Y-m-d'))) . ' 00:00:00', date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-01')))) . ' 00:00:00'])
                ->where('listing_type', '!=', 'Chinese')
                ->count();
            $data['yesterday_publish'] = EbayPublishProductModel::where('seller_id', $data['user_id'])
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('listing_type', '!=', 'Chinese')
                ->count();
            $data['created_date'] = date('Y-m');
            $ebayAmountStatistics = EbayAmountStatisticsModel::where('user_id', $data['user_id'])->where('created_date', date('Y-m'));
            if ($ebayAmountStatistics->count()) {
                $ebayAmountStatistics->update([
                    'january_publish' => $data['january_publish'],
                    'yesterday_publish' => $data['yesterday_publish'],
                    'created_date' => $data['created_date'],
                ]);
            } else {
                EbayAmountStatisticsModel::create($data);
            }
        }
        $end = microtime(true);
        echo 'EBAY销售额统计更新耗时' . round($end - $begin, 3) . '秒';
    }
}