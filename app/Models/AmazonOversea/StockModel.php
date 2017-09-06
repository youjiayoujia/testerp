<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;

class StockModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fba_stock_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'account_id',
        'channel_sku',
        'fnsku',
        'asin',
        'title',
        'mfn_fulfillable_quantity',
        'afn_warehouse_quantity',
        'afn_fulfillable_quantity',
        'afn_unsellable_quantity',
        'afn_reserved_quantity',
        'afn_total_quantity',
        'per_unit_volume',
        'afn_inbound_working_quantity',
        'afn_inbound_shipped_quantity',
        'afn_inbound_receiving_quantity'
    ];

    public $searchFields = ['channel_sku' => '渠道sku', 'fnsku' => 'fnsku'];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
                'item' => ['sku']
            ],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [
                'account' => ['account' => $this->getArray('App\Models\Channel\AccountModel', 'account')]
            ],
            'sectionSelect' => [],
        ];
    }

    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach ($inner_models as $key => $single) {
            $arr[$single->$name] = $single->$name;
        }
        return $arr;
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }
}
