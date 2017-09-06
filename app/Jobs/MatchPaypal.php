<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderModel;
use App\Models\Order\OrderPaypalDetailModel;
use App\Modules\Paypal\PaypalApi;


class MatchPaypal extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderModel $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $start = microtime(true);
        $is_paypals = false;
        $erp_country_code = trim($this->order->shipping_country);
        $erp_state = trim($this->order->shipping_state);
        $erp_city = trim($this->order->shipping_city);
        $erp_address = trim($this->order->shipping_address);
        $erp_address_1 = trim($this->order->shipping_address1);
        $erp_address = trim($erp_address . $erp_address_1);
        $erp_address = str_replace(' ', '', $erp_address); //把地址信息中的空格都去掉
        $erp_name = trim($this->order->shipping_firstname . $this->order->shipping_lastname);
        $erp_zip = trim($this->order->shipping_zipcode);
        $error = array();
        $paypals = $this->order->channelAccount->paypal;
        foreach ($paypals as $paypal) {
            $api = new  PaypalApi($paypal);
            $result = $api->apiRequest('gettransactionDetails', $this->order->transaction_number);
            $transactionInfo = $api->httpResponse;
            if ($result && $transactionInfo != null && (strtoupper($transactionInfo ['ACK']) == 'SUCCESS' || strtoupper($transactionInfo ['ACK']) == 'SUCCESSWITHWARNING')) {
                $is_paypals = true;
                $tInfo = $transactionInfo;
                $paypal_account = isset($tInfo ['EMAIL']) ? $tInfo ['EMAIL'] : '';
                $paypal_buyer_name = isset($tInfo ['SHIPTONAME']) ? trim($tInfo ['SHIPTONAME']) : '';
                $paypal_country_code = isset($tInfo['SHIPTOCOUNTRYCODE']) ? trim($tInfo['SHIPTOCOUNTRYCODE']) : ''; //国家简称
                $paypal_country = isset($tInfo['SHIPTOCOUNTRYNAME']) ? trim($tInfo['SHIPTOCOUNTRYNAME']) : ''; //国家
                $paypal_city = isset($tInfo['SHIPTOCITY']) ? trim($tInfo['SHIPTOCITY']) : '';        //城市
                $paypal_state = isset($tInfo['SHIPTOSTATE']) ? trim($tInfo['SHIPTOSTATE']) : '';       //州
                $paypal_street = isset($tInfo['SHIPTOSTREET']) ? trim($tInfo['SHIPTOSTREET']) : '';      //街道1
                $paypal_street2 = isset($tInfo['SHIPTOSTREET2']) ? trim($tInfo['SHIPTOSTREET2']) : '';     //街道2
                $paypal_zip = isset($tInfo['SHIPTOZIP']) ? trim($tInfo['SHIPTOZIP']) : '';         //邮编
                $paypal_phone = isset($tInfo['SHIPTOPHONENUM']) ? trim($tInfo['SHIPTOPHONENUM']) : '';    //电话
                $paypalAddress = $paypal_street . ' ' . $paypal_street2 . ' ' . $paypal_city . ' ' . $paypal_state . ' ' . $paypal_country . '(' . $paypal_country_code . ') ' . $paypal_zip;
                if (strtoupper($erp_country_code) != strtoupper($paypal_country_code)) {
                    $error[] = '国家不一致';
                }
                $feeAmt = $tInfo['FEEAMT'];
                $currencyCode = $tInfo['CURRENCYCODE'];
                //把paypal的信息记录
                $is_exist = OrderPaypalDetailModel::where('order_id', $this->order->id)->first();
                if (empty($is_exist)) {
                    $add = [
                        'order_id' => $this->order->id,
                        'paypal_id' => $paypal->id,
                        'paypal_account' => $paypal_account,
                        'paypal_buyer_name' => $paypal_buyer_name,
                        'paypal_address' => $paypalAddress,
                        'paypal_country' => $paypal_country_code,
                        'feeAmt' => $feeAmt,
                        'currencyCode' => $currencyCode
                    ];
                    OrderPaypalDetailModel::create($add);
                }
                if (!empty($error)) { //设置为匹配失败
                    $this->order->update(['order_is_alert' => '1']);
                    //$this->order->remark('paypal匹配失败:'.implode(',',$error));
                    $this->relation_id = $this->order->id;
                    $this->result['status'] = 'fail';
                    $this->result['remark'] = 'paypal匹配失败:' . implode(',', $error);
                } else { //设置为匹配成功
                    $this->order->update(['order_is_alert' => '2', 'fee_amt' => $feeAmt]);
                    //$this->order->remark('paypal匹配成功:'.implode(',',$error));
                    $this->relation_id = $this->order->id;
                    $this->result['status'] = 'success';
                    $this->result['remark'] = 'paypal匹配成功.';
                    break;
                }

            }
        }
        if (!$is_paypals) { //说明对应的paypal 都没有找到信息

            $this->order->update(['order_is_alert' => '1']);
            // $this->order->remark('paypal匹配失败:当前交易凭证在预设的PayPal组中，未查询到交易详情，请通过其它方式查询');
            $this->relation_id = $this->order->id;
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'paypal匹配失败:当前交易凭证在预设的PayPal组中，未查询到交易详情，请通过其它方式查询.';
        }
        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('MatchPaypal',
            isset($api->httpResponse) ? json_encode($api->httpResponse) : json_encode(array('匹配失败')));

    }
}
