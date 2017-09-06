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
use App\Models\RecieveWrapsModel;

class RecieveWrapsController extends Controller
{
    public function __construct(RecieveWrapsModel $recieveWraps)
    {
        $this->model = $recieveWraps;
        $this->mainIndex = route('recieveWraps.index');
        $this->mainTitle = '收货包装';
        $this->viewPath = 'recieveWraps.';
    }
}