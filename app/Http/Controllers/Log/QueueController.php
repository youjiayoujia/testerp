<?php
/**
 * 队列日志控制器
 *
 * 2016-07-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use App\Models\Log\QueueModel;

class QueueController extends Controller
{
    public function __construct(QueueModel $queue)
    {
        $this->model = $queue;
        $this->mainIndex = route('logQueue.index');
        $this->mainTitle = '队列日志';
        $this->viewPath = 'log.queue.';
    }
}