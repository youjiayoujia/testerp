<?php
/**
 * 渠道产品模型
 *
 * 2016-06-06
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Channel;

use App\Base\BaseModel;

class ProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $searchFields = ['channel_sku'];

    protected $rules = [
        'create' => [
            'item_id' => 'required',
            'channel_sku' => 'required',
        ],
        'update' => [
            'item_id' => 'required',
            'channel_sku' => 'required',
        ]
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }

    public function sync($channelSku)
    {
        
    }

}
