<?php
/**
 * 信息模版模型
 *
 * 2016-01-14
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Message;

use App\Base\BaseModel;

class TemplateModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'message_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'type_id',
        'name',
        'content',
    ];

    public $searchFields = ['id'=>'ID','name'=>'名称'];

    protected $rules = [
        'create' => [
            'type_id' => 'required',
            'name' => 'required|unique:message_templates,name',
            'content' => 'required',
        ],
        'update' => [
            'type_id' => 'required',
            'name' => 'required|unique:message_templates,name,{id}',
            'content' => 'required',
        ]
    ];

    public function type()
    {
        return $this->belongsTo('App\Models\Message\Template\TypeModel', 'type_id');
    }

}
