<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\ChannelModel;

class ChannelController extends Controller
{
    public function __construct(ChannelModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('channel.index');
        $this->mainTitle = '渠道';
        $this->viewPath = 'channel.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'drivers' => config('channel.drivers'),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $account = $this->model->find($id);
        if (!$account) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $account,
            'drivers' => config('channel.drivers'),
        ];
        return view($this->viewPath . 'edit', $response);
    }
}