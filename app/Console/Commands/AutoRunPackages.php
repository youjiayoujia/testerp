<?php

namespace App\Console\Commands;

use App\Jobs\AssignStocks as autoAssignStocks;
use App\Jobs\DoPackages as autoDoPackages;
use App\Jobs\AssignLogistics as autoAssignLogistics;
use App\Jobs\PlaceLogistics as autoPlaceLogistics;

use App\Models\OrderModel;
use App\Models\PackageModel;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AutoRunPackages extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoRun:packages {queueNames}';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'auto run packages to queue.';

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
        $queueNames = explode(',', $this->argument('queueNames'));
        foreach ($queueNames as $queueName) {
            switch($queueName) {
                case 'doPackages':
                    $len = 500;
                    $start = 0;
                    $count = 0;
                    $orders = OrderModel::where('status', 'PREPARED')->skip($start)->take($len)->get();
                    while($orders->count()) {
                        foreach($orders as $order) {
                            $count++;
                            $job = new autoDoPackages($order);
                            $job = $job->onQueue('doPackages');
                            $this->dispatch($job);
                            $order->eventLog('系统', '定时任务自动加入doPackages队列', json_encode($order));
                        }
                        unset($orders);
                        $start += $len;
                        $orders = OrderModel::where('status', 'PREPARED')->skip($start)->take($len)->get();
                    }
                    $this->info($count.'orders have been put into the doPackages queue');
                    break;

                case 'assignStocks':
                    $len = 500;
                    $start = 0;
                    $count = 0;
                    $packages = PackageModel::whereIn('status', ['NEW','NEED'])->where('queue_name', '!=', 'assignStocks')
                    ->whereHas('order', function($query){
                        $query->where('status', '!=', 'REVIEW');
                    })
                    ->skip($start)->take($len)->get();
                    while($packages->count()) {
                        foreach($packages as $package) {
                            $count++;
                            $package->update(['queue_name' => 'assignStocks']);
                            $job = new autoAssignStocks($package);
                            $job = $job->onQueue('assignStocks');
                            $this->dispatch($job);
                            $package->eventLog('系统', '定时任务自动加入assignStocks队列', json_encode($package));
                        }
                        unset($packages);
                        $start += $len;
                        $packages = PackageModel::whereIn('status', ['NEW','NEED'])->where('queue_name', '!=', 'assignStocks')
                        ->whereHas('order', function($query){
                            $query->where('status', '!=', 'REVIEW');
                        })
                        ->skip($start)->take($len)->get();
                    }
                    $this->info($count.'packages have been put into the assignStocks queue');
                    break;
                    
                case 'assignLogistics':
                    $len = 500;
                    $start = 0;
                    $count = 0;
                    $packages = PackageModel::whereIn('status', ['WAITASSIGN','ASSIGNFAILED'])->where('queue_name', '!=', 'assignLogistics')
                    ->whereHas('order', function($query){
                        $query->where('status', '!=', 'REVIEW');
                    })
                    ->skip($start)->take($len)->get();
                    while($packages->count()) {
                        foreach($packages as $package) {
                            $count++;
                            $package->update(['queue_name' => 'assignLogistics']);
                            $job = new autoAssignLogistics($package);
                            $job = $job->onQueue('assignLogistics');
                            $this->dispatch($job);
                            $package->eventLog('系统', '定时任务自动加入assignLogistics队列', json_encode($package));
                        }
                        unset($packages);
                        $start += $len;
                        $packages = PackageModel::whereIn('status', ['WAITASSIGN','ASSIGNFAILED'])->where('queue_name', '!=', 'assignLogistics')
                        ->whereHas('order', function($query){
                            $query->where('status', '!=', 'REVIEW');
                        })
                        ->skip($start)->take($len)->get();
                    }
                    $this->info($count.'packages have been put into the assignLogistics queue');
                    break;

                case 'placeLogistics':
                    $len = 500;
                    $start = 0;
                    $count = 0;
                    $packages = PackageModel::whereIn('status', ['ASSIGNED','TRACKINGFAILED'])->where('queue_name', '!=', 'placeLogistics')
                    ->whereHas('order', function($query){
                        $query->where('status', '!=', 'REVIEW');
                    })
                    ->skip($start)->take($len)->get();
                    while($packages->count()) {
                        foreach($packages as $package) {
                            $count++;
                            $package->update(['queue_name' => 'placeLogistics']);
                            $job = new autoPlaceLogistics($package);
                            $job = $job->onQueue('placeLogistics');
                            $this->dispatch($job);
                            $package->eventLog('系统', '定时任务自动加入placeLogistics队列', json_encode($package));
                        }
                        unset($packages);
                        $start += $len;
                        $packages = PackageModel::whereIn('status', ['ASSIGNED','TRACKINGFAILED'])->where('queue_name', '!=', 'placeLogistics')
                        ->whereHas('order', function($query){
                            $query->where('status', '!=', 'REVIEW');
                        })
                        ->skip($start)->take($len)->get();
                    }
                    $this->info($count.'packages have been put into the placeLogistics queue');
                    break;
            }
        }
    }
}