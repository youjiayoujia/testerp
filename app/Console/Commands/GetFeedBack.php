<?php

namespace App\Console\Commands;
use Channel;
use Illuminate\Console\Command;
use App\Models\Publish\Ebay\EbayFeedBackModel;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;

class GetFeedBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getFeedBack:account{accountIDs=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get channel feedback information';

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

        if($this->argument('accountIDs') == 'all'){
            $channel = ChannelModel::where('driver','=','ebay')->first();
            $accounts = $channel->accounts;
            if(!$accounts->isEmpty()){
                foreach($accounts as $account){
                    $driver = Channel::driver($account->channel->driver, $account->api_config);
                    $result = $driver->GetFeedback();

                    foreach ($result as $re){
                        $re['channel_account_id'] = $account->id;
                        $feedback = EbayFeedBackModel::where(['feedback_id'=>$re['feedback_id'],'channel_account_id'=>$account])->first();
                        if(empty($feedback)){
                            EbayFeedBackModel::create($re);
                            $this->info('#the feedback -'. $re['feedback_id'] . 'added.');
                        }
                    }
                }
            }
        }else{
            $accountIds = explode(',', $this->argument('accountIDs'));
            foreach ($accountIds as $accountId) {
                $account = AccountModel::findOrFail($accountId);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $result = $channel->GetFeedback();
                foreach($result as $re){
                    $re['channel_account_id'] = $accountId;
                    $feedback = EbayFeedBackModel::where(['feedback_id'=>$re['feedback_id'],'channel_account_id'=>$accountId])->first();
                    if(empty($feedback)){
                        EbayFeedBackModel::create($re);
                    }
                }
            }
        }
    }
}
