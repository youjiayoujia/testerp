<?php
/**
 * FBA销量模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Oversea;

use App\Base\BaseModel;

class ReportModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversea_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'fba_address',
        'plan_id',
        'shipment_id',
        'reference_id',
        'shipment_name',
        'status',
        'print_status',
        'inStock_status',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'quantity',
        'created_at'
    ];

    public $searchFields = ['shipment_id' => 'shipment Id'];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
                'account' => ['account']
            ],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    public function forms()
    {
        return $this->hasMany('App\Models\Oversea\ReportFormModel', 'parent_id', 'id');
    }

    protected $rules = [
        'create' => [],
        'update' => []
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function boxes()
    {
        return $this->hasMany('App\Models\Oversea\BoxModel', 'parent_id', 'id');
    }
}
