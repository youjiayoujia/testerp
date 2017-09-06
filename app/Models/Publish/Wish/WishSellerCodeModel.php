<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-23
 * Time: 17:14
 */
namespace App\Models\Publish\Wish;

use App\Base\BaseModel;

class WishSellerCodeModel extends BaseModel
{

    protected $table = 'wish_seller_code';

    protected $fillable = [
        'seller_code',
        'user_id',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
            'seller_code' => 'required',
            'user_id' => 'required',
        ],
        'update' => [
            'seller_code' => 'required',
            'user_id' => 'required',

        ]
    ];

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }


    public  function getAllWishCode(){
        $result = $this->all();
        $return =[];
        foreach($result as $re){
            $return[(string)$re->seller_code] = $re->user_id;

        }
        return $return;
    }

    public function getWishCodeWithName(){
        $result = $this->all();
        $return =[];
        foreach($result as $re){
            $return[$re->user_id] = $re->operator->name;

        }
        return $return;
    }


}