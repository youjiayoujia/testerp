<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserModel extends BaseModel implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'email', 'password','is_available', 'code', 'warehouse_id'];
    public $searchFields = ['name'=>'name', 'id'=>'id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'email' => 'required|unique:users,email,{id}',
            'password' => 'required',
        ]
    ];

    public function channelAccounts()
    {
        return $this->belongsToMany('App\Models\UserModel', 'channel_account_user', 'channel_account_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsToMany('App\Models\RoleModel', 'user_roles', 'user_id', 'role_id')->withTimestamps();
    }

    public function hasRole() {
        if(is_string($role)){
            return $this->roles->contains('name', $role);
        }
        return !!$role->intersect($this->roles)->count();
    }

    public function messages()
    {
        return $this->hasMany('App\Models\message\MessageModel', 'assign_id');
    }

    public function getProcessMessagesAttribute()
    {
        return $this->messages()->where('status', 'PROCESS')->count();
    }
}
