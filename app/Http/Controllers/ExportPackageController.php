<?php
/**
 * 数据模板控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package\ExportModel;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\WarehouseModel;
use App\Models\PackageModel;
use Excel;
use Session;

class ExportPackageController extends Controller
{
    public function __construct(ExportModel $export)
    {
        $this->model = $export;
        $this->mainIndex = route('exportPackage.index');
        $this->mainTitle = '模版';
        $this->viewPath = 'package.export.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'fields' => config('exportPackage'),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $model = $this->model->create(request()->all());
        $fieldNames = request('fieldNames');
        foreach ($fieldNames as $fieldName) {
            $level = request($fieldName . ',level') ? request($fieldName . ',level') : 'Z';
            $defaultName = request($fieldName . ',name');
            $model->items()->create(['name' => $fieldName, 'level' => $level, 'defaultName' => $defaultName]);
        }
        if (request()->has('arr')) {
            $arr = request('arr');
            foreach ($arr['fieldName'] as $key => $value) {
                if ($value) {
                    $model->extra()->create([
                        'fieldName' => $arr['fieldName'][$key],
                        'fieldValue' => $arr['fieldValue'][$key],
                        'fieldLevel' => $arr['fieldLevel'][$key],
                    ]);
                }
            }
        }

        return redirect($this->mainIndex);
    }

    public function extraField()
    {
        $current = request('current');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'current' => $current,
        ];

        return view($this->viewPath . 'extraField', $response);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'exportPackageItems' => $model->items,
            'arr' => config('exportPackage'),
            'extras' => $model->extra,
        ];

        return view($this->viewPath . 'show', $response);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'fields' => config('exportPackage'),
            'items' => $model->items,
            'extras' => $model->extra
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $model->update(request()->all());
        $items = $model->items;
        $arr_items = request('fieldNames');
        if ($items->count() >= count($arr_items)) {
            foreach ($items as $key => $value) {
                if (array_key_exists($key, $arr_items)) {
                    $level = request($arr_items[$key] . ",level") ? request($arr_items[$key] . ",level") : 'z';
                    $defaultName = request($arr_items[$key] . ",name");
                    $value->update(['name' => $arr_items[$key], 'level' => $level, 'defaultName' => $defaultName]);
                } else {
                    $value->delete();
                }
            }
        } else {
            foreach ($items as $key => $value) {
                $level = request($arr_items[$key] . ",level") ? request($arr_items[$key] . ",level") : 'z';
                $defaultName = request($arr_items[$key] . ",name");
                $value->update(['name' => $arr_items[$key], 'level' => $level, 'defaultName' => $defaultName]);
            }
            for ($i = $items->count(); $i < count($arr_items); $i++) {
                $level = request($arr_items[$i] . ",level") ? request($arr_items[$i] . ",level") : 'z';
                $defaultName = request($arr_items[$key] . ",name");
                $model->items()->create(['name' => $arr_items[$i], 'level' => $level, 'defaultName' => $defaultName]);
            }
        }
        $extras = $model->extra;
        foreach ($extras as $extra) {
            $extra->delete();
        }
        if (request()->has('arr')) {
            $arr = request('arr');
            foreach ($arr['fieldName'] as $key => $value) {
                if ($value) {
                    $model->extra()->create([
                        'fieldName' => $arr['fieldName'][$key],
                        'fieldValue' => $arr['fieldValue'][$key],
                        'fieldLevel' => $arr['fieldLevel'][$key]
                    ]);
                }
            }
        }

        return redirect($this->mainIndex);
    }

    public function exportPackageView()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'fields' => $this->model->all(),
            'channels' => ChannelModel::all(),
            'warehouses' => WarehouseModel::where('is_available', '1')->get(),
            'statuses' => config('package'),
            'logisticses' => LogisticsModel::all(),
        ];

        return view($this->viewPath . 'exportPackageView', $response);
    }

    /**
     *  导出包裹数据信息
     *
     * @param none
     * @return excel
     *
     */
    public function exportPackageDetail()
    {
        ini_set('memory_limit', '2G');
        $field = $this->model->find(request('field_id'));
        $fieldItems = $field->items;
        $arr = [];
        foreach ($fieldItems as $fieldItem) {
            $arr[$fieldItem->level]['name'] = $fieldItem->name;
            $arr[$fieldItem->level]['defaultName'] = $fieldItem->defaultName;
            $arr[$fieldItem->level]['type'] = 'database';
        }
        $packages = '';
        
        if (request()->has('warehouse_id')) {
            $packages = PackageModel::where('warehouse_id', request('warehouse_id'));
        }
        if (request()->has('channel_id')) {
            $packages = $packages->where('channel_id', request('channel_id'));
        }
        if (request()->has('logistics_id')) {
            $packages = $packages->where('logistics_id', request('logistics_id'));
        }
        if (request()->has('status')) {
            $packages = $packages->where('status', request('status'));
        }
        if (request()->has('begin_shipped_at') && request()->has('over_shipped_at')) {
            $begin_shipped_at = date('Y-m-d H:i:s', strtotime(request('begin_shipped_at')));
            $over_shipped_at = date('Y-m-d H:i:s', strtotime(request('over_shipped_at')));
            $packages = $packages->whereBetween('shipped_at',
                [$begin_shipped_at, $over_shipped_at]);
        }
        if (request()->hasFile('accordingTracking')) {
            $file = request()->file('accordingTracking');
            $buf = $this->model->processGoods($file, 'tracking_no');
            if(!$buf) {
                return redirect(route('exportPackage.exportPackageView'))->with('alert', $this->alert('danger', '请查看excel表格是否有问题'));
            }
            $packageStatus = config('package');
            $packages = PackageModel::whereIn('tracking_no', $buf)->orWhere(function ($query) use ($buf) {
                $query = $query->whereIn('logistics_order_number', $buf);
            });
        }
        if (request()->hasFile('accordingPackageId')) {
            $file = request()->file('accordingPackageId');
            $buf = $this->model->processGoods($file, 'package_id');
            if(!$buf) {
                return redirect(route('exportPackage.exportPackageView'))->with('alert', $this->alert('danger', '请查看excel表格是否有问题'));
            }
            $packageStatus = config('package');
            $packages = PackageModel::whereIn('id', $buf);
        }
        $packages = $packages->get();
        if ($packages->count()) {
            $buf = config('exportPackage');
            $extras = [];
            foreach ($field->extra as $extra) {
                $extras[$extra->fieldLevel]['name'] = $extra->fieldName;
                $extras[$extra->fieldLevel]['value'] = $extra->fieldValue;
                $extras[$extra->fieldLevel]['type'] = 'extra';
            }
            $fields = array_merge($arr, $extras);
            ksort($fields);
            $rows = $this->model->calArray($packages, $buf, $fields);
            $name = 'export_packages';
            if(!$rows) {
                return redirect(route('exportPackage.exportPackageView'))->with('alert', $this->alert('danger', '导出信息有误，请查看模板是否有问题'));
            }
            Session::forget('alert');
            Excel::create($name, function ($excel) use ($rows) {
                $excel->sheet('', function ($sheet) use ($rows) {
                    $sheet->fromArray($rows);
                });
            })->download('csv');
        } else {
            request()->flash();
            return redirect(route('exportPackage.exportPackageView'))->with('alert', $this->alert('danger', '根据条件找不到包裹信息'));
        }
    }

    public function getTnoReturnExcel()
    {
        $rows[] = [
            '只一列追踪号' => '',
        ];
        $name = 'return_goods';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function getTnoExcel()
    {
        $rows[] = [
            'tracking_no' => '',
        ];
        $name = 'package_export';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function getTnoExcelById()
    {
        $rows[] = [
            'package_id' => '',
        ];
        $name = 'package_export';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}