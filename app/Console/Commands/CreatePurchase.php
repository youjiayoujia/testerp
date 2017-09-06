<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;
use App\Models\Purchase\RequireModel;
use App\Models\Log\CommandModel as CommandLog;

class CreatePurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create PurchaseOrders';

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
        ini_set('memory_limit', '2048M');
        $start = microtime(true);
        $commandLog = CommandLog::create([
            'relation_id' => 0,
            'signature' => __CLASS__,
            'description' => '生成采购需求',
            'lasting' => 0,
            'total' => 0,
            'result' => 'init',
            'remark' => 'init',
        ]);
        
        $itemModel = ItemModel::where('is_available','1')->get();
        
        
        $i = 0;
        foreach ($itemModel as $key => $model) {
            $model->createOnePurchaseNeedData();
            $i++;
        }
        
        $end = microtime(true);
        $lasting = round($end - $start, 3);
        $result['status'] = 'success';
        $result['remark'] = 'Success.';
        $commandLog->update([
            'data' => '',
            'lasting' => $lasting,
            'total' => $i,
            'result' => $result['status'],
            'remark' => $result['remark'],
        ]);
        $this->info('采购需求数据更新耗时' . $lasting . '秒,正在自动创建采购单,请稍后......');
        

        /*$requireModel = new RequireModel();
        $requireModel->createAllPurchaseOrder();
        $endcreate = microtime(true);
        echo '采购单创建完成,耗时'.round($endcreate - $end, 3).'秒';*/
    }
}
