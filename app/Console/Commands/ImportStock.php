<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use DB;
use App\Models\ItemModel;
use App\Models\StockModel;

class ImportStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:csvstock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer stock';

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
     * todo:订单优先级
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $begin = microtime(true);
        $fd = fopen('d:/stock.csv', 'r');
        $buf = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $buf[] = $row;
        }
        fclose($fd);
        if(!$buf[count($buf)-1]) {
            unset($buf[count($buf)-1]);
        }
        $arr = [];
        foreach($buf as $key => $value)
        {
            $tmp = [];
            if($key != 0) {
                foreach($value as $k => $v)
                {
                    $tmp[$buf[0][$k]] = $v;
                }
            $arr[] = $tmp;
            }
        }
        $error = [];
        foreach ($arr as $key => $stock) {
            $stock['position'] = iconv('gb2312', 'utf-8', $stock['position']);
            if (!PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->count()) {
                $error[] = $key;
                continue;
            }
            $stock['sku'] = iconv('gb2312', 'utf-8', $stock['sku']);
            $tmp_position = PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->first();
            if (!ItemModel::where(['sku' => $stock['sku']])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_item = ItemModel::where(['sku' => trim($stock['sku'])])->first();
            if (StockModel::where([
                'item_id' => $tmp_item->id,
                'warehouse_position_id' => $tmp_position->id
            ])->count()
            ) {
                $error[] = $key;
                continue;
            }
            DB::beginTransaction();
            try {
            $tmp_item->in($tmp_position->id, $stock['all_quantity'], $stock['all_quantity'] * $tmp_item->purchase_price,
                'MAKE_ACCOUNT');
            } catch(Exception $e) {
                DB::rollback();
                $error[] = $key;
            }
            DB::commit();
        }
        $end = microtime(true);
        echo 'time: ' . round($end - $begin, 3) . 'second'."\n";
        echo "error quantity:".count($error)."\n";
        if(count($error)) {
           foreach($error as $key => $row) {
                echo 'error rows:'.($row + 1) . "\n";
            } 
        }
    }
}
