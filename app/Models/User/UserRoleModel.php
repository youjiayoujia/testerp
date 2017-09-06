<?php

namespace App\Models\User;

use App\Base\BaseModel;

class UserRoleModel extends BaseModel
{
    protected $table = 'user_roles';

	protected $fillable = [

    ];

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }
}