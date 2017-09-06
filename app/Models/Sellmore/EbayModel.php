<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:02
 */
namespace App\Models\Sellmore;

use App\Models\Sellmore\EbayDeveloperModel;


class EbayModel extends SellMoreModel
{
    protected $table = 'sf_user_tokens';

    public function developer()
    {
        return $this->belongsTo('App\Models\Sellmore\EbayDeveloperModel', 'developer_id', 'account_id');
    }
}