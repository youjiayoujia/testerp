<?php
/**
 * 回复队列控制器
 *
 * 2016-02-01
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message\ReplyModel;

class ReplyController extends Controller
{
    public function __construct(ReplyModel $reply)
    {
        $this->model = $reply;
        $this->mainIndex = route('messageReply.index');
        $this->mainTitle = '回复队列';
        $this->viewPath = 'message.reply.';

    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $reply =  $this->model->WithOnly('message.account', ['id', 'account']);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, $reply),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

}