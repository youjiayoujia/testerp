<?php
/**
 * User: Norton
 * Date: 2016/8/13
 * Time: 15:31
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;
use App\Models\OrderModel;
use App\Models\Order\ItemModel;
class EbayCasesListsModel extends BaseModel{
    public $table = 'ebay_cases_lists';
    public $rules = [];
    public $searchFields =['id' => 'ID' , 'buyer_id' => '买家ID' , 'seller_id' => '卖家ID' ,'transaction_id' => '交易号'];
    public $fillable = [
        'case_id',
        'status',
        'type',
        'buyer_id',
        'seller_id',
        'item_id',
        'item_title',
        'transaction_id',
        'case_quantity',
        'case_amount',
        'respon_date',
        'creation_date',
        'last_modify_date',
        'global_id',
        'open_reason',
        'decision',
        'decision_date',
        'fvf_credited',
        'agreed_renfund_amount',
        'buyer_expection',
        'detail_status',
        'tran_date',
        'tran_price',
        'content',
        'account_id'
    ];

    public function account()
    {
        return $this->hasOne('App\Models\Channel\AccountModel', 'id', 'account_id');
    }
    public function orderItem(){
        return $this->hasOne('App\Models\Order\ItemModel','transaction_id','transaction_id');
    }
/*    public function order(){
        return $this->belongsTo('App\Models\OrderModel','','');
    }*/

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'case_id',
                'buyer_id',
                'transaction_id'
            ],
            'filterSelects' => [
                'type' => config('crm.ebay.case.type'),
                'status' => config('crm.ebay.case.status'),
            ],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => [],
        ];
    }

    public function getCaseContentAttribute(){
        $html = '';
        $note = unserialize(base64_decode($this->content));

        if(is_array($note)){
            if(isset($note['role'])){ //单条
                if($note['role'] == 'BUYER'){
                    $html .= '<div class="alert alert-warning col-md-10" role="alert">';
                    $html .= '<p>buyer:'.$this->buyer_id.'</p>';
                    $html .= '<p>seller:'.$this->seller_id.'</p>';
                    $html .= '<p>状态:'.$this->seller_id.'</p>';
                    $html .= '<p>activity:'.$this->seller_id.'</p>';
                    $html .= '<p>Date:'.$note['creationDate'].'</p>';
                    $html .= '<p>Date:'.$note['note'].'</p>';
                    $html .= '</div>';
                }else{
                    $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right">';
                    $html .= '<p>buyer:'.$this->buyer_id.'</p>';
                    $html .= '<p>seller:'.$this->seller_id.'</p>';
                    $html .= '<p>状态:'.$this->seller_id.'</p>';
                    $html .= '<p>activity:'.$this->seller_id.'</p>';
                    $html .= '<p>Date:'.$note['creationDate'].'</p>';
                    $html .= '<p>note:'.$note['note'].'</p>';
                    $html .= '</div>';
                }

            }else{ //多条
                foreach (array_reverse($note) as $item){
                    if($item['role'] == 'BUYER'){
                        $html .= '<div class="alert alert-warning col-md-10" role="alert">';

                        $html .= '<p>Date:'.$item['creationDate'].'</p>';
                        $html .= '<p>Date:'.$item['note'].'</p>';
                        $html .= '</div>';
                    }else{
                        $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right">';
                        $html .= '<p>activity:'.$this->seller_id.'</p>';
                        $html .= '<p>Date:'.$item['creationDate'].'</p>';
                        $html .= '<p>note:'.$item['note'].'</p>';
                        $html .= '</div>';
                    }
                }
            }
        }
        return $html;
    }

    public function getCaseOrderInfoAttribute()
    {
        if (!empty($this->transaction_id)) {
            $realted_order = ItemModel::where('transaction_id', $this->transaction_id)->first();
            if (!empty($realted_order)) {
                return $realted_order;
            }
        }
        return '';
    }
}


































