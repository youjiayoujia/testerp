<?php

namespace App\Console\Commands;

use App\Jobs\AssignStocks as autoAssignStocks;
use App\Jobs\DoPackages as autoDoPackages;
use App\Jobs\AssignLogistics as autoAssignLogistics;
use App\Jobs\PlaceLogistics as autoPlaceLogistics;

use App\Models\OrderModel;
use App\Models\PackageModel;
use App\Models\Package\ItemModel as PackageItemModel;
use App\Models\ItemModel;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TemplateWriteSku extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write:sku';

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
        $start = 0;
        $len = 1000;
        $packageItems = PackageItemModel::skip($start)->take($len)->get();
        var_dump('start....');
        while($packageItems->count()) {
            foreach($packageItems as $single) {
                $item = ItemModel::find($single->item_id);
                if($item) {
                    $single->update(['sku' => $item->sku]);
                }
            }
            $start += $len;
            $packageItems = PackageItemModel::skip($start)->take($len)->get();
            var_dump('1000  ok...');
        }
        var_dump('over');
    }
}