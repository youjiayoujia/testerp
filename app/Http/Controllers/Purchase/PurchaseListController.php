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
use App\Models\Purchase\PurchasePostageModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Warehouse\PositionModel;
use Excel;

class PurchaseListController extends Controller
{

    public function __construct(PurchasePostageModel $PurchasePostageModel)
    {	
        $this->model = $PurchasePostageModel;
        $this->mainIndex = route('purchaseList.index');
        $this->mainTitle = '包裹扫描';
		$this->viewPath = 'purchase.purchaseList.';
    }
    
	
	public function index()
    {
		request()->flash();

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
    
        return view($this->viewPath . 'index', $response);
    }
	
	public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
		$response['metas']['title']='查询采购运单';
        return view($this->viewPath . 'create', $response);
    }
	/**
     * 关联运单号
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function selectPurchaseOrder()
    { 
		$data=request()->all();
		$postcodingNum=PurchasePostageModel::where('post_coding',$data['post_coding'])->count();
		if($postcodingNum>0){
    		$res['postcoding']=PurchasePostageModel::where('post_coding',$data['post_coding'])->first();
    		$res['purchaseOrder']=PurchaseOrderModel::find($res['postcoding']->purchase_order_id);
    		$res['purchaseItems']=$this->model->where('purchase_order_id',$res['postcoding']->purchase_order_id)->get();
            $response = [
                'metas' => $this->metas(__FUNCTION__),
    			'postcodingNum' =>$postcodingNum,
    			'data' =>$res,
    			'postCoding' =>$data['post_coding'],
            ];
		}else{
    			$response = [
                'metas' => $this->metas(__FUNCTION__),
    			'postcodingNum' =>$postcodingNum,
    			'postCoding' =>$data['post_coding'],
            ];
		}
		$response['metas']['title']='查询采购运单';
        return view($this->viewPath . 'show', $response);
    }
	/**
     * 批量对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	//public function examinePurchaseItem($purchase_item_id,$arrival_num)
	public function examinePurchaseItem()
	{ 
		$purcahse_active=explode(',',request()->get('purcahse_active'));
		foreach($purcahse_active as $key=>$value){
			$purcahse=explode('+',$value);
			$arrayItems=$this->model->find($purcahse[0]);
			if($arrayItems->item->weight >0){	
			if($purcahse[1]>0){
				$arrayItems->update(['active_status'=>1,'active'=>$purcahse[1]]);	
			}
			if($purcahse[1]==0 && $arrayItems->costExamineStatus ==2){
			$this->generateBarCode($arrayItems->id);
			$this->model->where('id',$purcahse[0])->where('stock_id','>',0)->update(['status'=>2,'arrival_num'=>$arrayItems->purchase_num,'lack_num'=>0,'arrival_time'=>date('Y-m-d h:i:s',time())]);
			$num=$this->model->where('purchase_order_id',$arrayItems->purchase_order_id)->where('status','<',2)->count();
			$purchaseOrder=PurchaseOrderModel::find($arrayItems->purchase_order_id);
			if($num==0){
				$purchaseOrder->update(['status'=>3]);
			}
			}
		}
		}
		return 1;
		
	}
	 
	/**
     * 生成条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function generateBarCode($id){
			$model=$this->model->find($id);
			$stock_num=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->count();
			if($stock_num>0){
				$res=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->get();
				foreach($res as $key=>$v){
					$stockInIds[$key]=$v->id;
				}
				$randKey=array_rand($stockInIds,1);
				$stock=StockModel::find($stockInIds[$randKey]);	
				$model->update(['bar_code'=>$model->sku,'stock_id'=>$stock->id]);
			}else{
				$position=PositionModel::where('warehouse_id',$model->warehouse_id)->get();
				$position_num=PositionModel::where('warehouse_id',$model->warehouse_id)->count();
				if($position_num == 0){
					continue;
					}
					foreach($position as $key=>$v){
						$WarehousePositionIds[$key]=$v->id;
					}
				$randKey=array_rand($WarehousePositionIds,1);
				$stockData['warehouse_id']=$model->warehouse_id;
				$stockData['item_id']=$model->item->id;
				$stockData['warehouse_position_id']=$WarehousePositionIds[$randKey];
				$stockData['all_quantity']=$model->arrival_num;
				$stockData['hold_quantity']=$model->arrival_num;
				$stockData['amount']=$model->purchase_cost;
				$stockCreate=StockModel::create($stockData);
				$stockId=$stockCreate->id;
				$model->update(['bar_code'=>$model->sku,'stock_id'=>$stockId]);
		}
	}

    public function export($str)
    {
        $arr = explode('|', $str);
        $rows = [];
        foreach($arr as $key => $single) {
            if($single) {
                $buf = explode('.', $single);
                $rows[] = [
                    '运单号' => $buf[0],
                    '扫描人' => $buf[1],
                    '扫描时间' => $buf[2],
                ];
            }
        }
        $name = 'export';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

	/**
     * 查看条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function printBarCode($id){
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'model' => $this->model->find($id),
        ];
		 return view($this->viewPath . 'printBarCode', $response);
	}

	/**
     * 保存到货数量
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function purchaseItemArrival(){
		$num = request()->input("num");

		echo $num;exit;
	}

	/**
     * 删除采购单和运单关联关系
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function deletePostage(){
		$id = request()->input("id");
		$model = PurchasePostageModel::find($id);
		$model->delete();
		echo json_encode(1);
	}

	/**
     * ajax回传修改产品重量
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function changeItemWeight(){
		$itemWeight=request()->get('item_weight');
		$purchase_id=request()->get('purchase_id');
		$model=$this->model->find($purchase_id);
		ItemModel::where('sku',$model->sku)->update(['weight'=>$itemWeight]);
		return 1;
		}
	
	/**
     * ajax回传修改采购条目物流单号
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function changePurchaseItemPostcoding(){
		$post_coding=request()->get('post_coding');
		$purchase_id=request()->get('purchase_id');
		$model=$this->model->find($purchase_id);
		$model->update(['post_coding'=>$post_coding]);
		return 1;
		}	
/**
     * ajax采购入库
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function changePurchaseItemStorageQty(){
		$storage_qty=request()->get('storage_qty');
		$purchase_id=request()->get('purchase_id');
		$model=$this->model->find($purchase_id);
		$model->update(['storage_qty'=>$storage_qty]);
		if($storage_qty>0){
			$model->update(['status'=>3]);
			PurchaseOrder::find($model->purchase_order_id)->update(['status',3]);
		}
		if($model->purchase_num ==$storage_qty){
			$model->update(['status'=>4]);
			}
			$num=$this->model->where('purchase_order_id',$model->purchase_order_id)->where('status','<>',4)->count();
			if($num ==0){
			PurchaseOrder::find($model->purchase_order_id)->update(['status',4]);
			}
		return 1;
		}
		
	public function binding(){
		$postage=request()->get('postage');
		$purchaseOrderId=request()->get('purchaseOrderId');
		$postCoding=request()->get('postCoding');
		$wuliu_id = request()->input('wuliu_id');
		$purchaseNum=PurchaseOrderModel::where('id',$purchaseOrderId)->count();
		$data['post_coding']=$postCoding;
		$data['postage']=$postage;
		$data['purchase_order_id']=$purchaseOrderId;
		$data['user_id'] = request()->user()->id;

		if($purchaseNum>0){
			$model = PurchasePostageModel::find($wuliu_id);
			$model->update($data);
			return 1;
		}else{
			return 2;
		}
			
	}

	public function ajaxScan(){
        $id = request()->input('id');

		$postcodingNum=PurchasePostageModel::where('post_coding',$id)->count();
		if($postcodingNum>0){
			$res['postcoding']=PurchasePostageModel::where('post_coding',$id)->first();
			$res['purchaseOrder']=PurchaseOrderModel::find($res['postcoding']->purchase_order_id)?PurchaseOrderModel::find($res['postcoding']->purchase_order_id)->id:'0';
			//print_r($res['purchaseOrder']);exit;
			$res['purchaseItems']=$this->model->where('purchase_order_id',$res['postcoding']->purchase_order_id)->get();
	        $response = [
	            'metas' => $this->metas(__FUNCTION__),
				'postcodingNum' =>$postcodingNum,
				'data' =>$res,
				'postCoding' =>$id,
				'bang'=>0,
	        ];
		}else{
			$model = PurchasePostageModel::create(['post_coding'=>$id]);
			$response = [
            'metas' => $this->metas(__FUNCTION__),
			'postcodingNum' =>$postcodingNum,
			'postCoding' =>$id,
			'wuliu_id' =>$model->id,
			'bang'=>1,
        	];
		}
        return view($this->viewPath . 'ajaxScan', $response);
    }	
	
}









