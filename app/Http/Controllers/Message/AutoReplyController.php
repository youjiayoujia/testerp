<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message\AutoReplyRulesModel;
use App\Models\ChannelModel;
use App\Models\Message\Template\TypeModel;


class AutoReplyController extends Controller
{
    public function __construct(AutoReplyRulesModel $rules)
    {
        $this->model = $rules;
        $this->mainIndex = route('autoReply.index');
        $this->mainTitle = '自动回复规则';
        $this->viewPath = 'message.auto_reply.';



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channel = new ChannelModel;
        $channel = $channel->getAutoReplyChannel();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => $channel,
            'parents' => TypeModel::where('parent_id', 0)->get(),
        ];
        return view($this->viewPath . 'create', $response);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
/*    public function store()
    {
        //
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }

        $channel = new ChannelModel;

        $channel = $channel->getAutoReplyChannel();

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels' => $channel,
            'parents' => TypeModel::where('parent_id', 0)->get(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
/*    public function update($id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
/*    public function destroy($id)
    {
        //
    }*/
}
