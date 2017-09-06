<?php
/**
 * 黑名单模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/5
 * Time: 下午7:44
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\ChannelModel;
use App\Models\OrderModel;

class BlacklistModel extends BaseModel
{
    public $table = 'order_blacklists';

    public $searchFields = ['ordernum' => '订单号', 'name' => '姓名', 'email' => '邮箱', 'zipcode' => '邮编'];

    public $fillable = [
        'channel_id',
        'ordernum',
        'name',
        'email',
        'by_id',
        'zipcode',
        'channel_account',
        'type',
        'remark',
        'total_order',
        'refund_order',
        'refund_rate',
        'color',
    ];

    public $rules = [
        'create' => [
            'ordernum' => 'required',
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'total_order' => 'required',
            'refund_order' => 'required',
            'refund_rate' => 'required',
        ],
        'update' => [
            'ordernum' => 'required',
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'total_order' => 'required',
            'refund_order' => 'required',
            'refund_rate' => 'required',
        ],
    ];

    public function getMixedSearchAttribute()
    {
        foreach(ChannelModel::all() as $channel) {
            $arr[$channel->name] = $channel->name;
        }
        return [
            'relatedSearchFields' => [
                
            ],
            'filterFields' => [
                'ordernum',
                'by_id',
                'name',
                'email',
                'zipcode'
            ],
            'filterSelects' => [
                'type' => config('order.blacklist_type')
            ],
            'sectionSelect' => [
                'time' => ['created_at']
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => $arr],
            ]
        ];
    }

    public function getTypeNameAttribute()
    {
        $arr = config('order.blacklist_type');
        return $arr[$this->type];
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function exportAll()
    {
        $all = $this->all();
        $rows = '';
        foreach($all as $model) {
            $rows[] = [
                'channel_id' => $model->channel_id,
                'ordernum' => $model->ordernum,
                'name' => $model->name,
                'email' => $model->email,
                'by_id' => $model->by_id,
                'zipcode' => $model->zipcode,
                'channel_account' => $model->channel_account,
                'type' => $model->type,
                'remark' => $model->remark,
                'total_order' => $model->total_order,
                'refund_order' => $model->refund_order,
                'refund_rate' => $model->refund_rate,
            ];
        }
        return $rows;
    }

    public function exportPart($blacklist_id_arr)
    {
        $part = $this->whereIn('id', $blacklist_id_arr)->get();
        $rows = '';
        foreach($part as $model) {
            $rows[] = [
                'id' => $model->id,
                'channel_id' => $model->channel->name,
                'ordernum' => $model->ordernum,
                'name' => $model->name,
                'email' => $model->email,
                'by_id' => $model->by_id,
                'zipcode' => $model->zipcode,
                'channel_account' => $model->channel_account,
                'type' => $model->type_name,
                'remark' => $model->remark,
                'total_order' => $model->total_order,
                'refund_order' => $model->refund_order,
                'refund_rate' => $model->refund_rate,
            ];
        }
        return $rows;
    }

    /**
     * 处理excel
     */
    public function excelProcess($file, $name)
    {
        $path = config('order.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->$name($path.'excelProcess.xls');
    }

    /**
     * 处理excel数据
     */
    public function excelBlacklistProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $blacklist)
        {
            $blacklist['channel_id'] = iconv('gb2312','utf-8',$blacklist['channel_id']);
            $blacklist['ordernum'] = iconv('gb2312','utf-8',$blacklist['ordernum']);
            $blacklist['name'] = iconv('gb2312','utf-8',$blacklist['name']);
            $blacklist['email'] = iconv('gb2312','utf-8',$blacklist['email']);
            $blacklist['by_id'] = iconv('gb2312','utf-8',$blacklist['by_id']);
            $blacklist['zipcode'] = iconv('gb2312','utf-8',$blacklist['zipcode']);
            $blacklist['channel_account'] = iconv('gb2312','utf-8',$blacklist['channel_account']);
            $blacklist['type'] = iconv('gb2312','utf-8',$blacklist['type']);
            $blacklist['remark'] = iconv('gb2312','utf-8',$blacklist['remark']);
            $blacklist['total_order'] = iconv('gb2312','utf-8',$blacklist['total_order']);
            $blacklist['refund_order'] = iconv('gb2312','utf-8',$blacklist['refund_order']);
            $blacklist['refund_rate'] = iconv('gb2312','utf-8',$blacklist['refund_rate']);
            $channel = ChannelModel::where('id', $blacklist['channel_id'])->count();
            if($channel > 0) {
                $this->create($blacklist);
            }else {
                break;
            }
            $channel_id = ChannelModel::where('driver', 'wish')->first()->id;
            $orders1 = OrderModel::where('email', $blacklist['email'])
                ->where('channel_id', '!=', $channel_id)
                ->get();
            if(count($orders1)) {
                foreach($orders1 as $order1) {
                    $order1->update(['blacklist' => '0']);
                }
            }
            $lastname = explode(' ', $blacklist['name'])[0];
            $firstname = explode(' ', $blacklist['name'])[1];
            $orders2 = OrderModel::where('shipping_zipcode', $blacklist['zipcode'])
                ->where('shipping_lastname', $lastname)
                ->where('shipping_firstname', $firstname)
                ->where('channel_id', $channel_id)
                ->get();
            if(count($orders2)) {
                foreach($orders2 as $order2) {
                    $order2->update(['blacklist' => '0']);
                }
            }
        }

        return $error;
    }

    public function transfer_arr($arr)
    {
        $buf = [];
        foreach($arr as $key => $value)
        {
            $tmp = [];
            if($key != 0) {
                foreach($value as $k => $v)
                {
                    $tmp[$arr[0][$k]] = $v;
                }
                $buf[] = $tmp;
            }
        }

        return $buf;
    }

}