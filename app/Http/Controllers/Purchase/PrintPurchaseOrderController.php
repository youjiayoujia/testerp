<?php
/**
 * 采购条目控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Product\SupplierModel;

class PrintPurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder,PurchaseItemModel $purchaseItem)
    {
        $this->model = $purchaseOrder;
		$this->purchaseItem=$purchaseItem;
        $this->mainIndex = route('printPurchaseOrder.create');
        $this->mainTitle = '采购单';
		$this->viewPath = 'purchase.printPurchaseOrder.';
    }
    
	
	public function index()
    {
		$user=request()->user()->id;
		$purchaseOrders=$this->model->select('id')->where('assigner',$user)->get()->toArray();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->purchaseItem->whereIn('purchase_order_id',$purchaseOrders)),//->where('assigner',12)
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	
	public function create(){
		$user=request()->user()->id;
		$response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' =>$this->model->select('warehouse_id')->where('assigner',$user)->groupBy('warehouse_id')->get(),
			'assigner'=>12,
        ];
		$response['metas']['mainTitle']='打印采购单';
		$response['metas']['title']='打印页';
        return view($this->viewPath . 'create', $response);
	}
	/**
     * ajax 根据仓库筛选采购条目
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function checkWarehouse(){
		$user=request()->user()->id;
		$warehouseId=request()->get('warehouseId');
		$purchaseOrderIds=$this->model->select('id')->where('warehouse_id',$warehouseId)->where('assigner',$user)->get()->toArray();
		$purchaseItemSkus=$this->purchaseItem->select('sku','id')->whereIn('purchase_order_id',$purchaseOrderIds)->where('status',0)->get()->toArray();
		$spus='';
		$spu='';
		$skuArray='';
		$i=-1;
		foreach($purchaseItemSkus as $key=>$vo){
			$skuArray=explode('-',$vo['sku']);
			if($spu!=$skuArray[0].$skuArray[1]){
				$i++;
				$j=-1;
				$spu=$skuArray[0].$skuArray[1];
				$spus[$i]['spu_colour']=$skuArray[0].$skuArray[1];
				$item=$this->purchaseItem->find($vo['id']);
				$spus[$i]['img']=$item->item->product->image->src;
				}
				$j++;
				$spus[$i]['item'][$j]=$this->purchaseItem->find($vo['id']);	
				$spus[$i]['item'][$j]['size']=$skuArray[2];
			}	
		return view($this->viewPath . 'purchaseItemList',['data' => $spus]); 
	}
	/**
     * ajax 根据仓库筛选采购条目
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function warehouseAddress()
	{
		$warehouseId=request()->get('warehouseId');
		$warehouse=WarehouseModel::find($warehouseId)->toArray();		
		$address='地址:'.$warehouse['province'].$warehouse['city'].$warehouse['address'].'&nbsp;电话:'.$warehouse['telephone'];	
		return 	$address;
	}

}









