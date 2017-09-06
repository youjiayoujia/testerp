<?php

namespace App\Models;

use App\Base\BaseModel;

class LabelModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'labels';

    protected $fillable = ['name','group_id'];

	public $rules = [
	        'create' => [
	            'name' => 'required|unique:users,email,{id}',
	            'group_id' => 'required',
	        ],
	        'update' => [
	            'name' => 'required|unique:users,email,{id}',
	            'group_id' => 'required',
	        ]
	    ];
	    
	public $searchFields = ['name'=>'名称','group_id'=>'组别'];

}
