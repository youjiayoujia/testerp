<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/27
 * Time: 10:28
 */
namespace App\Models;

use App\Base\BaseModel;

class SyncApiModel extends BaseModel
{
    public $table = 'sync_api';
    public $guarded = [];

    public function getColorAttribute()
    {
        if ($this->status == 0) {
            return 'warning';
        }
        if ($this->status == 1) {
            return 'success';
        }
    }

    public function getTextAttribute()
    {
        if ($this->status == 0) {
            return '未成功';
        }
        if ($this->status == 1) {
            return '已成功';
        }
    }
}