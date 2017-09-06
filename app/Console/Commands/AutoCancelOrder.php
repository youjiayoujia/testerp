<?php
/**
 * 订单导入时间超过20天 系统自动撤单
 * Created by PhpStorm.
 * User: llf
 * Date: 2017-02-17
 * Time: 09:45
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\OrderModel;
class AutoCancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoCancelOrder:cancelOrder';

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


        $orders = OrderModel::whereIn('status', ['UNPAID','PAID','PREPARED','NEED','PACKED','REVIEW'])->where('created_at','<',date('Y-m-d H:i:s',strtotime("-20 days")))->get();
        if($orders->count()){
            foreach($orders as $order){
               echo $order->id.'\n';
                $result = $order->cancelOrder(10);//撤单，4为客户撤单类型
                if($result){
                     $order->eventLog('队列', '订单导入超过20天，系统自动撤单.');
                }else{
                     $order->eventLog('队列', '系统自动撤单失败.');
                }
            }
        }
    }

}