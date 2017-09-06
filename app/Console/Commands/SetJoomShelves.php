<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Models\OrderModel;
use App\Models\Publish\Joom\JoomShippingModel;
use App\Models\Publish\Joom\JoomPublishProductDetailModel;
use App\Models\LogisticsModel;
use App\Models\Channel\AccountModel;
use Illuminate\Console\Command;



class SetJoomShelves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'JoomShelves:account{accountID}';

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
        $account_ids = $this->argument('accountID');
        if(!$account_ids){
            echo "Parameter error！";exit;  //参数不能为空
        }
        $exist_status = array('saleOutStopping', 'stopping');
        $begin = microtime(true);
            $account = AccountModel::find($account_ids);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $shelves = JoomPublishProductDetailModel::where(['enabled'=>'1'])->get();
            foreach($shelves as $s_k=>$s_v){
                if(!isset($s_v->item->id)){     //not exist sku_info
                    continue;
                }
                if(!isset($s_v->item->stocks) || empty($s_v->item->stocks) || !isset($s_v->item->status) || !in_array($s_v->item->status,$exist_status)){     //not exist sku_available_quantity
                    continue;
                }
                $available = 0;
                foreach($s_v->item->stocks as $sto_v){       //仓库只要有一个仓库有虚库存  不下架
                    if(!isset($sto_v->id)){
                        continue;
                    }
                    if(isset($sto_v->available_quantity) && $sto_v->available_quantity > 0){
                        $available += $sto_v->available_quantity;
                    }
                }
                if($available == 0){    //上架状态的sku  多个仓库虚库存小于等于0  下架
                    $res = $channel->changeProductStatusbySku($s_v->sku,'disable');
                    if(isset($res['code']) && $res['code']==0){
                        DB::table('joom_publish_product_detail')->where('sku', $s_v->sku)->update([
                            'enabled' => 0]);
                    }
                }
            }
        $end = microtime(true);
        echo 'time consuming ' . round($end - $begin, 3) . ' second';
    }
}
