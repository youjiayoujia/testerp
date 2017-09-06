<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\StockModel;
use App\Models\Stock\TakingModel;

class StockTaking extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $stock;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->description = 'Stock Taking. '.date('Y-m-d H:i:s', time());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        var_dump('begin');
        if(!Cache::store('file')->get('stockIOStatus')) {
            var_dump('taking, over...');
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'stockTaking , stock is locked.';
            $this->lasting = 0;
            $this->log('StockTaking');
        } else {
            Cache::store('file')->forever('stockIOStatus', '0');
            var_dump('lock stock...');
            $first = TakingModel::orderBy('id', 'desc')->first();
            if($first && $first->check_status == '0') {
                var_dump('fail, the prev one is ing');
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'the prev one is ing';
                $this->log('StockTaking');
            } else {
                $taking = TakingModel::create(['taking_id'=>'PD'.time()]);
                $stocks_arr = StockModel::all()->chunk(1000);
                var_dump('create form info,please wait...');
                $len = 100;
                $start = 0;
                $stocks = StockModel::skip($start)->take($len)->get();
                while($stocks->count()) {
                    foreach($stocks as $stock) 
                    {
                        $stock->stockTakingForm()->create(['stock_taking_id'=>$taking->id]);
                    }
                    $start += $len;
                    unset($stocks);
                    $stocks = StockModel::skip($start)->take($len)->get();
                }
                $this->result['status'] = 'success';
                $this->result['remark'] = 'success. create taking';
                $this->log('StockTaking');
            }
        }
    }
}