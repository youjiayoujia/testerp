<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\PickReportModel;
use App\Models\PickListModel;
use App\Models\WarehouseModel;
use Excel;
use App\Models\UserModel;

class PickReportController extends Controller
{
    public function __construct(PickReportModel $pickReport)
    {
        $this->model = $pickReport;
        $this->mainIndex = route('pickReport.index');
        $this->mainTitle = '拣货排行榜';
        $this->viewPath = 'pick.report.';
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
        $monthModel = $this->model->where('warehouse_id', $warehouse_id)->whereBetween('day_time',[date('Y-m-d', strtotime(date('Y-m', strtotime('now')))), date('Y-m-d', strtotime(date('Y-m', strtotime('+1 month'))))])->get()->groupBy('user_id');
        if($model) {
            $last_time = $model->day_time;
            $model = $this->model->where('warehouse_id', $warehouse_id)->orderBy('day_time', 'desc')->get()->groupBy('day_time')->get($last_time);
        }
        if(request()->has('date') && !empty(request('date'))) {
            $last_time = '';
            $model = $this->model;
            if(request()->has('warehouseid') && !empty(request('warehouseid'))) {
                $warehouse_id = request('warehouseid');
                $model = $model->where('warehouse_id', request('warehouseid'));
            }
            $model = $model->orderBy('day_time', 'desc')->get()->groupBy('day_time');
            foreach($model as $time => $single) {
                if(date('Y-m-d', strtotime($time)) == date('Y-m-d', strtotime(request('date')))) {
                    $last_time = $time;
                }
            }
            $model = $model->get($last_time);
            $monthModel = $this->model->where('warehouse_id', $warehouse_id)->whereBetween('day_time', [
                date('Y-m-d', strtotime(date('Y-m', strtotime($last_time)))),
                date('Y-m-d', strtotime(date('Y-m', strtotime($last_time) + strtotime('now') - strtotime('-1 month'))))])->get()->groupBy('user_id');
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $model,
            'mixedSearchFields' => $this->model->mixed_search,
            'monthModel' => $monthModel,
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'warehouseid' => request()->has('warehouseid') ? request('warehouseid') : '',
            'date' => request()->has('date') ? request('date') : '',
        ];

        return view($this->viewPath . 'index', $response);
    }

    //目前生成数据没有仓库，有仓库后将会变正常
    public function download()
    {
        $warehouseId = '3';
        $date = date('Y-m', time());
        if(!empty(request('date'))){
            $date = request('date');
        }
        if(!empty(request('warehouseid'))) {
            $warehouseId = request('warehouseid');
        }
        $model = $this->model->where('warehouse_id', $warehouseId)->whereBetween('day_time', [date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($date) + strtotime('1 month') - strtotime('now'))])->get()->groupBy('user_id');
        $i = 0;
        $rows = [];
        foreach($model as $userId => $block) {
            $single = $block->first();
            $rows[$i] = [
                '拣货人' => $single->user->name,
                '仓库' => $single->warehouse ? $single->warehouse->name : '无所属仓库',
                '本月拣货sku数(分单单，单多，多多)' => (($block->sum('single') + $block->sum('singleMulti') + $block->sum('multi')).'(单单:'.$block->sum('single').',单多:'.$block->sum('singleMulti').',多多:'.$block->sum('multi').')'),
                '本月漏检SKU数' => $block->sum('missing_pick'),
                '漏检率' => ($block->sum('single') + $block->sum('singleMulti') + $block->sum('multi')) ? round($block->sum('missing_pick')/($block->sum('single') + $block->sum('singleMulti') + $block->sum('multi'))*100, 2).'%' : ''
            ];
            $i++;
        }
        $name = '拣货排行榜';
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function createData()
    {
        $model = PickListModel::wherebetween('pick_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1 day'))])->get()->groupBy('pick_by');
        $errors = PickListModel::all()->filter(function($single){
            return $single->status != 'PACKAGED' && strtotime('-1 day') > strtotime($single->created_at);
        })->groupBy('pick_by');
        foreach($model as $userId => $block) {
            if(!empty($userId)) {
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
                    'missing_pick' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')));
                    })->sum('quantity'),
                    'today_pick' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')));
                    })->sum('account'),
                    'today_picklist' => $block->filter(function($single){
                        return strtotime($single->pick_at) > strtotime(date('Y-m-d', strtotime('now'))) &&
                               strtotime($single->pick_at) < strtotime(date('Y-m-d', strtotime('+1 day')));
                    })->count(),
                    'day_time' => date('Y-m-d H:i:s', time()),
                    'today_picklist_undone' => $block->filter(function($single){
                        return in_array($single->status, ['PICKING', 'INBOXED', 'PACKAGEING']);
                    })->count(),
                    'more_than_twenty_four' => isset($errors[$userId]) ? $errors->get($userId)->count() : 0,
                    'warehouse_id' => $block->first()->warehouse_id,
                ]);
            }
        }
        
        return redirect($this->mainIndex);
    }
}