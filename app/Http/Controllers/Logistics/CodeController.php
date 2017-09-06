<?php
/**
 * 跟踪号控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/28
 * Time: 上午10:50
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CodeModel;
use App\Models\LogisticsModel;
use DB;


class CodeController extends Controller
{
    public function __construct(CodeModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('logisticsCode.index');
        $this->mainTitle = '跟踪号';
        $this->viewPath = 'logistics.code.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses'=>LogisticsModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $codes = $this->model->where('code', request('code'))->count();
        if($codes == 0) {
            $this->model->create($data);
            return redirect($this->mainIndex);
        }else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '跟踪号已存在'));
        }
    }

    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $this->validate(request(), $this->model->rules('update'));
        $data = request()->all();
        $codes = $this->model->where('code', request('code'))->count();
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        if($codes == 0) {
            $model->update($data);
            return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
        }else {
            return redirect($url)->with('alert', $this->alert('danger', '跟踪号已存在'));
        }
    }

    /**
     * 某个物流方式追踪号首页
     */
    public function one($id)
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('logistics_id', $id)),
            'id' => $id,
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     * 编辑
     */
    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses'=>LogisticsModel::all(),
            'hideUrl' => $hideUrl,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 批量上传物流号页面
     * @param $logistics_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function batchAddTrCode($logistics_id)
    {
        $logistics = LogisticsModel::find($logistics_id);
        $response = [
            'metas' => $this->metas(__FUNCTION__, '导入-号码池'),
            'logistics' => $logistics,
        ];
        return view($this->viewPath . 'batchadd', $response);
    }

    /**
     * 处理批量上传物流号程序
     * @return mixed
     */
    public function batchAddTrCodeFn()
    {
        if(request()->has('logistics_id')){
            $logistics_id = request()->input('logistics_id');
        }else{
            return redirect('batchAddTrCode')->with('alert', $this->alert('danger', $this->mainTitle . '未选择物流方式.'));
        }

        // 保存上传文件
        if (request()->hasFile('trackingnos')) {
            if (request()->file('trackingnos')->isValid()) {
                $file = request()->file('trackingnos');
                $destinationPath = public_path() . '/uploads/logistics/codes';
                $fileName = date("Y-m-d", time()) . '-' . rand(100000, 999999) . '-' . $file->getClientOriginalName();
                request()->file('trackingnos')->move($destinationPath, $fileName);
            }else{
                return redirect('batchAddTrCode/'.$logistics_id)->with('alert', $this->alert('danger',  '文件非法'));
            }
        }else{
            return redirect('batchAddTrCode/'.$logistics_id)->with('alert', $this->alert('danger',  '未上传任何文件'));
        }

        //写操作
        $codes = DB::table('logistics_codes')->lists('code');  //获取已经取得的物流号，用于后面的筛选
        $successNumber = 0;
        $repeatNumber = 0;
        $repeatCodes = [];

        if (($handle = fopen($destinationPath.'/'.$fileName, "r")) !== FALSE) {
            $created_at = null;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                if(in_array($data[0], $codes)){
                    $repeatCodes[] = $data[0];
                    $repeatNumber++;
                }else{
                    $nowTime = date('Y-m-d H:i:s',time());
                    $data = ['logistics_id'=>$logistics_id, 'code'=>$data[0], 'status'=>"0", 'created_at'=>$nowTime, 'updated_at'=>$nowTime];
                    //插入
                    $this->model->create($data);
                    $successNumber++;
                }
            }
            fclose($handle);

            $totalNumber = $successNumber + $repeatNumber;
            $content = "本次共选择导入".$totalNumber."个跟踪号,成功导入".$successNumber."个,有".$repeatNumber."个重复未导入,如下：";
            if(count($repeatCodes)){
                foreach($repeatCodes as $repeatCode){
                    $content .= $repeatCode.",";
                }
                $content = substr($content,0,strlen($content)-1);
            }
            return redirect('logisticsCode')->with('alert', $this->alert('success', $content));
        }else{
            return redirect('logisticsCode')->with('alert', $this->alert('danger', '上传失败！'));
        }
    }

    /**
     * 扫描录入物流号页面
     * @param $logistics_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function scanAddTrCode($logistics_id)
    {
        $logistics = LogisticsModel::find($logistics_id);
        $response = [
            'metas' => $this->metas(__FUNCTION__, '扫描-号码池'),
            'logistics' => $logistics,
        ];
        return view($this->viewPath . 'scanadd', $response);
    }

    /**
     * 扫描录入处理方法
     * @return mixed
     */
    public function scanAddTrCodeFn()
    {
        if(request()->has('logistics_id')){
            $logistics_id = request()->input('logistics_id');
        }else{
            return redirect('batchAddTrCode')->with('alert', $this->alert('danger', $this->mainTitle . '未选择物流方式.'));
        }

        if(request()->has('codes')){
            $input_codes = request()->input('codes');
        }else{
            $input_codes = "";
        }

        if($input_codes){

            $codes = DB::table('logistics_codes')->lists('code');  //获取已经取得的物流号，用于后面的筛选
            $successNumber = 0;
            $repeatNumber = 0;
            $repeatCodes = [];

            foreach($input_codes as $input_code){
                if(in_array($input_code, $codes)){
                    $repeatCodes[] = $input_code;
                    $repeatNumber++;
                }else{
                    $nowTime = date('Y-m-d H:i:s',time());
                    $data = ['logistics_id'=>$logistics_id, 'code'=>$input_code, 'status'=>"0", 'created_at'=>$nowTime, 'updated_at'=>$nowTime];
                    $this->model->create($data);
                    $successNumber++;
                }
            }

            $totalNumber = $successNumber + $repeatNumber;
            $content = "本次共扫描".$totalNumber."个跟踪号,成功录入".$successNumber."个,有".$repeatNumber."个重复未录入,如下：";
            if(count($repeatCodes)){
                foreach($repeatCodes as $repeatCode){
                    $content .= $repeatCode.",";
                }
                $content = substr($content,0,strlen($content)-1);
            }
            return redirect('logisticsCode')->with('alert', $this->alert('success', $content));

        }else{
            return redirect('scanAddTrCode/'.$logistics_id)->with('alert', $this->alert('success',  '未输入任何物流号！'));
        }
    }
}