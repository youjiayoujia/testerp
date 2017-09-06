<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyncApiModel;
use Tool;

class SyncSellmoreApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncSellmoreApi:all';

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
        $datas = SyncApiModel::where('status',0)->get(); //获取未同步记录
        if(!$datas->isEmpty()){
            foreach ($datas as $data){
                $result = Tool::postCurlHttpsData($data->url,unserialize($data->data));
                $res_ary = json_decode($result,true);
                if($res_ary['status'] == 'success'){
                    $data->status = 1;
                    $data->save();
                    $this->info('#'.$data->relations_id.'has to sync sellmore databsae');
                }else{
                    $data->times     = $data->times +1;
                    $data->error_msg = isset($res_ary['message']) ? $res_ary['message'] : '';
                    $data->save();
                    $this->comment('#'.$data->relations_id.'has not to sync sellmore database');
                }
            }
        }
    }
}
