<?php

namespace App\Models;

use App\Base\BaseModel;

class RoleModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    public $rules = [
        'create' => [
            'role' => 'required|unique:roles,role',
            'role_name' => 'required|unique:roles,role_name',
        ],
        'update' => [
            'role' => 'required|unique:roles,role,{id}',
            'role_name' => 'required|unique:roles,role_name,{id}',
        ]
    ];

    public $searchFields = ['name', 'id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'role','role_name'];

    public function permission()
    {
        return $this->belongsToMany('App\Models\PermissionModel','role_permissions','role_id','permission_id')->withTimestamps();
    }
}
