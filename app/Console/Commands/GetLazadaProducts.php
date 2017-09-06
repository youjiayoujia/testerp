<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Lazada\erpLazadaProduct;

class GetLazadaProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getLazadaProducts:account{accountID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取lazada产品';

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
        $start = microtime(true);
        $account = AccountModel::find($this->argument('accountID'));
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        for($i=1;$i>0;$i++){
            $offset = ($i-1)*100;
            $result = $channel->getLazadaProducts($offset);           
            if(!(isset($result['Body']['Products']))){
                break;
            }
            
            if(empty($result['Body']['Products'])){
                break;
            }
            
            $resultProducts = $result['Body']['Products']['Product'];    
            foreach($resultProducts as $productInfo){
                $sku = $this->getSkuCode($productInfo['SellerSku']);
                $productInfo['sku'] = trim($sku);
                $productInfo['account'] = $account->account;
                $res = erpLazadaProduct::where(['sellerSku'=>$productInfo['SellerSku'],'account'=>$account->account])->first();
                
                //从lazada上获取的产品的Variation数据理论上string,但是不知为何有些返回为array
                if(is_array($productInfo['Variation']) && !empty($productInfo['Variation'])){
                    $variation = json_encode($productInfo['Variation']);
                }elseif(!empty($productInfo['Variation'])){
                    $variation = $productInfo['Variation'];
                }else{
                    $variation = '';
                }
                
                if(is_array($productInfo['ProductId']) && !empty($productInfo['ProductId'])){
                    $productId = json_encode($productInfo['ProductId']);
                }elseif(!empty($productInfo['ProductId'])){
                    $productId = $productInfo['ProductId'];
                }else{
                    $productId = '';
                }
                
                if($res){
                    $updateArr = array();
                    //$updateArr['sellerSku'] = $productInfo['SellerSku'];
                    $updateArr['shopSku'] = empty($productInfo['ShopSku']) ? '' : $productInfo['ShopSku'];
                    //$updateArr['sku'] = $productInfo['sku'];
                    $updateArr['name'] = addslashes($productInfo['Name']);
                    $updateArr['variation'] = $variation;
                    $updateArr['quantity'] = $productInfo['Quantity'];
                    $updateArr['price'] = $productInfo['Price'];
                    $updateArr['salePrice'] = empty($productInfo['SalePrice']) ? 0 : $productInfo['SalePrice'];                        
                    $updateArr['saleStartDate'] = empty($productInfo['SaleStartDate'] ? '0000-00-00 00:00:00' : $productInfo['SaleStartDate']);
                    $updateArr['saleEndDate'] = empty($productInfo['SaleEndDate'] ? '0000-00-00 00:00:00' : $productInfo['SaleEndDate']);
                    $updateArr['status'] = $productInfo['Status'];
                    $updateArr['account'] = $productInfo['account'];
                    $updateArr['productId'] = $productId;
                    erpLazadaProduct::where('id',$res['id'])->update($updateArr);
                }else{
                    $addArr = array();
                    $addArr['sellerSku'] = $productInfo['SellerSku'];
                    $addArr['shopSku'] = empty($productInfo['ShopSku']) ? '' : $productInfo['ShopSku'];
                    $addArr['sku'] = $productInfo['sku'];
                    $addArr['name'] = $productInfo['Name'];
                    $addArr['variation'] = $variation;
                    $addArr['quantity'] = $productInfo['Quantity'];
                    $addArr['price'] = $productInfo['Price'];
                    $addArr['salePrice'] = $productInfo['SalePrice'];
                    $addArr['saleStartDate'] = $productInfo['SaleStartDate'];
                    $addArr['saleEndDate'] = $productInfo['SaleEndDate'];
                    $addArr['status'] = $productInfo['Status']; 
                    $addArr['productId'] = $productId;
                    $addArr['account'] = $productInfo['account'];
                    erpLazadaProduct::create($addArr);
                }           
            }            
        }
        $end = microtime(true);
        echo ' Running time ' . round($end - $start, 3) . ' seconds';
    }
    
    public function getSkuCode($skuCode){
        $sku = '';
        if(strstr($skuCode,"*")){
            $sku = explode("*",$skuCode);
            return $sku[1];
        }else{
            return $skuCode;
        }
    }
}
