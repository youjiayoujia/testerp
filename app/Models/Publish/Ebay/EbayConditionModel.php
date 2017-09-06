<?php
/**ebay 物品状况模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-28
 * Time: 14:57
 */
namespace App\Models\Publish\Ebay;

use Channel;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
class EbayConditionModel extends BaseModel
{
    protected $table = 'ebay_condition';
    protected $fillable = [
        'condition_id',
        'condition_name',
        'category_id',
        'site',
        'is_variations',
        'is_condition',
        'is_upc',
        'is_ean',
        'is_isbn',
        'last_update_time',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];


    public function getSiteCategoryCondition($category_id,$site){
        $result = $this->where(['category_id'=>$category_id,'site'=>$site])->orderBy('condition_id', 'ASC')->get()->toArray();
        if(count($result)!=0){
            if(time()-strtotime($result[0]['last_update_time'])<24*60*60){
                return $result;
            }
        }
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $condition = $channel->getEbayCondition($category_id,$site);
        if($condition){
            foreach($condition as $con){
                $is_has = $this->where(['category_id'=>$category_id,'site'=>$site,'condition_id'=>$con['condition_id']])->first();
                if(empty($is_has)){
                    $this->create($con);
                }else{
                    $is_has->update($con);
                }
            }
            $result = $this->where(['category_id'=>$category_id,'site'=>$site])->orderBy('condition_id', 'ASC')->get()->toArray();
            return $result;
        }else{
            return false;
        }


    }


}