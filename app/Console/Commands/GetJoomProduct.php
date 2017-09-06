<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Joom\JoomPublishProductModel;
use App\Models\Publish\Joom\JoomPublishProductDetailModel;
use Illuminate\Console\Command;



class GetJoomProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joomProduct:account{accountID}';

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
        $begin = microtime(true);
        $account_arr = explode(',',$account_ids);
        foreach($account_arr as $account_id){
            $account = AccountModel::find($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $hasProduct = true;
            $start = 0;
            while ($hasProduct) {
                $productList = $channel->getOnlineProduct($start, 50);
                if ($productList) {
                    foreach ($productList as $product) {
                        $is_add = true;
                        $productInfo = $product['productInfo'];
                        $variants = $product['variants'];
                        foreach ($variants as $key => $variant) {
                            $productInfo['sellerID'] = $variant['sellerID'];
                            $variants[$key]['account_id'] = $account_id;
                        }
                        $productInfo['account_id'] = $account_id;
                        $thisProduct = JoomPublishProductModel::where('productID', $productInfo['productID'])->first();

                        if ($thisProduct) {
                            $is_add = false;
                            $mark_id = $thisProduct->id;
                        }
                        if ($is_add) {    //not data create
                            $joom = JoomPublishProductModel::create($productInfo);
                            foreach ($variants as $detail) {
                                $detail['product_id'] = $joom->id;
                                $joomDetail = JoomPublishProductDetailModel::create($detail);
                            }
                        } else {         //exist update data
                            JoomPublishProductModel::where('productID', $productInfo['productID'])->update($productInfo);
                            foreach ($variants as $key1 => $item) {
                                $productDetail = JoomPublishProductModel::find($mark_id)->details;
                                if (count($variants) == count($productDetail)) {
                                    foreach ($productDetail as $key2 => $productItem) {
                                        if ($key1 == $key2) {
                                            $productItem->update($item);
                                        }
                                    }
                                } else {
                                    foreach ($productDetail as $key2 => $orderItem) {
                                        $orderItem->delete($item);
                                    }
                                    foreach ($variants as $value) {
                                        $value['product_id'] = $mark_id;
                                        JoomPublishProductDetailModel::create($value);
                                    }
                                }
                            }
                        }
                    }
                    $start++;
                } else {
                    $hasProduct = false;
                }
            }
        }
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';

    }
}
