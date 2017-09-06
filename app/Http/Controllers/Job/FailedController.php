<?php
/**
 * 异常队列控制器
 *
 * 2016-07-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Job\FailedModel;

class FailedController extends Controller
{
    public function __construct(FailedModel $failed)
    {
        $this->model = $failed;
        $this->mainIndex = route('jobFailed.index');
        $this->mainTitle = '异常队列';
        $this->viewPath = 'job.failed.';
    }
}