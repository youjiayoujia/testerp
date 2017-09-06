<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order\RefundModel;

class AutoChangeAliOderRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliexpressRefundStatus:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '速卖通订单，退款记录小于 15 USD 批量修改状态';

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
        //状态为待审核 小于 15 USD 的速卖通订单 修改状态为 已退款
        $refund = new RefundModel;

        $refunds = $refund->getAliexpressrefunds();
        if(!$refunds->isEmpty()){
            foreach ($refunds as $item) {
                $item->process_status = 'COMPLETE';
                $item->save();
                $this->info('changed on');

            }
        }
    }
}
