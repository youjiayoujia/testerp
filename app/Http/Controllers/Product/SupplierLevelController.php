<?php
/**
 *  供货商评级控制器
 *  处理与供货商评级相关的操作
 *
 * @author:MC<178069409@qq.com>
 * Date:2016/4/9
 * Time:15:26
 *
 */

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Product\SupplierLevelModel;

class SupplierLevelController extends Controller
{
    public function __construct(SupplierLevelModel $level)
    {
        $this->model = $level;
        $this->mainIndex = route('supplierLevel.index');
        $this->mainTitle = '供货商评级';
        $this->viewPath = 'product.supplier.level.';
    }
}