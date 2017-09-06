<?php

namespace App\Models;

use Exception;
use App\Base\BaseModel;
use App\Models\ItemModel;
use App\Models\Package\ItemModel as PackageItemModel;
use App\Models\Pick\ListItemModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\Pick\PackageScoreModel;
use App\Models\Warehouse\PositionModel;

class PickListModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'picklists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picknum', 'type', 'warehouse_id', 'status', 'logistic_id', 'pick_by', 'print_at', 'pick_at', 'inbox_by', 'inbox_at', 'pack_at', 'pack_by', 'quantity', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function getAccountAttribute()
    {
        $sum = 0;
        foreach($this->package as $package) {
            foreach($package->items as $packageItem) {
                $sum += $packageItem->quantity;
            }
        }
        return $sum;
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'type' => ['SINGLE' => '单单', 'SINGLEMULTI' => '单多', 'MULTI' => '多多'],
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'doubleRelatedSearchFields' => [],
        ];
    }

    //查询
    public $searchFields=['picknum' => '拣货单号'];

    public function printRecords()
    {
        return $this->hasMany('App\Models\Pick\PrintRecordModel', 'picklist_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo("App\Models\WarehouseModel", 'warehouse_id', 'id');
    }

    //拣货单item关联关系
    public function pickListItem()
    {
        return $this->hasMany('App\Models\Pick\ListItemModel', 'picklist_id', 'id');
    }

    //pacakge关联关系
    public function package()
    {
        return $this->hasMany('App\Models\PackageModel', 'picklist_id', 'id');
    }

    //物流关联关系
    public function logistic()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistic_id', 'id');
    }

    //拣货人关联关系
    public function pickByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'pick_by', 'id');
    }

    public function inboxByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'inbox_by', 'id');
    }

    public function packByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'pack_by', 'id');
    }

    public function getPackageNumAttribute()
    {
        return $this->package->count();
    }

    public function getSkuNumAttribute()
    {
        return $this->pickListItem->count();
    }

    public function getGoodsQuantityAttribute()
    {
        return $this->pickListItem->sum('quantity');
    }

    /**
     * 接受packages，对应相应的操作,单单单多/多多
     * 
     * @param $packages 满足条件的包裹
     * @return none
     *
     */
    public function createPickListItems($packages)
    {

        foreach($packages as $package)
        {
            if($package->type != 'MULTI') {
                $this->createListItems($package);
            } else {
                $score = $this->getScore($package);
                PackageScoreModel::create(['package_id'=>$package->id, 'package_score'=>$score]);
            }
        }
    }

    /**
     * 生成pickListItems
     *
     * @param $package 包裹
     * @return none
     *
     */
    public function createListItems($package)
    {
        foreach($package->items as $packageitem)
        {
            $query = ListItemModel::where(['item_id'=>$packageitem->item_id, 'warehouse_position_id'=>$packageitem->warehouse_position_id, 'type'=>$package->type, 'picklist_id'=>'0'])->first();
            if(!$query) {
                $obj = ListItemModel::create(['type'=>$package->type, 'item_id'=>$packageitem->item_id, 'sku'=>$packageitem->sku, 'warehouse_position_id'=>$packageitem->warehouse_position_id, 'quantity'=>$packageitem->quantity]);
                $obj->pickListItemPackages()->create(['package_id' => $package->id]);
            } else {
                $query->quantity += $packageitem->quantity;
                $query->save();
                $query->pickListItemPackages()->create(['package_id' => $package->id]);
            }  
        }
    }

    /**
     * 获取某个包裹得分
     *
     * @param $package 包裹
     * @return score integer
     *
     */
    public function getScore($package)
    {
        $buf = [];
        foreach($package->items as $packageitem)
        {
            $position = PositionModel::find($packageitem->warehouse_position_id);
            if($position) {
                $name = $position->name;
                $tmp = substr($name,4,2);
                $buf[] = (int)$tmp;
            }
        }
        $buf = array_unique($buf);
        $num = 0;
        foreach($buf as $value)
        {
            $num += pow(2,floor($value/2));
        }
        
        return $num;
    }

    /**
     * 生成pickList ,非混合物流
     *
     * @param $listItemQuantity SINGLE/SINGLEMULTI 拣货单上的条目个数
     * @param $multiQuantity MULTI 同上 | $logistic_id 物流
     *
     * @return none
     */
    public function createPickList($listItemQuantity, $multiQuantity, $logistic_id, $warehouse_id)
    {
        srand(time());
        $query = ListItemModel::where(['picklist_id'=>'0', 'type'=>'SINGLE']);
        if($query->count()) {
            $picklists = $query->get()->sortBy(function($query){
                return $query->position ? $query->position->name : 'a';
            })->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['type'=>'SINGLE', 'status'=>'NONE', 'logistic_id'=>$logistic_id, 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'1'.'0'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackages as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                        $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                    }
                }
            }
            unset($picklists);
        }
        $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLEMULTI']);
        if($query->count()) {
            $picklists = $query->get()->sortBy(function($query){
                return $query->position ? $query->position->name : 'a';
            })->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['type'=>'SINGLEMULTI', 'status'=>'NONE', 'logistic_id'=>$logistic_id, 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'2'.'0'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackages as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                        $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                    }
                }
            }
            unset($picklists);
        }
        $query = PackageScoreModel::where(['picklist_id'=>'0']);
        if($query->count()) {            
            $packageScores = $query->orderBy('package_score')->get()->chunk($multiQuantity);
            foreach($packageScores as $packageScore) {
                $obj = $this->create(['type'=>'MULTI', 'status'=>'NONE', 'logistic_id'=>$logistic_id, 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'3'.'0'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($packageScore as $score)
                {
                    $score->picklist_id = $obj->id;
                    $score->save();
                    $this->createListItems(PackageModel::find($score->package_id));
                    $package = PackageModel::find($score->package_id);
                    $package->picklist_id = $obj->id;
                    $package->status = 'PICKING';
                    $package->save();
                    $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                }
                $this->setPickListId($obj->id);
            }
        }
    }

    /**
     * 生成pickList ,混合物流
     *
     * @param $listItemQuantity SINGLE/SINGLEMULTI 拣货单上的条目个数
     * @param $multiQuantity MULTI 同上 | $logistic_id 物流
     *
     * @return none
     */
    public function createPickListFb($listItemQuantity, $multiQuantity, $warehouse_id)
    {
        srand(time());
        $query = ListItemModel::where(['picklist_id'=>'0', 'type'=>'SINGLE']);
        if($query->count()) {
            $picklists = $query->get()->sortBy(function($query){
                return $query->position->name;
            })->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['type'=>'SINGLE', 'status'=>'NONE', 'logistic_id'=>'0', 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'1'.'1'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackages as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                        $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                    }
                }
            }
            unset($picklists);
         }
        $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLEMULTI']);
        if($query->count()) {
            $picklists = $query->get()->sortBy(function($query){
                return $query->position->name;
            })->chunk($listItemQuantity);
            foreach($picklists as $picklist) {
                $obj = $this->create(['type'=>'SINGLEMULTI', 'status'=>'NONE', 'logistic_id'=>'0', 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'2'.'1'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($picklist as $picklistItem) {
                    $picklistItem->picklist_id = $obj->id;
                    $picklistItem->save();
                    foreach($picklistItem->pickListItemPackages as $listItemPackage) {
                        $package = PackageModel::find($listItemPackage->package_id);
                        $package->picklist_id = $obj->id;
                        $package->status = 'PICKING';
                        $package->save();
                        $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                    }
                }
            }
            unset($picklists);
        }
        $query = PackageScoreModel::where(['picklist_id'=>'0']);
        if($query->count()) {            
            $packageScores = $query->get()->sortBy(function($query){
                return $query->position->name;
            })->chunk($multiQuantity);
            foreach($packageScores as $packageScore) {
                $obj = $this->create(['type'=>'MULTI', 'status'=>'NONE', 'logistic_id'=>'0', 'warehouse_id' => $warehouse_id]);
                $obj->update(['picknum' => 'P'.'3'.'1'.str_pad($obj->id, '7', '0', STR_PAD_LEFT)]);
                foreach($packageScore as $score)
                {
                    $score->picklist_id = $obj->id;
                    $score->save();
                    $this->createListItems(PackageModel::find($score->package_id));
                    $package = PackageModel::find($score->package_id);
                    $package->picklist_id = $obj->id;
                    $package->status = 'PICKING';
                    $package->save();
                    $package->eventLog('系统', '包裹加入拣货单，拣货单号:'.$obj->picknum,json_encode($package));
                }
                $this->setPickListId($obj->id);
            }
        }
    }

    /**
     * 设置picklist_id 
     *
     * @param $id integer
     * @return none
     *
     */
    public function setPickListId($id)
    {
        $pickListItems = ListItemModel::where(['picklist_id'=>'0', 'type'=>'MULTI'])->get();
        foreach($pickListItems as $pickListItem)
        {
            $pickListItem->picklist_id = $id;
            $pickListItem->save();
        }
    }

    /**
     * 获取器,status_name 
     *
     * @param none
     * 
     */
    public function getStatusNameAttribute()
    {
        $arr = config('pick');
        return $arr[$this->status];
    }
}
