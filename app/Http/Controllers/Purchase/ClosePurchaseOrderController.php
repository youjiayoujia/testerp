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
use App\Models\Purchase\PurchasePostageModel;

class ClosePurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder)
    {
        $this->model = $purchaseOrder;
        $this->mainIndex = route('closePurchaseOrder.index');
        $this->mainTitle = '采购单结算';
		$this->viewPath = 'purchase.closePurchaseOrder.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('status','>',0)),
        ];
		foreach($response['data'] as $key=>$v ){
			$response['data'][$key]['sumPostage']=PurchasePostageModel::where('purchase_order_id',$v->id)->sum('postage');
			}
        return view($this->viewPath . 'index', $response);
    }
	
	
	
 	/**
     * 采购页面
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
		if ($model->costExamineStatus !=2) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '成本未审核通过的采购单.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
			'sumPostage'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage'),
        ];
        return view($this->viewPath . 'edit', $response);	
	}
	 
 
	/**
     * 结算采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$model=$this->model->find($id);
		if ($model->examineStatus !=1 ) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未审核通过的采购单.'));
        }
		$data=request()->all();
		$items=PurchaseItemModel::where('purchase_order_id',$id)->get();
		foreach($items as $key=>$v){
		if($v->status ==0 || $v->status ==5){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '未开始的或已取消的采购单不能结算.'));
			}
		}
		$model->update($data);
        return redirect($this->mainIndex);		
	}
	
	 
	 
}









