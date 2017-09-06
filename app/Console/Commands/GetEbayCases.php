<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use Channel;
use App\Models\ChannelModel;

class GetEbayCases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getEbayCases {accountName=all}';

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
        //暂时注释掉

        $account_name =  $this->argument('accountName');  //渠道名称

        if($account_name == 'all'){
            $channel = ChannelModel::where('driver','=','ebay')->first();
            $accounts = $channel->accounts;
            foreach ($accounts as $account){
                $this->info('#'.$account->account.'start get cases');
                $driver = Channel::driver($account->channel->driver, $account->api_config);
                $case_lists = $driver->getCases();
            }
        }else{
            $account = AccountModel::where('account',$account_name)->first();
            if(is_object($account)){
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $case_lists = $channel->getCases();
            }else{
                $this->comment('account num maybe worng.');
            }
        }
        $this->info('#finish');
    }
}
