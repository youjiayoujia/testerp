<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\SupplierModel as smSupplier;
use App\Models\Product\SupplierModel;

class TransferSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:supplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Supplier';

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
        $createdNum = 0;
        $updatedNum = 0;
        $smSuppliers = smSupplier::skip($start)->take($len)->get();
        while ($smSuppliers->count()) {
            $start += $len;
            foreach ($smSuppliers as $smSupplier) {
                $originNum++;
                $supplier = [
                    'id' => $smSupplier->suppliers_id,
                    'name' => $smSupplier->suppliers_name,
                    'contact_name' => $smSupplier->suppliers_name,
                    'address' => $smSupplier->suppliers_address,
                    'company' => $smSupplier->suppliers_company,
                    'url' => $smSupplier->suppliers_website,
                    'official_url' => $smSupplier->suppliers_website,
                    'telephone' => $smSupplier->suppliers_phone,
                    'purchase_time' => $smSupplier->supplierArrivalMinDays,
                    'bank_account' => $smSupplier->suppliers_bank,
                    'bank_code' => $smSupplier->suppliers_card_number,
                    'examine_status' => $smSupplier->suppliers_status,
                    'created_at' => $smSupplier->create_time,
                    'updated_at' => $smSupplier->modify_time,
                ];
                $exist = SupplierModel::where(['id' => $smSupplier->suppliers_id])->first();
                if($exist) {
                    $exist->update($supplier);
                    $updatedNum++;
                } else {
                    SupplierModel::create($supplier);
                    $createdNum++;
                }
            }
            $smSuppliers = smSupplier::skip($start)->take($len)->get();
        }
        $this->info('Transfer [Supplier]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);
    }
}
