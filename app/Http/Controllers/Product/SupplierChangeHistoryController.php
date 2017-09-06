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
use App\Models\Product\SupplierModel;
use App\Models\Product\SupplierChangeHistoryModel;

class SupplierChangeHistoryController extends Controller
{
    public function __construct(SupplierChangeHistoryModel $history)
    {
        $this->model = $history;
        $this->mainIndex = route('supplierChangeHistory.index');
        $this->mainTitle = '采购员变更历史';
        $this->viewPath = 'product.supplier.changeHistory.';
    }
}