<?php
/**
 * EBAY销售额统计
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/16
 * Time: 上午11:14
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\EbayAmountStatisticsModel;

class EbayAmountStatisticsController extends Controller
{
    public function __construct(EbayAmountStatisticsModel $ebayAmountStatistics)
    {
        $this->model = $ebayAmountStatistics;
        $this->mainIndex = route('ebayAmountStatistics.index');
        $this->mainTitle = 'EBAY销量额统计';
        $this->viewPath = 'order.ebayAmountStatistics.';
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

}