<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;

class BoxModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversea_boxs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'boxNum',
        'fee',
        'logistics_id',
        'tracking_no',
        'width',
        'status',
        'height',
        'length',
        'weight',
        'parent_id',
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

    public function forms()
    {
        return $this->hasMany('App\Models\Oversea\BoxFormModel', 'parent_id', 'id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

    public function report()
    {
        return $this->belongsTo('App\Models\Oversea\ReportModel', 'parent_id', 'id');
    }
}
