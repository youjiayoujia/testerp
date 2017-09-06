<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/14
 * Time: 17:46
 */
namespace App\Models\Message\Issues;

use App\Base\BaseModel;
use Tool;
use Carbon\Carbon;

class AliexpressIssuesDetailModel extends BaseModel
{
    protected $table = 'aliexpress_issues_detail';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
            ],
            'filterSelects' => [
            ],
            'selectRelatedSearchs' => [
            ],
        ];
    }

    public function issueList()
    {
        return $this->belongsTo('App\Models\Message\Issues\AliexpressIssueListModel', 'issue_list_id', 'id');
    }

    public function getProductInfoAttribute(){

        $info = Tool::unserializeBase64Decode($this->productPrice);
        if(!empty($info)){
            $price = $info->currencyCode .' '. $info->amount;

        }else{
            $price = '';
        }
        return $price;
    }

    public function getBuyerSolutionInfoAttribute(){
        $info = Tool::unserializeBase64Decode($this->buyerSolutionList);
        return $info;
    }

    public function getSellerSolutionInfoAttribute(){
        $info = Tool::unserializeBase64Decode($this->sellerSolutionList);
        return $info;
    }
}