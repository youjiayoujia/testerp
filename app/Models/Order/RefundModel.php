<?php
/**
 * 订单退款模型
 * modify Norton
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/25
 * Time: 下午3:26
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;
use App\Models\UserModel;

class RefundModel extends BaseModel
{
    public $table = 'order_refunds';

    public $searchFields = ['order_id' => '订单号'];

    public $fillable = [
        'order_id',
        'refund_amount',
        'price',
        'refund_currency',
        'refund',
        'reason',
        'type',
        'memo',
        'detail_reason',
        'image',
        'refund_voucher',
        'user_paypal_account',
        'customer_id',
        'channel_id',
        'account_id',
        'process_status'
    ];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //状态为待审核 小于 15 USD 的速卖通订单
    public function scopeAliexpress15Usd($query){
        $channel_id = ChannelModel::where('name','Aliexpress')->first();

        return $query->where('channel_id',$channel_id->id)
            ->where('refund_amount','<',15)
            ->where('refund_currency','=','USD')
            ->where('type','FULL');

    }

    public function getReasonNameAttribute()
    {
        $arr = config('order.reason');
        return $arr[$this->reason];
    }

    public function getTypeNameAttribute()
    {
        $arr = config('refund.type');
        return $arr[$this->type];
    }

    public function getRefundNameAttribute()
    {
        $arr = config('refund.refund');
        if(isset($arr[$this->refund])){
            return $arr[$this->refund];
        }
        return '';
    }
    public function getProcessStatusNameAttribute(){
        return config('refund.process')[$this->process_status];
    }

    public function Order(){
        return $this->hasOne('App\Models\OrderModel','id','order_id');
    }
    public function User(){
        return $this->hasOne('App\Models\UserModel','id','customer_id');
    }
    public function Account(){
        return $this->hasOne('App\Models\Channel\AccountModel','id','account_id');
    }

    public function Currency(){
        return $this->hasOne('App\Models\CurrencyModel','code','refund_currency');
    }

    public function OrderItems(){
        return $this->hasMany('App\Models\Order\ItemModel','refund_id','id');
    }
    public function channel(){
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id' , 'id');
    }

    public function paypalDetail()
    {
        return $this->hasMany('App\Models\Order\OrderPaypalDetailModel', 'order_id', 'order_id');
    }

    //订单备注
    public function remarks()
    {
        return $this->hasMany('App\Models\Order\RemarkModel', 'order_id', 'id');
    }

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        //dd(UserModel::all()->pluck('name','name'));
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'type' => config('refund.type'),
                'refund' => config('refund.refund'),
                'process_status' => config('refund.process'),
                'customer_id' => UserModel::where('is_available', 1)->get()->pluck('name', 'id'),

            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => ChannelModel::all()->pluck('name', 'name')],
                //'assigner' => ['name' => UserModel::all()->pluck('name','name')],
            ],
            'sectionSelect' => ['time'=>['created_at']],
        ];
    }

    public function getSKUsAttribute(){
        $items = $this->OrderItems;
        $sku ='';
        foreach ($items as $item){
            if($item->is_refund == '1'){
                $sku = empty($sku) ? $item->sku : $sku.','.$item->sku;
            }
        }
        return $sku;
    }

    public function getPaidTimeAttribute(){
        return $this->Order->payment_date;
    }
    public function getChannelNameAttribute(){
        return $this->channel->name;
    }
    public function getOrderRemarksAttribute(){
        $remarks = $this->remarks;
        $html = '<ul>';
        if(!$remarks->isEmpty()){
            foreach ($remarks as $remark){
                $html .= "<li>{$remark->remark} {$remark->created_at}</li>";
            }
        }else{

        }

        $html .= '</ul>';
        return $html;
    }

    public function getCustomerNameAttribute(){
        $name = '无';
        if(!empty($this->customer_id)){
            $name = $this->User->name;
        }
        return $name;
    }

    public function batchProcess($paramAry){
        $ids_ary = explode(',',$paramAry['ids']);
        $collection = $this->find($ids_ary);
        if(!$collection->isEmpty()){
            foreach ($collection as $refund){
                $refund->process_status = $paramAry['process'];
                $refund->save();
            }
            return true;
        }
        return false;
    }

    public function getChannelAccountNameAttribute(){
        return $this->Account->account;
    }

    public function getRefundProductsAttribute(){
        $skus = '';
        if(!$this->OrderItems->isEmpty()){
            foreach ($this->OrderItems as $item){
                $skus .= $item->sku.'*'.$item->quantity.';';
            }
        }
        return $skus;
    }

    public function getRefundOrderLogisticsAttribute(){
        //$this->Order->packages;
        if($this->Order->packages->first()){
            if(!empty($this->Order->packages->first()->logistics)){
                return  $this->Order->packages->first()->name;
            }else{
                return '无';
            }

        }else{
            return '无';
        }
    }

    public function getRefundOrderShipTimeAttribute(){
        if(!$this->Order->packages->isEmpty()){
            return $this->Order->packages->first()->shipped_at;
        }else{
            return '';
        }
    }

    public function getPakcageWeightAttribute(){
        $weight = 0;
        if(!$this->Order->items->isEmpty()){
            foreach($this->Order->items as $order_item){
                $weight += $order_item->item->weight;
            }
        }
        return $weight;
    }

    public function getAliexpressrefunds(){
        return $this->Aliexpress15Usd()->get();
    }
}