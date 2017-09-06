<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtUserSaleCode extends BaseModel
{
    //
    protected $table = "smt_user_sale_code";
    
    public $fillable = ['user_id','sale_code'];

    protected $rules = [
        'create' => [
            'sale_code' => 'required',
            'user_id' => 'required',
        ],
        'update' => [
            'sale_code' => 'required',
            'user_id' => 'required',

        ]
    ];
    
    protected $searchFields = [];
    public function User(){
        return $this->belongsTo('App\Models\UserModel','user_id');
    }

    public function getAllSmtCode(){
        $result = $this->all();
        $return =[];
        foreach($result as $re){
            $return[(string)$re->sale_code] = $re->user_id;
        }
        return $return;
    }
}
