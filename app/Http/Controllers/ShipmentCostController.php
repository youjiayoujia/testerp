<?php
/**
 * 物流对账控制器
 * 处理物流对账相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package\ShipmentCostModel;
use Excel;

class ShipmentCostController extends Controller
{
    public function __construct(ShipmentCostModel $shipmentCost)
    {
        $this->model = $shipmentCost;
        $this->mainIndex = route('shipmentCost.index');
        $this->mainTitle = '物流对账';
        $this->viewPath = 'package.shipmentCost.';
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $model->items,
        ];
        
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showError($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'items' => $model->errors()->paginate('20')
        ];

        return view($this->viewPath . 'showErrors', $response);
    }

    public function destroyRows($arr)
    {
        foreach(explode(',', $arr) as $id) {
            $model = $this->model->find($id);
            if(!$model) {
                continue;
            }
            foreach($model->items as $item) {
                $item->delete();
            }
            foreach($model->errors as $error) {
                $error->delete();
            }
            $model->delete();
        }

        return redirect($this->mainIndex);
    }

    public function export()
    {
    	$rows[] = [
    		'挂号码' => 'LN108905230CN',
    		'目的地' => '美国',
    		'计费重量(kg)' => '0.237',
    		'渠道名称' => 'JSCS_EUB',
    		'不含挂号费' => '18.96',
    		'挂号费' => '7',
    		'通折' => '0.83',
    		'非通折' => '',
    	];
    	$name = '物流对账模板';
    	Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function import()
    {
    	$response = [
    		'metas' => $this->metas(__FUNCTION__),
    	];

    	return view($this->viewPath.'import', $response);
    }

    public function importProcess()
    {
    	$file = request()->file('import');
        $arr = $this->model->importProcess($file, request()->user()->id);
        return redirect($this->mainIndex);
    }
}