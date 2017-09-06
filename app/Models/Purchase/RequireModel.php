<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\Purchase\RequireModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseRequireModel;
use App\Models\Product\SupplierModel;
use App\Models\StockModel;
use App\Models\PackageModel;
use App\Models\ItemModel;
use App\Models\Order\ItemModel as OrderItemModel;
use App\Models\Purchase\PurchasesModel;

class RequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requires';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id'=>'id','sku'=>'sku'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    } 
     public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['user' => ['name']],
            'filterFields' => [],
            'filterSelects' => ['require_create' => config('purchase.require'),'thrend' => config('purchase.thrend')],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }
	
    //计算建议采购数量
    public function getNeedPurchaseNum($items){
        foreach ($items as $item) {
            //在途数量
            $purchaseItems = PurchaseItemModel::where("sku",$item['sku'])->whereIn("status",['1', '2','3'])->get();
            $zaitu_num = 0;
            foreach ($item->purchase as $purchaseItem) {
                if($purchaseItem->status>0&&$purchaseItem->status<4){
                    if(!$purchaseItem->purchaseOrder->write_off){
                        if($purchaseItem->purchaseOrder->status>0&&$purchaseItem->purchaseOrder->status<4){
                            $zaitu_num += $purchaseItem->purchase_num-$purchaseItem->storage_qty-$purchaseItem->unqualified_qty;
                        }
                    }
                }
            }
            //实库存
            $itemModel = ItemModel::find($item['item_id']);
            $shi_kucun = $itemModel->all_quantity;
            //虚库存
            $xu_kucun = $shi_kucun - $item['quantity'];

            //7天销量
            $sevenDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                            ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                            ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-7 day')))
                            ->where('order_items.quantity','<',5)
                            ->where('order_items.item_id',$item['id'])
                            ->sum('order_items.quantity');
        
            //14天销量
            $fourteenDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                                ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                                ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-14 day')))
                                ->where('order_items.quantity','<',5)
                                ->where('order_items.item_id',$item['id'])
                                ->sum('order_items.quantity');

            //30天销量
            $thirtyDaySellNum=OrderItemModel::leftjoin('orders','orders.id','=','order_items.order_id')
                                ->whereIn('orders.status',['PAID', 'PREPARED','NEED','PACKED','SHIPPED','COMPLETE'])
                                ->where('orders.create_time','>',date('Y-m-d H:i:s',strtotime('-30 day')))
                                ->where('order_items.quantity','<',5)
                                ->where('order_items.item_id',$item['id'])
                                ->sum('order_items.quantity');

            //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
            if($sevenDaySellNum==0||$fourteenDaySellNum==0){
                $coefficient_status=3;
                $coefficient=1;
            }else{
                if(($sevenDaySellNum/7)/($fourteenDaySellNum/14*1.1) >=1){
                    $coefficient=1.3;
                    $coefficient_status=1;
                }elseif(($fourteenDaySellNum/14*0.9)/($sevenDaySellNum/7) >=1){
                    $coefficient=0.6;
                    $coefficient_status=2;
                }else{
                    $coefficient=1;
                    $coefficient_status=4;
                } 
            }
            
            //预交期
            $delivery=$itemModel->supplier?$itemModel->supplier->purchase_time:7;

            //采购建议数量
            if($itemModel->purchase_price > 200 && $fourteenDaySellNum <3 || $itemModel->status ==4){
                $needPurchaseNum = 0-$xu_kucun-$zaitu_num;
            }else{
                if($itemModel->purchase_price >3 && $itemModel->purchase_price <=40){
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(7+$delivery)*$coefficient-$xu_kucun-$zaitu_num;
                }elseif($itemModel->purchase_price <=3){
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(12+$delivery)*$coefficient-$xu_kucun-$zaitu_num;
                }elseif ($itemModel->purchase_price > 40) {
                    $needPurchaseNum = ($fourteenDaySellNum/14)*(12+$delivery)*$coefficient-$xu_kucun-$zaitu_num;  
                }
            }

            //任务计划使用该函数
            $this->intoPurchaseRequire($item['item_id'],$needPurchaseNum); 
            
        }
            
            
            //退货率
            //$orderNum=orderItemModel::leftjoin('orders','orders.id','=','orderitems.order_id')->where('orders.status',)->where('orderitems.item_id',$item_id)->count();   
            //$orderNum=orderItemModel::where('item_id',$item_id)->count();
            
    }

    public function intoPurchaseRequire($item_id,$needPurchaseNum){
        if(PurchaseRequireModel::where('item_id',$item_id)->count()){
            PurchaseRequireModel::where('item_id',$item_id)->update(['quantity'=>$needPurchaseNum,'status'=>0]);
        }else{
            PurchaseRequireModel::create(['quantity'=>$needPurchaseNum,'item_id'=>$item_id]);
        }       
    }

    //一键生成采购单
    public function createAllPurchaseOrder(){
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $needPurchases=PurchasesModel::where('require_create',1)->get();
        foreach ($needPurchases as $key => $purchases) {
            $itemModel = $purchases->item;
            $data['type']=0;
            //v3测试专用
            if($v['warehouse_id']==1){
                $data['warehouse_id']=3;
            }
            if($v['warehouse_id']==2){
                $data['warehouse_id']=4;
            }
            $data['warehouse_id']=UserModel::find($v->purchase_adminer)->warehouse_id?UserModel::find($v->purchase_adminer)->warehouse_id:'3';
            $data['sku']=$itemModel->sku;
            $data['item_id']=$itemModel->id;
            $data['purchase_cost']=$itemModel->purchase_price;
            $data['supplier_id']=$itemModel->supplier_id ? $itemModel->supplier_id : 0;
            $data['purchase_num']=$purchases->need_purchase_num;
            $data['user_id']=$itemModel->purchase_adminer;
            $data['lack_num']=$data['purchase_num'];

            if($data['purchase_num']>0){
                $p_item = PurchaseItemModel::create($data);
                $fillRequireNum = $this->where("item_id",$itemModel->id)->where('is_require','1')->get()->sum('quantity');
                $fillRequireArray =  $this->where("item_id",$itemModel->id)->get();
                if($fillRequireNum <=$data['purchase_num']){
                    $this->where("item_id",$itemModel->id)->update(['is_require'=>'0']);
                }else{
                    $temp_quantity = 0;
                    foreach ($fillRequireArray as $value) {
                        $temp_quantity += $value->quantity;
                        if($temp_quantity>$data['purchase_num']){
                            $require_id = $value->id;break;
                        }
                    }
                        
                    $this->where("id",'<',$require_id)->where('item_id',$v->id)->update(['is_require'=>'0','purchase_item_id'=>$p_item->id]);
                    
                }
            }
            
            $purchases->update(['require_create'=>'0']);
        }
        $warehouse_supplier=PurchaseItemModel::select('id','warehouse_id','supplier_id','user_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->groupBy('warehouse_id')->groupBy('supplier_id')->groupBy('user_id')->get()->toArray();
        
        if(isset($warehouse_supplier)){
            foreach($warehouse_supplier as $key=>$v){
                //v3测试专用仓库
                if($v['warehouse_id']==1){
                    $data['warehouse_id']=3;
                }
                if($v['warehouse_id']==2){
                    $data['warehouse_id']=4;
                }
                //$data['warehouse_id']=$v['warehouse_id'] ? $v['warehouse_id'] : 0;       
                $data['supplier_id']=$v['supplier_id'] ? $v['supplier_id'] : 0;
                $supplier=SupplierModel::find($v['supplier_id']);
                $data['assigner']=$supplier->purchase_id ? $supplier->purchase_id : 0;
                $purchaseOrder=PurchaseOrderModel::create($data);
                $purchaseOrderId=$purchaseOrder->id; 
                if($purchaseOrderId >0){
                    PurchaseItemModel::where('warehouse_id',$v['warehouse_id'])->where('user_id',$v['user_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->update(['purchase_order_id'=>$purchaseOrderId]); 
                }                
            }       
        }     
    }
}