<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\StockModel as smStock;
use App\Models\Warehouse\PositionModel;
use App\Models\StockModel;

class TransferStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Stock';

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
        $originNum = 0;
        $updatedNum = 0;
        $createdNum = 0;
        $smStocks = smStock::skip($start)->take($len)->get();
        while ($smStocks->count()) {
            $start += $len;
            foreach ($smStocks as $smStock) {
                $originNum++;
                $warehouseId = $smStock->stock_warehouse_id == 1000 ? 1 : 2;
                if ($smStock->item) {
                    $tmp_stock = StockModel::where(['item_id' => $smStock->item->id, 'warehouse_id' => $warehouseId])->first();
                    if($tmp_stock) {
                        $updatedNum++;
                        $smStock->item->in($tmp_stock->warehouse_position_id, $smStock->actual_stock,
                            $smStock->item->cost * $smStock->actual_stock, 'ADJUSTMENT');
                        continue;
                    }
                    $position = PositionModel::where('name', $smStock->item->warehouse_position)->first();
                    if ($position) {
                        $createdNum++;
                        $smStock->item->in($position->id, $smStock->actual_stock,
                            $smStock->item->cost * $smStock->actual_stock, 'MAKE_ACCOUNT');
                    }
                }
            }
            $smStocks = smStock::skip($start)->take($len)->get();
        }
        $this->info('Transfer [stock]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);
    }
}
