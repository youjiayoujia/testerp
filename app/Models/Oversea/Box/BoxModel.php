<?php

namespace App\Models\Oversea\Box;

use App\Base\BaseModel;

class BoxModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversead_boxes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['boxnum','parent_id','length', 'width', 'height','logistics_id','tracking_no', 'weight','created_at', 'shipped_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['boxnum' => '箱号'];

    public function forms()
    {
        return $this->hasMany('App\Models\Oversea\Box\BoxFormModel', 'parent_id', 'id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\Oversea\FirstLeg\FirstLegModel', 'logistics_id', 'id');
    }

    public function getExpectedFeeAttribute()
    {
        if($this->logistics) {
            return $this->getVolumnWeight() * $this->firstleg_price;
        } else {
            return 0;
        }
    }

    public function getVolumnWeight()
    {
        $sum = 0;
        foreach($this->forms as $form) {
            $sum += $form->item->volumn_rate * $form->quantity * $form->item->weight;
        }
        return $sum;
    }

    public function getFirstlegPriceAttribute()
    {
        $logistics = $this->logistics;
        if(!$logistics) {
            return 0;
        }
        $weight = $this->getVolumnWeight();
        foreach($logistics->forms()->orderBy('weight_from')->get() as $form) {
            if($form->weight_from <= $weight && $form->weight_to >= $weight) {
                return $form->cost;
            }
        }
        return 0;
    }
}
