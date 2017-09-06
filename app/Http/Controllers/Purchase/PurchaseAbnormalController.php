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
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Product\SupplierModel;

class PurchaseAbnormalController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseItem )
    {
        $this->model = $purchaseItem;
        $this->mainIndex = route('purchaseAbnormal.index');
        $this->mainTitle = '异常采购条目';
		$this->viewPath = 'purchase.purchaseAbnormal.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('active','>',0)),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 批量创建异常采购条目页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{
		 $response = [
			'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'create', $response);		
	}
	
	/**
     * 批量创建异常采购条目
	 *     
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store()
	{
		$data=request()->all();
		$data['skus']=explode('#',$data['sku']);
		foreach($data['skus'] as $k=>$sku){
			$this->model->where('sku',$sku)->where('status','<',2)->update(['active'=>$data['active'],'active_status'=>1]);
		}
		return redirect($this->mainIndex);
	}
	
	/**
     * 修改异常采购条目
	 *     
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
		
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'secondSupplier'=>SupplierModel::find($model->item->second_supplier_id),
        ];
        return view($this->viewPath . 'edit', $response);
    }
	
	/**
     * 处理异常采购条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $data=request()->all();
		if($model->active==1 && $data['active_status']==0){
		if(empty($data['supplier_id'])){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '请选择更改后的供应商'));
			}else{
				ItemModel::where('sku',$model->sku)->update(['supplier_id'=>$data['supplier_id']]);
				}
		}
        $model->update($data);
		if($model->active==1 && $data['active_status']==2){
			$this->model->destroy($id);
			}
        return redirect($this->mainIndex);
    }

	

}









