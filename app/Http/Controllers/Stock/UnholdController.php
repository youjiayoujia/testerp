<?php
/**
 * unhold控制器
 * 处理unhold相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\UnholdModel;

class UnholdController extends Controller
{
    public function __construct(UnholdModel $unhold)
    {
        $this->model = $unhold;
        $this->mainIndex = route('stockUnhold.index');
        $this->mainTitle = 'unhold库存';
        $this->viewPath = 'stock.unhold.';
    }
}