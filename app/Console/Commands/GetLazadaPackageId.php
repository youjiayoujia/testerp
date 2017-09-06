<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\GetLazadaPackageId as GetLazadaPackage;

use App\Models\PackageModel;


class GetLazadaPackageId extends Command
{
    use  DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getLazadaPackageId:account{accountIDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取lazada面单需要的PackageId';

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
        $accounts = $this->argument('accountIDs');
        $accountsArr = explode(',',$accounts);
        $reuslt  = PackageModel::where('order_id', 12914)->where('is_mark',1)->where('tracking_no','')->whereIn('channel_account_id',$accountsArr)->get();

        foreach($reuslt as $package){
            $job = new GetLazadaPackage($package);
            $job = $job->onQueue('getLazadaPackageId');
            $this->dispatch($job);
        }

    }
}
