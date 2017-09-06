<?php

namespace App\Jobs;

use Tool;
use Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\ItemModel;

class GetEbayProduct extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $item_id;
    private $account_id;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($item_id,$account_id)
    {
        //
        $this->item_id = $item_id;
        $this->account_id = $account_id;
        $this->description = ' Info:[ Ebay ' . $item_id .'] 获取广告详情.';


    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        //
        $start = microtime(true);
        $account = AccountModel::find($this->account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getProductDetail($this->item_id);
        //echo $this->item_id;
        $EbaySellerCode = new EbaySellerCodeModel();
        $sellerIdInfo = $EbaySellerCode->getAllEbayCode();
        if($result){
            $sku_info = $result['sku_info'];
            $list_info = $result['list_info'];
            $list_info['account_id'] = $this->account_id;
            $list_info['status'] = 2;
            $list_info['update_time'] = date('Y-m-d H:i:s');
            $sell_code = Tool::getSellCode($list_info['sku']);
            $list_info['seller_id'] = isset($sellerIdInfo[$sell_code])?$sellerIdInfo[$sell_code]:'';
            $list_info['multi_attribute'] = count($sku_info)>1?1:0;
            $is_has = EbayPublishProductModel::where('item_id',$this->item_id)->first();
            if(empty($is_has)){
                $ebay_product = EbayPublishProductModel::create($list_info);
                if($ebay_product){
                    foreach($sku_info as $sku){
                        $sku['publish_id'] = $ebay_product->id;
                        $sku['status'] = 1;
                        $sell_code = Tool::getSellCode($sku['sku']);
                        $sku['seller_id'] = isset($sellerIdInfo[$sell_code])?$sellerIdInfo[$sell_code]:'';
                        $sku['erp_sku'] = Tool::getErpSkuBySku($sku['sku']);
                        $erp_item = ItemModel::where('sku',$sku['erp_sku'])->first();
                        if(!empty($erp_item)){
                            $sku['product_id'] =$erp_item->id;
                        }else{
                            $sku['product_id'] = 0;
                        }
                        $sku['update_time'] = date('Y-m-d H:i:s');
                        EbayPublishProductDetailModel::create($sku);
                    }
                }
            }else{
                $is_has->update($list_info);
                EbayPublishProductDetailModel::where('item_id',$this->item_id)->update(array('status'=>0));
                foreach($sku_info as $sku){
                    $sku['publish_id'] = $is_has->id;

                    $sku['status'] = 1;
                    $sell_code = Tool::getSellCode($sku['sku']);
                    $sku['seller_id'] = isset($sellerIdInfo[$sell_code])?$sellerIdInfo[$sell_code]:'';
                    $sku['erp_sku'] = Tool::getErpSkuBySku($sku['sku']);
                    $erp_item = ItemModel::where('sku',$sku['erp_sku'])->first();
                    if(!empty($erp_item)){
                        $sku['product_id'] =$erp_item->id;
                    }else{
                        $sku['product_id'] = 0;
                    }
                    $sku['update_time'] = date('Y-m-d H:i:s');
                    $is_has_sku = EbayPublishProductDetailModel::where(['item_id'=>$this->item_id,'sku'=>$sku['sku']])->first();
                    if(empty($is_has_sku)){
                        EbayPublishProductDetailModel::create($sku);
                    }else{
                        $is_has_sku->update($sku);
                    }
                }
            }
            $this->relation_id =(int)$this->item_id;
            $this->result['status'] = 'success';
            $this->result['remark'] = '成功.';

        }else{
            $this->relation_id = (int)$this->item_id;
            $this->result['status'] = 'fail';
            $this->result['remark'] = '获取详情失败.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('GetEbayProduct',isset($result)?json_encode($result):json_encode(array('获取失败')));

    }
}
