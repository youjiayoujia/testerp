<?php

namespace App\Jobs;

use Cache;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\StockModel;
use App\Models\Stock\InOutModel;
use App\Models\Stock\CarryOverModel;
use DB;

class StockCarrying extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $this->time = $time;
        $this->description = 'Stock Taking. '.date('Y-m-d H:i:s', time());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        var_dump('in');
        $carryOver = CarryOverModel::orderBy('date', 'desc')->first();
        if($carryOver) {
            var_dump('has data');
            $latest = strtotime($carryOver->date);
            if($latest >= $this->time) {
                var_dump('date error, maybe is already carryOvered');
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'date error, maybe is already carryOvered';
                $this->log('StockCarrying');
                return;
            }
            $below40Days = (strtotime('now') - strtotime('-40 day'));
            if(($this->time - $below40Days) > $latest) {
                var_dump('date error, maybe the before month carryOver not done');
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'date error, maybe the before month carryOver not done';
                $this->log('StockCarrying');
                return;
            }
            $carryOverNewObj = CarryOverModel::create([
                    'date' => date('Y-m', $this->time),
                ]);
            $len = 1000;
            $start = 0;
            $carryOverForms = $carryOver->forms()->skip($start)->take($len)->get();
            while($carryOverForms->count()) {
                foreach($carryOverForms as $carryOverForm) {
                    $buf = $carryOverForm->over_quantity;
                    $buf1 = $carryOverForm->over_amount;
                    $stockIns = InOutModel::where('stock_id', $carryOverForm->stock_id)->where('outer_type', 'IN')->whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $this->time)])->get();
                    foreach($stockIns as $stockIn)
                    {
                        $buf += $stockIn->quantity;
                        $buf1 += $stockIn->amount;
                    }    
                    $stockOuts = InOutModel::where('stock_id', $carryOverForm->stock_id)->where('outer_type', 'OUT')->whereBetween('created_at', [date('Y-m-d G:i:s', strtotime($carryOver->date)), date('Y-m-d G:i:s', $this->time)])->get();
                    foreach($stockOuts as $stockOut)
                    {
                        $buf -= $stockOut->quantity;
                        $buf1 -= $stockOut->amount;
                    }  
                    $carryOverNewObj->forms()->create(['stock_id'=>$carryOverForm->stock_id, 
                                                    'purchase_price' => $carryOverForm->stock ? ($carryOverForm->stock->item ? $carryOverForm->stock->item->purchase_price : 0) : 0,
                                                    'begin_quantity' => $carryOverForm->over_quantity,
                                                    'begin_amount' => $carryOverForm->over_amount,
                                                    'over_quantity' => $buf,
                                                    'over_amount' => $buf1
                                                    ]);
                }
                $start += $len;
                $carryOverForms = $carryOver->forms()->skip($start)->take($len)->get();
            }
            $this->result['status'] = 'success';
            $this->result['remark'] = 'success.  Stock CarryOver';
            $this->log('StockCarrying');
        } else {
            var_dump('new');
            $carryOverNewObj = CarryOverModel::create([
                    'date' => date('Y-m', $this->time),
                ]);
            $len = 1000;
            $start = 0;
            $stocks = StockModel::skip($start)->take($len)->get();
            while($stocks->count()) {
                foreach($stocks as $stock)
                {
                    $carryOverNewObj->forms()->create([
                            'stock_id' => $stock->id,
                            'purchase_price' =>$stock->item ? $stock->item->purchase_price : 0,
                            'over_quantity' => $stock->all_quantity,
                            'over_amount' => $stock->all_quantity * $stock->unit_cost,
                        ]);
                }
                $start += $len;
                $stocks = StockModel::skip($start)->take($len)->get();
            }
        
            $this->result['status'] = 'success';
            $this->result['remark'] = 'success.  Stock CarryOver';
            $this->log('StockCarrying');
        }
    }
}