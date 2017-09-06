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
use App\Models\Package\ShipmentCostItemModel;

class ShipmentCostItemController extends Controller
{
    public function __construct(ShipmentCostItemModel $shipmentCost)
    {
        $this->model = $shipmentCost;
        $this->mainIndex = route('shipmentCost.index');
        $this->mainTitle = '物流对账';
        $this->viewPath = 'package.shipmentCost.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showInfo($id)
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('parent_id', $id)),
            'mixedSearchFields' => $this->model->mixed_search,
        ];

        return view($this->viewPath . 'show', $response);
    }
}