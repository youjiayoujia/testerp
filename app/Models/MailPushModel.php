<?php

namespace App\Models;
use App\Base\BaseModel;

class MailPushModel extends BaseModel
{
    //
    public $table = 'mail_push';

    public $fillable = [
        'code',
        'name',
        'value',
        'description',
    ];
    public $rules = [
        'create' => [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
            'value' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
            'value' => 'required',
        ],
    ];

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        //dd(UserModel::all()->pluck('name','name'));
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'mail_push.name',
                'mail_push.code',
            ],
            'filterSelects' => [
            ],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => [],
        ];
    }


}
