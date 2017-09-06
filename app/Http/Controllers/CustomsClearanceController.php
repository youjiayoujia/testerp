<?php
/**
 * 通关报关控制器
 * @author: MC <178069409>
 * Date: 2016-1-4 10:46:32
 */
namespace App\Http\Controllers;

use Excel;
use Exception;
use App\Models\ProductModel;
use App\Http\Controllers\Controller;
use App\Models\CustomsClearanceModel;
use App\Models\PackageModel;
use App\Models\ItemModel;

class CustomsClearanceController extends Controller
{
    public function __construct(CustomsClearanceModel $customesClearance)
    {
        $this->model = $customesClearance;
        $this->mainIndex = route('customsClearance.index');
        $this->mainTitle = '通关报关';
        $this->viewPath = 'customsClearance.';
    }

   /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     * index页面 
     * 
     *  @param none
     *  @return view
     *
     */
    public function bao3index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__, '三宝产品'),
            'data' => $this->autoList($this->model),
        ];

        return view($this->viewPath . 'bao3index', $response);
    }

    public function exportProductZY()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportProductZY($arr);
        $this->exportExcel($rows, 'export_Product_ZY');
    }

    public function exportProductEUB()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportProductEUB($arr);
        $this->exportExcel($rows, 'export_Product_EUB');
    }

    public function exportProductFed()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportProductFed($arr);
        $this->exportExcel($rows, 'export_Product_Fed');
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->destroy($id);
        return redirect()->route('customsClearance.bao3index');
    }

    public function exportNXB()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportNXB($arr);
        $this->exportExcel($rows, 'export_NXB');
    }

    public function exportEUB()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportEUB($arr);
        $this->exportExcel($rows, 'export_EUB');
    }

    public function exportEUBWeight()
    {
        $str = request('model');
        $arr = explode("\n", $str);
        $rows = $this->model->exportEUBWeight($arr);
        $this->exportExcel($rows, 'export_EUB_Weight');
    }

    public function exportFailModel()
    {
        $rows = $this->model->exportFailModel();
        $this->exportExcel($rows, 'export_fail_product', '导出未备案model信息');
    }

    public function exportExcel($rows, $name)
    {
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function exportFailItem()
    {
        $rows = $this->model->exportFailItem();
        $this->exportExcel($rows, 'export_fail_item');
    }

    public function exportProduct()
    {
        $model = request('model');
        $arr = explode("\n", $model);
        $rows = $this->model->exportProduct($arr);
        $this->exportExcel($rows, 'export_product');
    }

    public function updateProduct()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $function = 'excelUpdateProcess';
           $errors = $this->model->excelProcess($file, $function);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadResult', $response);
        }
    }

    public function updateNumber()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $function = 'excelNumberProcess';
           $errors = $this->model->excelProcess($file, $function);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadNumberResult', $response);
        }
    }

    public function updateNanjing()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $function = 'excelNanjingProcess';
           $errors = $this->model->excelProcess($file, $function);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadNanjingResult', $response);
        }
    }

    public function updateOver()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $function = 'excelOverProcess';
           $errors = $this->model->excelProcess($file, $function);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadOverResult', $response);
        }
    }

    public function uploadProduct()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $function = 'excelDataProcess';
           $errors = $this->model->excelProcess($file, $function);
           $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadResult', $response);
        }
    }

    public function downloadToNanjing()
    {
        $rows = [
                    [ 
                     'package_id'=>'',
                     'is_tonanjing'=>'',
                    ]
            ];
        $name = 'download_tonanjing';
        $this->exportExcel($rows, '更新到南京状态');
    }

    public function downloadOver()
    {
        $rows = [
                    [ 
                     'package_id'=>'',
                     'is_over'=>'',
                    ]
            ];
        $this->exportExcel($rows, 'download_over');
    }

    public function downloadUploadProduct()
    {
        $rows = [
                    [ 
                     'model'=>'BLOU0302B791C',
                     'cn_name' => '123',
                     'hs_code'=>'6206400090',
                     'unit'=>'^011/^035',
                     'f_model'=>'织造方法:机织,种类:衬衫,类别:女式,成分含量:100%聚酯纤维,品牌:choies',
                    ]
            ];
        $name = 'upload_product';
        $this->exportExcel($rows, '上传三宝产品');
    }

    public function downloadUpdateProduct()
    {
        $rows = [
                    [ 
                     'model'=>'BLOU0302B791C',
                     'cn_name' => '123',
                     'hs_code'=>'6206400090',
                     'unit'=>'^011/^035',
                     'f_model'=>'织造方法:机织,种类:衬衫,类别:女式,成分含量:100%聚酯纤维,品牌:choies',
                     'status' => '1',
                    ]
            ];
        $name = 'update_product';
        $this->exportExcel($rows, '更新三宝产品');
    }

    public function downloadNumber()
    {
        $rows = [
                    [ 
                     'code' => '',
                     'number' => '',
                    ]
            ];
        $name = 'update_number';
        $this->exportExcel($rows, '更新国家number');
    }
}
