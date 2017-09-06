<?php
namespace App\Models\Warehouse;

use Excel;
use App\Base\BaseModel;
use App\Models\WarehouseModel;

class PositionModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'warehouse_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'warehouse_id', 'remark', 'size', 'length', 'width', 'height', 'is_available'];

    // 用于规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouse_positions,name',
            'warehouse_id' => 'required',
            'size' => 'required',
            ],
        'update' => [
            'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
            'warehouse_id' => 'required',
            'size' => 'required',
            ]
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'warehouse_id' => $this->getAvailableWarehouse('App\Models\WarehouseModel', 'name'),
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'doubleRelatedSearchFields' => [],
        ];
    }

    //查询
    public $searchFields = ['name' => '库位名'];
    
    //仓库关联关系
    public function warehouse()
    {
       return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    //库存关联关系
    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'warehouse_position_id', 'id');
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针 
     *
     */
    public function excelProcess($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path.'excelProcess.xls');
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
        foreach($arr as $key=> $position)
        {
            $position['warehouse'] = iconv('gb2312','utf-8',$position['warehouse']);
            $position['remark'] = iconv('gb2312','utf-8',$position['remark']);
            if(!WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available'=>'1'])->count()) {
                $error[] = $key;
                continue;
            }
            $tmp_warehouse = WarehouseModel::where(['name' => trim($position['warehouse']), 'is_available'=>'1'])->first();
            $position['name']=iconv('gb2312','utf-8',$position['name']);
            $position['warehouse_id'] = $tmp_warehouse->id;
            if(PositionModel::where(['name' => trim($position['name'])])->count()) {
                $tmp_position = PositionModel::where(['name' => trim($position['name'])])->first();
                $tmp_position->update($position);
                continue;
            }

            $tmp_position = $this->create($position);
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
