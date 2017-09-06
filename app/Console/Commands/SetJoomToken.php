<?php

namespace App\Console\Commands;

use Tool;
use Channel;
use App\Models\Channel\AccountModel;
use Illuminate\Console\Command;



class SetJoomToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'JoomToken:account{accountID}';

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
        if(!$account_ids){
            echo "Parameter error！";exit;  //参数不能为空
        }
        $account_arr = explode(',',$account_ids);
        foreach($account_arr as $account_id){
            $account = AccountModel::find($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $expiry = $account->joom_expiry_time - time();
            if($expiry < 60*60*24*28){     //expiry
                 continue;
            }
            $url = "https://api-merchant.joom.it/api/v2/oauth/refresh_token";
            $post_data = "client_id=".$account->client_id."&client_secret=".$account->client_secret."&refresh_token=".$account->refresh_token."&grant_type=refresh_token";
            $json_data = $channel->postCurlHttpsData($url,$post_data);   //刷新token返回的信息
            if(isset($json_data->data)){
                $ret = DB::table('channel_accounts')->where('id', $account->id)->update([
                    'joom_access_token' => $json_data->data->access_token,
                    'joom_refresh_token' => $json_data->data->refresh_token,
                    'joom_expiry_time' => $json_data->data->expiry_time]);
                if($ret){
                    echo "success:refresh token:".$json_data->data->access_token;
                }else{
                    echo "success:refresh token success！";
                }
            }else{
                echo "error:refresh token error！";
            }
        }
        $end = microtime(true);
        echo ' time consuming ' . round($end - $begin, 3) . ' second';
    }
}
