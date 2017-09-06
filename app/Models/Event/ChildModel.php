<?php

namespace App\Models\Event;

use App\Base\BaseModel;

class ChildModel extends BaseModel
{
    protected $table = 'event_childs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'parent_id', 'type_id', 'what', 'when', 'who', 'from_arr', 'to_arr', 'created_at'];

    public $searchFields = [''];

    public $rules = [
        'create' => [],
        'update' => []
    ];

    public function whoName()
    {
        return $this->belongsTo('App\Models\UserModel', 'who', 'id');
    }

    public function parentName()
    {
        return $this->belongsTo('App\Models\Event\CategoryModel', 'parent_id', 'id');
    }
}
