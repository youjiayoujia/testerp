<?php
/** ebay站点分类
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-28
 * Time: 10:53
 */
namespace App\Models\Publish\Ebay;
use Channel;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;

class EbayCategoryModel extends BaseModel
{
    protected $table = 'ebay_category';
    protected $fillable = [
        'category_id',
        'best_offer',
        'auto_pay',
        'category_level',
        'category_name',
        'category_parent_id',
        'leaf_category',
        'site',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];



    public function getSuggestCategory($query,$site){
        $return = [];
        if(is_numeric(trim($query))){
            $id = $this->where('category_id',$query)->where('site',$site)->first()->id;
            if($id){
                $return[0]['category_full_name'] = $this->getCategoryFullNameChilden($query,$site);
                $return[0]['category_id'] = $query;
                $return[0]['percent'] = 100;
            }else{
                return false;
            }
        }else{
            $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->getSuggestedCategories($query,$site);
            if($result){
                foreach($result as $key=> $re){
                    $return[$key]['category_full_name'] = $this->getCategoryFullNameChilden($re['CategoryID'],$site);
                    $return[$key]['category_id'] = $re['CategoryID'];
                    $return[$key]['percent'] = $re['Percent'];
                }
            }else{
                return false;
            }
        }
        return $return;
    }


    public function  getCategoryFullNameChilden($category_parent_id,$site,$last_result=''){
        $result  = $this->where('category_id',$category_parent_id)->where('site',$site)->first()->toArray();
        if(!empty($result)){
            if($result['category_level'] != 1){
                $last_result ='>>'.$result['category_name'].$last_result;
                $last_result=   $this->getCategoryFullNameChilden($result['category_parent_id'],$site,$last_result);
            }else{
                $last_result =$result['category_name'].$last_result;
                return   $last_result;
            }
        }else{
            return '';
        }


        return $last_result;
    }

}