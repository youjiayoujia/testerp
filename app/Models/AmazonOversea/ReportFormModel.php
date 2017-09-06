<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;

class ReportFormModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversea_report_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'parent_id',
        'warehouse_position_id',
        'sku',
        'fnsku',
        'report_quantity',
        'out_quantity',
        'inbox_quantity',
        'boxNum',
        'in_quantity',
        'created_at'
    ];

    public $searchFields = [];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
            ],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    protected $rules = [
        'create' => [],
        'update' => []
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }
}
