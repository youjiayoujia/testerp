<?php

namespace App\Http\Controllers\Message\FeedBack;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Publish\Ebay\EbayFeedBackModel;

class EbayFeedBackController extends Controller
{

    public function __construct(EbayFeedBackModel $ebay_feedback)
    {
        $this->model = $ebay_feedback;
        $this->mainIndex = route('ebayFeedBack.index');
        $this->mainTitle = 'ebay feedback';
        $this->viewPath = 'message.feedback.ebay.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd($this->model->distinct()->get(['channel_account_id']));
        $response = [
            'metas'  => $this->metas(__FUNCTION__),
            'data'   => $this->autoList($this->model),
            'status' => $this->model->distinct()->get(['channel_account_id']),
            'types'  => $this->model->distinct()->get(['comment_type']),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index',$response);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 差评统计报表
     */
    public function feedBackStatistics(){
        //当月统计
        $begin = date('Y-m-01 00:00:00');
        $end   = date('Y-m-d H:i:s');
        $this_month = $this->model->getFeedBackStatistics(compact('begin','end'));
        //当天统计
        $begin = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d H:i:s');
        $this_day = $this->model->getFeedBackStatistics(compact('begin','end'));
        $total = [
           '当天'     => $this_day,
           '当月累计'  => $this_month,
        ];

        $metas = [
            'mainIndex' => route('feeback.feedBackStatistics'),
            'mainTitle' => '报表',
            'title'     => '差评统计',
        ];
        $response = [
            'metas' => $metas,
            //'model' => $model,
            'data'  => $total,
        ];
        return view($this->viewPath . 'statistics',$response);
    }

}
