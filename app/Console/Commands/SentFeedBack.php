<?php

namespace App\Console\Commands;
use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;

class SentFeedBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentFeedBack:account{accountIDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取速卖通需要评价的订单，然后评价订单';

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
        //265

        $accountIds = explode(',', $this->argument('accountIDs'));
        foreach ($accountIds as $accountId) {
            $account = AccountModel::findOrFail($accountId);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $page = 1;
            $is_do = true;
            do{
                $result = $channel->getSellerEvaluationOrderList($page,20);
                if($result){
                    foreach($result as $order){ //orderId
                        $channel->evaluateOrder($order['orderId']);
                    }
                    $page++;
                }else{
                    $is_do =false;
                }
            }while($is_do);
        }
    }
}
