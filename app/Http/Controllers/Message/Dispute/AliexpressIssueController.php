<?php

namespace App\Http\Controllers\Message\Dispute;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Message\Issues\AliexpressIssueListModel;
use App\Models\Message\Issues\AliexpressIssuesDetailModel;
use App\Models\Channel\AccountModel;
use Carbon\Carbon;
use Channel;

class AliexpressIssueController extends Controller
{

    public function __construct(AliexpressIssueListModel $issueList)
    {
        $this->model = $issueList;
        $this->mainIndex = route('AliexpressIssue.index');
        $this->mainTitle = 'Aliexpress Issues';
        $this->viewPath = 'message.aliexpress_issues.';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, null, ['*'], null, null, ['account']),
            'mixedSearchFields' => $this->model->mixed_search,

            //'reasonFilter' => $this->model->distinct()->get(['reasonChinese']),
            //'accounts'     => AccountModel::all(),
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
        $list = $this->model->find($id);

        $data = AliexpressIssuesDetailModel::where('issue_list_id',$id)->first();
        if(empty($data)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'issue'  => $data,
        ];
        return view($this->viewPath . 'edit',$response);
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
     * 批量留言
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doRefuseIssues(){
        $form = request()->input();
        $error_info = '';
        if(!empty($form['checked-ids']) && !empty($form['remark'])){
            $issue_ids = explode(',',$form['checked-ids']);
            foreach ($issue_ids as $id){
                $issue = $this->model->find($id);
                $adpter = Channel::driver($issue->account->channel->driver, $issue->account->api_config);
                $result = $adpter->leaveOrderMessage($issue->orderId, trim($form['remark']));
                if(! $result){
                    $error_info .= $issue->id.',';
                }
            }
        }else{
            return redirect($this->mainIndex)->with('alert', '参数不完整');
        }
        if(! empty($error_info)){
            return redirect($this->mainIndex)->with('alert', $error_info.'发送失败');
        }else{
            return redirect($this->mainIndex)->with('success', '发送成功');
        }
    }

    public function MutiChangePlatformProcess()
    {
        $ids = request()->input('ids');
        $ids = explode(',', $ids);
        return $this->model->platformDeal($ids);

    }






































}
