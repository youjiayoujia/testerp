<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\Sellmore\AmaLogisticsModel as smAmaLogistics;
use App\Models\Sellmore\WishLogisticsModel as smWishLogistics;
use App\Models\Sellmore\DhgateLogisticsModel as smDhgateLogistics;
use App\Models\Sellmore\LazadaLogisticsModel as smLazadaLogistics;
use App\Models\Sellmore\AliExpressLogisticsModel as smAliExpressLogistics;
use App\Models\Sellmore\EbayLogisticsModel as smEbayLogistics;
use App\Models\Sellmore\JoomLogisticsModel as smJoomLogistics;
use App\Models\Logistics\BelongsToModel;
use App\Models\Sellmore\ShipmentModel as smShipment;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Sellmore\LogisticsModel as smChannelLogistics;
use App\Models\Channel\LogisticsModel as kChannelLogistics;


class ChannelLogistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:logistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Channel Logistics';

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
        $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
        $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $dhgate->logistics_name, 'logistics_key' => $dhgate->logistics_key]);
                $arr = [];
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smAliExpressLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $dhgate->logistics_name, 'logistics_key' => $dhgate->logistics_name]);
                $arr = [];
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smLazadaLogistics]: Origin:'.$originNum);

        /*****************************/
        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $dhgate->logistics_name, 'logistics_key' => $dhgate->logistics_name]);
                $arr = [];
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smLazadaLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Dhgate'])->first()->id;
        $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $dhgate->logistics_name, 'logistics_key' => $dhgate->logistics_name]);
                $arr = [];
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smDhgateLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
        $wishes = smWishLogistics::skip($start)->take($len)->get();
        while ($wishes->count()) {
            $start += $len;
            foreach ($wishes as $wish) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $wish->logistics_name, 'logistics_key' => $wish->logistics_name]);
                $arr = [];
                if ($wish->logisticses) {
                    foreach ($wish->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $wishes = smWishLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smWishLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;
        $originNum = 0;
        $smShipments = smShipment::all()->groupBy('shipmentCdiscountCodeID');
        foreach($smShipments as $key => $value) {
            $channelname = ChannelNameModel::create(['channel_id' => $id, 'name' => $key, 'logistics_key' => $key]);
            $logisticses = $channelname->logisticsCdiscount;
            $arr = [];
            foreach($logisticses as $logistics) {
                $originNum++;
                $arr[] = $logistics->shipmentID;
            }
            $channelname->logistics()->sync($arr);
        }
        $this->info('Transfer [smShipment-cdiscount]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;
        $originNum = 0;
        $smShipments = smShipment::all()->groupBy('shipmentAMZCode');
        foreach($smShipments as $key => $value) {
            $channelname = ChannelNameModel::create(['channel_id' => $id, 'name' => $key, 'logistics_key' => $key]);
            $logisticses = $channelname->logisticsCdiscount;
            $arr = [];
            foreach($logisticses as $logistics) {
                $originNum++;
                $arr[] = $logistics->shipmentID;
            }
            $channelname->logistics()->sync($arr);
        }
        $this->info('Transfer [smShipment-amazon]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Joom'])->first()->id;
        $wishes = smJoomLogistics::skip($start)->take($len)->get();
        while ($wishes->count()) {
            $start += $len;
            foreach ($wishes as $wish) {
                $originNum++;
                $channelName = ChannelNameModel::create(['channel_id' => $id, 'name' => $wish->logistics_name, 'logistics_key' => $wish->logistics_name]);
                $arr = [];
                if ($wish->logisticses) {
                    foreach ($wish->logisticses as $logistics) {
                        $arr[] = $logistics->shipmentID;
                    }
                }
                $channelName->logistics()->sync($arr);
            }
            $wishes = smJoomLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smJoomLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Ebay'])->first()->id;
        $originNum = 0;
        $smShipments = smShipment::all()->groupBy('shipmentCarrierInfo');
        foreach($smShipments as $key => $value) {
            $channelname = ChannelNameModel::create(['channel_id' => $id, 'name' => unserialize($key)['name'], 'logistics_key' => unserialize($key)['name']]);
            $logisticses = $channelname->logisticsEbay;
            $arr = [];
            foreach($logisticses as $logistics) {
                $originNum++;
                $arr[] = $logistics->shipmentID;
            }
            $channelname->logistics()->sync($arr);
            $key = unserialize($key);
            $channelname->update(['name' => $key['name']]);
        }
        $this->info('Transfer [smShipment-Ebay]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $wishes = smChannelLogistics::skip($start)->take($len)->get();

        while ($wishes->count()) {
            $start += $len;
            foreach ($wishes as $wish) {
                $originNum++;
                kChannelLogistics::create(['id' => $wish->methodID, 'name' => $wish->methodTitle]);
            }
            $wishes = smChannelLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smChannelLogistics]: Origin:'.$originNum);
    }
}
