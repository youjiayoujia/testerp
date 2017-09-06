<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\PackageModel;
use App\Jobs\ReturnTrack;
use App\Models\Order\OrderMarkLogicModel;

use Illuminate\Foundation\Bus\DispatchesJobs;



class SentReturnTrack extends Command
{
    use  DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentReturnTrack:get {channel_id}';

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
        //
        $packages  = new PackageModel();
        $channel_id =  $this->argument('channel_id');
        $result =  OrderMarkLogicModel::where('channel_id',$channel_id)->where('is_use',1)->orderBy('priority','desc')->get();
        if(!empty($result)){
            foreach($result as $re){

                if($re->wish_upload_tracking_num==1){
                    $list = $packages->where('channel_id', $channel_id)->where('tracking_no','!=','' );
                    $list->where(['is_mark'=>1,'is_upload'=>0,]); //已标 未上传
                }else{
                    $list = $packages->where('channel_id', $channel_id);
                    $list->where('is_mark',0); //未标记
                }

                if(!empty($re->order_status)){ //订单状态
                    $order_status = json_decode($re->order_status,true);
                    $order_create ='';
                    $order_pay ='';
                    $expired_time = '';
                    if(!empty($re->order_create)){ //订单创建时间
                        $order_create = date('Y-m-d H;i:s',strtotime(- $re->order_create." hour"));
                    }
                    if(!empty($re->order_pay)){//订单付款时间
                        $order_pay = date('Y-m-d H;i:s',strtotime(- $re->order_pay." hour"));
                    }
                    $list = $list->whereHas('order', function ($query) use ($order_status,$order_create,$order_pay,$expired_time) {
                        $query = $query->whereIn('status', $order_status);
                        if(!empty($order_create)){
                            $query = $query->where('orders.created_at','>=' ,$order_create);
                        }
                        if(!empty($order_pay)){
                            $query = $query->where('payment_date','>=' ,$order_pay);
                        }
                      /*  if(!empty($expired_time)){
                            $query = $query->where('orders_expired_time','<=' ,$expired_time);
                        }*/

                        //$query = $query->where('created_at','<=' , date('Y-m-d H;i:s',strtotime(" -30 days"))); //30天以内的订单

                    });
                }
                $packages_result = $list->orderBy('id','desc')->get();
                foreach($packages_result as $package){
                    $job = new ReturnTrack($package,$re);
                    $job = $job->onQueue('returnTrack');
                    $this->dispatch($job);
                }


            }


        }else{
            echo 'empty';
        }

      exit;
    }
}
