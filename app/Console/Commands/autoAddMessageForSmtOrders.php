<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\OrderModel;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;

class autoAddMessageForSmtOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoAddMessageForSmtOrders:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动给速卖通平台订单添加留言';

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
        $channel_id = ChannelModel::where('name','AliExpress')->first()->id;
        $orderAarr = OrderModel::where(['status'=>'SHIPPED','customer_remark'=>'','channel_id'=>$channel_id])->get();      //获取状态为已发货、未留言的速卖通平台订单
        if(count($orderAarr)){
            $logistics = array('182','204'); //YW-深圳、YW-金华，这两个物流的订单需要根据物流订单号进行留言；暂时固定写
            foreach ($orderAarr as $order){
                $tracking_no = $order->packages->tracking_no;
                $tracking_url = LogisticsModel::where('id',$order->packages->logistics_id)->first()->url;
                if(in_array($order->logistics_id,$logistics)){
                    $tracking_no = $order->packages->logistics_order_number;
                }
                $account = AccountModel::findOrFail($order->channel_account_id);
                $smtApi = Channel::driver($account->channel->driver, $account->api_config);
                $data['orderId'] = $order->channel_ordernum;
                $data['buyId'] = $order->aliexpress_loginId;
                $data['comments'] = "Dear customer,
                                    Thank you for your support.
                                    Your order has been sent out now.The tracking number is :".$tracking_no." , for more info, please click:".$tracking_url." 
                                    Please kindly note it takes about 3 to 7days to update the tracking information, and it takes about 30 to 60 days to reach you. If it is not reaching you by then, please do feel free to contact us, we will find a nice solution for you asap.
                                    Hope you can receive it soon, and we look forward to serving you much better in the future. 
                                    Regards";
                $result = $smtApi->addMessageNew($data);
                if($result){
                    OrderModel::where('id',$order->id)->update(['customer_remark'=>'Add message success!']);
                }
            }
            
        }                
    }
}
