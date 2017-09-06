<?php
/**
 *
 * Paypal控制器
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 13:46
 */
namespace App\Http\Controllers;
use App\Models\PaypalsModel;
use App\Models\PermissionModel;
use App\Models\PaypalRatesModel;

class PaypalController extends Controller
{
    public function __construct(PaypalsModel $paypal)
    {
        $this->model = $paypal;
        $this->mainIndex = route('paypal.index');
        $this->mainTitle = 'paypal';
        $this->viewPath = 'paypal.';
    }

    /**
     * paypal税
     * @param PaypalRatesModel $paypalRates
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ShowPaypalRate(PaypalRatesModel $paypalRates){

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'rates' => $paypalRates->find(1), //获得paypal税
        ];


        return view('paypal.paypal_rate',$response);
    }

    public function updatePaypalRates(PaypalRatesModel $paypalRates){
        $data = request()->all();
        $obj = $paypalRates->find(1);
        $obj->update($data);

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'rates' => $obj, //获得paypal税
        ];

        return view('paypal.paypal_rate',$response);

    }

}