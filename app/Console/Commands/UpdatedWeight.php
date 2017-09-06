<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PackageModel;


class UpdatedWeight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updated:weight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updated Weight';

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
        $len = 100;
        $start = 0;
        $packages = PackageModel::whereIn('status', ['NEW','NEED','WAITASSIGN','ASSIGNED','ASSIGNFAILED','TRACKINGFAILED','PROCESSING',
    'PICKING'])->skip($start)->take($len)->get();
        $num = 0;
        while ($packages->count()) {
            foreach($packages as $package) {
                $newWeight = 0;
                foreach($package->items as $packageItem) {
                    $newWeight += $packageItem->item->weight * $packageItem->quantity;
                }
                $package->update(['weight' => $newWeight]);
            }
            $start += $len;
            $packages = PackageModel::whereIn('status', ['NEW','NEED','WAITASSIGN','ASSIGNED','ASSIGNFAILED','TRACKINGFAILED','PROCESSING',
    'PICKING'])->skip($start)->take($len)->get();
        }
    }
}
