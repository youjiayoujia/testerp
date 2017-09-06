<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-08-19
 * Time: 14:15
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;

class EbaySellerCodeModel extends BaseModel
{

    protected $table = 'ebay_seller_code';

    protected $fillable = [
        'seller_code',
        'user_id',
    ];

    public $searchFields = ['seller_code' => '销售代码'];

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


    public  function getAllEbayCode(){
        $result = $this->all();
        $return =[];
        foreach($result as $re){
            $return[(string)$re->seller_code] = $re->user_id;

        }
        return $return;
    }

    public function getEbayCodeWithName(){
        $result = $this->all();
        $return =[];
        foreach($result as $re){
            $return[$re->user_id] = $re->operator->name;

        }
        return $return;
    }


}