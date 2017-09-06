<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Models\OrderModel;
use App\Models\Publish\Joom\JoomShippingModel;
use App\Models\LogisticsModel;
use App\Models\Channel\AccountModel;
use Illuminate\Console\Command;



class SetJoomToshipping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'JoomToshipping:account{accountID}';

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
        $begin = microtime(true);
            $account = AccountModel::find($account_ids);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $orders = OrderModel::where(['channel_id'=>'9'])->where("created_at",">",date('Y-m-d H:i:s',strtotime('-20 day')))->whereIn('status',['PAID','NEED','SHIPPED','PREPARED','COMPLETE'])->get();
            if(empty($orders)){
                echo "ID:".$account_ids."Account id for the data is empty！";
            }
            foreach($orders as $track){
                $channel_arr = explode("+",$track->channel_ordernum);
                $i = 0;
                foreach($channel_arr as $item){      //foreach channel_ordernum request
                    $provider = '';
                    foreach($track->packages as $p_v){
                        $channel_name = LogisticsModel::find($p_v->logistics_id);
                        foreach($channel_name->channelName as $channel_type){
                            if($channel_type->channel_id == 9){      //Joom物流承运商
                                $provider = $channel_type->name;
                            }
                        }
                    }
                    if(!$provider){
                        $provider = 'SFExpress';      //如果为空  默认为 SFExpress
                    }
                    $order_shipping = JoomShippingModel::where(['joomID'=>$item])->where("tracking_no","<>",$track->tracking_no ? $track->tracking_no : '')->first();
                    if(isset($order_shipping->id)){    //exist modify number
                         if(!isset($track->tracking_no) || !$track->tracking_no){
                            continue;
                        }
                        $modifytrack = $channel->joomApiOrdersmodifytracking($provider,$track->tracking_no,$item);
                        if(isset($modifytrack['code']) && $modifytrack['code']== 0 || isset($modifytrack['data']['success']) && $modifytrack['data']['success']== 1){
                            DB::table('joom_shipping')->where('id', $order_shipping->id)->update([
                                'tracking_no' => $track->tracking_no]);
                        }
                        continue;
                    }
                    $to_shipping = JoomShippingModel::where(['joomID'=>$item,'orderID'=>$track->id ? $track->id : ''])->first();
                    if(isset($to_shipping->id)){    //exist false
                        continue;
                    }
                    $orderList = $channel->joomApiOrdersToShipping($item,$provider,$track->tracking_no,$track->status);   //Toshipping order
                    if(isset($orderList['code']) && $orderList['code']== 0 || isset($orderList['data']['success']) && $orderList['data']['success']== 1){
                        $add = array();
                        $add = [
                            'account' => 'liufei@moonarstore.com',
                            'orderID' => $track->id,
                            'joomID' => $item,
                            'tracking_no' => $track->tracking_no,
                            'requestTime' => time(),
                            'erp_orders_status' => 1
                        ];
                        JoomShippingModel::create($add);          //request success
                    }
                    $i++;
                }
            }
        $end = microtime(true);
        echo 'time consuming ' . round($end - $begin, 3) . ' second';
    }
}
