<?php
/**
 *  供货商控制器
 *  处理与供货商相关的操作
 *
 * @author:MC<178069409@qq.com>
 *    Date:2015/12/18
 *    Time:11:18
 *
 */

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\ItemModel;
use App\Models\Product\SupplierModel;
use App\Models\Item\ItemPrepareSupplierModel;
use App\Models\Product\SupplierLevelModel;
use App\Models\Product\SupplierChangeHistoryModel;
use Tool;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
        $this->mainIndex = route('productSupplier.index');
        $this->mainTitle = '供货商';
        $this->viewPath = 'product.supplier.';
    }


    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, null, ['*'], null, null, ['purchaseName', 'createdByName', 'levelByName']),

            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'levels' => SupplierLevelModel::all(),
            'users' => UserModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $data=request()->all();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->supplierCreate($data, request()->file('qualifications'));
		if($model=='imageError'){
			return redirect(route('productSupplier.create'))->with('alert', $this->alert('danger', '图片格式不正确.'));
		}else{
            $name = UserModel::find(request()->user()->id)->name;
            $to = json_encode($model);
            $this->eventLog($name, '数据更新', $to, '');

/*			SupplierChangeHistoryModel::create([
				'supplier_id' => $model->id,
				'to' =>request()->input('purchase_id'),
				'adjust_by' => '3',
			]);*/
        return redirect($this->mainIndex);
		}
    }

    /**
     * 编辑
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
            'levels' => SupplierLevelModel::all(),
            'users' => UserModel::all(),
            'attachments' => $model->attachment,
            'refer_url' => Tool::referUrl(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        $from = json_encode($model);


        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $data=request()->all();
        $this->validate(request(), $this->model->rules('update'));
/*        if($model->purchase_id != request('purchase_id')) {
            SupplierChangeHistoryModel::create([              
                'supplier_id' => $id,
                'from' => $model->purchase_id,
                'to' =>request()->input('purchase_id'),
                'adjust_by' => '3',
            ]);
        }*/
        $res=$this->model->updateSupplier($id,$data,request()->file('qualifications'));
		if($res == true){
            $name = UserModel::find(request()->user()->id)->name;
            $to = $this->model->find($id);
            $to = json_encode($to);
            $this->eventLog($name, '数据更新', $to, $from);
            return redirect(Tool::referUrl($this->mainIndex))->with('alert', $this->alert('success', '修改成功.'));
        }else{
            return redirect(route('productSupplier.edit', $id))->with('alert', $this->alert('danger', '文件上传失败.'));

        }
    }

    /**
     * 详情
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
        $item_id_arr = ItemPrepareSupplierModel::where('supplier_id',$id)->get(['item_id'])->toArray();
        $item_id = array_column($item_id_arr,'item_id');
        $itemModel = ItemModel::whereIn('id',$item_id)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'itemModel' => $itemModel,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 跳转创建供货商等级 
     *
     * @param none
     * @return view
     *
     */
    public function createLevel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'createLevel', $response);
    }

    /**
     * 等级save 
     *
     * @param none
     * @return view
     *
     */
    public function levelStore()
    {
        SupplierLevelModel::create(request()->all());

        return redirect($this->mainIndex);
    }
	
	public function beExamine(){
		$channel_id = request()->input('channel_id');
        $product_id_str = request()->input('product_ids');
        $product_id_arr = explode(',',$product_id_str);
		$suppliers=$this->model->find($product_id_arr);
		foreach($suppliers as $key=>$vo){
			if($vo->examine_status != 'currentData'){ //审核通过
				$vo->update(['examine_status'=>$channel_id]);
			}
			}
		return 1;	
	}

    /**
     * 获取供应商信息
     */
    public function ajaxSupplier()
    {
        if(request()->ajax()) {
            $supplier = trim(request()->input('supplier'));
            $buf = SupplierModel::where('name', 'like', '%'.$supplier.'%')->where('examine_status','currentData')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }

        return json_encode(false);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $res = $model->destroy($id);

        if($res){
            $post['type']         = 'delete';
            $post['key']          = 'slme';
            $post['suppliers_id'] = $model->id;

            $result = Tool::postCurlHttpsData(config('product.sellmore.api_url'),$post);
        }
        return redirect($this->mainIndex);
    }
	
}