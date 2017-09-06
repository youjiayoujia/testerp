<?php

namespace App\Models\Oversea\Allotment;

use App\Models\WarehouseModel;
use App\Base\BaseModel;

class AllotmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversead_allotments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['allotment_num', 'out_warehouse_id', 'in_warehouse_id', 'logistics_id', 'allotment_by', 'status', 'check_by', 'check_status', 'created_at', 'actual_rate_value', 'tracking_no', 'remark', 'expected_date', 'fee'];

    public function getLimits()
    {
        $arr = [];
        foreach($this->boxes as $box) {
            $arr['create']['boxinfo.'.$box->id.'.length'] = 'required';
            $arr['create']['boxinfo.'.$box->id.'.width'] = 'required';
            $arr['create']['boxinfo.'.$box->id.'.height'] = 'required';
            $arr['create']['boxinfo.'.$box->id.'.weight'] = 'required';
            $arr['create']['boxinfo.'.$box->id.'.logistics_id'] = 'required';
        }

        return $arr;
    }

    //查询
    public $searchFields=['allotment_num' => '调拨单号'];

    public function outWarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'out_warehouse_id', 'id');
    }

    public function inWarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'in_warehouse_id', 'id');
    }

    public function boxes()
    {
        return $this->hasMany('App\Models\Oversea\Box\BoxModel', 'parent_id', 'id');
    }

    public function allotmentBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'allotment_by', 'id');
    }

    public function checkBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    public function allotmentForms()
    {
        return $this->hasMany('App\Models\Oversea\Allotment\AllotmentFormModel', 'parent_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        return config('oversea.allotmentStatus')[$this->status];
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\Oversea\FirstLeg\FirstLegModel', 'logistics_id', 'id');
    }

    public function getVirtualRateAttribute()
    {
        $warehouse = WarehouseModel::find($this->in_warehouse_id);
        switch($warehouse->code) {
            case 'US':
                $sum = 0;
                foreach($this->allotmentForms as $form) {
                    $sum += $form->item->declared_value * $form->item->us_rate * $form->inboxed_quantity;
                }
                return $sum;
                break;
            case 'UK':
                $sum = 0;
                foreach($this->allotmentForms as $form) {
                    $sum += $form->item->declared_value * $form->item->uk_rate * $form->inboxed_quantity;
                }
                return $sum;
                break;
            case 'US':
                $sum = 0;
                foreach($this->allotmentForms as $form) {
                    $sum += $form->item->declared_value * $form->item->us_rate * $form->inboxed_quantity;
                }
                return $sum;
                break;
            default:
                return 0;
        }
    }
}
