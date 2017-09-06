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
use App\Models\Product\SupplierModel;

class PurchaseOrderAbnormalController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder)
    {
        $this->model = $purchaseOrder;
        $this->mainIndex = route('purchaseOrderAbnormal.index');
        $this->mainTitle = '异常采购单';
		$this->viewPath = 'purchase.purchaseOrderAbnormal.';
    }
    
	
	public function index()
    {
		$purchaseAbnormalOrder=PurchaseItemModel::select('purchase_order_id')->where('active','>',0)->where('purchase_order_id','>','0')->groupBy('purchase_order_id')->get()->toArray();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->whereIn('id',$purchaseAbnormalOrder)),
        ];
        return view($this->viewPath . 'index', $response);
    }
	/**
     * 修改异常采购单页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function edit($id)
	{	
		$model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
		if ($model->examineStatus !=2) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
        ];
        return view($this->viewPath . 'edit', $response);	
	}
	/**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function cancelOrder($id)
	{	$num=purchaseItemModel::where('purchase_order_id',$id)->where('status','>',1)->count();
		if($num>0){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此采购单不能取消.'));
			}
		$purchaseItem=PurchaseItemModel::where('purchase_order_id',$id)->update(['active'=>0,'active_status'=>0,'remark'=>'','arrival_time'=>'','purchase_order_id'=>0]);
		$this->model->destroy($id);
		return redirect($this->mainIndex);	
	}	 
		
}









