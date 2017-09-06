<?php
/**
 * 包裹信息报表
 *
 * 2016-01-04
 * @author: MC<178069409>
 */
namespace App\Http\Controllers;

use App\Models\Package\AllReportModel;
use App\Models\PackageModel;

class AllReportController extends Controller
{
    public function __construct(AllReportModel $allReport)
    {
        $this->model = $allReport;
        $this->mainIndex = route('allReport.report');
        $this->mainTitle = '包裹中心';
        $this->viewPath = 'package.allReport.';
    }

    /**
     *  生成数据 
     *
     *  @param none
     *  @return view
     *
     */
    public function createData()
    {

        $allByWarehouseId = PackageModel::where('created_at', '>', date('Y-m', strtotime('now')))->get()
                    ->filter(function($single){
                        return !in_array($single->status, ['NEW', 'NEED', 'WAITASSIGN', 'CANCEL']);
                    })
                    ->groupBy('warehouse_id');
        $arr = [];
        foreach($allByWarehouseId as $warehouseId => $row) {
            foreach($row->groupBy('channel_id') as $key => $single) {
                $date = $single->filter(function($fd){
                    return strtotime($fd->created_at) > strtotime(date('Y-m-d', strtotime('-1 day'))) &&
                        strtotime($fd->created_at) < strtotime(date('Y-m-d', strtotime('now')));
                    });
                $date1 = $single->filter(function($fd){
                    return strtotime($fd->created_at) > strtotime(date('Y-m', strtotime('-30 days'))) &&
                        strtotime($fd->created_at) < strtotime(date('Y-m', strtotime('now')));
                    });
                $buf = [];
                $buf1 = [];
                foreach($date as $row) {
                    if(!in_array($row->order, $buf)) {
                        $buf[] = $row->order;
                    }
                }
                foreach($date1 as $row) {
                    if(!in_array($row->order, $buf1)) {
                        $buf1[] = $row->order;
                    }
                }
                $amount = 0;
                $amount1 = 0;
                foreach($buf as $order) {
                    $amount += $order->amount;
                }
                foreach($buf1 as $order) {
                    $amount1 += $order->amount;
                }
                $this->model->create([
                        'warehouse_id' => $warehouseId,
                        'channel_id' => $key,
                        'wait_send' => $single->filter(function($single1){
                            return in_array($single1->status, ['ASSIGNED', 'ASSIGNFAILED', 'TRACKINGFAILED']);
                        })->count(),
                        'sending' => $single->filter(function($single1){
                            return in_array($single1->status, ['PREOCESS', 'PICKING', 'PACKED']);
                        })->count(),
                        'sended' => $single->filter(function($single1){
                            return $single1->status == 'SHIPPED';
                        })->count(),
                        'more' => $single->filter(function($single1){
                            return $single1->status == 'PICKING' && strtotime($single1->created_at) < strtotime('-3 days');
                        })->count(),
                        'less' => $single->filter(function($single1){
                            return $single1->status == 'PICKING' && strtotime($single1->created_at) > strtotime('-3 days');
                        })->count(),
                        'daily_send' => $single->filter(function($single1){
                            return date('Y-m', strtotime($single1->created_at)) == date('Y-m', strtotime($single1->shipped_at));
                        })->count(),
                        'need' => $single->where('status', 'NEED')->count(),
                        'daily_sales' => $amount,
                        'monty_sales' => $amount1,
                        'day_time' => date('Y-m-d', strtotime('now')),
                    ]);
            }
        }
        
        return redirect($this->mainIndex);
    }

    /**
     *  主页显示 
     *
     *  @param none
     *  @return view
     *
     */
    public function packageReport()
    {
        $model = $this->model->orderBy('day_time', 'desc')->first();
        $last_time = '';
        $arr = [];
        if($model) {
            $last_time = $model->day_time;
            $model = $this->model->orderBy('day_time', 'desc')->get()->groupBy('day_time')->get($last_time);
            foreach($model->groupBy('warehouse_id') as $warehouseId => $block) {
                $arr[$warehouseId][] = $block->sum('wait_send');
                $arr[$warehouseId][] = $block->sum('sending');
                $arr[$warehouseId][] = $block->sum('sended');
                $arr[$warehouseId][] = $block->sum('more');
                $arr[$warehouseId][] = $block->sum('less');
                $arr[$warehouseId][] = $block->sum('daily_send');
                $arr[$warehouseId][] = $block->sum('need');
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'arr' => $arr,
        ];

        return view($this->viewPath . '.index', $response);
    }

}