<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;

class SuggestFormModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_suggest_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'channel_sku',
        'fnsku',
        'fba_all_quantity',
        'fba_available_quantity',
        'all_quantity',
        'sales_in_seven',
        'sales_in_fourteen',
        'suggest_quantity',
        'account_id',
        'created_at',
    ];

    public $searchFields = ['channel_sku' => '渠道sku'];

    public function getMixedSearchAttribute()
    {
        $accounts = AccountModel::all();
        $arr = [];
        foreach($accounts as $account) {
            $arr[$account->id] = $account->account;
        }
        return [
            'relatedSearchFields' => [
                'item' => ['sku']
            ],
            'filterFields' => [],
            'filterSelects' => [
                'account_id' => $this->getArray('App\Models\Channel\AccountModel', 'account')
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach ($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }

    protected $rules = [
        'create' => [],
        'update' => []
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }
}
