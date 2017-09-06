<?php
/**
 * 国家信息控制器
 * 国家信息相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountriesModel;
use BarcodeGen;
use App\Models\CountriesSortModel;

class CountriesController extends Controller
{
    public function __construct(CountriesModel $countries)
    {
        $this->model = $countries;
        $this->mainIndex = route('countries.index');
        $this->mainTitle = '国家信息';
        $this->viewPath = 'countries.';
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
            'sorts' => CountriesSortModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function barcodePrint($content, $height = 50, $orientation = 'horizontal', $type = 'code128', $length = 1)
    {
        return BarcodeGen::generate([$content, $height, $orientation, $type, $length])->response('png');
    }
}