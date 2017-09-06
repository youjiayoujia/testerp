<?php
/**
 * 3宝package信息控制器
 * 3宝package信息相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bao3PackageModel;

class Bao3PackageController extends Controller
{
    public function __construct(Bao3PackageModel $bao3Package)
    {
        $this->model = $bao3Package;
        $this->mainIndex = route('bao3Package.index');
        $this->mainTitle = '国家信息';
        $this->viewPath = 'customsClearance.bao3Package.';
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
            'metas' => $this->metas(__FUNCTION__, '3宝package'),
            'data' => $this->autoList($this->model->where('is_tonanjing', '1')),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        
        return view($this->viewPath . 'index', $response);
    }
}