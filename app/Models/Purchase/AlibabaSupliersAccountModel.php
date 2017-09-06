<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/13
 * Time: 13:53
 */
namespace App\Models\Purchase;
use App\Base\BaseModel;

class AlibabaSupliersAccountModel extends BaseModel{
    public $table = 'alibaba_suppliers_account';

    public $rules = [
        'create' => [

        ],
        'update' => [

        ]
    ];
    public $fillable = [
        'id',
        'resource_owner',
        'memberId',
        'access_token',
        'purchase_user_id'
    ];

    public $searchFields = ['resource_owner'  => '账户名','memberId' => '账户ID'];


    public function user(){

        return $this->hasOne('App\Models\UserModel','id','purchase_user_id');


    }

    public function getPurchaseUserNameAttribute(){
        return  !empty($this->user) ? $this->user->name : '';
    }

}