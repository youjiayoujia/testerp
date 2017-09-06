<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use App\Models\Logistics\BelongsToModel;
use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\EmailTemplateModel;
use App\Models\Logistics\LimitsModel;
use App\Models\Logistics\RuleModel;
use App\Models\Logistics\TemplateModel;
use App\Models\Logistics\Zone\CountriesModel;
use App\Models\Logistics\Zone\SectionPriceModel;
use App\Models\Logistics\ZoneModel;
use App\Models\LogisticsModel;
use App\Models\UserModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\SupplierModel;
use App\Models\ChannelModel;
use App\Models\Logistics\ChannelNameModel;
use App\Models\Logistics\ChannelModel as logisticsChannel;
use App\Models\Logistics\Rule\LimitModel as ruleLimit;
use App\Models\Logistics\Rule\AccountModel as ruleAccount;
use App\Models\Logistics\Rule\CatalogModel as ruleCatalog;
use App\Models\Logistics\Rule\CountryModel as ruleCountry;
use App\Models\Logistics\Rule\ChannelModel as ruleChannel;
use App\Models\Logistics\Rule\TransportModel as ruleTransport;

class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $logistics)
    {
        $this->model = $logistics;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流方式';
        $this->viewPath = 'logistics.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $arr = [];
        $channels = ChannelModel::all();
        foreach($channels as $channel) {
            $arr[$channel->name] = $channel->logisticsChannelName;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'arr' => $arr,
            'channels' => ChannelModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 复制信息
     */
    public function createData()
    {
        $logisticsId = request()->input('logistics_id');
        $model = $this->model->find($logisticsId);
        $data = $model->toArray();
        $data['name'] = $model->name . '[复制]';
        $data['pool_quantity'] = '';
        $data['logistics_code'] = '';
        unset($data['id']);
        $logistics = $this->model->create($data);
        if($model->channelName) {
            foreach($model->channelName as $channelName) {
                $value['logistics_id'] = $logistics->id;
                $value['logistics_channel_id'] = $channelName->id;
                $value['created_at'] = date("Y-m-d H:i:s");
                $value['updated_at'] = date("Y-m-d H:i:s");
                $belongsTo = new BelongsToModel();
                $belongsTo->create($value);
            }
        }
        if($model->logisticsChannels) {
            foreach($model->logisticsChannels as $logisticsChannel) {
                $v['logistics_id'] = $logistics->id;
                $v['channel_id'] = $logisticsChannel->channel_id;
                $v['url'] = $logisticsChannel->url;
                $v['is_up'] = $logisticsChannel->is_up;
                $v['created_at'] = date("Y-m-d H:i:s");
                $v['updated_at'] = date("Y-m-d H:i:s");
                $channel = new \App\Models\Logistics\ChannelModel();
                $channel->create($v);
            }
        }
        if($model->zones) {
            foreach($model->zones as $zone) {
                $zone->logistics_id = $logistics->id;
                $zoneModel = new ZoneModel();
                $logisticsZone = $zoneModel->create($zone->toArray());
                if($zone->type == 'second') {
                    if($zone->zone_section_prices) {
                        foreach($zone->zone_section_prices as $price) {
                            unset($price->id);
                            $price->logistics_zone_id = $logisticsZone->id;
                            $sectionPrice = new SectionPriceModel();
                            $sectionPrice->create($price->toArray());
                        }
                    }
                }
                if($zone->zone_countries) {
                    foreach($zone->zone_countries as $zoneCountry) {
                        unset($zoneCountry->id);
                        $zoneCountry->logistics_zone_id = $logisticsZone->id;
                        $zoneCountry->created_at = date("Y-m-d H:i:s");
                        $zoneCountry->updated_at = date("Y-m-d H:i:s");
                        $country = new CountriesModel();
                        $country->create($zoneCountry->toArray());
                    }
                }
            }
        }
        if($model->logisticsRules) {
            foreach($model->logisticsRules as $logisticsRule) {
                $data = $logisticsRule->toArray();
                $data['name'] = $logisticsRule->name . '[复制]';
                $data['type_id'] = $logistics->id;
                $rule = RuleModel::create($data);
                if($logisticsRule->catalog_section) {
                    $ruleCatalog = ruleCatalog::where('logistics_rule_id', $logisticsRule->id)->get();
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
                if($logisticsRule->account_section) {
                    $ruleAccount = ruleAccount::where('logistics_rule_id', $logisticsRule->id)->get();
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
                if($logisticsRule->country_section) {
                    $ruleCountry = ruleCountry::where('logistics_rule_id', $logisticsRule->id)->get();
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
                if($logisticsRule->limit_section) {
                    $ruleLimit = ruleLimit::where('logistics_rule_id', $logisticsRule->id)->get();
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
                if($logisticsRule->channel_section) {
                    $ruleChannel = ruleChannel::where('logistics_rule_id', $logisticsRule->id)->get();
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
                if($logisticsRule->transport_section) {
                    $ruleTransport = ruleTransport::where('logistics_rule_id', $logisticsRule->id)->get();
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
            }
        }

        return 1;
    }

    //启用停用
    public function updateEnable()
    {
        $logistics_id = request()->input('logistics_id');
        $model = $this->model->find($logistics_id);
        if ($model->is_enable == '1') {
            $model->update(['is_enable' => '0']);
        } else {
            $model->update(['is_enable' => '1']);
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
        foreach(request('url') as $k => $v) {
            $data['channel_id'] = $k;
            $data['url'] = $v;
            $data['is_up'] = request('channel_id')[$k];
            $data['delivery'] = request('delivery')[$k];
            $model->logisticsChannels()->create($data);
        }
        $str = '';
        if(request()->has('logistics_limits')) {
            $str = implode(',', request('logistics_limits'));
            $model->update(['limit' => $str]);
        }
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            if(!empty($value)) {
                $arr = explode(',', $value);
                $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
                if(!$channelName) {
                    continue;
                }
                $buf[$key] = $channelName->id;
            } else {
                if(!empty(request($key.'_name'))) {
                    $channelName = ChannelNameModel::create(['channel_id' => request($key.'_channelId'), 'name' => request($key.'_name')]);
                    $buf[$key] = $channelName->id;
                }
            }
        }
        $model->channelName()->sync($buf);
        $model = $this->model->with('logisticsChannels')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        return redirect($this->mainIndex);
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
            'channelNames' => $model->channelName,
            'logisticsChannels' => $model->logisticsChannels,
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
        $logistics = $this->model->find($id);
        $limits = explode(",",$logistics->limit);
        $selectedLimits = LimitsModel::whereIn('id', $limits)->get();
        if (!$logistics) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $arr = [];
        $channels = ChannelModel::all();
        foreach($channels as $channel) {
            $arr[$channel->name] = $channel->logisticsChannelName;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $logistics,
            'logisticsChannels' => $logistics->logisticsChannels,
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'selectedLimits' => $selectedLimits,
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'arr' => $arr,
            'channels' => ChannelModel::all(),
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
        $buf = request()->all();
        $buf['limit'] = '';
        if(request()->has('logistics_limits')) {
            $buf['limit'] = implode(',', request('logistics_limits'));
        }
        $model->update($buf);
        foreach(request('url') as $k => $v) {
            $logisticsChannels = logisticsChannel::where(['logistics_id' => $id, 'channel_id' => $k]);
            if($logisticsChannels->count() > 0) {
                if($logisticsChannels->first()->url != $v) {
                    $logisticsChannels->update(['url' => $v]);
                }
                if($logisticsChannels->first()->is_up != request('channel_id')[$k]) {
                    $logisticsChannels->update(['is_up' => request('channel_id')[$k]]);
                }
                if($logisticsChannels->first()->delivery != request('delivery')[$k]) {
                    $logisticsChannels->update(['delivery' => request('delivery')[$k]]);
                }
            }else {
                $data['logistics_id'] = $id;
                $data['channel_id'] = $k;
                $data['url'] = $v;
                $data['is_up'] = request('channel_id')[$k];
                $data['delivery'] = request('delivery')[$k];
                logisticsChannel::create($data);
            }
        }
        $buf = [];
        foreach(request('merchant') as $key => $value) {
            if(!empty(request($key.'_name'))) {
                $channelName = ChannelNameModel::create(['channel_id' => request($key.'_channelId'), 'name' => request($key.'_name')]);
                $buf[$key] = $channelName->id;
            } else {
                if(!empty($value)) {
                    $arr = explode(',', $value);
                    $channelName = ChannelNameModel::where(['channel_id' => $arr[0], 'name' => $arr[1]])->first();
                    if(!$channelName) {
                        continue;
                    }
                    $buf[$key] = $channelName->id;
                }
            }
        }
        $model->channelName()->sync($buf);
        $model = $this->model->with('logisticsChannels')->find($id);
        $to = json_encode($model);
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 更新号码池数量
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $array = CodeModel::distinct()->get(['logistics_id']);
        foreach($array as $key => $value)
        {
            $all = CodeModel::where(['logistics_id' => $value['logistics_id']])->count();
            $used = CodeModel::where(['logistics_id' => $value['logistics_id'], 'status' => '1'])->count();
            $unused = $all - $used;
            $pool_quantity = $unused."/".$used."/".$all;
            $arr = LogisticsModel::where(['id' => $value['logistics_id']])->get()->toArray();
            if(count($arr)) {
                foreach($arr as $k => $val)
                {
                    $model = $this->model->find($val['id']);
                    $val['pool_quantity'] = $pool_quantity;
                    $model->update(['pool_quantity' => $val['pool_quantity']]);
                }
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function getLogistics()
    {
        $logistics_id = request('logistics');
        $logistics = $this->model->find($logistics_id);
        if(!$logistics) {
            $logistics = $this->model->where('code', $logistics_id)->get();
            if(!$logistics->count()) {
                return json_encode(false);
            }
            $str = '';
            foreach($logistics as $single) {
                $str .= "<option class='logis' value='".$single->id."'>".$single->code."</option>";
            }
            return $str;
        }
        $str = "<option class='logis' value='".$logistics->id."'>".$logistics->code."</option>";
        return $str;
    }

    /**
     * 获取物流商信息
     */
    public function ajaxSupplier()
    {
        if (request()->ajax()) {
            $supplier = trim(request()->input('logistics_supplier_id'));
            $buf = SupplierModel::where('name', 'like', '%' . $supplier . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
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