<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product\SupplierAttachmentModel;
use App\Models\Product\SupplierModel;

class changeSupplierFlienameDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changeSuppliersDirectory:do';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一次性修改原来供应商的文件目录';

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
        $suppliers = SupplierModel::where('qualifications', '<>', '')->get();
        $attachment = new SupplierAttachmentModel;
        $attachments = $attachment->all();

        if(! $suppliers->isEmpty()){
            foreach ($suppliers as $supplier){
                if(! $attachments->contains($supplier->qualifications)){
                    $supplier_id = $supplier->id;
                    $filename = $supplier->qualifications;
                    $attachment->create(compact('supplier_id', 'filename'));
                    $this->info($supplier_id . '+' . $filename);
                }
            }
        }
    }
}
