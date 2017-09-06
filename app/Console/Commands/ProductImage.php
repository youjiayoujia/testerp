<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductModel;
use App\Models\Product\ImageModel;
use App\Models\Log\QueueModel;
use App\Jobs\ImportImages;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ProductImage extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:create {type}';

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
    public function handle(ProductModel $product, QueueModel $queueModel)
    {
        ini_set('memory_limit', '2048M');
        if ($this->argument('type') == 'fail') {
            $queues = $queueModel
                ->where('queue', '=', 'ImportImages')
                ->where('result', '=', 'fail')
                ->get();
            foreach ($queues as $queue) {
                $model = $product->find($queue->relation_id);
                if ($model) {
                    $job = new ImportImages($model);
                    $job = $job->onQueue('importImages');
                    $this->dispatch($job);
                }
                $queue->forceDelete();
            }
        } else {
            $image_id = ImageModel::all()->last()->product_id;
            foreach ($product->where('id','>',$image_id)->get() as $model) {
                $job = new ImportImages($model);
                $job = $job->onQueue('importImages');
                $this->dispatch($job);
            }
        }
    }
}
