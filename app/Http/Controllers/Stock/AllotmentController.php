<?php
/**
 * 库存调拨控制器
 * 处理库存调拨相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/1/11
 * Time: 11:09
 */

namespace App\Http\Controllers\Stock;

use DB;
use App\Http\Controllers\Controller;
use App\Models\Stock\AllotmentModel;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\AllotmentFormModel;
use App\Models\Stock\OutRepository;
use App\Models\StockModel;
use App\Models\Stock\AllotmentLogisticsModel;
use App\Models\Stock\InOutModel;
use Tool;
use App\Models\LogisticsModel;
use App\Models\UserModel;

class AllotmentController extends Controller
{
    public function __construct(AllotmentModel $allotment)
    {
        $this->model = $allotment;
        $this->mainIndex = route('stockAllotment.index');
        $this->mainTitle = '库存调拨';
        $this->viewPath = 'stock.allotment.';
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
            'allotments' =>$this->model->find($id)->allotmentforms,
            'stockins' => InOutModel::where(['inner_type'=>'ALLOTMENT', 'outer_type' => 'IN', 'relation_id'=>$id])->with('stock')->get(),
            'stockouts' => InOutModel::where(['inner_type'=>'ALLOTMENT', 'outer_type' => 'OUT', 'relation_id'=>$id])->with('stock')->get(),
            'logisticses' => AllotmentLogisticsModel::where('allotment_id', $id)->get(),
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
            'warehouses' => WarehouseModel::where(['is_available' => '1'])->whereIn('type',['local', 'fbaLocal'])->get(),
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
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.item_id')));
        $name = UserModel::find(request()->user()->id)->name;
        $buf = request()->all();
        $buf['allotment_by'] = request()->user()->id;
        $obj = $this->model->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_allotment_id'] = $obj->id;
            $buf['amount'] = $buf['quantity'] * $buf['unit_cost'];
            AllotmentFormModel::create($buf);
            $item = ItemModel::find($buf['item_id']);
            $item->hold($buf['warehouse_position_id'], $buf['quantity'], 'ALLOTMENT', $obj->id);
        }
        $to = $this->model->with('allotmentforms')->find($obj->id);
        $to = json_encode($to);
        $this->eventLog($name, '新增调拨记录,id='.$obj->id, $to);

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
        $allotment = $model->allotmentforms;
        $arr = [];
        $available_quantity = [];
        foreach($allotment as $key => $value) 
        {
            $obj = StockModel::where(['warehouse_id'=>$model->out_warehouse_id, 'item_id'=>$value->item_id])->get();
            $available_quantity[] =  $obj->first()->available_quantity;
            $buf = [];
            foreach($obj as $v)
            {   
                $buf[] = $v->position->toArray();
            }
            $arr[] = $buf;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $model,
            'warehouses' => WarehouseModel::where(['is_available' => '1'])->whereIn('type',['local', 'fbaLocal'])->get(),
            'skus' => StockModel::where(['warehouse_id'=>$model->out_warehouse_id])->distinct()->with('item')->get(['item_id']),
            'positions' => $arr,
            'allotmentforms' => $allotment, 
            'availquantity' => $available_quantity,
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
        $model = $this->model->with('allotmentforms')->find($id);
        $this->validate(request(), $this->model->rule(request()));
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $obj = $model->allotmentforms;
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($model);
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->unhold($value->warehouse_position_id, $value->quantity, 'ALLOTMENT', $model->id);
        }
        $obj_len = count($obj);
        $model->update($buf);
        $arr = request()->input('arr');
        for($i=0; $i<$len; $i++)
        {   
            unset($buf);
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $buf['stock_allotment_id'] = $id;
            $buf['amount'] = round($buf['unit_cost'] * $buf['quantity'], 3);
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }
        $obj = $model->allotmentforms;
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->hold($value->warehouse_position_id, $value->quantity, 'ALLOTMENT', $model->id);
        }
        $to = json_encode($model);
        $this->eventLog($name, '调拨记录更新,id='.$model->id, $to, $from);

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
        foreach($obj->allotmentforms as $val) {
            $item = ItemModel::find($val->item_id);
            $item->unhold($val->warehouse_position_id, $val->quantity, 'ALLOTMENT', $obj->id);
            $val->delete();
        }
        foreach($obj->logistics as $tmp)
            $tmp->delete();
        $obj->delete();

        return redirect($this->mainIndex);
    }

    /**
     * 调拨单审核处理 
     *
     * @param $id 调拨单id
     * @return mainIndex
     *
     */
    public function checkResult($id)
    {
        $model = $this->model->find($id);
        $arr = request()->all();
        $time = date('Y-m-d',time());    
        $name = UserModel::find(request()->user()->id)->name;   
        if($arr['result'] == 0) {
            $model->update(['check_status'=>'1', 'remark'=>$arr['remark'], 'check_time'=>$time, 'check_by'=>request()->user()->id]);
            $to = json_encode($model);
            $this->eventLog($name, '审核未通过', $to, $to);
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '审核已拒绝...'));
        }
        $time = date('Y-m-d',time());       
        $model->update(['check_status'=>'2', 'remark'=>$arr['remark'], 'check_time'=>$time, 'check_by'=>request()->user()->id]); 
        $to = json_encode($model);
        $this->eventLog($name, '审核通过', $to, $to);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '已审核...'));
    }

    /**
     * 跳转出库物流回传页面
     *
     *  @return view
     *
     */
    public function checkout()
    {
        $id = request()->input('id');
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath.'checkout', $response);
    }

    /**
     * 出库操作
     * 物流信息回传，出库
     *
     * @return mainIndex
     *
     */
    public function getLogistics($id)
    {
        $model = $this->model->find($id);
        if(!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $name = UserModel::find(request()->user()->id)->name; 
        $arr = request()->all();
        $arr['allotment_id'] = $id;
        DB::beginTransaction();
        try {
            $model->logistics()->create($arr);
            $model->update(['allotment_status'=>'out']);
            $model->relation_id = $model->id;
            $arr = $model->toArray();
            $buf = $model->allotmentforms->toArray();
            for($i=0;$i<count($buf);$i++) {
                $tmp = array_merge($arr, $buf[$i]);
                $tmp['type'] = 'ALLOTMENT';
                $item = ItemModel::find($tmp['item_id']);
                $item->holdout($tmp['warehouse_position_id'], $tmp['quantity'], 'ALLOTMENT', $model->id);
            }
        } catch(Exception $e) {
            DB::rollback();
        }
        DB::commit();
        $to = json_encode($model);
        $this->eventLog($name, '确认出库', $to, $to);
        return redirect($this->mainIndex);
    }

    /**
     * 强制结束调拨单 
     *
     *  @return mainIndex
     *
     */
    public function allotmentOver($id)
    {
        $model = $this->model->find($id);
        $model->update(['allotment_status'=>'over']);
        $name = UserModel::find(request()->user()->id)->name; 
        $to = json_encode($model);
        $this->eventLog($name, '强制结束调拨单', $to, $to);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '强制结束调拨单成功'));
    }

    /**
     * 跳转调拨单审核页面
     *
     * @return view
     *
     */
    public function allotmentCheck($id)
    {   
        $model = $this->model->find($id);
        $allotmentform = $model->allotmentforms;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'allotments' => $allotmentform,
        ];

        return view($this->viewPath.'allotmentcheck', $response);
    }

    /**
     * 处理ajax请求，返回重新审核 
     *
     *  @param none
     *  @return any
     *
     */
    public function ajaxAllotmentNew()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $model = $this->model->find($id);
            $model->update(['allotment_status'=>'new', 'check_status'=>'0', 'check_time'=>'0000-00-00',
                'check_by'=>request()->user()->id]);
            $name = UserModel::find(request()->user()->id)->name; 
            $to = json_encode($model);
            $this->eventLog($name, '重置信息', $to, $to);
            return json_encode('111');
        }

        return json_encode('false');
    }

    /**
     *  处理ajax请求 
     *
     *  @param none
     *  @return view
     *
     */
    public function ajaxAllotmentAdd()
    {
        if(request()->ajax()) {
            $current = request()->input('current');
            $response = [

                'current'=>$current,
            ];
            return view($this->viewPath.'add', $response);
        }
    }

    /**
     * ajax请求函数
     *  
     * @param none
     * @return json
     *
     */
    public function allotmentpick($id)
    {
        $model = $this->model->find($id);
        $name = UserModel::find(request()->user()->id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($model->allotment_status == 'new') {
            $model->update(['allotment_status'=>'pick']);
        }
        $allotmentforms = AllotmentFormModel::where('stock_allotment_id', $id)->orderBy('warehouse_position_id')->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'allotmentforms' => $allotmentforms,
        ];
        $to = json_encode($model);
        $this->eventLog($name, '打印拣货单', $to, $to);
        
        return view($this->viewPath.'printAllotment', $response);
    }

    /**
     * 跳转对单页面 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function checkform($id)
    {
        $model = $this->model->find($id);
        $allotmentforms = $model->allotmentforms;
        $arr = [];
        foreach($allotmentforms as $allotmentform) {
            $buf = [];
            $stocks = StockModel::where(['warehouse_id' => $model->in_warehouse_id, 'item_id' => $allotmentform->item_id])->with('position')->get();
            $buf[] = $stocks;
            $buf[] = $stocks->count();
            $arr[] = $buf;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'allotment' => $model,
            'allotmentforms' => $allotmentforms,
            'positions' => $arr,
        ];

        return view($this->viewPath.'checkform', $response);
    }

    /**
     * 对单数据更新 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function checkformupdate($id)
    {
        request()->flash();

        $arr = request()->all();
        $this->model->find($id)->update(['checkform_by'=>request()->user()->id]);
        $name = UserModel::find(request()->user()->id)->name;
        $from = json_encode($this->model->with('allotmentforms')->find($id));

        $obj = $this->model->find($id)->allotmentforms;
        DB::beginTransaction();
        try {
            $buf[] = $arr['arr']['new_receive_quantity'];
            $buf[] = $arr['arr']['warehouse_position_id'];
            $buf[] = $arr['arr']['old_receive_quantity'];
            for($i=0; $i<count($buf[0]); $i++)
            {   
                if($buf[0][$i] == '' || $buf[1][$i] == '')
                    continue;
                $obj[$i]->update(['receive_quantity'=>($buf[0][$i]+$buf[2][$i]), 'in_warehouse_position_id'=>$buf[1][$i]]);
            }
            $flag = 1;
            $buf[] = $arr['arr']['quantity'];
            for($i=0;$i<count($buf[3]);$i++)
            {
                if($buf[3][$i] != ($buf[0][$i] + $buf[2][$i]))
                    $flag = 0;
            }
            if($flag == 1)
            {
                $arr['allotment_status'] = 'over';
            } else {
                $arr['allotment_status'] = 'check';
            }

            $arr['checkform_time'] = date('Y-m-d',time());
            $this->model->find($id)->update(['allotment_status'=>$arr['allotment_status'], 'checkform_time'=>$arr['checkform_time'], 'remark'=>$arr['remark']]);
            $len = count($arr['arr']['item_id']);
            for($i=0; $i<$len; $i++)
            {
                $buf = [];
                foreach($arr['arr'] as $key => $value)
                {
                    $buf[$key] = $value[$i];
                }
                $buf = array_merge($buf,$arr);
                $buf['type'] = "ALLOTMENT";
                $buf['relation_id'] = $id;
                $buf['amount'] = round($buf['amount']/$buf['quantity']*$buf['new_receive_quantity'],3);
                $buf['quantity'] = $buf['new_receive_quantity'];
                $buf['item_id'] = ItemModel::where('sku',$buf['item_id'])->get()->first()->id;
                if($buf['quantity'] == '' || $buf['warehouse_position_id'] == '')
                    continue;
                if($buf['amount'] < 0)
                    throw new Exception('库存金额低于0了');
                $item = ItemModel::find($buf['item_id']);
                if($buf['quantity'])
                    $item->in($buf['warehouse_position_id'], $buf['quantity'], $buf['amount'], $buf['type'], $buf['relation_id'], $buf['remark']);
            }
        } catch(Exception $e) {
            DB::rollback();
        }
        DB::commit();
        $to = json_encode($this->model->with('allotmentforms')->find($id));
        $this->eventLog($name, '对单', $to, $from);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '对单成功'));
    }
}