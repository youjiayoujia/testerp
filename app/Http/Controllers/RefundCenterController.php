<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order\RefundModel;
use App\Models\PaypalsModel;
use App\Modules\Paypal\PaypalApi;
use App\Models\CurrencyModel;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use App\Models\ChannelModel;
use Excel;
use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;
use DB;


class RefundCenterController extends Controller
{
    public function __construct(RefundModel $refund)
    {
        $this->model = $refund;
        $this->mainIndex = route('refundCenter.index');
        $this->mainTitle = '退款中心';
        $this->viewPath = 'refund.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas'   => $this->metas(__FUNCTION__),
            //, 'Order.channel', 'User', 'Currency', 'OrderItems', 'paypalDetail', 'Account'
            'data' => $this->autoList($this->model, null, ['*'], null, null, ['Order', 'User', 'channel', 'paypalDetail', 'OrderItems', 'remarks', 'Account']),
            'paypals' => PaypalsModel::where('is_enable','=','1')->get(),
            'users'   => UserModel::all(),
            'channels'=> ChannelModel::all(),
            'types'    => config('refund.type'),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index',$response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $refund = $this->model->find($id);
        $currency = CurrencyModel::all();
        return view($this->viewPath . 'edit',compact('refund','currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $form = request()->all();
        if(isset($form['image'])){
            if(!empty($model->image)){
                Storage::delete(str_replace('uploads/','',$model->image));
            }
            $file = request()->file('image');
            if($file->isValid()){
                $entension = $file->getClientOriginalExtension(); //上传文件的后缀.
                if($entension == 'php'){
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', '上传文件类型不被允许.'));
                }
                $path = config('refund.image_path').$model->order_id;
                $image_name = '/'.time().'.'.$file->getClientOriginalExtension();
                $file->move($path , $image_name);

                $form['image'] = 'uploads/refund/' . $model->order_id . $image_name;
            }else{

            }
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update($form);



        $name = UserModel::find(request()->user()->id)->name;
        $to = $this->model->find($id);
        $to = json_encode($to);
        $this->eventLog($name, '数据更新', $to, json_encode($model));

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * paypal退款
     */
    public function doPaypalRefund(){
        $form = request()->input();
        if(!empty($form['paypal_id']) && !empty($form['password']) && !empty($form['id'])){
            $paypal = PaypalsModel::find($form['paypal_id']);
            if('ASJDCARSDFJWETGDFGERT' == $form['password']){ //写死密码 用于客服验证用
                $refund = $this->model->find($form['id']);
                $from = $refund;
                $paramAry = array();
                $paramAry['TRANSACTIONID'] = $refund->Order->transaction_number;
                $paramAry['REFUNDTYPE']    = ( $refund['refundType'] === 'FULL' ) ? 'Full' : 'Partial';
                $paramAry['CURRENCYCODE']  = $refund['refund_currency'];
                if(!empty($refund['memo'])){
                    $paramAry['NOTE'] = urldecode($refund['memo']);
                }

                $paramAry['AMT'] = $refund['refund_amount']; //退款金额

                $paypalApi = new PaypalApi($paypal->ApiConfig);
                if($paypalApi->apiRefund($paramAry)){
                    $refund->process_status = 'COMPLETE';
                    $refund->save();
                    $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '退款操作', $refund, $from);
                    return redirect($this->mainIndex)->with('alert', $this->alert('success', '退款成功！'));
                }else{
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', '退款失败，请确认用户支付的账号退款账号是否稳合！'));
                }
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', 'Paypal密码错误'));
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '参数不完整'));
        }

    }
    
    public function batchProcessStatus(){
        $form = request()->input();
        if(!empty($form['process']) && !empty($form['ids'])){
            if($this->model->batchProcess($form)){
                 return 10;
            }
        }
        return -10;
    }

    public function RefundCsvFormat(){
        $rows = [
            [
                'Refund No.' => '例：450098',
                '退款凭证(注意：退款成功填凭证，失败不填)'     => '9VR69784B8555501V',
            ]
        ];

        $this->exportExcel($rows, 'refundMoneyTemplate');
    }
    public function  exportExcel($rows,$name){
        Excel::create($name, function($excel) use ($rows){
                $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    //导出财务退
    public function financeExport() {
        $form = request()->input();
        $data = [];
        if(isset($form['channel']) && isset($form['process'])){
            $data[] = [
                'Refund NO.',
                '内单号',
                '渠道',
                '买家ID',
                'SKU',
                '退款金额',
                '交易凭证',
                '退款原因',
                '客服',
                '状态',
                '录入日期',
                '客户Paypal帐号'
            ];
            $refunds = $this->model->where('channel_id','=',$form['channel'])->where('process_status','=',$form['process'])->get();

            if(!$refunds->isEmpty()){
                foreach ($refunds as $refund){
                    $data[] =[
                        $refund->id,
                        $refund->order_id,
                        $refund->ChannelName,
                        $refund->Order->by_id,
                        $refund->SKUs,
                        $refund->refund_amount,
                        $refund->PaidTime,
                        $refund->ReasonName,
                        $refund->CustomerName,
                        $refund->ProcessStatusName,
                        $refund->created_at,
                        $refund->user_paypal_account,
                    ];
                }
            }

            Excel::create('moneyBackManageExport'.date('Y-m-d'), function($excel) use ($data){
                $excel->sheet('', function($sheet) use ($data){
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }
    }
    public function changeReundNoteStatus(){
        // $file = request()->file('excel');
        $result_array   = [];

        if(isset($_FILES['excel']['tmp_name'])){
            $refund_array = Excel::load($_FILES['excel']['tmp_name'],'gb2312')->noHeading()->toArray();
            if(is_array($refund_array) && count($refund_array) >= 2){
                unset($refund_array[0]);
                if(strpos($refund_array[1][1],':')){
                    unset($refund_array[1]);
                }
                foreach($refund_array as $refund){
                    $refund_object = RefundModel::find($refund[1]);
                    if(!empty($refund_object)){
                        if(!empty($refund[2])){ //退款成功
                            $refund_object->refund_voucher = $refund[2];
                            $refund_object->process_status = 'COMPLETE';
                            $result_array[] = [
                                'id'     => $refund[1],
                                'status' => 1
                            ];
                        }else{ //失败
                            $refund_object->process_status = 'FAILED';
                            $result_array[] = [
                                'id'     => $refund[1],
                                'status' => -1,
                            ];
                        }
                        $refund_object->save();
                    }
                }
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '数据导入成功'));
            }

        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '数据导入失败'));

    }

    /**
     * 退款统计报表
     */
    public function refundStatistics(){

        $metas = [
            'mainIndex' => route('feeback.feedBackStatistics'),
            'mainTitle' => '报表',
            'title'     => '退款统计',
        ];



        $response = [
            'metas' => $metas,
            'channels' => ChannelModel::all(),
            //'data'  => $total,
        ];

        return view($this->viewPath . 'statistics',$response);
    }

    public function getChannelAccount(){

        $channel_id = request()->input('channel_id');
        $channel_ids   = explode(',',$channel_id);
        $accounts   = AccountModel::whereIn('channel_id',$channel_ids)->get(['account','id'])->toJson();
        echo $accounts;
    }

    public function exportRefundDetail(){
        $form = request()->input();
        $items = new ItemModel;
        if(!empty($form['skus']))
            $items = $items->whereIn('sku',explode(',',$form['skus']));
        $items = $items->where('refund_id','<>','');

        if(! empty($form['time-type'])){
            if($form['time-type'] == 'order'){
                $items = $items->where('created_at','<=',$form['end']);
                $items = $items->where('created_at','>=',$form['start']);
            }
        }
        $items = $items->distinct();
        $items = $items->get(['refund_id']);

        $refunds =  new RefundModel;
        if(!$items->isEmpty()){
            $refund_ids = array_column($items->toArray(),'refund_id');
            $refunds = $refunds->whereIn('id',$refund_ids);
        }
        if(!empty($form['refund-statistics-channel-ids']))
            $refunds = $refunds->whereIn('channel_id',explode(',',$form['refund-statistics-channel-ids']));

        if(!empty($form['refund-statistics-account-ids']))
            $refunds = $refunds->whereIn('account_id',explode(',',$form['refund-statistics-account-ids']));

        if(!empty($form['refund-statistics-reason']))
            $refunds = $refunds->whereIn('reason',explode(',',$form['refund-statistics-reason']));

        if(! empty($form['time-type'])) {
            if ($form['time-type'] == 'refund') {
                $refunds = $refunds->where('created_at', '<=', $form['end']);
                $refunds = $refunds->where('created_at', '>=', $form['start']);
            }
        }

        $result = $refunds->get();
        if(!$result->isEmpty()){
            $data[] = [
                '退款ID号',
                '买家ID',
                '买家Eamil',
                '收货人',
                '退款类型',
                '退款原因',
                '订单号',
                '退款金额',
                '币种',
                '汇率比',
                '国家代码',
                '订单总金额',
                '运费',
                '订单状态',
                '追踪号',
                '渠道',
                '销售账号',
                '退款状态',
                '录入人员',
                '退款备注',
                'SKU*数量',
                '下单时间',
                '支付时间',
                '退款创建时间',
                '订单备注',
                '运费成本',
                '平台成本',
                '利润率',
                '物流名称',
                '发货时间',
                '包裹重量',
            ];
            foreach ($result as $refund){
                $data[] =[
                    $refund->id,
                    $refund->Order->by_id,
                    $refund->Order->email,
                    $refund->Order->shipping_firstname . ' '.$refund->Order->shipping_lastname,
                    $refund->RefundName,
                    $refund->ReasonName,
                    $refund->order_id,
                    $refund->refund_amount,
                    $refund->refund_currency,
                    $refund->Currency->rate,
                    $refund->Order->shipping_country,
                    $refund->Order->amount,
                    $refund->Order->amount_shipping,
                    $refund->Order->StatusText,
                    $refund->Order->transaction_number,
                    $refund->ChannelName,
                    $refund->Account->account,
                    $refund->ProcessStatusName,
                    $refund->CustomerName,
                    $refund->detail_reason,
                    $refund->RefundProducts,
                    $refund->Order->create_time,
                    $refund->Order->payment_date,
                    $refund->created_at,
                    $refund->Order->OrderReamrks,
                    $refund->Order->packages->sum('cost'),
                    $refund->Order->calculateOrderChannelFee(),   //平台成本
                    $refund->Order->profit_rate,
                    $refund->RefundOrderLogistics,
                    $refund->RefundOrderShipTime,
                    $refund->PakcageWeight,
                ];
            }
            Excel::create('refund'.date('Y-m-d'), function($excel) use ($data){
                $excel->sheet('', function($sheet) use ($data){
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }else{
            return redirect(route('refund.exportRefundDetail'))->with('alert', $this->alert('danger', '没有找到数据。'));
        }
    }
}
