<?php
/**
 * 采购需求
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\SuggestFormModel;
use App\Models\Oversea\ChannelSaleModel;
use App\Models\Oversea\StockModel;

class SuggestFormController extends Controller
{
    public function __construct(SuggestFormModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('suggestForm.index');
        $this->mainTitle = '采购需求';
        $this->viewPath = 'oversea.suggestForm.';
    }

    public function createForms()
    {
        $channelSales = ChannelSaleModel::where('create_time', '>', date('Y-m-d H:i:s', strtotime('-14 days')))->get()->groupBy('account_id');
        foreach($channelSales as $channelSale) {
            foreach($channelSale->groupBy('item_id') as $singleItems) {
                $sevenSales = 0;
                $fourteenSales = 0;
                foreach($singleItems as $singleItem) {
                    $fourteenSales += $singleItem->quantity;
                    if(strtotime($singleItem->create_time) > strtotime('-7 days')) {
                        $sevenSales += $singleItem->quantity;
                    }
                }
                $single = $singleItems->first();
                $model = $this->model->where(['item_id' => $single->item_id, 'account_id' => $single->account_id])->first();
                $stock = StockModel::where(['channel_sku' => $single->channel_sku, 'account_id' => $single->account_id])->first();
                if(!$stock) {
                    continue;
                }
                if(!$model) {
                    $model = $this->model->create(['item_id' => $single->item_id,
                                          'channel_sku' => $single->channel_sku,
                                          'fnsku' => $stock->fnsku,
                                          'fba_all_quantity' => $stock->afn_warehouse_quantity,
                                          'fba_available_quantity' => $stock->afn_fulfillable_quantity,
                                          'sales_in_seven' => $sevenSales,
                                          'sales_in_fourteen' => $fourteenSales,
                                          'account_id' => $single->account_id,
                                          'suggest_quantity' => 20]);
                } else {
                    $model->update(['sales_in_seven' => $sevenSales,
                                    'fnsku' => $stock->fnsku,
                                    'fba_all_quantity' => $stock->afn_warehouse_quantity,
                                    'fba_available_quantity' => $stock->afn_fulfillable_quantity,
                                    'sales_in_fourteen' => $fourteenSales]);
                }
            }
        }

        return redirect($this->mainIndex);
    }
}