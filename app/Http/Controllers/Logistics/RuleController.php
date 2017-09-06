<?php
/**
 * 物流分配规则控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午3:20
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\CountriesModel;
use App\Models\Logistics\Rule\LimitModel as ruleLimit;
use App\Models\Logistics\Rule\AccountModel as ruleAccount;
use App\Models\Logistics\Rule\CatalogModel as ruleCatalog;
use App\Models\Logistics\Rule\CountryModel as ruleCountry;
use App\Models\Logistics\Rule\ChannelModel as ruleChannel;
use App\Models\Logistics\Rule\TransportModel as ruleTransport;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\TransportModel;
use App\Models\LogisticsModel;
use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Logistics\LimitsModel;
use App\Models\CountriesSortModel;
use App\Models\UserModel;

class RuleController extends Controller
{
    public function __construct(RuleModel $rule)
    {
        $this->model = $rule;
        $this->mainIndex = route('logisticsRule.index');
        $this->mainTitle = '物流分配规则';
        $this->viewPath = 'logistics.rule.';
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
            'catalogs' => CatalogModel::all(),
            'countrySorts' => CountriesSortModel::all(),
            'channels' => ChannelModel::all(), 
            'logisticsLimits' => LimitsModel::all(),
            'accounts' => AccountModel::all(),
            'transports' => TransportModel::all(),
            'logistics_id' => $logistics_id,
            'logistics_name' => $logistics_name,
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 某个物流方式分配规则首页
     */
    public function one($id)
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, $this->model->where('type_id', $id)),
            'mixedSearchFields' => $this->model->mixed_search,
            'id' => $id,
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     * 复制信息
     */
    public function createData()
    {
        $ruleId = request()->input('rule_id');
        $model = $this->model->find($ruleId);
        $data = $model->toArray();
        $data['name'] = $model->name . '[复制]';
        unset($model->id);
        $rule = $this->model->create($data);
        if($model->catalog_section) {
            $ruleCatalog = ruleCatalog::where('logistics_rule_id', $ruleId)->get();
            if($ruleCatalog->count() > 0) {
                foreach($ruleCatalog as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleCatalog = new ruleCatalog();
                    $ruleCatalog->create($limit->toArray());
                }
            }
        }
        if($model->account_section) {
            $ruleAccount = ruleAccount::where('logistics_rule_id', $ruleId)->get();
            if($ruleAccount->count() > 0) {
                foreach($ruleAccount as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleAccount = new ruleAccount();
                    $ruleAccount->create($limit->toArray());
                }
            }
        }
        if($model->country_section) {
            $ruleCountry = ruleCountry::where('logistics_rule_id', $ruleId)->get();
            if($ruleCountry->count() > 0) {
                foreach($ruleCountry as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleCountry = new ruleCountry();
                    $ruleCountry->create($limit->toArray());
                }
            }
        }
        if($model->limit_section) {
            $ruleLimit = ruleLimit::where('logistics_rule_id', $ruleId)->get();
            if($ruleLimit->count() > 0) {
                foreach($ruleLimit as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleLimit = new ruleLimit();
                    $ruleLimit->create($limit->toArray());
                }
            }
        }
        if($model->channel_section) {
            $ruleChannel = ruleChannel::where('logistics_rule_id', $ruleId)->get();
            if($ruleChannel->count() > 0) {
                foreach($ruleChannel as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleChannel = new ruleChannel();
                    $ruleChannel->create($limit->toArray());
                }
            }
        }
        if($model->transport_section) {
            $ruleTransport = ruleTransport::where('logistics_rule_id', $ruleId)->get();
            if($ruleTransport->count() > 0) {
                foreach($ruleTransport as $limit) {
                    unset($limit->id);
                    $limit->logistics_rule_id = $rule->id;
                    $limit->created_at = date("Y-m-d H:i:s");
                    $limit->updated_at = date("Y-m-d H:i:s");
                    $ruleTransport = new ruleTransport();
                    $ruleTransport->create($limit->toArray());
                }
            }
        }

        return 1;
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
        $model = $this->model->create(request()->all());
        $model->createAll(request()->all());
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '新增成功.'));
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
            'countries' => $model->rule_countries_through,
            'channels' => $model->rule_channels_through,
            'catalogs' => $model->rule_catalogs_through,
            'limits' => $model->rule_limits_through,
            'accounts' => $model->rule_accounts_through,
            'transports' => $model->rule_transports_through,
        ];

        return view($this->viewPath . 'show', $response);
    }


    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $selectedCountry = explode(",",$model->country);
        $selectedCountries = CountriesModel::whereIn('code', $selectedCountry)->get();
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses' => LogisticsModel::all(),
            'catalogs_outer' => CatalogModel::all(),
            'countrySorts' => CountriesSortModel::all(),
            'channels_outer' => ChannelModel::all(), 
            'logisticsLimits_outer' => LimitsModel::all(),
            'accounts_outer' => AccountModel::all(),
            'transports_outer' => TransportModel::all(),
            'countries' => $model->rule_countries_through,
            'channels' => $model->rule_channels_through,
            'catalogs' => $model->rule_catalogs_through,
            'limits' => $model->rule_limits_through,
            'accounts' => $model->rule_accounts_through,
            'transports' => $model->rule_transports_through,
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
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->updateAll(request()->all());
        $model = $this->model->with('rule_transports')->with('rule_limits')->with('rule_countries')->with('rule_accounts')->with('rule_channels')->with('rule_catalogs')->find($id);
        $to = json_encode($model);
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
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
        $model->rule_limits_through()->sync([]);
        $model->rule_channels_through()->sync([]);
        $model->rule_catalogs_through()->sync([]);
        $model->rule_countries_through()->sync([]);
        $model->rule_accounts_through()->sync([]);
        $model->rule_transports_through()->sync([]);
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}