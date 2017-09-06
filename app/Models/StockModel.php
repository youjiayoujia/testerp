<?php

namespace App\Models;

use DB;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\Base\BaseModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;
use App\Models\Warehouse\PositionModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\Product\ProductEnglishValueModel;
use App\Models\Oversea\ItemCostModel;
use App\Models\Oversea\Stock\AdjustmentModel;

class StockModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'warehouse_position_id',
        'all_quantity',
        'available_quantity',
        'hold_quantity',
        'created_at',
        'oversea_sku',
        'oversea_cost',
    ];

    // 用于查询
    public $searchFields = ['id' => 'ID'];

    // 规则验证
    public $rules = [
        'create' => [
            'warehouse_id' => 'required|integer',
            'warehouse_position_id' => 'required',
            'all_quantity' => 'required|integer',
        ]
    ];

    public function getMixedSearchAttribute()
    {
        $warehosues = WarehouseModel::where('is_available', '1')->get();
        $arr = [];
        foreach($warehosues as $warehouse) {
            $arr[$warehouse->name] = $warehouse->name;
        }
        return [
            'relatedSearchFields' => ['item' => ['sku']],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [ 'warehouse' => ['name' => $arr]],
            'sectionSelect' => [],
        ];
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    public function getOverseaCostAttribute()
    {
        $itemCost = $this->warehouse->overseaItemCost()->where('item_id', $this->item_id)->first();
        if(!$itemCost) {
            return 0;
        }

        return $itemCost->cost;
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockInOut()
    {
        return $this->hasMany('App\Models\Stock\InOutModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockHold()
    {
        return $this->hasMany('App\Models\Stock\HoldModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockUnhold()
    {
        return $this->hasMany('App\Models\Stock\UnholdModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two model
     *
     * @return
     *
     */
    public function stockOut()
    {
        return $this->hasMany('App\Models\Stock\InOutModel', 'stock_id', 'id');
    }

    /**
     * return the relation ship
     *
     * @return relation
     *
     */
    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function stockTakingForm()
    {
        return $this->hasOne('App\Models\Stock\TakingFormModel', 'stock_id', 'id');
    }

    /**
     * add additional attribute according to sku ,get the goods unit cost
     *
     * @param none
     * @return json
     *
     */
    public function getUnitCostAttribute()
    {
        $item = ItemModel::where('id', $this->item_id)->first();
        if($item) {
            if($item->cost) {
                return $item->cost;
            } else {
                if(ProductEnglishValueModel::where('product_id', $item->product_id)->first()) {
                    return ProductEnglishValueModel::where('product_id', $item->product_id)->first()->sale_usd_price;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * in api
     * @param
     * $quantity 数量
     * $amount 金额
     * $type 入库类型
     * $relation_id   例:调整表的某个id
     * $remark 备注
     *
     * @return none
     *
     */
    public function in($quantity, $amount, $type = '', $relation_id = '', $remark = '')
    {
        $this->all_quantity += $quantity;
        $this->available_quantity += $quantity;
        $this->save();
        $this->stockInOut()->create([
            'quantity' => $quantity,
            'amount' => $amount,
            'outer_type' => 'IN',
            'inner_type' => $type,
            'relation_id' => $relation_id,
            'remark' => $remark
        ]);
    }

    /**
     * hold api
     * @param
     * $quantity 数量
     *
     * @return none
     *
     */
    public function hold($quantity, $type = '', $relation_id = '', $remark = '')
    {
        $this->available_quantity -= $quantity;
        if ($this->available_quantity < 0) {
            throw new Exception('');
        }
        $this->hold_quantity += $quantity;
        $this->save();
        $this->stockHold()->create([
                'quantity' => $quantity,
                'type' => $type,
                'relation_id' => $relation_id,
                'remark' => $remark
            ]);
    }

    /**
     * unhold api
     * @param
     * $quantity 数量
     *
     * @return none
     *
     */
    public function holdout($quantity, $type = '', $relation_id = '', $remark = '')
    {
        $price = $this->unit_cost;
        if($this->unit_cost <= 0) {
            return false;
        }
        $this->hold_quantity -= $quantity;
        $this->all_quantity -= $quantity;
        if ($this->hold_quantity < 0) {
            return false;
        }
        $this->save();
        $this->stockUnhold()->create([
                'quantity' => $quantity,
                'type' => $type,
                'relation_id' => $relation_id,
                'remark' => $remark
            ]);
        $this->stockOut()->create([
            'quantity' => $quantity,
            'amount' => $quantity * $price,
            'outer_type' => 'OUT',
            'inner_type' => $type,
            'relation_id' => $relation_id,
            'remark' => $remark
        ]);
        return true;
    }

    /**
     * unhold api
     * @param
     * $quantity 数量
     *
     * @return none
     *
     */
    public function unhold($quantity, $type = '', $relation_id = '', $remark = '')
    {
        $this->hold_quantity -= $quantity;
        if ($this->hold_quantity < 0) {
            throw new Exception('unhold时，hold数量为负了');
        }
        $this->available_quantity += $quantity;
        $this->save();
        $this->stockUnhold()->create([
                'quantity' => $quantity,
                'type' => $type,
                'relation_id' => $relation_id,
                'remark' => $remark
            ]);
    }

    /**
     * in api
     * @param
     * $quantity 数量
     * $type 入库类型
     * $relation_id   例:调整表的某个id
     * $remark 备注
     *
     * @return none
     *
     */
    public function out($quantity, $type = '', $relation_id = '', $remark = '')
    {
        $price = $this->unit_cost ? $this->unit_cost : $this->purchase_price;
        if($price <= 0) {
            throw new Exception('单价不是正数，出错');
        }
        $this->all_quantity -= $quantity;
        $this->available_quantity -= $quantity;
        if ($this->available_quantity < 0) {
            throw new Exception('Quantity ERROR.');
        }
        $this->save();
        $this->stockInOut()->create([
            'quantity' => $quantity,
            'amount' => $quantity * $price,
            'outer_type' => 'OUT',
            'inner_type' => $type,
            'relation_id' => $relation_id,
            'remark' => $remark
        ]);
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针
     *
     */
    public function excelProcess($file)
    {
        ini_set('memory_limit', '128M');
        set_time_limit('0');
        $path = config('setting.stockExcelPath');
        !file_exists($path . 'stockExcelProcess.csv') or unlink($path . 'stockExcelProcess.csv');
        $file->move($path, 'stockExcelProcess.csv');
        return $this->excelDataProcess($path . 'stockExcelProcess.csv');
    }

    public function overseaExcelProcess($file)
    {
        ini_set('memory_limit', '128M');
        set_time_limit('0');
        $path = config('setting.stockExcelPath');
        !file_exists($path . 'stockExcelProcess.csv') or unlink($path . 'stockExcelProcess.csv');
        $file->move($path, 'stockExcelProcess.csv');
        return $this->overseaExcelDataProcess($path . 'stockExcelProcess.csv');
    }

    public function overseaExcelDataProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        $i = 1;
        $adjustment = AdjustmentModel::create(['date' => date('Y-m-d H:i:s', time()), 'adjust_by' => request()->user()->id]);
        foreach ($arr as $key => $stock) {
            $stock['position'] = iconv('gb2312', 'utf-8', $stock['position']);
            if (!PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->count()) {
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = '库位不存在';
                $i++;
                continue;
            }
            if($stock['sku'])
            $stock['sku'] = iconv('gb2312', 'utf-8', $stock['sku']);
            $tmp_position = PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->first();
            if (!ItemModel::where(['sku' => $stock['sku']])->count()) {
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = 'Item不存在';
                $i++;
                continue;
            }
            $tmp_item = ItemModel::where(['sku' => trim($stock['sku'])])->first();
            $tmp_stock = StockModel::where(['warehouse_position_id' => $tmp_position->id, 'oversea_sku' => $stock['oversea_sku']])->first();
            if (!$tmp_stock) {
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = '该对应Oversea没有库存,入库';
                $error[$i]['quantity'] = (int)$stock['all_quantity'];
                $adjustment->forms()->create(['sku' => $stock['sku'], 'warehouse_position' => $stock['position'], 'quantity' => $error[$i]['quantity'], 'type' => 'in', 'oversea_sku' => $stock['oversea_sku'], 'oversea_cost' => $stock['oversea_cost'], 'remark' => '该对应Oversea没有库存,入库']);
            } else {
                $error[$i]['key'] = $key;
                $error[$i]['quantity'] = (int)$stock['all_quantity'] - $tmp_stock->all_quantity;
                if($error[$i]['quantity'] > 0) {
                    $adjustment->forms()->create(['sku' => $stock['sku'],'quantity' => $error[$i]['quantity'],  'warehouse_position' => $stock['position'], 'type' => 'in', 'oversea_sku' => $stock['oversea_sku'], 'oversea_cost' => $stock['oversea_cost'], 'remark' => '该对应Oversea没有库存,入库']);
                } else {
                    $adjustment->forms()->create(['sku' => $stock['sku'],'quantity' => -$error[$i]['quantity'], 'warehouse_position' => $stock['position'], 'type' => 'out', 'oversea_sku' => $stock['oversea_sku'], 'oversea_cost' => $stock['oversea_cost'], 'remark' => '该对应Oversea没有库存,入库']);
                }
            }
            $this->oversea_importStock($arr, $error[$i]);
            $i++;
        }

        return $error;
    }

    public function oversea_importStock($arr, $buf)
    {
        $item = ItemModel::where('sku', $arr[$buf['key']]['sku'])->first();
        if(!$item) {
            return false;
        }
        $position = PositionModel::where('name', $arr[$buf['key']]['position'])->first();
        if(!$position) {
            return false;
        }
        if($buf['quantity'] > 0) {
            $item->in($position->id, (int)$buf['quantity'], ((int)$buf['quantity'] * ($item->cost ? $item->cost : $item->purchase_price)), 'ADJUSTMENT');
        }
        if($buf['quantity'] < 0) {
            $item->out($position->id, -(int)$buf['quantity'], 'ADJUSTMENT');
        }
        $stock = $this->where(['item_id' => $item->id, 'warehouse_position_id' => $position->id])->first();
        if($stock) {
            $stock->update(['oversea_sku' => $arr[$buf['key']]['oversea_sku']]);
            if(isset($arr[$buf['key']]['oversea_cost'])) {
                $itemCost = $stock->warehouse->overseaItemCost()->where('item_id', $stock->item_id)->first();
                if($itemCost) {
                    $itemCost->update(['cost' => $arr[$buf['key']]['oversea_cost']]);
                } else {
                    ItemCostModel::create(['item_id' => $stock->item_id, 'cost' => $arr[$buf['key']]['oversea_cost'], 'code' => $stock->warehouse->code]);
                }
            }
        }
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while (!feof($fd)) {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if (!$arr[count($arr) - 1]) {
            unset($arr[count($arr) - 1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        $i = 1;
        foreach ($arr as $key => $stock) {
            $stock['position'] = iconv('gb2312', 'utf-8', $stock['position']);
            if (!PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->count()) {
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = '库位不存在';
                $i++;
                continue;
            }
            if($stock['sku'])
            $stock['sku'] = iconv('gb2312', 'utf-8', $stock['sku']);
            $tmp_position = PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->first();
            if (!ItemModel::where(['sku' => $stock['sku']])->count()) {
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = 'Item不存在';
                $i++;
                continue;
            }
            $tmp_item = ItemModel::where(['sku' => trim($stock['sku'])])->first();
            DB::beginTransaction();
            try {
            $tmp_item->in($tmp_position->id, $stock['all_quantity'], $stock['all_quantity'] * $tmp_item->cost,
                'MAKE_ACCOUNT');
            } catch(Exception $e) {
                DB::rollback();
                $error[$i]['key'] = $key;
                $error[$i]['remark'] = '数据有异常';
            }
            DB::commit();
            $i++;
        }

        return $error;
    }

    public function transfer_arr($arr)
    {
        $buf = [];
        foreach ($arr as $key => $value) {
            $tmp = [];
            if ($key != 0) {
                foreach ($value as $k => $v) {
                    $tmp[$arr[0][$k]] = $v;
                }
                $buf[] = $tmp;
            }
        }

        return $buf;
    }
}
