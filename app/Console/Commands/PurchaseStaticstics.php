<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;

class PurchaseStaticstics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchaseStaticstics:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'createPurchaseStaticstics';

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
        $itemModel = new ItemModel();
        $itemModel->createPurchaseStaticstics();
        $end = microtime(true);
        
    }
}
