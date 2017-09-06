<?php
namespace App\Models\Purchase;
use Excel;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchasePostageModel;

class PurchaseItemModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_items';
    public $rules = [
        'create' => [
            'purchase_num'=> 'required|numeric',
        ],
        'update' => [
 			
        ]
    ];
    public $searchFields = ['id','sku','supplier_id','warehouse_id','user_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['type','status','unqualified_qty','order_item_id','item_id','sku','supplier_id','purchase_num','arrival_num','lack_num','user_id','update_userid','warehouse_id','purchase_order_id','postage','post_coding','storageStatus','purchase_cost','costExamineStatus','active','active_status','start_buying_time','arrival_time','bar_code','storage_qty','remark','stock_id','wait_remark','wait_time'];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'sku','sku');
    }
    public function productItem()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }
	 public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseOrderModel', 'purchase_order_id');
    }
	 public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id');
    }

    public function getWarehousePositionNameAttribute()
    {
        $stock = StockModel::where('item_id',$this->item_id)->where('warehouse_id',$this->warehouse_id)->get()->first();
        if($stock){
            return $stock->position?$stock->position->name:'';
        }else{
            return '';
        }
        
    }

    public function arrivalLog()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemArrivalLogModel', 'purchase_item_id');
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
        !file_exists($path . 'excelProcess.xls') or unlink($path . 'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path . 'excelProcess.xls');
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
        foreach ($arr as $key => $vo) {
			$model=$this->find($vo['purchase_item_id']);
			//回传采购价格
			if($vo['purchase_price']>0){
				if($vo['purchase_price'] > 0.6*$model->item->purchase_price && $vo['purchase_price'] < 1.3*$model->item->purchase_price){
						ItemModel::where('sku',$model->sku)->update(['purchase_price'=>$vo['purchase_price']]);
						$data['purchase_cost']=$vo['purchase_price'];
						$data['costExamineStatus']=2;
					}else{
						$data['purchase_cost']=$vo['purchase_price'];
					}
			}
			
			$model->update($data);
        }

        
    }
	 /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
	
	 public function postExcelDataProcess($path)
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
        foreach ($arr as $key => $vo) {
			$model=$this->find($vo['purchase_item_id']);
			//回传物流运单号和运费
			if($vo['post_coding']){
				$data['post_coding']=$vo['post_coding'];
				$postNum=PurchasePostageModel::where('post_coding',$vo['post_coding'])->first();
				if(count($postNum)==0){
					$purchasePost['purchase_item_id']=$vo['purchase_item_id'];
					$purchasePost['purchase_order_id']=$model->purchase_order_id;
					$purchasePost['post_coding']=$vo['post_coding'];
					$purchasePost['postage']=$vo['postage'];
					PurchasePostageModel::create($purchasePost);
				}else{
					if($vo['postage'] > 0 && $vo['postage']!=$postNum->postage){
						PurchasePostageModel::where('post_coding',$vo['post_coding'])->update(['postage'=>$vo['postage']]);
					}
				}
				
				}
			$model->update($data);
        }

      
    }
	/**
     * 将arr转换成相应的格式
     *
     * @param $arr type:array
     * @return array
     *
     */
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