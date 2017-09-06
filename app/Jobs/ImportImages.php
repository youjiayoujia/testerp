<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\ItemModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportImages extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->relation_id = $model->id;
        $this->description = 'Import ' . $model->id . ' images';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->model->oneSku()) {
            $this->result['status'] = 'success';
            $this->result['remark'] = 'Success.';
        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'Fail.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('ImportImages');
    }
}