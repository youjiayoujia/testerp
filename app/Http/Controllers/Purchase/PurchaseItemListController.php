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
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\StorageLogModel;
use Maatwebsite\Excel\Facades\Excel; 

class PurchaseItemListController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseList)
    {
        $this->model = $purchaseList;
        $this->mainIndex = route('purchaseItemList.index');
        $this->mainTitle = '采购条目';
		$this->viewPath = 'purchase.purchaseItemList.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
		foreach($response['data'] as $key=>$vo){
			$response['data'][$key]['all_quantity']=StockModel::where('item_id',$vo->item->id)->sum('all_quantity');
			}
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 产看界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
        ];
        return view($this->viewPath . 'edit', $response);
    }
	
	/**
     * 编辑界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
			'storageLogs'=>InModel::where('relation_id',$id)->get(),
        ];
        return view($this->viewPath . 'show', $response);
    }
	
	/**
     * 对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$model=$this->model->find($id);
		$data['active_status']=1;
		$model->update($data);
        return redirect($this->mainIndex);		
	}
	/**
     * 批量还原采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseItemReduction(){
		$response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
		
        return view($this->viewPath . 'itemReduction', $response);
		}
		
	/**
     * 批量更改采购需求状态
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function reductionUpdate(){
		$data=request()->all();
		$updateIds=explode('#',$data['purchaseItemIds']);
		$items=$this->model->find($updateIds);
		foreach($items as $key=>$item){
			if($data['status'] == 0){
				if($item->status == 1){
					$item->update(['status'=>0]);
				}
				$orderItemNum=$this->model->where('purchase_order_id',$item->purchase_order_id)->where('status','>',0)->count();
				if($orderItemNum ==0){
					PurchaseOrderModel::where('id',$item->purchase_order_id)->update(['status'=>0]);	
				}
			}elseif($data['status'] == 1){
				if($item->status == 0){
					$item->update(['status'=>1]);
				}			
				PurchaseOrderModel::where('id',$item->purchase_order_id)->where('examineStatus',2)->update(['status'=>1]);	
			}elseif($data['status'] == 3){
				if($item->status < 2 ){
					$item->destroy($item->id);
				}
				}
		}
		return redirect($this->mainIndex);
	}
	
	/**
     * 单个还原采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function itemReductionUpdate($id){	 
		$item=$this->model->find($id);
			if($item->status == 1){
				$item->update(['status'=>0]);
			}
			$orderItemNum=$this->model->where('purchase_order_id',$item->purchase_order_id)->where('status','>',0)->count();
			if($orderItemNum ==0){
				PurchaseOrderModel::where('id',$item->purchase_order_id)->update(['status'=>0]);	
				}
		return redirect($this->mainIndex);
	}
	/**
     * excel导入回传采购价格采购物流页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function excelReduction(){
		$response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'excelReduction', $response);
	}
	/**
     * excel导入回传采购价格采购物流页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function postExcelReduction(){
		$response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'postExcelReduction', $response);
	}
	/**
     * 回传采购物流单号及物流费用
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseItemPostExcel(){
		$name='采购物流回传范本';
		$rows ='';
		$rows=[
				[
				 'purchase_item_id'=>'',
				 'post_coding'=>'',
				 'postage'=>'',
				 ]
			 ];
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购物流回传范本';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('csv');
		
	}
	/**
     * 回传采购价格excel
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseItemPriceExcel(){
		$name='采购价格回传范本';
		$rows ='';
		$rows=[
				[
				 'purchase_item_id'=>'',
				 'purchase_price'=>'',
				 ]
			 ];
		Excel::create($name, function($excel) use ($rows) {
			$nameSheet='采购价格回传范本';
			$excel->sheet($nameSheet, function($sheet) use ($rows) {
				$sheet->fromArray($rows);
			});
		})->download('csv');
	}	
	/**
     * excel采购价格回传
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function excelReductionUpdate()
    {
        if (request()->hasFile('excel')) {
            $file = request()->file('excel');
            $errors = $this->model->excelProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__, '导入结果'),
                'errors' => $errors,
            ];
            return view($this->viewPath . 'excelReduction', $response);
        }
    }
	
	/**
     * excel物流单号物流费用回传
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function excelReductionUpdatePost()
    { 
        if (request()->hasFile('excel')) {
            $file = request()->file('excel');
            $errors = $this->model->postExcelDataProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__, '导入结果'),
                'errors' => $errors,
            ];
            return view($this->viewPath . 'postExcelReduction', $response);
        }
    }
		
}









