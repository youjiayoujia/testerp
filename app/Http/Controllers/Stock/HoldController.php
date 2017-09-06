<?php
/**
 * hold控制器
 * 处理hold相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\HoldModel;

class HoldController extends Controller
{
    public function __construct(HoldModel $hold)
    {
        $this->model = $hold;
        $this->mainIndex = route('stockHold.index');
        $this->mainTitle = 'hold库存';
        $this->viewPath = 'stock.hold.';
    }
}