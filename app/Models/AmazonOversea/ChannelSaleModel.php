<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;

class ChannelSaleModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'channel_sku',
        'quantity',
        'account_id',
        'create_time',
    ];

    public $searchFields = [];

    protected $rules = [
        'create' => [],
        'update' => []
    ];
}
