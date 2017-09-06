<?php
/**
 * 库存调整控制器
 * 处理库存调整相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/24
 * Time: 14:22
 */

namespace App\Http\Controllers\Stock;

use DB;
use Exception;
use App\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Models\Stock\AdjustmentModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\InModel;
use App\Models\Stock\OutModel;
use App\Models\StockModel;
use App\Models\Stock\AdjustFormModel;

class AdjustmentController extends Controller
{
    public function __construct(AdjustmentModel $adjust)
    {
        $this->model = $adjust;
        $this->mainIndex = route('stockAdjustment.index');
        $this->mainTitle = '库存调整';
        $this->viewPath = 'stock.adjustment.';
        $this->middleware('StockIOStatus');
    }

    /**
     * 信息详情页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $adjustmentforms = $model->adjustments;
        $access_quantity = [];
        foreach($adjustmentforms as $key => $adjustmentform)
        {
            $stock = StockModel::where(['item_id' => $adjustmentform->item_id, 'warehouse_position_id' => $adjustmentform->warehouse_position_id])->first();
            if($stock) {
                $access_quantity[] = $stock->available_quantity;
            } else {
                $access_quantity[] = 0;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'positions' =>PositionModel::where(['warehouse_id' => $model->warehouse_id, 'is_available' => '1'])->get()->toArray(),
            'access_quantity' => $access_quantity,
        ];

        return view($this->viewPath.'show', $response);
    }

    /**
     * 跳转创建页 
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'users' => UserModel::all(),
        ];

        return view($this->viewPath.'create', $response);
    }

    /**
     * 数据保存 
     *
     * @param none
     * @return view
     *
     */
    public function store()
    {
        request()->flash();
        $name = UserModel::find(request()->user()->id)->name;
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $buf['adjust_by'] = request()->user()->id;
        $obj = $this->model->create($buf);
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {   
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_adjustment_id'] = $obj->id;
            $buf['amount'] = $buf['quantity'] * $buf['unit_cost'];
            if($buf['type'] == 'OUT') {
                $item = ItemModel::find($buf['item_id']);
                $item->hold($buf['warehouse_position_id'], $buf['quantity'], 'ADJUSTMENT', $obj->id);
            }
            AdjustFormModel::create($buf);
        }
        $to = $this->model->with('adjustments')->find($obj->id);
        $to = json_encode($to);
        $this->eventLog($name, '新增调整记录,id='.$obj->id, $to);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
    }

    /**
     * 跳转数据编辑页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $adjustmentforms = $model->adjustments;
        $access_quantity = [];
        foreach($adjustmentforms as $key => $adjustmentform)
        {
            $stock = StockModel::where(['item_id' => $adjustmentform->item_id, 'warehouse_position_id' => $adjustmentform->warehouse_position_id])->first();
            if($stock) {
                $access_quantity[] = $stock->available_quantity + $adjustmentform->quantity;
            } else {
                $access_quantity[] = 0;
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'access_quantity' => $access_quantity,
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     * 数据更新 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function update($id)
    {
        request()->flash();
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $model = $this->model->with('adjustments')->find($id);
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($model);
        foreach($model->adjustments as $single) {
            if($single->type == 'OUT') {
                $item = ItemModel::find($single->item_id);
                $item->unhold($single->warehouse_position_id, $single->quantity, 'ADJUSTMENT', $model->id);
            }
        }
        $obj = $model->adjustments;
        $obj_len = count($obj);
        $buf['adjust_by'] = request()->user()->id;
        $this->model->find($id)->update($buf);
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['adjust_forms_id'] = $id;
            $buf['amount'] = $buf['quantity'] * $buf['unit_cost'];
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }
        $model = $this->model->find($id);
        foreach($model->adjustments as $single) {
            if($single->type == 'OUT') {
                $item = ItemModel::find($single->item_id);
                $item->hold($single->warehouse_position_id, $single->quantity, 'ADJUSTMENT', $model->id);
            }
        }
        $to = json_encode($model);
        $this->eventLog($name, '调整记录更新,id='.$model->id, $to, $from);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }

    /**
     * 记录删除 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function destroy($id)
    {
        $obj = $this->model->find($id);
        foreach($obj->adjustments as $val)
            $val->delete();
        $obj->delete($id);

        return redirect($this->mainIndex);
    }

    /**
     * 新增一个条目
     * 
     * @param current int warehouse 仓库
     * @return view
     *
     */
    public function ajaxAdjustAdd()
    {
        if(request()->ajax()) {
            $current = request()->input('current');
            $warehouse = request()->input('warehouse');
            $response = [
                'current' => $current,
                'positions' => PositionModel::where(['warehouse_id' => $warehouse, 'is_available' => '1'])->get(),
            ];

            return view($this->viewPath.'add', $response);
        }
    }

    /**
     * 跳转审核页面 
     *
     * @param $id adjustment  id
     * @return view 
     *
     */
    public function check($id)
    {
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'adjustments' => $model->adjustments,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
        ];

        return view($this->viewPath.'check', $response);
    }

    /**
     * 审核结果处理 
     *
     *  @param $id adjustment id
     *  @return index
     *
     */
    public function checkResult($id)
    {
        $result = request('result');
        $obj = $this->model->find($id);
        $name = UserModel::find(request()->user()->id)->name;
        if($result) {
            $obj->update(['status'=>'2', 'check_time'=>date('Y-m-d h:i:s'), 'check_by'=>request()->user()->id]); 
            $obj->relation_id = $obj->id;
            $arr = $obj->toArray();
            $buf = $obj->adjustments->toArray();
            DB::beginTransaction();
            try {
                for($i=0;$i<count($buf);$i++) {
                    $tmp = array_merge($arr,$buf[$i]);
                    $item = ItemModel::find($tmp['item_id']);
                    if($tmp['type'] == 'IN') {
                        $tmp['type'] = 'ADJUSTMENT';
                        $item->in($tmp['warehouse_position_id'], $tmp['quantity'], $tmp['amount'], $tmp['type'], $tmp['relation_id'], $tmp['remark']);
                    } else {
                        $tmp['type'] = 'ADJUSTMENT';
                        $item->holdout($tmp['warehouse_position_id'], $tmp['quantity'], $tmp['type'], $tmp['relation_id'], $tmp['remark']);
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
            $to = json_encode($obj);
            $this->eventLog($name, '审核通过', $to, $to);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '已审核...'));
        } else {
            $obj->update(['status'=>'1', 'check_time'=>date('Y-m-d h:i:s'), 'check_by'=>'2']);
            $to = json_encode($obj);
            $this->eventLog($name, '审核未通过', $to, $to);
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '审核未通过...'));
        }
    }
}