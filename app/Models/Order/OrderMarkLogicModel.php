<?php
/**标记发货规则设置模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:41
 */

namespace App\Models\Order;

use App\Base\BaseModel;
class OrderMarkLogicModel extends BaseModel{

    protected $table = 'order_mark_logic';

    protected $fillable = [
        'name',
        'channel_id',
        'order_status',
        'order_create',
        'order_pay',
        'assign_shipping_logistics',
        'shipping_logistics_name',
        'is_upload',
        'expired_time',
        'user_id',
        'priority',
        'is_use',
        'wish_upload_tracking_num'
    ];

    protected $searchFields = [];

    protected $rules = [
        'create'=>[
            'order_status'=>'required',
            'assign_shipping_logistics'=>'required'
        ],
        'update'=>[
            'assign_shipping_logistics'=>'required'
        ]
    ];




    public function getAssignShippingAttribute()
    {
        return $this->assign_shipping_logistics==1 ? '根据物流已设置承运商标记发货' : '手动指定承运商标记发货';
    }

    public function getIsUsedAttribute()
    {
        return $this->is_use==1 ? '启用' : '未启用';
    }

    public function getIsUploadedAttribute()
    {
        return $this->is_upload==1 ? '按物流渠道设置' : '标记发货但不上传跟踪号';
    }


    public function getWishUploadTrackingAttribute()
    {
        return $this->wish_upload_tracking_num==1 ? '是' : '否';
    }


    public function userOperator()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }
}