<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;

class ImportPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:csvposition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer position';

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
        $fd = fopen('d:/position.csv', 'r');
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
        foreach($arr as $key=> $position)
        {
            $position['warehouse'] = iconv('gb2312','utf-8',$position['warehouse']);
            $position['remark'] = iconv('gb2312','utf-8',$position['remark']);
            if(!WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available'=>'1'])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_warehouse = WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available'=>'1'])->first();
            $position['name']=iconv('gb2312','utf-8',$position['name']);
            $position['warehouse_id'] = $tmp_warehouse->id;
            if(PositionModel::where(['name' => trim($position['name']), 'warehouse_id' => $position['warehouse_id']])->count()) {
                $tmp_position = PositionModel::where(['name' => trim($position['name']), 'warehouse_id' => $position['warehouse_id']])->first();
                $tmp_position->update($position);
                continue;
            }
            $tmp_position = PositionModel::create($position);
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
