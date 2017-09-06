<?php

namespace App\Console\Commands;


use App\Models\Order\BlacklistModel;
use App\Models\OrderModel;
use Illuminate\Console\Command;

class UpdateBlacklists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blacklists:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Blacklists';

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

        //周日更新黑名单
        foreach(BlacklistModel::all() as $blacklist) {
            if($blacklist->channel->driver == 'wish') {
                $lastname = explode(' ', $blacklist->name)[0];
                $firstname = explode(' ', $blacklist->name)[1];
                $orders = OrderModel::where('shipping_zipcode', $blacklist->zipcode)
                    ->where('shipping_lastname', $lastname)
                    ->where('shipping_firstname', $firstname)
                    ->orderBy('id', 'ASC')
                    ->get();
            }else {
                $orders = OrderModel::where('email', $blacklist->email)
                    ->orderBy('id', 'ASC')
                    ->get();
            }
            $count3 = 0;
            $ordernum = '';
            foreach($orders as $order) {
                if($order->refunds->count()) {
                    $count3++;
                }
                $ordernum = $order->ordernum;
            }
            $data['ordernum'] = $ordernum;
            $data['refund_order'] = $count3;
            $data['total_order'] = count($orders);
            $data['refund_rate'] = round(($count3 / count($orders)) * 100) . '%';
            $blacklist->update($data);
        }
        $end = microtime(true);
        echo '黑名单更新耗时' . round($end - $begin, 3) . '秒';
    }
}