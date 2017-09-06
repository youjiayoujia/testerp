<?php
/**
 * 定时任务日志控制器
 *
 * 2016-07-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use App\Models\Log\CommandModel;

class CommandController extends Controller
{
    public function __construct(CommandModel $command)
    {
        $this->model = $command;
        $this->mainIndex = route('logCommand.index');
        $this->mainTitle = '定时任务日志';
        $this->viewPath = 'log.command.';
    }
}