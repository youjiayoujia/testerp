<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Alibaba\Alibaba;
use App\Models\Purchase\PurchasePostageModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\AlibabaSupliersAccountModel;


class GetAliShipmentNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliShipmentName:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采购单的物流单号以及订单信息';

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
     * 采购有阿里巴巴外部单号但是外部物流单号缺失的情况，系统自动获取
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ali = new Alibaba(); //初始化阿里账号
        $ali_accounts = AlibabaSupliersAccountModel::all();
        $purchase_orders =  PurchaseOrderModel::whereIn('status',[1,2,3])->where('post_coding','!=','')->get();
        foreach ($purchase_orders as $purchase_order) {
                foreach ($ali_accounts as $account){
                    if(empty($account->access_token)){
                        continue;
                    }
                    $curl_params['access_token'] = $account->access_token;
                    $curl_params['id']           =  $purchase_order->post_coding;

                    $param['access_token']  = $curl_params['access_token'];
                    $param['id']            = $curl_params['id'];
                    $curl_params['_aop_signature'] = $ali->getSignature($param, $ali->order_list_api_url.'/'.$ali->app_key);
                    $crul_url = $ali->ali_url .'/openapi/'.$ali->order_list_api_url.'/'.$ali->app_key;
                    $order_detail = json_decode($ali->get($crul_url,$curl_params),true);

                    /**
                     * step1: 填写阿里订单信息
                     */
                    //订单价格
                    if(empty($purchase_order->alibaba_price) && !empty($order_detail['orderModel']['sumPayment'])){
                        $purchase_order->alibaba_price = $order_detail['orderModel']['sumPayment'];
                    }

                    //订单状态
                    $purchase_order->alibaba_price = !empty($order_detail['orderModel']['status']) ? $order_detail['orderModel']['status'] : null;
                    
                    /**
                     * step2: 获取物流单号
                     */
                    if(!empty($order_detail['orderModel']['logisticsOrderList'])){
                        foreach ($order_detail['orderModel']['logisticsOrderList'] as $item_logistics){
                            if(!empty($item_logistics['logisticsBillNo'])) {
                                $postage = PurchasePostageModel::where('post_coding','=',$item_logistics['logisticsBillNo'])->first();
                                if(empty($postage)){
                                    $postage_by_purcahse_order =  PurchasePostageModel::where('purchase_order_id',$purchase_order->id)->where('post_coding','!=','')->first();
                                    if(!empty($postage_by_purcahse_order)){ //存在记录的话
                                        $postage_by_purcahse_order->post_coding = $item_logistics['logisticsBillNo'];
                                        $postage_by_purcahse_order->save();
                                        $this->info('#Order:'.$purchase_order->id.' add logisticsOrderNo :'. $item_logistics['logisticsBillNo'].' insert success');
                                    }else{
                                        $new_postage = new PurchasePostageModel;
                                        $new_postage->purchase_order_id = $purchase_order->id;
                                        $new_postage->post_coding       = $item_logistics['logisticsBillNo'];
                                        //$new_postage->user_id           = $user_id;
                                        $new_postage->save();
                                        $this->info('#Order:'.$purchase_order->id.' add logisticsOrderNo :'. $item_logistics['logisticsBillNo'].' insert success');
                                    }
                                }else{ //如果已经存在此物流单号  直接break;
                                    break;
                                }
                            }
                        }
                    }
                }
            $purchase_order->save();

        }
        $this->info('finish.');
    }
}
