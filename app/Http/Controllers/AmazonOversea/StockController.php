<?php
/**
 * 海外仓箱子Controller
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\StockModel;
use App\Models\Channel\AccountModel;
use App\Modules\Channel\Adapter\AmazonAdapter;
use App\Models\LogisticsModel;
use Tool;
use App\Models\ItemModel;

class StockController extends Controller
{
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
        $this->mainIndex = route('fbaStock.index');
        $this->mainTitle = 'fba库存信息';
        $this->viewPath = 'oversea.stock.';
    }

    public function updateStock()
    {
        $account = AccountModel::find(1);
        $single = new AmazonAdapter($account->api_config);
        $requestId = $single->requestReport();
        sleep(20);
        $reportRequestId = $this->step1($requestId);
        sleep(5);
        $buf = $single->getReport($reportRequestId);
        $arr = explode("\n", $buf);
        $keys = explode("\t", $arr[0]);
        $vals = [];
        foreach($arr as $key => $value) {
            if(!$key) {
                continue;
            }
            $buf = explode("\t", $value);
            foreach($buf as $k => $v) {
                $vals[$keys[$k]] = $v;
            }
            $tmp = Tool::filter_sku($vals['sku']);
            if(count($tmp)) {
                $item = ItemModel::where('sku', $tmp['0']['erpSku'])->first();
                if($item) {
                    $vals['item_id'] = $item->id;
                }
            }
            $vals['title'] = $vals['product-name'];
            $vals['channel_sku'] = $vals['sku'];
            $vals['mfn_fulfillable_quantity'] = $vals['mfn-fulfillable-quantity'];
            $vals['afn_warehouse_quantity'] = $vals['afn-warehouse-quantity'];
            $vals['afn_fulfillable_quantity'] = $vals['afn-fulfillable-quantity'];
            $vals['afn_unsellable_quantity'] = $vals['afn-unsellable-quantity'];
            $vals['afn_reserved_quantity'] = $vals['afn-reserved-quantity'];
            $vals['afn_total_quantity'] = $vals['afn-total-quantity'];
            $vals['per_unit_volume'] = $vals['per-unit-volume'];
            $vals['afn_inbound_working_quantity'] = $vals['afn-inbound-working-quantity'];
            $vals['afn_inbound_shipped_quantity'] = $vals['afn-inbound-shipped-quantity'];
            $vals['account_id'] = '1';
            $model = $this->model->where(['channel_sku' => $vals['sku']])->first();
            if(!$model) {
                $this->model->create($vals);
                continue;
            }
            $model->update($vals);
        }

        return redirect($this->mainIndex);
    }

    public function step1($id)
    {
        $account = AccountModel::find(1);
        $single = new AmazonAdapter($account->api_config);
        $reportRequestId = $single->getReportRequestList($id);

        return $reportRequestId;
    }   

    public function updateFBAStock()
    {
        $account = AccountModel::find(1);
        $single = new AmazonAdapter($account->api_config);
        if(!empty($single->listInShipment('FBAWWDKDK'))) {
            $this->updateStock();
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
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
        $model->destroy($id);
        return redirect($this->mainIndex);
    }

}