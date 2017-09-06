<?php
/**
 * EbaySku销量报表
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/5
 * Time: 下午3:51
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\EbaySkuSaleReportModel;

class EbaySkuSaleReportController extends Controller
{
    public function __construct(EbaySkuSaleReportModel $ebaySkuSaleReport)
    {
        $this->model = $ebaySkuSaleReport;
        $this->mainIndex = route('ebaySkuSaleReport.index');
        $this->mainTitle = 'EbaySku销量报表';
        $this->viewPath = 'order.ebaySkuSaleReport.';
    }
}