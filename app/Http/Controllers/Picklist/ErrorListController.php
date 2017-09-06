<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers\Picklist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pick\ErrorListModel;
use App\Models\PackageModel;
use Excel;

class ErrorListController extends Controller
{
    public function __construct(ErrorListModel $errorList)
    {
        $this->model = $errorList;
        $this->mainIndex = route('errorList.index');
        $this->mainTitle = '拣货单异常';
        $this->viewPath = 'pick.errorList.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];

        return view($this->viewPath . 'index', $response);
    }

    /**
     * 列表显示 
     *
     * @param $id 
     * @return view
     *
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
            'packages' => $model->package()->with('items')->get(),
        ];

        return view($this->viewPath.'show', $response);
    }

    public function exportException($arr)
    {
        $rows = [];
        foreach(explode(',', $arr) as $id) {
            $model = $this->model->find($id);
            $rows[] = [
                'sku' => $model->item ? $model->item->sku : '',
                // iconv('utf-8', 'gbk', '包裹号') => $model->packageNum,
                // iconv('utf-8', 'gbk', '库位') => iconv('utf-8', 'gbk', $model->warehousePosition ? $model->warehousePosition->name : ''),
                // iconv('utf-8', 'gbk', '仓库') => iconv('utf-8', 'gbk', $model->warehouse ? $model->warehouse->name : ''),
                // iconv('utf-8', 'gbk', '数量') => $model->quantity,
                '包裹号' => $model->packageNum,
                '库位' => $model->warehousePosition ? $model->warehousePosition->name : '',
                '仓库' => $model->warehouse ? $model->warehouse->name : '',
                '数量' => $model->quantity,
            ];
        }
        $name = 'export_exception';
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
}