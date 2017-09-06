<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use DB;

class ReduceUnuseSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:reduceSuppliers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除冗余供应商';

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
        $groups = SupplierModel::all()->groupBy('company');


        foreach ($groups as $group_key => $group){
            if($group->count() > 1){   //如果有多个相同的供货商
                foreach($group as $key => $supplier){
                    if($key == 0){ //保留id最小的供货商，然后把其他的 sku 关联到 最小的供货商id  其余的全删除
                        $correct_supplier_id = $supplier->id;
                    }else{
                        $sql = 'update items set supplier_id = ' . $correct_supplier_id . ' where supplier_id = ' . $supplier->id;
                        DB::update($sql);
                        //更换备选
                        DB::update('update item_prepare_suppliers set supplier_id = ' . $correct_supplier_id . ' where supplier_id = ' . $supplier->id);
                        $this->info('#一行数据处理中...');
                        $supplier->delete(); //删除多余
                    }
                }
            }
        }
        $this->info('#处理完毕。');
    }
}
