<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 13:40
 */


namespace App\Models;
use App\Base\BaseModel;

class PaypalsModel extends BaseModel
{

    protected $table = 'paypals';

    protected $fillable = ['paypal_email_address', 'paypal_account', 'paypal_password','paypal_token','is_enable'];

    protected $searchFields = ['paypal_email_address'];

    protected $rules = [
        'create' => [
            'paypal_email_address' => 'required',
            'paypal_account' => 'required',
            'paypal_password' => 'required',
            'paypal_token' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'paypal_email_address' => 'required',
            'paypal_account' => 'required',
            'paypal_password' => 'required',
            'paypal_token' => 'required',
            'is_enable' => 'required',
        ]
    ];

    public function getPaypalEnableAttribute()
    {
        return $this->is_enable==1 ? '是' : '否';
    }
    public function getPayPal($key='id'){
        $retrun =[];
        $result=    $this->where('is_enable',1)->get();
        foreach($result as $re){
            $retrun[$re[$key]] = $re['paypal_email_address'];
        }
       return $retrun;
    }

    public function getApiConfigAttribute(){
        return (object)[
            'paypal_account'  => $this->paypal_account,
            'paypal_password' => $this->paypal_password,
            'paypal_token'    => $this->paypal_token,
        ];
    }

}
