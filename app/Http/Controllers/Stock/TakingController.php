<?php
/**
 * 盘点控制器
 * 处理盘点相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 16/3/3
 * Time: 10:04am
 */

namespace App\Http\Controllers\Stock;

use Cache;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingFormModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\ItemModel;
use App\Models\Stock\InOutModel;
use App\Models\Warehouse\PositionModel;
use App\Models\StockModel;

class TakingController extends Controller
{
    public function __construct(TakingModel $taking)
    {
        $this->model = $taking;
        $this->mainIndex = route('stockTaking.index');
        $this->mainTitle = '盘点';
        $this->viewPath = 'stock.taking.';
    }

    /**
     * 列表展示 
     *
     * @param $id id号
     * @return view
     *
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
            'stockTakingForms'=>$model->stockTakingForms()->paginate('100'),
            'stockins' => InOutModel::where(['inner_type'=>'INVENTORY_PROFIT', 'relation_id'=>$id])->with('stock')->paginate('500'),
            'stockouts' => InOutModel::where(['inner_type'=>'SHORTAGE', 'relation_id'=>$id])->with('stock')->paginate('500'),
        ];

        return view($this->viewPath.'show', $response);
    }

    /**
     * 信息编辑 
     *
     * @param $id id号
     * @return view
     *
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response= [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'takingForms' => $model->stockTakingForms()->paginate('1000')
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     *  跳转盘点调整页面
     *
     *  @param $id 盘点单id
     *  @return view
     *
     */
    public function takingAdjustmentShow($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas'=>$this->metas(__FUNCTION__),
            'model'=>$model,
            'stockTakingForms' => $model->stockTakingForms()->whereRaw('stock_taking_status != ?', ['equal'])->paginate('1000'),
        ];

        return view($this->viewPath.'takingAdjustmentShow', $response);
    }

    /**
     * 信息更新 
     *
     * @param $id id号
     * @return index
     *
     */
    public function update($id)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1G');
        if(!request()->hasFile('actual_stock')) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '没传文件'));
        }
        $this->model->find($id)->update(['stock_taking_by'=>request()->user()->id, 'stock_taking_time'=>date('Y-m-d h:m:s',time())]);
        $file = request()->file('actual_stock');
        $arr = $this->model->excelProcess($file);
        $flag = 1;
        foreach($arr as $key => $block) {
            if($block['quantity'] == '0') {
                $flag = 0;
                continue;
            }
            $item = ItemModel::where('sku', $block['sku'])->first();
            if(!$item) {
                $flag = 0;
                continue;
            }
            $item_id = $item->id;
            $position = PositionModel::where('name', $block['position'])->first();
            if(!$position) {
                $flag = 0;
                continue;
            }
            $position_id = $position->id;
            $stock = StockModel::where(['item_id' => $item_id, 'warehouse_position_id' => $position_id])->first();
            if(!$stock) {
                $flag = 0;
                continue;
            }
            $stock_id = $stock->id;
            $taking = TakingFormModel::where('stock_id', $stock_id)->first();
            if($taking && $taking->stock) {
                if($taking->stock->all_quantity == $block['quantity']) {
                    $taking->update(['quantity'=>$block['quantity'], 'stock_taking_status'=>'equal', 'stock_taking_yn'=>'0']);
                }
                if($taking->stock->all_quantity < $block['quantity']) {
                    $taking->update(['quantity'=>$block['quantity'], 'stock_taking_status'=>'more', 'stock_taking_yn'=>'1']);
                }
                if($block['quantity'] < $taking->stock->all_quantity && $taking->stock->all_quantity - $block['quantity'] <= $taking->stock->available_quantity) {
                    $taking->update(['quantity'=>$block['quantity'], 'stock_taking_status'=>'less', 'stock_taking_yn'=>'1']);
                }
                if($block['quantity'] < $taking->stock->all_quantity && $taking->stock->all_quantity - $block['quantity'] > $taking->stock->available_quantity) {
                    $taking->update(['quantity'=>$block['quantity'], 'stock_taking_status'=>'less', 'stock_taking_yn'=>'0']);
                    $flag = 0;
                }
            }    
        }
        $flag ? $this->model->find($id)->update(['create_taking_adjustment' => '1']) : $this->model->find($id)->update(['create_taking_adjustment' => '0']);
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
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
        DB::table('stock_taking_forms')->where('stock_taking_id', $model->id)->delete();
        $model->delete();
        Cache::store('file')->forever('stockIOStatus', 1);
        system('supervisorctl start assignStocks');

        return redirect($this->mainIndex);
    }

    /**
     * ajax审核生成盘点表 
     *
     * @param none 
     * @return json
     *
     */
    public function ajaxTakingCreate()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $model = $this->model->find($id);
            $model->update(['create_status' => '1', 'adjustment_by'=>request()->user()->id, 'adjustment_time'=>date('Y-m-d h:m:s', time())]);
            return json_encode('11');
        }
        
        return json_encode('false');
    }

    /**
     * 盘点调整审核页面 
     *
     * @param $id id号
     * @return view
     *
     */
    public function takingCheck($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas'=>$this->metas(__FUNCTION__),
            'model'=>$model,
            'stockTakingForms' => $model->stockTakingForms()->whereRaw('stock_taking_status != ?', ['equal'])->paginate('1000'),
        ];

        return view($this->viewPath.'check', $response);
    }

    /**
     * 盘点调整审核 
     *
     * @param none
     * @return index
     *
     */
    public function takingCheckResult($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if(request()->input('result') == 1) {
            $takingforms = $model->stockTakingForms;
            foreach($takingforms as $takingform) {
                if($takingform->stock_taking_status == 'equal') {
                    continue;
                }
                $item = ItemModel::find($takingform->stock->item_id);
                $warehousePositionId = $takingform->stock->warehouse_position_id;
                $quantity = abs($takingform->stock->all_quantity - $takingform->quantity);
                $amount = $quantity*$takingform->stock->unit_cost;
                $type = $takingform->stock_taking_status;
                $relation_id = $model->id;
                if($type == 'more') {
                    $type = 'INVENTORY_PROFIT';
                    $item->in($warehousePositionId, $quantity, $amount, $type, $relation_id);
                }
                if($type == 'less') {
                    $type = 'SHORTAGE';
                    $item->out($warehousePositionId, $quantity, $type, $relation_id);
                }
            }
            $model->update(['check_by'=>request()->user()->id, 'check_status'=>'2', 'check_time'=>date('Y-m-d h:m:s', time())]);
            Cache::store('file')->forever('stockIOStatus', '1');
            system('supervisorctl start assignStocks');
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '审核已通过'));
        } else {
            $model->update(['check_by'=>request()->user()->id, 'check_status'=>'1', 'check_time'=>date('Y-m-d h:m:s', time())]);
            Cache::store('file')->forever('stockIOStatus', '1');
            system('supervisorctl start assignStocks');
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '审核未通过'));
        }
    }
}