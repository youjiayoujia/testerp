<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;
use App\Models\Sellmore\AmazonModel as smAmazon;
use App\Models\Sellmore\WishModel as smWish;
use App\Models\Sellmore\SmtModel as smSmt;
use App\Models\Sellmore\LazadaModel as smLazada;
use App\Models\Sellmore\CdModel as smCd;
use App\Models\Sellmore\EbayModel as smEbay;

class TransferChannelAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:channelAccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ChannelAccount';

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
        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;
        $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
        while ($smAmazons->count()) {
            $start += $len;
            foreach ($smAmazons as $smAmazon) {
                $originNum++;
                $url = parse_url($smAmazon->place_site);
                $amazon = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'account' => $smAmazon->seller_account,
                    'alias' => $smAmazon->place_name,
                    'order_prefix' => $smAmazon->seller_account,
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'amazon_api_url' => ($url['scheme']."://".$url['host']),
                    'amazon_marketplace_id' => $smAmazon->place_id,
                    'amazon_seller_id' => $smAmazon->merchant_id,
                    'amazon_accesskey_id' => $smAmazon->access_key,
                    'amazon_accesskey_secret' => $smAmazon->secret_key,
                    'is_available' => $smAmazon->status,
                ];
                $exist = AccountModel::where(['amazon_marketplace_id' => $smAmazon->place_id, 'amazon_accesskey_id' => $smAmazon->access_key])->first();
                if($exist) {
                    $exist->update($amazon);
                    $updatedNum++;
                } else {
                    AccountModel::create($amazon);
                    $createdNum++;
                }
            }
            $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
        }
        $this->info('Transfer [smAmazon]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
        $smWishes = smWish::skip($start)->take($len)->get();
        while ($smWishes->count()) {
            $start += $len;
            foreach ($smWishes as $smWish) {
                $originNum++;
                $wish = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'account' => $smWish->account_name,
                    'alias' => $smWish->account_name,
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'wish_publish_code' => $smWish->publish_code,
                    'wish_client_id' => $smWish->client_id,
                    'wish_client_secret' => $smWish->client_secret,
                    'wish_redirect_uri' => $smWish->redirect_uri,
                    'wish_refresh_token' => $smWish->refresh_token,
                    'wish_access_token' => $smWish->access_token,
                    'wish_expiry_time' => $smWish->expiry_time,
                    'wish_proxy_address' => $smWish->proxy_address ? $smWish->proxy_address : '',
                    'wish_sku_resolve' => $smWish->sku_type,
                    'is_available' => $smWish->status,
                ];

                $exist = AccountModel::where(['wish_client_id' => $smWish->client_id])->first();
                if($exist) {
                    $exist->update($wish);
                    $updatedNum++;
                } else {
                    AccountModel::create($wish);
                    $createdNum++;
                }
            }
            $smWishes = smWish::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smWish]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
        $smSmts = smSmt::skip($start)->take($len)->get();
        while ($smSmts->count()) {
            $start += $len;
            foreach ($smSmts as $smSmt) {
                $originNum++;
                $smt = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'account' => $smSmt->seller_account,
                    'alias' => $smSmt->seller_account,
                    'aliexpress_member_id' => $smSmt->member_id,
                    'aliexpress_appkey' => $smSmt->appkey,
                    'aliexpress_appsecret' => $smSmt->appsecret,
                    'aliexpress_returnurl' => $smSmt->returnurl,
                    'aliexpress_refresh_token' => $smSmt->refresh_token,
                    'aliexpress_access_token' => $smSmt->access_token,
                    'aliexpress_access_token_date' => $smSmt->access_token_date,
                    'operator_id' => $smSmt->customerservice_id,
                    'customer_service_id' => $smSmt->customerservice_id,
                    'is_available' => '1'
                ];

                $exist = AccountModel::where(['aliexpress_appkey' => $smSmt->appkey])->first();
                if($exist) {
                    $exist->update($smt);
                    $updatedNum++;
                } else {
                    AccountModel::create($smt);
                    $createdNum++;
                }
            }
            $smSmts = smSmt::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smSmt]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $smLazadas = smLazada::skip($start)->take($len)->get();
        while ($smLazadas->count()) {
            $start += $len;
            foreach ($smLazadas as $smLazada) {
                $originNum++;
                $lazada = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'account' => $smLazada->sales_account,
                    'alias' => $smLazada->sales_account,
                    'lazada_access_key' => $smLazada->Key,
                    'lazada_user_id' => $smLazada->lazada_user_id,
                    'lazada_site' => $smLazada->site,
                    'lazada_currency_type' => $smLazada->currency_type,
                    'lazada_currency_type_cn' => $smLazada->currency_type_cn,
                    'lazada_api_host' => $smLazada->api_host,
                    'is_available' => '1',
                ];

                $exist = AccountModel::where(['lazada_access_key' => $smLazada->Key])->first();
                if($exist) {
                    $exist->update($lazada);
                    $updatedNum++;
                } else {
                    AccountModel::create($lazada);
                    $createdNum++;
                }
            }
            $smLazadas = smLazada::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smLazada]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);


        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;
        $smCds = smCd::skip($start)->take($len)->get();
        while ($smCds->count()) {
            $start += $len;
            foreach ($smCds as $smCd) {
                $originNum++;
                $cd = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'account' => $smCd->sales_account,
                    'alias' => $smCd->sales_account,
                    'cd_currency_type' => $smCd->currency_type,
                    'cd_currency_type_cn' => $smCd->currency_type_cn,
                    'cd_account' => $smCd->account,
                    'cd_token_id' => $smCd->token_id,
                    'cd_pw' => $smCd->pw,
                    'cd_sales_account' => $smCd->sales_account,
                    'cd_expires_in' => $smCd->expires_in,
                    'is_available' => '1',
                ];

                $exist = AccountModel::where(['cd_account' => $smCd->account])->first();
                if($exist) {
                    $exist->update($cd);
                    $updatedNum++;
                } else {
                    AccountModel::create($cd);
                    $createdNum++;
                }
            }
            $smCds = smCd::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smCd]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $id = ChannelModel::where(['name' => 'Ebay'])->first()->id;
        $smEbays = smEbay::skip($start)->take($len)->get();
        while ($smEbays->count()) {
            $start += $len;
            foreach ($smEbays as $smEbay) {
                $originNum++;
                $ebay = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 7,
                    'sync_pages' => 100,
                    'account' => $smEbay->seller_account,
                    'alias' => $smEbay->seller_account,
                    'ebay_developer_account' => $smEbay->developer->developer_account,
                    'ebay_developer_devid' => $smEbay->developer->devid,
                    'ebay_developer_appid' => $smEbay->developer->appid,
                    'ebay_developer_certid' => $smEbay->developer->certid,
                    'ebay_token' => $smEbay->user_token,
                    'ebay_eub_developer' => $smEbay->eub_developer_id ? $smEbay->eub_developer_id : '',
                    'customer_service_id' => $smEbay->sf_order,
                    'is_available' => '1',
                ];

                $exist = AccountModel::where(['ebay_developer_account' => $smEbay->developer->developer_account, 'account' => $smEbay->seller_account])->first();
                if($exist) {
                    $exist->update($ebay);
                    $updatedNum++;
                } else {
                    AccountModel::create($ebay);
                    $createdNum++;
                }
            }
            $smEbays = smEbay::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smEbay]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);
    }
    
}
