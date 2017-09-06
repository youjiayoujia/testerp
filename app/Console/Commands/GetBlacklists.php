<?php

namespace App\Console\Commands;

use App\Models\ChannelModel;
use App\Models\Order\BlacklistModel;
use App\Models\Order\RefundModel;
use App\Models\OrderModel;
use Illuminate\Console\Command;

class GetBlacklists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blacklists:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Blacklists';

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
        $begin = microtime(true);

        //根据邮编和收货人相同抓取黑名单用户
        $channel_id = ChannelModel::where('driver', 'wish')->first()->id;
        $orders = OrderModel::where('created_at', '<=', date('Y-m-d H:m:s'))
            ->where('created_at', '>=', date('Y-m-d H:m:s', strtotime("last year")))
            ->where('channel_id', $channel_id)
            ->get()
            ->groupBy('shipping_zipcode', 'shipping_lastname', 'shipping_firstname');
        foreach($orders as $key => $order) {
            if($order->count() >= 5) {
                $count = 0;
                foreach($order as $value) {
                    $refunds = RefundModel::where('order_id', $value->id)->get();
                    if($refunds) {
                        $count++;
                    }
                }
                if($count >= 5) {
                    foreach($order as $v) {
                        $v->update(['blacklist' => '0']);
                    }
                    $obj = OrderModel::where('shipping_zipcode', $key)->orderBy('id', 'DESC')->first();
                    $data['channel_id'] = $channel_id;
                    $data['ordernum'] = $obj->ordernum;
                    $data['name'] = trim($obj->shipping_lastname . ' ' . $obj->shipping_firstname);
                    $data['email'] = $obj->email;
                    $data['by_id'] = $obj->by_id;
                    $data['zipcode'] = $obj->shipping_zipcode;
                    $data['channel_account'] = $obj->channelAccount->account;
                    $data['type'] = 'SUSPECTED';
                    $data['remark'] = NULL;
                    $data['total_order'] = count($order);
                    $data['refund_order'] = $count;
                    $data['refund_rate'] = round(($count / count($order)) * 100) . '%';
                    $data['color'] = 'orange';
                    $blacklist = BlacklistModel::where('zipcode', $data['zipcode'])
                        ->where('name', $data['name'])
                        ->where('channel_id', $channel_id)
                        ->count();
                    if($blacklist <= 0) {
                        BlacklistModel::create($data);
                    }
                }
            }
        }

        //根据邮箱相同抓取黑名单用户
        $orders2 = OrderModel::where('created_at', '<=', date('Y-m-d H:m:s'))
            ->where('created_at', '>=', date('Y-m-d H:m:s', strtotime("last year")))
            ->where('channel_id', '!=', $channel_id)
            ->get()
            ->groupBy('email');
        foreach($orders2 as $key2 => $order2) {
            if($order2->count() >= 5) {
                $count2 = 0;
                foreach($order2 as $val) {
                    $refund = RefundModel::where('order_id', $val->id)->get();
                    if($refund) {
                        $count2++;
                    }
                }
                if($count2 >= 5) {
                    $channels2 = [];
                    foreach($order2 as $v2) {
                        $v2->update(['blacklist' => '0']);
                        if(!in_array($v2->channel_id, $channels2)) {
                            $channels2[] = $v2->channel_id;
                        }
                    }
                    foreach($channels2 as $channel2) {
                        $obj = OrderModel::where('email', $key2)->where('channel_id', $channel2)->orderBy('id', 'DESC')->first();
                        $data['channel_id'] = $channel2;
                        $data['ordernum'] = $obj->ordernum;
                        $data['name'] = trim($obj->shipping_lastname . ' ' . $obj->shipping_firstname);
                        $data['email'] = $obj->email;
                        $data['by_id'] = $obj->by_id;
                        $data['zipcode'] = $obj->shipping_zipcode;
                        $data['channel_account'] = $obj->channelAccount->account;
                        $data['type'] = 'SUSPECTED';
                        $data['remark'] = NULL;
                        $data['total_order'] = count($order2);
                        $data['refund_order'] = $count2;
                        $data['refund_rate'] = round(($count2 / count($order2)) * 100) . '%';
                        $data['color'] = 'green';
                        $blacklist = BlacklistModel::where('email', $data['email'])->where('channel_id', $channel2)->count();
                        if($blacklist <= 0) {
                            BlacklistModel::create($data);
                        }
                    }
                }
            }
        }
        $end = microtime(true);
        echo '黑名单抓取耗时' . round($end - $begin, 3) . '秒';
    }
}