<?php
/** ebay 细节模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-07-28
 * Time: 17:04
 */
namespace App\Models\Publish\Ebay;
use Channel;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;

class EbaySpecificsModel extends BaseModel
{
    protected $table = 'ebay_specifics';
    protected $fillable = [
        'name',
        'category_id',
        'site',
        'value_type',
        'min_values',
        'max_values',
        'selection_mode',
        'variation_specifics',
        'specific_values',
        'last_update_time'
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function getSiteCategorySpecifics($category_id,$site){
        $result = $this->where(['category_id'=>$category_id,'site'=>$site])->orderBy('last_update_time', 'DESC')->get()->toArray();
        if(count($result)!=0){
          if(time()-strtotime($result[0]['last_update_time'])<24*60*60){
              return $result;
          }
        }
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $specifics = $channel->getEbayCategorySpecifics($category_id,$site);
        if($specifics){
            foreach($specifics as $spe){
                $is_has = $this->where(['category_id'=>$category_id,'site'=>$site,'name'=>$spe['name']])->first();
                if(empty($is_has)){
                    $this->create($spe);
                }else{
                    $is_has->update($spe);
                }
            }
            $result = $this->where(['category_id'=>$category_id,'site'=>$site])->orderBy('last_update_time', 'DESC')->get()->toArray();
            return $result;
        }else{
            return false;
        }

    }

}