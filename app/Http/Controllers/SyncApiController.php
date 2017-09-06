<?php
/**
 * 接口控制器
 *
 * 2016-10-27
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\SyncApiModel;

class SyncApiController extends Controller
{
    public function __construct(SyncApiModel $syncApi)
    {
        $this->model = $syncApi;
        $this->mainIndex = route('syncApi.index');
        $this->mainTitle = '同步接口';
        $this->viewPath = 'syncApi.';
    }
}