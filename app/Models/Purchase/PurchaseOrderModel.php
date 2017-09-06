<?php
namespace App\Models\Purchase;

use Exception;
use App\Base\BaseModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;
use App\Models\UserModel;
use App\Models\ItemModel;
use Maatwebsite\Excel\Facades\Excel;


class PurchaseOrderModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'purchase_orders';
    public $rules = [
        'create' => [
            'supplier_id' => 'required',
            'warehouse_id' => 'required',
            'item.0.sku' => 'required',
            'item.0.purchase_num' => 'required',
            'item.0.purchase_cost' => 'required',
        ],
        'update' => [
            /*'status' => 'required',*/
        ]
    ];
    public $searchFields = ['id'=>'采购单号','post_coding'=>'外部单号'];


    protected $fillable = [
        'type',
        'status',
        'carriage_type',
        'supplier_id',
        'user_id',
        'update_userid',
        'warehouse_id',
        'costExamineStatus',
        'examineStatus',
        'post_coding',
        'total_postage',
        'total_purchase_cost',
        'is_certificate',
        'close_status',
        'purchase_userid',
        'start_buying_time',
        'arrival_time',
        'assigner',
        'pay_type',
        'write_off',
        'print_num',
        'remark',
        'print_status'
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['purchaseUser' => ['name']],
            'filterFields' => [],
            'filterSelects' => ['warehouse_id' =>$this->getAvailableWarehouse('App\Models\WarehouseModel', 'name'),
                                'status' => config('purchase.purchaseOrder.status'),
                                'examineStatus' => config('purchase.purchaseOrder.examineStatus'),
                                'write_off' => config('purchase.purchaseOrder.write_off'),
                                'type' =>config('purchase.purchaseOrder.type'),
                                'pay_type'=>config('purchase.purchaseOrder.pay_type'),
                                'close_status'=>config('purchase.purchaseOrder.close_status'),
                                ],
            'selectRelatedSearchs' => ['supplier' => ['name' => []],],
            'sectionSelect' => ['time' => ['created_at']],
            'doubleRelatedSearchFields' => ['purchaseItem' => ['productItem' => ['sku']]],
        ];
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function purchasePostage()
    {
        return $this->hasMany('App\Models\Purchase\PurchasePostageModel', 'purchase_order_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function purchaseUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function purchaseItem()
    {
        return $this->hasMany('App\Models\Purchase\PurchaseItemModel', 'purchase_order_id');
    }

    public function getAssignerNameAttribute()
    {
        return UserModel::find($this->assigner)?UserModel::find($this->assigner)->name:'';
    }

    public function getUsersAttribute()
    {
        return UserModel::all();
    }

    //状态颜色
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case '4':
                $color = 'success';
                break;
            case '5':
                $color = 'active';
                break;
            default:
                $color = 'info';
                break;
        }
        return $color;
    }

    //到货时间
    public function getArrivalDayAttribute()
    {
        $startTime = strtotime($this->start_buying_time);
        $needTime = $this->supplier->purchase_time;
        $arrivalTime = $startTime + $needTime * 24 * 3600;
        return date('Y-m-d', $arrivalTime);
    }

    public function updatePurchaseOrder($id, $data)
    {
        $PurchaseOrder = $this->find($id);
        foreach ($data as $key => $v) {
            $PurchaseOrder->$key = $v;
        }
        $PurchaseOrder->save();
    }

    public function createPurchaseOrder($data)
    {   
        $data['user_id'] = request()->user()->id;
        $purchase_order = PurchaseOrderModel::create($data);
        
        foreach ($data['item'] as $item) {
            $item['lack_num'] = $item['purchase_num'];
            $item['purchase_order_id'] = $purchase_order->id;
            $item['supplier_id'] = $data['supplier_id'];
            $item['warehouse_id'] = $data['warehouse_id'];
            $item['item_id'] = ItemModel::where('sku',$item['sku'])->first()->id;
            $item['user_id'] = request()->user()->id;
            PurchaseItemModel::create($item);
        }
    }

    /**
     * 导出单张采购单为单张excel
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector0
     */

    public function allPurchaseExcelOut()
    {
        $name = '采购单';
        $assigner = request()->user()->id;
        $purchaseOrderIds = $this->select('id')->where('assigner', $assigner)->get()->toArray();
        $res = PurchaseItemModel::whereIn('purchase_order_id', $purchaseOrderIds)->orderBy('supplier_id',
            'desc')->get();
        $rows = '';
        foreach ($res as $key => $vo) {
            $supplier_province = $vo->supplier->province;
            $supplier_city = $vo->supplier->city;
            $supplier_address = $vo->supplier->address;
            $rows[$key]['PurcahseOrderID'] = $vo->purchase_order_id;
            $rows[$key]['PurchaseItemID'] = $vo->id;
            $rows[$key]['status'] = config("purchase.purchaseItem.status." . $vo->status);
            $rows[$key]['sku'] = $vo->sku;
            $rows[$key]['purchase_qty'] = $vo->purchase_num;
            $rows[$key]['purchase_price'] = $vo->purchase_cost;
            $rows[$key]['item_name'] = iconv("UTF-8", "gb2312", "'" . $vo->item->product->c_name . "'");
            $rows[$key]['supplier_SKU'] = $vo->item->supplier_sku;
            $rows[$key]['remark'] = $vo->remark;
            $rows[$key]['supplier_name'] = iconv("UTF-8", "gb2312", "'" . $vo->supplier->name . "'");
            $rows[$key]['supplier_link'] = 'http://' . $vo->supplier->url;
            $rows[$key]['purchas_address'] = iconv("UTF-8", "gb2312",
                "'" . $supplier_province . $supplier_city . $supplier_address . "'");
            $rows[$key]['user_id'] = iconv("UTF-8", "gb2312", $vo->user_id);
            $rows[$key]['supplier_telephone'] = $vo->supplier->telephone;
            $rows[$key]['tracking'] = $vo->post_coding;
            $rows[$key]['model'] = $vo->item->product->model;
        }
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '采购单';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * 导出多张采购单为单张excel
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function noArrivalOut()
    {
        $name = '采购单';
        $assigner = request()->user()->id;
        $purchaseOrderIds = $this->select('id')->where('assigner', $assigner)->get()->toArray();
        $res = PurchaseItemModel::whereIn('purchase_order_id', $purchaseOrderIds)->where('start_buying_time', '<',
            date('Y-m-d H:i:s', time() - 3600 * 24 * 3))->orderBy('supplier_id', 'desc')->get();
        $rows = '';
        foreach ($res as $key => $vo) {
            $supplier_province = $vo->supplier->province;
            $supplier_city = $vo->supplier->city;
            $supplier_address = $vo->supplier->address;
            $rows[$key]['PurcahseOrderID'] = $vo->purchase_order_id;
            $rows[$key]['PurchaseItemID'] = $vo->id;
            $rows[$key]['status'] = mb_convert_encoding(config("purchase.purchaseItem.status." . $vo->status), 'gb2312',
                'utf-8');
            $rows[$key]['sku'] = mb_convert_encoding($vo->sku, 'gb2312', 'utf-8');
            $rows[$key]['purchase_qty'] = $vo->purchase_num;
            $rows[$key]['purchase_price'] = $vo->purchase_cost;
            $rows[$key]['item_name'] = mb_convert_encoding($vo->item->product->c_name, 'gb2312', 'utf-8');
            $rows[$key]['supplier_SKU'] = $vo->item->supplier_sku;
            $rows[$key]['remark'] = $vo->remark;
            $rows[$key]['supplier_name'] = mb_convert_encoding($vo->supplier->name, 'gb2312', 'utf-8');
            $rows[$key]['supplier_link'] = 'http://' . $vo->supplier->url;
            //$rows[$key]['purchas_address']=iconv( "gb2312" ,"UTF-8",$supplier_province.$supplier_city.$supplier_address);
            $rows[$key]['user_id'] = $vo->user_id;
            $rows[$key]['supplier_telephone'] = $vo->supplier->telephone;
            $rows[$key]['tracking'] = $vo->post_coding;
            $rows[$key]['model'] = $vo->item->product->model;
            $rows[$key]['assigner'] = $vo->purchaseOrder->assigner;
            $rows[$key]['create_time'] = $vo->created_at;
        }
        //var_dump($rows);exit;
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '采购单';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}