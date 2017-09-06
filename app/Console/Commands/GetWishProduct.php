<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use Illuminate\Console\Command;



class GetWishProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishProduct:get {accountID}';

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
        $start = microtime(true);
        $account = AccountModel::find($this->argument('accountID'));
        if ($account) {
          //  $account = AccountModel::findOrFail($accountID);
            $channel = Channel::driver($account->channel->driver, $account->api_config);

            $hasProduct = true;
            $start = 0;

            while ($hasProduct) {

                $productList = $channel->getOnlineProduct($start, 100);

                if ($productList) {
                    foreach ($productList as $product) {

                        $is_add =true;
                        $productInfo = $product['productInfo'];
                        $variants = $product['variants'];

                        foreach ($variants as $key => $variant) {
                            $productInfo['sellerID'] = $variant['sellerID']; //这个随便保存一个就好
                            $variants[$key]['account_id'] = $account->id;
                        }

                        $productInfo['account_id'] = $account->id;
                        $thisProduct = WishPublishProductModel::where('productID', $productInfo['productID'])->first();

                        if ($thisProduct) {
                            $is_add = false;
                            $mark_id = $thisProduct->id;
                        }

                        if ($is_add) {
                            $wish = WishPublishProductModel::create($productInfo);

                            foreach ($variants as $detail) {
                                $detail['product_id'] = $wish->id;
                                $wishDetail = WishPublishProductDetailModel::create($detail);
                            }

                        } else {

                            WishPublishProductModel::where('productID', $productInfo['productID'])->update($productInfo);
                            foreach ($variants as $key1 => $item) {
                                $productDetail = WishPublishProductModel::find($mark_id)->details;
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
                                        WishPublishProductDetailModel::create($value);
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
        echo '耗时' . round($end - $start, 3) . '秒';

    }
}
