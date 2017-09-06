<?php
/**
 * 物流分区控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:46
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\CountriesModel;
use App\Models\Logistics\PartitionModel;
use App\Models\Logistics\ZoneModel;
use App\Models\LogisticsModel;
use App\Models\Logistics\Zone\SectionPriceModel;
use App\Models\Logistics\Zone\CountriesModel as zoneCountry;

class ZoneController extends Controller
{
    public function __construct(ZoneModel $zoneModel)
    {
        $this->model = $zoneModel;
        $this->mainIndex = route('logisticsZone.index');
        $this->mainTitle = '物流分区';
        $this->viewPath = 'logistics.zone.';
    }

    /**
     * 新增
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $arr = explode('/', $_SERVER['HTTP_REFERER']);
        $logistics_id = $arr[count($arr) - 1];
        $logistics_name = LogisticsModel::where('id', $logistics_id)->first()->name;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'partitions' => PartitionModel::with('partitionSorts.country')->get(),
            'model' => $this->model->where('logistics_id', LogisticsModel::first()->id)->first(),
            'logistics_id' => $logistics_id,
            'logistics_name' => $logistics_name,
            'hideUrl' => $hideUrl,
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 复制信息
     */
    public function createData()
    {
        $zoneId = request()->input('zone_id');
        $model = $this->model->find($zoneId);
        $data = $model->toArray();
        $data['zone'] = $model->zone . '[复制]';
        unset($data['id']);
        $logisticsZone = $this->model->create($data);
        if($model->type == 'second') {
            if($model->zone_section_prices) {
                foreach($model->zone_section_prices as $price) {
                    unset($price->id);
                    $price->logistics_zone_id = $logisticsZone->id;
                    $sectionPrice = new SectionPriceModel();
                    $sectionPrice->create($price->toArray());
                }
            }
        }
        if($model->zone_countries) {
            foreach($model->zone_countries as $zoneCountry) {
                unset($zoneCountry->id);
                $zoneCountry->logistics_zone_id = $logisticsZone->id;
                $zoneCountry->created_at = date("Y-m-d H:i:s");
                $zoneCountry->updated_at = date("Y-m-d H:i:s");
                $country = new zoneCountry();
                $country->create($zoneCountry->toArray());
            }
        }

        return 1;
    }

    public function sectionAdd()
    {
        $current = request('current');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'current' => $current,
        ];

        return view($this->viewPath.'add', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $logistics_id = request('logistics_id');
        $this->model->createData(request()->all());
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '新增成功.'));
//        return redirect($this->mainIndex . '/one/' . $logistics_id);
    }


    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $buf = '';
        if(request()->has('logisticsId')) {
            $buf = $this->model->where('logistics_id', request('logisticsId'));
        }
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList(!empty($buf) ? $buf : $this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }


    /**
     * 某个物流方式分区报价首页
     */
    public function one($id)
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, $this->model->where('logistics_id', $id)),
            'mixedSearchFields' => $this->model->mixed_search,
            'id' => $id,
        ];
        return view($this->viewPath . 'index', $response);
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
            'countries' => $model->logistics_zone_countries,
            'sectionPrices' => $model->zone_section_prices,
        ];

        return view($this->viewPath . 'show', $response);
    }

    public function getCountries()
    {
        $logistics_id = request('logistics_id');
        $models = $this->model->where('logistics_id', $logistics_id)->get();
        $arr = [];
        foreach($models as $model) {
            $countries = $model->logistics_zone_countries;
            foreach($countries as $country) {
                $arr[] = $country->id;
            }
        }

        return $arr;
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->with('zone_section_prices')->with('logistics_zone_countries')->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $arr = explode('/', $_SERVER['HTTP_REFERER']);
        $logistics_id = $arr[count($arr) - 1];
        $logistics_name = LogisticsModel::where('id', $logistics_id)->first()->name;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'partitions' => PartitionModel::with('partitionSorts.country')->get(),
            'sectionPrices' => $model->zone_section_prices,
            'len' =>  $model->zone_section_prices->count(),
            'logistics_name' => $logistics_name,
            'hideUrl' => $hideUrl,
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
        $logistics_id = $model->logistics_id;
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->updateData(request()->all());

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 快递运费计算
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countExpress($id)
    {
        $zone = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $zone,
            'logistics' => LogisticsModel::all(),
            'country' => CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
        ];
        return view('logistics.zone.countExpress', $response);
    }

    /**
     * 小包运费计算
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countPacket($id)
    {
        $zone = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $zone,
            'logistics' => LogisticsModel::all(),
            'country' => CountriesModel::orderBy('code', 'asc')->get(['name', 'code']),
        ];
        return view('logistics.zone.countPacket', $response);
    }

    /**
     * ajax获取快递种类
     */
    public function zoneShipping()
    {
        $id = request()->input("id");
        $buf = $this->model->find($id)->shipping_id;
        return json_encode($buf);
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
        $countries = $model->zone_countries;
        foreach($countries as $country) {
            $country->delete();
        }
        $sectionPrices = $model->zone_section_prices;
        foreach($sectionPrices as $sectionPrice) {
            $sectionPrice->delete();
        }
        $model->destroy($id);
        return redirect($this->mainIndex);
    }

}