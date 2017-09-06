<?php

namespace App\Models\Item;

use App\Base\BaseModel;

class SkuMessageModel extends BaseModel
{
    protected $table = 'sku_messages';

	protected $guarded = [];

    //查询
    public $searchFields = ['id'=>'id','question'=>'提问','answer'=>'解答'];

    public function questionUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'question_user');
    }

    public function answerUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'answer_user');
    }

    public function messageSku()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku_id');
    }

    public function skuName()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku_id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['questionUser' => ['name'], 'answerUser' => ['name'], 'messageSku'=>['sku'] , 'skuName'=>['c_name'] ],
            'filterFields' => [],
            'filterSelects' => ['question_group' => config('product.question.types'),],
            'selectRelatedSearchs' => [],
            'sectionSelect' => ['time' => ['created_at']],
        ];
    }

}
