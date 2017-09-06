<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 16:33
 */

namespace App\Http\Controllers\Publish\Ebay;

use Tool;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayDataTemplateModel;



class EbayDataTemplateController extends Controller
{
    public function __construct(EbayDataTemplateModel $dataTemplate)
    {
        $this->model = $dataTemplate;
        $this->mainIndex = route('ebayDataTemplate.index');
        $this->mainTitle = 'Ebay数据模板设置';
        $this->viewPath = 'publish.ebay.dataTemplate.';
    }


    public function store(){
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $buyer_requirement = [];
        $buyer_requirement['LinkedPayPalAccount'] = isset($data['no_paypal'])?$data['no_paypal']:'';
        $buyer_requirement['ShipToRegistrationCountry'] = isset($data['no_ship'])?$data['no_ship']:'';

        $buyer_requirement['unpaid_on'] = isset($data['unpaid_on'])?$data['unpaid_on']:'';
        $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Count'] = isset($data['unpaid'])?$data['unpaid']:'';
        $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Period'] = isset($data['unpaid_day'])?$data['unpaid_day']:'';

        $buyer_requirement['policy_on'] = isset($data['policy_on'])?$data['policy_on']:'';
        $buyer_requirement['MaximumBuyerPolicyViolations']['Count'] = isset($data['policy'])?$data['policy']:'';
        $buyer_requirement['MaximumBuyerPolicyViolations']['Period'] = isset($data['policy_day'])?$data['policy_day']:'';

        $buyer_requirement['feedback_on'] = isset($data['feedback_on'])?$data['feedback_on']:'';
        $buyer_requirement['MinimumFeedbackScore'] = isset($data['feedback'])?$data['feedback']:''
        ;
        $buyer_requirement['item_count_on'] = isset($data['item_count_on'])?$data['item_count_on']:'';
        $buyer_requirement['MaximumItemRequirements']['MaximumItemCount'] = isset($data['item_count'])?$data['item_count']:'';
        $buyer_requirement['MaximumItemRequirements']['MinimumFeedbackScore'] = isset($data['item_count_feedback'])?$data['item_count_feedback']:'';

        $data['buyer_requirement'] = json_encode($buyer_requirement);

        $return_policy = [];
        $return_policy['ReturnsAcceptedOption'] = isset($data['returns_option'])?$data['returns_option']:'';
        $return_policy['ReturnsWithinOption'] = $data['returns_with_in'];
        $return_policy['RefundOption'] = isset($data['refund'])?$data['refund']:'';
        $return_policy['ShippingCostPaidByOption'] = $data['shipping_costpaid_by'];
        $return_policy['Description'] = $data['refund_description'];
        $return_policy['ExtendedHolidayReturns'] = isset($data['extended_holiday'])?$data['extended_holiday']:'';
        $data['return_policy'] = json_encode($return_policy);


        $shipping_details = [];

        foreach($data['shipping'] as $key => $v){
            if(!empty($v['ShippingService'])){
                $shipping_details['Shipping'][$key]['ShippingService'] = $v['ShippingService'];
                $shipping_details['Shipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):0.00;
                $shipping_details['Shipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):0.00;
            }
        }
        foreach($data['InternationalShipping'] as $key => $v){
            if(!empty($v['ShippingService'])){
                $shipping_details['InternationalShipping'][$key]['ShippingService'] = $v['ShippingService'];
                $shipping_details['InternationalShipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):0.00;
                $shipping_details['InternationalShipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):0.00;
                $shipping_details['InternationalShipping'][$key]['ShipToLocation'] =$v['ShipToLocation'];;
            }
        }
        $shipping_details['ExcludeShipToLocation'] =$data['un_ship'];
        $data['shipping_details'] = json_encode($shipping_details);


        $model = $this->model->create($data);
        $this->eventLog(request()->user()->id, '数据新增', serialize($model));
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '新增成功'));
    }


    public function update($id)
    {

        $model = $this->model->find($id);
        $from = serialize($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data = request()->all();
        $buyer_requirement = [];
        $buyer_requirement['LinkedPayPalAccount'] = isset($data['no_paypal'])?$data['no_paypal']:'';
        $buyer_requirement['ShipToRegistrationCountry'] = isset($data['no_ship'])?$data['no_ship']:'';

        $buyer_requirement['unpaid_on'] = isset($data['unpaid_on'])?$data['unpaid_on']:'';
        $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Count'] = isset($data['unpaid'])?$data['unpaid']:'';
        $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Period'] = isset($data['unpaid_day'])?$data['unpaid_day']:'';

        $buyer_requirement['policy_on'] = isset($data['policy_on'])?$data['policy_on']:'';
        $buyer_requirement['MaximumBuyerPolicyViolations']['Count'] = isset($data['policy'])?$data['policy']:'';
        $buyer_requirement['MaximumBuyerPolicyViolations']['Period'] = isset($data['policy_day'])?$data['policy_day']:'';

        $buyer_requirement['feedback_on'] = isset($data['feedback_on'])?$data['feedback_on']:'';
        $buyer_requirement['MinimumFeedbackScore'] = isset($data['feedback'])?$data['feedback']:''
        ;
        $buyer_requirement['item_count_on'] = isset($data['item_count_on'])?$data['item_count_on']:'';
        $buyer_requirement['MaximumItemRequirements']['MaximumItemCount'] = isset($data['item_count'])?$data['item_count']:'';
        $buyer_requirement['MaximumItemRequirements']['MinimumFeedbackScore'] = isset($data['item_count_feedback'])?$data['item_count_feedback']:'';

        $data['buyer_requirement'] = json_encode($buyer_requirement);

        $return_policy = [];
        $return_policy['ReturnsAcceptedOption'] = isset($data['returns_option'])?$data['returns_option']:'';
        $return_policy['ReturnsWithinOption'] = $data['returns_with_in'];
        $return_policy['RefundOption'] = isset($data['refund'])?$data['refund']:'';
        $return_policy['ShippingCostPaidByOption'] = $data['shipping_costpaid_by'];
        $return_policy['Description'] = $data['refund_description'];
        $return_policy['ExtendedHolidayReturns'] = isset($data['extended_holiday'])?$data['extended_holiday']:'';
        $data['return_policy'] = json_encode($return_policy);


        $shipping_details = [];

        foreach($data['shipping'] as $key => $v){
            if(!empty($v['ShippingService'])){
                $shipping_details['Shipping'][$key]['ShippingService'] = $v['ShippingService'];
                $shipping_details['Shipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):0.00;
                $shipping_details['Shipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):0.00;
            }
        }
        foreach($data['InternationalShipping'] as $key => $v){
            if(!empty($v['ShippingService'])){
                $shipping_details['InternationalShipping'][$key]['ShippingService'] = $v['ShippingService'];
                $shipping_details['InternationalShipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):0.00;
                $shipping_details['InternationalShipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):0.00;
                $shipping_details['InternationalShipping'][$key]['ShipToLocation'] =$v['ShipToLocation'];;
            }
        }
        $shipping_details['ExcludeShipToLocation'] =$data['un_ship'];
        $data['shipping_details'] = json_encode($shipping_details);


        $model->update($data);
        $to = serialize($model);
        $this->eventLog(request()->user()->id, '数据更新', $to, $from);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '数据更新成功'));
    }





}