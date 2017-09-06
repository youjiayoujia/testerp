<?php
/**
 * 国家转换控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/11/25
 * Time: 下午1:48
 */

namespace App\Http\Controllers;

use App\Models\CountriesChangeModel;
use App\Models\CountriesModel;

class CountriesChangeController extends Controller
{
    public function __construct(CountriesChangeModel $countriesChange)
    {
        $this->model = $countriesChange;
        $this->mainIndex = route('countriesChange.index');
        $this->mainTitle = '国家转换';
        $this->viewPath = 'countries.change.';
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'countries' => CountriesModel::all(),
            'model' => $model,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 获取来源国家信息
     */
    public function ajaxCountryTo()
    {
        if (request()->ajax()) {
            $country = trim(request()->input('country_to'));
            $buf = CountriesModel::where('code', 'like', '%' . $country . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->code;
                $arr[$key]['text'] = $value->code . ' ' . $value->cn_name;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }
}