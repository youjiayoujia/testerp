<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Jobs\GetEbayProduct as GetEbay;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;



class GetEbayProduct extends Command
{
    use  DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebayProduct:get {accountIDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        //378
        $account_ids = $this->argument('accountIDs');
        $account_arr = explode(',',$account_ids);
        foreach($account_arr as $account_id){
            $start = microtime(true);
            $account = AccountModel::find($account_id);
            if ($account) {
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $is_do =true;
                $i=1;
                while($is_do) {
                    $productList = $channel->getOnlineProduct($i);
                    if ($productList) {
                        foreach($productList as $key=> $itemId){
                            $job = new GetEbay($itemId,$account_id);
                            $job = $job->onQueue('getEbayProduct');
                            $this->dispatch($job);
                        }
                        $i++;
                    }else{
                        $is_do=false;
                    }
                }
                //获取近期下架的
                $start_time = date('Y-m-d',strtotime('-3 Days'));
                $end_time = date('Y-m-d',strtotime('+1 Days'));
                $endList = $channel->getSellerEvents($start_time,$end_time);
                if($endList){
                    foreach($endList as $item){
                        EbayPublishProductModel::where('item_id',$item)->update(array('status'=>3));
                        EbayPublishProductDetailModel::where('item_id',$item)->update(array('status'=>1));
                    }
                }

            }
            $end = microtime(true);
            echo '耗时' . round($end - $start, 3) . '秒';
        }
    }

}
