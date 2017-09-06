<?php
/**
 * 包装限制控制器
 * 处理包装限制相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WrapLimitsModel;

class WrapLimitsController extends Controller
{
    public function __construct(WrapLimitsModel $wrapLimits)
    {
        $this->model = $wrapLimits;
        $this->mainIndex = route('wrapLimits.index');
        $this->mainTitle = '包装限制';
        $this->viewPath = 'wrapLimits.';
    }
}