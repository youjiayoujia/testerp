<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Message\StaticsticsModel;
use App\Models\Message\MessageModel;
use Carbon\Carbon;


class ComputeCrmSatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日定时执行，统计客服回复消息报表';

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
        $yesterday = new Carbon('yesterday');
        $today = new Carbon('today');

       /* $yesterday = Carbon::create(2016, 9, 5, 1);
        $today = Carbon::create(2016, 12, 1, 12);*/

        $messages = MessageModel::select('id', 'assign_id', 'status')->where('created_at', '>', $yesterday)
            ->where('created_at', '<', $today)
            ->whereNotNull('assign_id')
            ->get();
        if(! $messages->isEmpty()){
            $groups =$messages->groupBy('assign_id');
            $compute_time = $yesterday;
            foreach ($groups as $user_id => $group){
                $reply = $group->where('status', 'COMPLETE')->count('id');
                $not_reply = $group->where('status', 'UNREAD')->count('id') + $group->where('status', 'PROCESS')->count('id');
                $data[] = compact('user_id', 'reply', 'not_reply', 'compute_time');
            }
            //根据客服分组统计
        }
        if(! empty($data)){
            foreach ($data as $key => $item){
                $should_reply = 0;
                //当前用户被分配的账号
                $accounts =  AccountModel::select('id')
                    ->where('customer_service_id', $item['user_id'])
                    ->where('is_available', 1)
                    ->get();

                //计算当前用户的被分配的消息数量
                if(! $accounts->isEmpty()){
                    //dd($accounts->pluck('id'));
                    $should_reply = MessageModel::select('id')
                        ->where('created_at', '>', $yesterday)
                        ->where('created_at', '<', $today)
                        ->whereIn('account_id', $accounts->pluck('id'))
                        ->count('id');
                }
                $item['should_reply'] = $should_reply;
                $this->info('写入一条统计数据');
                StaticsticsModel::create($item);
            }
        }
    }
}
