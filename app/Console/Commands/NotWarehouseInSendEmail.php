<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase\PurchaseOrderModel;
use Mail;
use App\Models\UserModel;

class NotWarehouseInSendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEmailToPurchase:notWarehouse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $purchase_items_ary ='';
        //
        $purchaseOrders = PurchaseOrderModel::whereIn('status',['1','2','3'])->get();
        foreach($purchaseOrders as $purchaseOrder){
            $items = $purchaseOrder->purchaseItem()->get();
            if(!$items->isEmpty()){
                foreach ($items as $key => $item){
                    if($item->arrival_num > $item->storage_qty){
                        $user = UserModel::find($item->user_id);

                        $purchase_items_ary[] = [
                            'id'                => $key+1,
                            'purchase_order_id' => $item->purchase_order_id,
                            'warehouse_name'    => isset($item->warehouse->name) ? $item->warehouse->name : '',
                            'sku'               => $item->sku,
                            'arrival_num'      => $item->arrival_num,
                            'user_name'         => !empty($user) ? $user->name : '',
                            'arrival_time'      => $item->arrival_time,
                        ];
                    }
                }

            }
        }
        if(!empty($purchase_items_ary)){
            //邮件模板数据
            $data = [
                'email'              => '694929659@qq.com',
                'name'               => env('MAIL_USERNAME'),
                'purchase_items_ary' => $purchase_items_ary
            ];
            //发送邮件
            Mail::send('purchase.purchaseOrder.notWarehouseIn', $data, function($message) use($data){
                $message->to($data['email'], $data['name'])->subject('已收货未入库采购单');
            });
            $this->info('send');
        }else{
            $this->comment('no send');
        }
    }
}
