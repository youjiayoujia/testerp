<?php

namespace App\Models\Log;

use App\Base\BaseModel;

class CommandModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_commands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    //查询
    public $searchFields = ['relation_id' => '关联ID', 'signature' => '命令', 'description' => '描述', 'remark' => '备注'];

    public function getColorAttribute()
    {
        if ($this->result == 'fail') {
            return 'danger';
        }
        if ($this->result == 'init') {
            return 'warning';
        }
        if ($this->result == 'success') {
            return 'success';
        }
    }

}
