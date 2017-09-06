<?php

namespace App\Models\Event;

use App\Base\BaseModel;

class CategoryModel extends BaseModel
{
    protected $table = 'event_categorys';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'model_name'];

    public $searchFields = ['model_name' => 'model名'];

    public $rules = [
        'create' => [],
        'update' => []
    ];

    public function child()
    {
        return $this->hasMany('App\Models\Event\ChildModel', 'parent_id', 'id');
    }
}
