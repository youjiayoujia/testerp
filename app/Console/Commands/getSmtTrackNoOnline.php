<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\PackageModel;
use App\Models\Channel\AccountModel;
use App\Models\Log\CommandModel as CommandLog;

class getSmtTrackNoOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getSmtTrackNoOnline:do{logistics_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取速卖通线上发货订单的追踪号';

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
        $logistics_id = $this->argument('logistics_id');    //需要标记发货的SMT渠道ID
        //获取速卖通、包裹状态为待分配、追踪号为空的包裹信息
       $package_list = PackageModel::where(['channel_id'=>2,'status'=>'WAITASSIGN','is_upload'=>0,'tracking_no'=>'','logistics_id'=>$logistics_id])->get();
       if(count($package_list)){
           foreach($package_list as $package){
               $start = microtime(true);
               $total = 0;
               $commandLog = CommandLog::create([
                   'relation_id' => $account->id,
                   'signature' => __CLASS__,
                   'description' => '获取速卖通线上发货订单的追踪号!',
                   'lasting' => 0,
                   'total' => 0,
                   'result' => 'init',
                   'remark' => 'init',
               ]);
               $log = array();
               $log['data'] = '获取速卖通线上发货订单的追踪号';
               
               $account = AccountModel::findOrFail($package->channel_account_id);
               $smtApi = Channel::driver($account->channel->driver, $account->api_config);
               //根据订单号获取国际物流号
               $orderId = $package->order->channel_ordernum;
               $res = $smtApi->getJsonData('api.getOnlineLogisticsInfo','orderId='.$orderId.'&logisticsStatus=wait_warehouse_receive_goods');
               $logistics_info = json_decode($res,true);
               if(array_key_exists('success',$logistics_info) && $logistics_info['success']){
                   if($logistics_info['result']){
                       foreach($logistics_info['result'] as $row){
                           // 根据物流分类+物流运单号 获取国际运单号
                           if($row['internationalLogisticsType'] == $package->logistics->logistics_code && $row['onlineLogisticsId'] == $package->logistics_order_number){
                               $trackingNumber = $row['internationallogisticsId']; //国际运单号
                               break;
                           }
                       }
                       if($trackingNumber){
                           PackageModel::where('id',$package->id)->update(['tracking_no'=>$trackingNumber]);
                           $log['status'] = 'success';
                           $log['remark'] = '订单号:'.$orderId.'获取追踪号成功!';
                       }else{
                           $log['status'] = 'fail';
                           $log['remark'] = '订单号:'.$orderId.'获取追踪号失败!';
                       }
                   }
               }else{
                   $log['status'] = 'fail';
                   $log['remark'] = '订单号:'.$orderId.'获取追踪号失败!错误原因：'.$logistics_info['errorDesc'];
               }
               $end = microtime(true);
               $lasting = round($end - $start, 3);
               $commandLog->update([
                   'data' => $log['data'],
                   'lasting' => $lasting,
                   'total' => $total,
                   'result' => $log['status'],
                   'remark' => $log['remark'],
               ]);
           }
       }
    }
}
