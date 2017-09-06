<?php

namespace App\Models\Spu;

use App\Base\BaseModel;

class SpuMultiOptionModel extends BaseModel
{
    protected $table = 'spu_multi_options';

	protected $fillable = [
        'it_name', 'it_description', 'it_keywords', 
        'de_name', 'de_description', 'de_keywords', 
        'fr_name', 'fr_description', 'fr_keywords',
        'zh_name', 'zh_description', 'zh_keywords',
        'en_name', 'en_description', 'en_keywords',
        'channel_id','spu_id',
    ];

    // 规则验证
    public $rules = [
        'create' => [   
                //'it_name' => 'required|max:255|unique:product_requires,name',
        ],
        'update' => [   
                //'it_name' => 'required|max:255|unique:product_requires,name, {id}',
        ]
    ];

    //查询
    public $searchFields = ['name'];

    /**
     * 更新多渠道多语言信息
     * 2016年6月3日10:43:18 YJ
     * @param array data 修改的信息
     */
    public function updateMulti($data)
    {   
        foreach ($data['info'] as $channel_id => $language) {
            $arr = [];
            $pre = $language['language'];
            foreach ($language as $prefix => $value) {
                $arr[$pre."_".$prefix] = $value;
            }
            //print_r($arr);
            //exit;
            $model = $this->spuMultiOption->where("channel_id", (int)$channel_id)->first();
            if($model){
                $model->update($arr);
            }
           
        }
        //
    }
}
