<?php

namespace App\Models\Stock;

use App\Base\BaseModel;
use DB;


class TakingModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_takings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['taking_id', 'stock_taking_by', 'create_taking_adjustment', 'stock_taking_time', 'adjustment_by', 'adjustment_time', 'check_by', 'create_status', 'check_status', 'check_time', 'created_at'];


    // 用于查询
    public $searchFields = ['taking_id' => '盘点表id'];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'check_status' => ['0' => '未审核', '1' => '未通过', '2' => '已通过'],
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'doubleRelatedSearchFields' => [],
        ];
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingForms()
    {
        return $this->hasMany('App\Models\Stock\TakingFormModel', 'stock_taking_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function checkByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'stock_taking_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function adjustmentByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'adjustment_by', 'id');
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针
     *
     */
    public function excelProcess($file)
    {
        $path = config('setting.stockExcelPath');
        !file_exists($path . 'stockExcelProcess.csv') or unlink($path . 'stockExcelProcess.csv');
        $file->move($path, 'stockExcelProcess.csv');
        return $this->excelDataProcess($path . 'stockExcelProcess.csv');
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
        // var_dump($arr);exit;
        // foreach ($arr as $key => $stock) {
        //     $stock['position'] = iconv('gb2312', 'utf-8', $stock['position']);
        //     if (!PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->count()) {
        //         $error[] = $key;
        //         continue;
        //     }
        //     $stock['sku'] = iconv('gb2312', 'utf-8', $stock['sku']);
        //     $tmp_position = PositionModel::where(['name' => trim($stock['position']), 'is_available' => '1'])->first();
        //     if (!ItemModel::where(['sku' => $stock['sku']])->count()) {
        //         $error[] = $key;
        //         continue;
        //     }
        //     $tmp_item = ItemModel::where(['sku' => trim($stock['sku'])])->first();
        //     if (StockModel::where([
        //         'item_id' => $tmp_item->id,
        //         'warehouse_position_id' => $tmp_position->id
        //     ])->count()
        //     ) {
        //         $error[] = $key;
        //         continue;
        //     }
        //     DB::beginTransaction();
        //     try {
        //     $tmp_item->in($tmp_position->id, $stock['all_quantity'], $stock['all_quantity'] * $tmp_item->purchase_price,
        //         'MAKE_ACCOUNT');
        //     } catch(Exception $e) {
        //         DB::rollback();
        //         $error[] = $key;
        //     }
        //     DB::commit();
        // }

        return $arr;
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
