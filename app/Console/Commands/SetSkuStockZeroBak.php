<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\ItemModel;
use App\Models\Log\CommandModel as CommandLog;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductList;
use App\Models\Publish\Smt\smtProductSku;

class SetSkuStockZeroBak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $skuList = ItemModel::where('is_available',1)
            ->where(function($query){
                $query->where('status','saleOutStopping')
                    ->orWhere(function($query){
                        $query->where('status','stopping');
                    });})->get();   //获取所有卖完下架和停产状态 的SKU                 
         
         foreach($skuList as $sku){
             $virtualStock = $sku->available_quantity;
             $zaitu = $sku->normal_transit_quantity;
             if($virtualStock + $zaitu <= 0){
                 $skuInfo = smtProductSku::where(['isRemove'=>0,'skuCode'=>$sku->sku])
                     ->where(function($query){
                         $query->where('ipmSkuStock','>',0);})->get();
                 if(count($skuInfo)){
                     foreach ($skuInfo as $v){
                         $start = microtime(true);
                         $total = 0;
                         $commandLog = CommandLog::create([
                             'relation_id' => $account->id,
                             'signature' => __CLASS__,
                             'description' => '系统自动下调(货源待定)产品的可售库存!',
                             'lasting' => 0,
                             'total' => 0,
                             'result' => 'init',
                             'remark' => 'init',
                         ]);
                          
                         if($v->product->productStatusType != 'onSelling'){
                             continue;
                         }
                         $account = AccountModel::findOrFail($v->product->token_id);
                         $smtApi = Channel::driver($account->channel->driver, $account->api_config);
                         if($v->product->multiattribute == 0){
                             $result = $smtApi->updateProductPublishState('api.offlineAeProduct',$v->productId);
                             if(array_key_exists('success',$result) && $result['success']){
                                 smtProductList::where('productId',$v->productId)->update(['productStatusType'=>'offline','wsDisplay'=>'user_offline']);
                                 $total = 1;
                                 $log['status'] = 'success';
                                 $log['remark'] = '商品'.$v->productId.'自动下架成功!';
                             }else{
                                 $log['status'] = 'fail';
                                 $log['remark'] = $result['error_message'];
                                 $this->error( $log['remark']);
                             }
                         }elseif($v->product->multiattribute == 1){
                             $itemSkuInfo = smtProductSku::where(['productId'=>$v->productId,'isRemove'=>0])
                             ->where( function($query){
                                 $query->where('ipmSkuStock','>',0);
                             })->get();
                             if(0 == count($itemSkuInfo)){
                                 continue;
                             }elseif(1 == count($itemSkuInfo)){
                                 $result = $smtApi->updateProductPublishState('api.offlineAeProduct',$v->productId);
                                 if(array_key_exists('success',$result) && $result['success']){
                                     smtProductList::where('productId',$v->productId)->update(['productStatusType'=>'offline','wsDisplay'=>'user_offline']);
                                 }else{
                                     if(!array_key_exists('success',$result) && $v->ipmSkuStock > 1){
                                         $data['productId'] = $v->productId;
                                         $data['ipmSkuStock'] = 1;
                                         $data['skuId'] = $v->sku_active_id;
                                         $res = $this->editSKUStocks($account,$data);
                                         $log['status'] = $res['status'];
                                         $log['remark'] = $res['remark'];
                                     }
                                 }
                             }else{
                                 $data['productId'] = $v->productId;
                                 $data['ipmSkuStock'] = 0;
                                 $data['skuId'] = $v->sku_active_id;
                                 $result = $this->editSKUStocks($account, $data);
                                 $log['status'] = $result['status'];
                                 $log['remark'] = $result['remark'];
                             }
                        }                         
                        smtProductSku::where(['productId'=>$v->productId,'skuCode'=>$sku->skuCode,'smtSkuCode'=>$v->smtSkuCode])->update(['last_turndown_date'=>date('Y-m-d H:i:s')]);
                     }
                     $end = microtime(true);
                     $lasting = round($end - $start, 3);
                     $commandLog->update([
                         'data' => '',
                         'lasting' => $lasting,
                         'total' => $total,
                         'result' => $log['status'],
                         'remark' => $log['remark'],
                     ]);
                 }   
             }             
         }
    }
    
    public function editSKUStocks(AccountModel $account,$data){
        $log = array();
        $smtApi = Channel::driver($account->channel->driver, $account->api_config);
        $result = $smtApi->editSingleSkuStock($data);
        if(array_key_exists('success',$result) && $result['success']){
            smtProductSku::where(['productId'=>$v->productId,'skuCode'=>$v->skuCode])->update(['ipmSkuStock'=>1]);
            $log['status'] = 'success';
            $log['remark'] = '下调成功!';
        }else{
            $log['status'] = 'fail';
            $log['remark'] = $result['error_message'];
        }
        return $log;
    }
}
