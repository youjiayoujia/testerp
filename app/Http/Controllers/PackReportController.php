<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\PackReportModel;
use App\Models\PickListModel;
use Excel;
use App\Models\UserModel;

class PackReportController extends Controller
{
    public function __construct(PackReportModel $packReport)
    {
        $this->model = $packReport;
        $this->mainIndex = route('packReport.index');
        $this->mainTitle = '包装报表';
        $this->viewPath = 'package.packReport.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $warehouse_id = UserModel::find(request()->user()->id)->warehouse_id;
        $model = $this->model->where('warehouse_id', $warehouse_id)->orderBy('day_time', 'desc')->first();
        $last_time = '';
        $monthModel = $this->model->where('warehouse_id', $warehouse_id)->whereBetween('day_time', [date('Y-m-d', strtotime(date('Y-m', strtotime('now')))), date('Y-m-d', strtotime(date('Y-m', strtotime('+1 month'))))])->get()->groupBy('user_id');
        if($model) {
            $last_time = $model->day_time;
            $model = $this->model->where('warehouse_id', $warehouse_id)->orderBy('day_time', 'desc')->get()->groupBy('day_time')->get($last_time);
            $monthModel = $this->model->where('warehouse_id', $warehouse_id)->whereBetween('day_time', [date('Y-m-d H:i:s', strtotime(date('Y-m', strtotime('-1 month')))), date('Y-m-d H:i:s', strtotime(date('Y-m', strtotime('+1 month'))))])->get()->groupBy('user_id');
        }
        if(request()->has('report')) {
            $last_time = '';
            $model = $this->model->where('warehouse_id', $warehouse_id)->orderBy('day_time', 'desc')->get()->groupBy('day_time');
            foreach($model as $time => $single) {
                if(date('Y-m-d', strtotime($time)) == date('Y-m-d', strtotime(request('report')))) {
                    $last_time = $time;
                }
            }
            $model = $model->get($last_time);
            $monthModel = $this->model->where('warehouse_id', $warehouse_id)->whereBetween('day_time', [
                date('Y-m-d H:i:s', strtotime(date('Y-m', strtotime($last_time) - strtotime('now') + strtotime('-1 month')))),
                date('Y-m-d H:i:s', strtotime(date('Y-m', strtotime($last_time) + strtotime('now') - strtotime('-1 month'))))])->get()->groupBy('user_id');
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $model,
            'last_time' => $last_time,
            'monthModel' => $monthModel,
            'mixedSearchFields' => $this->model->mixed_search,
            'daytime' => request()->has('report') ? request('report') : ''
        ];

        return view($this->viewPath . 'index', $response);
        
    }

    public function createdata()
    {
        $model = PickListModel::wherebetween('pack_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))])->get()
                ->filter(function($row){
                    return $row->pack_by != 0;
                })
                ->groupBy('pack_by');
        $yesModel = PickListModel::wherebetween('pack_at', [date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('now'))])->get()->filter(function($row){
                return $row->pack_by != 0;
            })
            ->groupBy('pack_by');
        foreach($model as $userId => $block) {
            $this->model->create([
                'user_id' => $userId,
                'single' => $block->filter(function($single){
                    return $single->type == 'SINGLE';
                })->sum('account'),
                'singleMulti' => $block->filter(function($single){
                    return $single->type == 'SINGLEMULTI';
                })->sum('account'),
                'multi' => $block->filter(function($single){
                    return $single->type == 'MULTI';
                })->sum('account'),
                'yesterday_send' => count($yesModel) ? ($yesModel->get($userId) ? $yesModel->get($userId)->sum('account') : 0) : 0,
                'day_time' => date('Y-m-d H:i:s', time()),
                'warehouse_id' => $block->first()->warehouse_id,
            ]);
        }
        
        return redirect($this->mainIndex);
    }

    public function download()
    {
        $date = date('Y-m', time());
        if(!empty(request('date'))){
            $date = request('date');
        }
        $model = $this->model->whereBetween('day_time', [date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($date) + strtotime('1 month') - strtotime('now'))])->get()->groupBy('user_id');
        $i = 0;
        $rows = [];
        foreach($model as $userId => $block) {
            $single = $block->first();
            $rows[$i] = [
                '包装人' => $single->user->name,
                '仓库' => $single->warehouse ? $single->warehouse->name : '无所属仓库',
                '本月包装sku数(分单单，单多，多多)' => (($block->sum('single') + $block->sum('singleMulti') + $block->sum('multi')).'(单单:'.$block->sum('single').',单多:'.$block->sum('singleMulti').',多多:'.$block->sum('multi').')'),
            ];
            $i++;
        }
        $name = '包装排行榜';
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function changeData()
    {
        $time = request('time');
        $quantity = request('quantity');
        $date = request('date');
        $userId = request('userid');
        $model = $this->model->where('user_id', $userId)->whereBetween('day_time', [date('Y-m-d', strtotime(date('Y-m', strtotime($date)))), date('Y-m-d', strtotime(date('Y-m', strtotime($date) + strtotime('+1 month') - strtotime('now'))))])->first();
        if(!$model) {
            return json_encode(false);
        }
        $model->update(['all_worktime' => $time, 'error_send' => $quantity]);

        return json_encode(true);

    }
}