<?php
/**
 * Ã‡Ã¾ÂµÃ€Â¿Ã˜Ã–Ã†Ã†Ã·
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\ReportModel;
use App\Models\Channel\AccountModel;
use App\Models\Oversea\ReportFormModel;
use App\Models\Oversea\BoxModel;
use App\Models\LogisticsModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Oversea\StockModel as fbaStock;
use Tool;

class ReportController extends Controller
{
    public function __construct(ReportModel $report)
    {
        $this->model = $report;
        $this->mainIndex = route('report.index');
        $this->mainTitle = '申请表';
        $this->viewPath = 'oversea.report.';
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
            'accounts' => AccountModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function add()
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
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $obj = $this->model->create($buf);
        for($i=0; $i<$len; $i++)
        {   
            $arr = request()->input('arr');
            foreach($arr as $key => $val)
            {
                $val = array_values($val);
                $buf[$key] = $val[$i];      
            }
            $item = ItemModel::find($buf['item_id']);
            $stock = fbaStock::where(['item_id' => $buf['item_id'], 'account_id' => $obj->account_id])->first();
            $buf['sku'] = $item->sku;
            $buf['parent_id'] = $obj->id;
            $buf['fnsku'] = $stock ? $stock->fnsku : '';
            ReportFormModel::create($buf);
            $item->hold($buf['warehouse_position_id'], $buf['report_quantity'], 'FBA', $obj->id);
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
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
        $all_weight = 0;
        $volumn = 0;
        $arr = [];
        foreach($model->forms as $form) {
            $all_weight += $form->out_quantity * $form->item->cost;
        }
        foreach($model->boxes as $box) {
            $sum = 0;
            foreach($box->forms as $form) {
                $sum += $form->item->cost * $form->quantity;
            }
            $arr[] = $sum;
            $volumn += ($box->length * $box->width * $box->height)/5000;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'forms' => $model->forms,
            'boxes' => $model->boxes,
            'all_weight' => $all_weight,
            'actual_weight' => $model->boxes->sum('weight'),
            'volumn' => $volumn,
            'fee' => $model->boxes->sum('fee'),
            'arr' => $arr,
        ];
        return view($this->viewPath . 'show', $response);
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
        $arr = [];
        $available_quantity = [];
        foreach($model->forms as $key => $value) 
        {
            $obj = StockModel::where('item_id', $value->item_id)->whereHas('warehouse', function($query){
                $query = $query->where('type', 'fbaLocal');
            });
            $available_quantity[] =  $obj->where('warehouse_position_id', $value->warehouse_position_id)->first()->available_quantity;
            $buf = [];
            foreach($obj->get() as $v)
            {   
                $buf[] = $v->position->toArray();
            }
            $arr[] = $buf;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'forms' => $model->forms,
            'accounts' => AccountModel::all(),
            'positions' => $arr,
            'available_quantity' => $available_quantity,
        ];
        return view($this->viewPath . 'edit', $response);
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
        $model = $this->model->find($id);
        $len = count(array_keys(request()->input('arr.item_id')));
        $buf = request()->all();
        $obj = $model->forms;
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->unhold($value->warehouse_position_id, $value->report_quantity, 'FBA', $model->id);
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
            $item = ItemModel::find($buf['item_id']);
            $buf['sku'] = $item->sku;
            $obj[$i]->update($buf);
        }
        while($i != $obj_len) {
            $obj[$i]->delete();
            $i++;
        }
        foreach($obj as $key => $value) {
            $item = ItemModel::find($value->item_id);
            $item->hold($value->warehouse_position_id, $value->report_quantity, 'FBA', $model->id);
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }

    /**
     * ajax请求函数
     *  
     * @param none
     * @return json
     *
     */
    public function pick($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($model->status == 'PASS') {
            $model->update(['status'=>'PICKING', 'print_status' => 'PRINTED']);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'formArray' => $model->forms->chunk('25'),
        ];

        return view($this->viewPath.'print', $response);
    }

    /**
     * 打包页面
     *
     * @param $id
     * @return view
     *
     */
    public function package($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->status = 'PACKING';
        $model->save();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'forms' => $model->forms,
        ];
        
        return view($this->viewPath.'package', $response);
    }

    public function packageStore($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->update(['status' => 'PACKED']);

        return redirect($this->mainIndex);
    }

    public function reportFormUpdate()
    {
        $id = trim(request('id'));
        $itemId = trim(request('itemId'));
        $boxId = trim(request('boxId'));
        $model = ReportFormModel::find($id);
        if(!$model) {
            return json_encode(false);
        }
        $model->inbox_quantity = $model->inbox_quantity + 1;
        $model->save();
        $box = BoxModel::find($boxId);
        $item = ItemModel::find($itemId);
        $box->weight = $box->weight + $item->weight;
        $box->save();

        $single = $box->forms->where('sku', $item->sku)->first();
        if(!$single) {
            $single = $box->forms()->create(['sku' => $item->sku, 'fnsku' => $model->fnsku]);
        }
        $single->quantity += 1;
        $single->save();

        return json_encode($item->weight);
    }

    public function ctrlZ()
    {
        $id = request('id');
        $itemId = trim(request('itemId'));
        $boxId = trim(request('boxId'));
        $model = ReportFormModel::find($id);
        if(!$model) {
            return json_encode(false);
        }
        $model->inbox_quantity = $model->inbox_quantity - 1;
        $model->save();

        $box = BoxModel::find($boxId);
        $item = ItemModel::find($itemId);
        $box->weight -= $item->weight;
        $box->save();
        $single = $box->forms->where('sku', $item->sku)->first();
        $single->quantity -= 1;
        $single->save();
        if($single->quantity == 0) {
            $single->delete();
        }

        return json_encode(true);
    }

    public function createBox()
    {
        $id = request('id');
        $model = $this->model->find($id);
        $model->quantity += 1;
        $model->save();
        if(!$model) {
            return json_encode(false);
        }
        $box = $model->boxes()->create(['boxNum' => 'box'.strtotime('now')]);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $box,
        ];

        return view($this->viewPath.'box', $response);
    }

    public function check($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'forms' => $model->forms,
        ];
        return view($this->viewPath . 'check', $response);
    }

    public function checkResult($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $result = request('result');
        if($result) {
            $model->update(['status' => 'PASS']);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '审核通过'));
        } else {
            $model->update(['status' => 'FAIL']);
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '审核未通过'));
        }
    }

    public function shipment()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath.'shipment', $response);
    }

    public function sendExec()
    {
        $boxNum = request('boxNum');
        $logistics = request('logistics');
        $tracking_no = request('tracking_no');
        $fee = request('fee');
        $box = BoxModel::where(['boxNum' => $boxNum, 'status' => '0'])->first();
        if(!$box) {
            return json_encode(false);
        }
        $box->update(['logistics_id' => $logistics, 'status' => '1', 'tracking_no' => $tracking_no, 'fee' => $fee]);
        $report = $box->report;
        $flag = 1;
        foreach($report->boxes as $singleBox) {
            if(!$singleBox->status) {
                $flag = 0;
            }
            foreach($singleBox->forms as $form) {
                $reportForm = $report->forms()->where('sku', $form->sku)->first();
                if(!$reportForm) {
                    continue;
                }
                $reportForm->item->holdout($reportForm->warehouse_position_id, $form->quantity, 'FBA', $report->id);
                $reportForm->out_quantity += $form->quantity;
                $reportForm->save();
            }
        }
        if($flag) {
            $report->update(['status' => 'SHIPPED']);
        }
        return json_encode(true);
    }
}