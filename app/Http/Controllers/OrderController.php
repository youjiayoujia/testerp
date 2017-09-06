<?php
/**
 * 订单控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/19
 * Time: 上午9:53
 */

namespace App\Http\Controllers;

use App\Jobs\DoPackages;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\CountriesModel;
use App\Models\CurrencyModel;
use App\Models\ItemModel;
use App\Models\Order\EbayAmountStatisticsModel;
use App\Models\Order\EbaySkuSaleReportModel;
use App\Models\OrderModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\RoleModel;
use App\Models\User\UserRoleModel;
use App\Models\UserModel;
use App\Models\ItemModel as productItem;
use App\Models\Order\ItemModel as orderItem;

class OrderController extends Controller
{
    public function __construct(OrderModel $order)
    {
        $this->model = $order;
        $this->mainIndex = route('order.index');
        $this->mainTitle = '订单管理';
        $this->viewPath = 'order.';
    }

    /**
     * 跳转创建页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 获取国家信息
     */
    public function ajaxCountry()
    {
        if (request()->ajax()) {
            $country = trim(request()->input('shipping_country'));
            $buf = CountriesModel::where('code', 'like', '%' . $country . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->code;
                $arr[$key]['text'] = $value->code;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    /**
     * 获取sku信息
     */
    public function ajaxSku()
    {
        if (request()->ajax()) {
            $sku = trim(request()->input('sku'));
            $buf = ItemModel::where('sku', 'like', '%' . $sku . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->sku;
                $arr[$key]['text'] = $value->warehouse->name . ' ' . $value->sku . ' ' .
                    $value->product->c_name . ' ' . $value->getAllQuantityAttribute() . ' ' . $value->status_name;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    public function createVirtualPackage()
    {
        set_time_limit(0);
        $len = 1000;
        $start = 0;
        $model = $this->model->where('status', 'PREPARED')->skip($start)->take($len)->get();
        while(count($model)) {
            foreach ($model as $key => $single) {
                $job = new DoPackages($single);
                $job = $job->onQueue('doPackages');
                $this->dispatch($job);
            }
            $start += $len;
            $model = $this->model->where('status', 'PREPARED')->skip($start)->take($len)->get();
        }

        return redirect('/')->with('alert', $this->alert('success', '已成功加入doPackages队列'));
    }

    /**
     * EbaySku销量报表
     */
    public function saleReport()
    {
        $channelId = ChannelModel::where('driver', 'ebay')->first()->id;
        $ebayPublishProducts = EbayPublishProductModel::all();
        foreach ($ebayPublishProducts as $ebayPublishProduct) {
            $data['sku'] = substr(strstr(strstr($ebayPublishProduct->sku, '*'), '[', true), 1);
            $data['channel_name'] = 'Ebay';
            $data['site'] = $ebayPublishProduct->site_name;
            $data['one_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))) . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['seven_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['fourteen_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-14 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['sale_different'] = $data['seven_sale'] - ($data['fourteen_sale'] - $data['seven_sale']);
            if ($data['fourteen_sale'] - $data['seven_sale'] == 0) {
                $data['sale_different_proportion'] = 0;
            } else {
                $data['sale_different_proportion'] = $data['sale_different'] / ($data['fourteen_sale'] - $data['seven_sale']);
            }
            $data['thirty_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['ninety_sale'] = orderItem::where('channel_id', $channelId)
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-90 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('sku', $data['sku'])
                ->count();
            $data['created_time'] = null;
            $data['status'] = null;
            $item = ItemModel::where('sku', $data['sku'])->first();
            if ($item) {
                $data['created_time'] = $item->created_at;
                $data['status'] = $item->status;
            }
            $data['is_warning'] = '1';
            if ($data['status'] == 'stopping') {
                $data['is_warning'] = '0';
            }
            $ebaySkuSaleReports = EbaySkuSaleReportModel::where('sku', $data['sku'])->where('site', $data['site']);
            if ($ebaySkuSaleReports->count()) {
                $ebaySkuSaleReports->update([
                    'sale_different' => $data['sale_different'],
                    'sale_different_proportion' => $data['sale_different_proportion'],
                    'one_sale' => $data['one_sale'],
                    'seven_sale' => $data['seven_sale'],
                    'fourteen_sale' => $data['fourteen_sale'],
                    'thirty_sale' => $data['thirty_sale'],
                    'ninety_sale' => $data['ninety_sale'],
                    'created_time' => $data['created_time'],
                    'status' => $data['status'],
                    'is_warning' => $data['is_warning']
                ]);
            } else {
                EbaySkuSaleReportModel::create($data);
            }
        }

        return 1;
    }

    /**
     * EBAY销售额统计
     */
    public function amountStatistics()
    {
        $roleId = RoleModel::where('role', 'ebay_staff')->first()->id;
        $userRoles = UserRoleModel::where('role_id', $roleId)->get();
        $data['channel_name'] = 'Ebay';
        foreach ($userRoles as $userRole) {
            $data['user_id'] = $userRole->user_id;
            $data['prefix'] = 0;
            $ebayPublishProducts = EbayPublishProductModel::where('seller_id', $data['user_id']);
            if ($ebayPublishProducts->count()) {
                $data['prefix'] = explode('*', $ebayPublishProducts->first()->sku)[0];
            }
            foreach ($ebayPublishProducts->get() as $ebayPublishProduct) {

            }
            $data['january_publish'] = EbayPublishProductModel::where('seller_id', $data['user_id'])
                ->whereBetween('created_at', [date('Y-m-01', strtotime(date('Y-m-d'))) . ' 00:00:00', date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-01')))) . ' 00:00:00'])
                ->where('listing_type', '!=', 'Chinese')
                ->count();
            $data['yesterday_publish'] = EbayPublishProductModel::where('seller_id', $data['user_id'])
                ->whereBetween('created_at', [date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))) . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->where('listing_type', '!=', 'Chinese')
                ->count();
            $data['created_date'] = date('Y-m');
            $ebayAmountStatistics = EbayAmountStatisticsModel::where('user_id', $data['user_id'])->where('created_date', date('Y-m'));
            if ($ebayAmountStatistics->count()) {
                $ebayAmountStatistics->update([
                    'january_publish' => $data['january_publish'],
                    'yesterday_publish' => $data['yesterday_publish'],
                    'created_date' => $data['created_date'],
                ]);
            } else {
                EbayAmountStatisticsModel::create($data);
            }
        }

        return 1;
    }

    /**
     * 保存数据
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rule(request()));
        $data = request()->all();
        foreach ($data['arr'] as $key => $item) {
            foreach ($item as $k => $v) {
                $data['items'][$k][$key] = $v;
            }
        }
        unset($data['arr']);
        $data['priority'] = 0;
        $data['package_times'] = 0;
        $model = $this->model->createOrder($data);
        $model = $this->model->with('items')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '新增成功.'));
    }

    /**
     * 首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $order = $this->model;
        $orderStatistics = '';
//        if ($this->allList($order)->count()) {
//            $totalAmount = 0;
//            $totalPlatform = 0;
//            $profit = 0;
//            foreach ($this->allList($order)->get() as $value) {
//                $totalAmount += $value->amount * $value->rate;
//                $profit += $value->profit;
//                $totalPlatform += $value->channel_fee;
//            }
//            $totalAmount = sprintf("%.2f", $totalAmount);
//            $averageProfit = sprintf("%.4f", $profit / $totalAmount) * 100;
//            $totalPlatform = sprintf("%.2f", $totalPlatform);
//            $orderStatistics = '总计金额:$' . $totalAmount . '平均利润率:' . $averageProfit . '%' . '总平台费:$' . $totalPlatform;
//        }
        $subtotal = 0;
//        foreach ($this->autoList($order) as $value) {
//            $subtotal += $value->amount * $value->rate;
//        }
        //订单首页不显示数据
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $orderUrl = route('order.index');
        if ($url == $orderUrl) {
            $order = $this->model->where('id', 0);
            $subtotal = 0;
            $orderStatistics = '';
        }
        $page = request()->input('page');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, $order, ['*'], null, 'restrict',
                [
                    'packages',
                    'channel',
                    'channelAccount',
                    'userOperator',
                    'country',
                    'remarks',
                    'remarks.user',
                    'unpaidOrder',
                    'refunds',
                    'items',
                    'items.item',
                    'items.item.warehouse',
                    'packages.logistics',
                    'packages.warehouse'
                ]
            ),
            'mixedSearchFields' => $this->model->mixed_search,
            'currencys' => CurrencyModel::get(['code']),
            'subtotal' => $subtotal,
            'hideUrl' => $url,
            'page' => $page,
            'orderStatistics' => $orderStatistics,
        ];
        $this->model->clearSession();
        
        return view($this->viewPath . 'index', $response);
    }

    //运费
    public function logisticsFee()
    {
        $arr = request('arr');
        $buf = [];
        if (!empty($arr)) {
            foreach ($arr as $key => $id) {
                $order = $this->model->find($id);
                if (!$order) {
                    $buf[$key][0] = '订单未找到';
                    $buf[$key][1] = 0;
                    continue;
                }
                $buf[$key][1] = ($order->logistics_fee ? $order->logistics_fee : 0) . 'RMB';
            }
        }

        return $buf;
    }

    //订单统计
    public function orderStatistics()
    {
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $orders = $this->model
            ->whereBetween('created_at', [date('Y-m-d H:i:s', strtotime($startDate)), date('Y-m-d H:i:s', strtotime('+1 day', strtotime($endDate)))]);
        $data['totalAmount'] = 0;
        $data['averageProfit'] = 0;
        $data['totalPlatform'] = 0;
        $profit = '';
        if ($orders->count()) {
            foreach ($orders->get() as $order) {
                $data['totalAmount'] += $order->amount * $order->rate;
                $profit += $order->profit_rate;
                $data['totalPlatform'] += $order->channel_fee;
            }
            $data['totalAmount'] = sprintf("%.2f", $data['totalAmount']);
            $data['averageProfit'] = sprintf("%.2f", $profit / $orders->count());
            $data['totalPlatform'] = sprintf("%.2f", $data['totalPlatform']);
        }

        return $data;
    }

    public function invoice($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'germanInvoice', $response);
    }

    /**
     * 跳转编辑页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->items,
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'aliases' => $model->channel->accounts,
            'arr' => $arr,
            'rows' => $model->items()->count(),
            'countries' => CountriesModel::all(),
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 跳转退款页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refund($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'orderItems' => $model->items->where('is_refund', '0'),
            'channels' => ChannelModel::all(),
            'accounts' => AccountModel::all(),
            'users' => UserModel::all(),
            'currencys' => CurrencyModel::all(),
            'aliases' => $model->channel->accounts,
            'arr' => $arr,
            'rows' => $model->items()->count(),
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'refund', $response);
    }

    /**
     * 保存退款信息
     *
     * @param $id
     */
    public function refundUpdate($id)
    {
        $model = $this->model->find($id);
        $page = request()->input('page');
        $url = request()->has('hideUrl') ? request('hideUrl').'&page='.$page : $this->mainIndex;
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($url)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $data = request()->all();
        $data['order_id'] = $id;
        $data['channel_id'] = $model->channel_id;
        $data['account_id'] = $model->channel_account_id;
        $model->refundCreate($data, request()->file('image'));
        $to = json_encode($model);
        $this->eventLog($userName->name, '退款新增,id=' . $id, $to, $from);
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * ajax 创建退款记录
     */
    public function ajaxAddRefund(){
        $data = request()->input();
        $model = $this->model->find($data['order_id']);
        $data['order_id'] = $model->id;
        $data['channel_id'] = $model->channel_id;
        $data['account_id'] = $model->channel_account_id;
        if(! empty($data['tribute_id'])){
            $data['tribute_id'] = array_unique($data['tribute_id']);
        }
        if(!empty($model->refundCreate($data, request()->file('image')))){
            $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
            return config('status.ajax.success');
        }else{
            return conifg('status.ajax.fail');
        }

    }

    /**
     * 更新备注
     */
    public function remarkUpdate($id)
    {
        request()->flash();
        $page = request()->input('page');
        $data = request()->all();
        $data['user_id'] = request()->user()->id;
        $this->model->find($id)->remarks()->create($data);
        $url = request()->has('hideUrl') ? request('hideUrl').'&page='.$page : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 数据更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        request()->flash();
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->with('items')->find($id));
        $this->validate(request(), $this->model->updateRule(request()));
        $data = request()->all();
        $data['status'] = 'REVIEW';
        foreach ($data['arr'] as $key => $item) {
            foreach ($item as $k => $v) {
                $data['items'][$k][$key] = $v;
            }
        }
        unset($data['arr']);
        $this->model->find($id)->update($data);
        foreach ($data['items'] as $key1 => $item) {
            $obj = productItem::where('sku', $item['sku'])->get();
            if (!count($obj)) {
                $item['item_id'] = 0;
                $this->model->find($id)->update(['status' => 'ERROR']);
            } else {
                $item['item_id'] = productItem::where('sku', $item['sku'])->first()->id;
                $item['item_status'] = productItem::where('sku', $item['sku'])->first()->status;
            }
            $orderItems = $this->model->find($id)->items;
            if (count($data['items']) == count($orderItems)) {
                foreach ($orderItems as $key2 => $orderItem) {
                    if ($key1 == $key2) {
                        $orderItem->update($item);
                    }
                }
            } else {
                foreach ($orderItems as $key2 => $orderItem) {
                    $orderItem->delete($item);
                }
                foreach ($data['items'] as $value) {
                    $value['item_id'] = productItem::where('sku', $value['sku'])->first()->id;
                    $value['item_status'] = productItem::where('sku', $value['sku'])->first()->status;
                    $this->model->find($id)->items()->create($value);
                }
            }
        }
        if ($this->model->find($id)->packages) {
            foreach ($this->model->find($id)->packages as $package) {
                $package->cancelPackage();
            }
        }
        $job = new DoPackages($this->model->find($id));
        $job->onQueue('doPackages');
        $this->dispatch($job);

        $to = json_encode($this->model->with('items')->find($id));
        $this->eventLog($userName->name, '数据更新,id=' . $id, $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 信息详情页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        $arr = [];
        foreach ($model->items as $orderItem) {
            $arr[] = $orderItem->sku;
        }
//        foreach($arr as $key => $value) {
//            $obj = productItem::where(['sku' => $value])->first();
//            if ($obj->product && $obj->product->url1 != '') {
//                $arr[$key] = $obj->product->url1;
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'orderItems' => $model->items,
            'packages' => $model->packages,
            'model' => $model,
            'arr' => $arr,
        ];

        return view($this->viewPath . 'show', $response);
    }

    /**
     * 数据删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $obj = $this->model->find($id);
        foreach ($obj->items as $val) {
            $val->delete();
        }
        $obj->delete($id);

        return redirect($this->mainIndex);
    }

    /**
     * 验证订单sku
     *
     * @return string
     */
    public function getMsg()
    {
        if (request()->ajax()) {
            $sku = request()->input('sku');
            $obj = productItem::where(['sku' => $sku])->first();
            if ($obj) {
                $result = $obj->product->url1;
                return json_encode($result);
            } else {
                return json_encode(false);
            }

        }
        return json_encode(false);
    }

    /**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxOrderAdd()
    {
        if (request()->ajax()) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath . 'add', $response);
        }
        return null;
    }

    /**
     * 渠道对应渠道账号
     *
     * @return string
     */
    public function account()
    {
        $id = request()->input('id');
        $buf = ChannelModel::find($id)->accounts;
        return json_encode($buf);
    }

    //审核
    public function updateStatus()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $model = $this->model->find($order_id);
        if ($model->items) {
            $count = 0;
            foreach ($model->items as $item) {
                if ($item->item) {
                    $count++;
                }
            }
            if ($count == $model->items->count()) {
                $model->calculateOrderChannelFee();
                if($model->packages->count()) {
                    $model->update(['status' => 'PICKING', 'is_review' => 1]);
                } else {
                    $model->update(['status' => 'PREPARED', 'is_review' => 1]);
                }
                $model->packagesToQueue();
                if ($model->remarks) {
                    foreach ($model->remarks as $remark) {
                        if ($remark->type == 'PAYPAL') {
                            $model->update(['order_is_alert' => 2]);
                        }
                        if ($remark->type != 'DEFAULT') {
                            $remark->delete();
                        }
                    }
                }
            }
        }
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '审核更新,id=' . $order_id, $to, $from);
        return 1;
    }

    //暂停发货
    public function updatePrepared()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $this->model->find($order_id)->update(['active' => 'STOP']);
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '暂停发货更新,id=' . $order_id, $to, $from);

        return 1;
    }

    //恢复正常
    public function updateNormal()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $this->model->find($order_id)->update(['active' => 'NORMAL']);
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '恢复正常更新,id=' . $order_id, $to, $from);

        return 1;
    }

    //恢复订单
    public function updateRecover()
    {
        $order_id = request()->input('order_id');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($order_id));
        $this->model->find($order_id)->update(['status' => 'REVIEW']);
        $to = json_encode($this->model->find($order_id));
        $this->eventLog($userName->name, '恢复订单更新,id=' . $order_id, $to, $from);

        return 1;
    }

    /**
     * 批量审核
     *
     * @return int
     */
    public function partReview()
    {
        $userName = UserModel::find(request()->user()->id);
        $ids = request()->input('ids');
        $ids_arr = explode(',', $ids);
        foreach ($ids_arr as $id) {
            $model = $this->model->find($id);
            if ($model->items) {
                $count = 0;
                foreach ($model->items as $item) {
                    if ($item->item) {
                        $count++;
                    }
                }
                if ($count == $model->items->count()) {
                    $model->calculateOrderChannelFee();
                    if ($model) {
                        if ($model->remarks) {
                            foreach ($model->remarks as $remark) {
                                if ($remark->type == 'PAYPAL') {
                                    $model->update(['order_is_alert' => 2]);
                                }
                                if ($remark->type != 'DEFAULT') {
                                    $remark->delete();
                                }
                            }
                        }
                        $from = json_encode($model);
                        if ($model->status = 'REVIEW') {
                            if($model->packages->count()) {
                                $model->update(['status' => 'PICKING', 'is_review' => '1']);
                            } else {
                                $model->update(['status' => 'PREPARED', 'is_review' => '1']);
                            }
                            $model->packagesToQueue();
                        }
                        $to = json_encode($model);
                        $this->eventLog($userName->name, '批量审核,id=' . $id, $to, $from);
                    }
                }
            }
        }
        return 1;
    }

    /**
     * 批量撤单
     *
     * @return int
     */
    public function withdrawAll()
    {
        $userName = UserModel::find(request()->user()->id);
        $order_ids = request()->input('order_ids');
        $order_ids_arr = explode(',', $order_ids);
        $data = request()->all();
        foreach ($order_ids_arr as $id) {
            if ($this->model->find($id)) {
                $from = json_encode($this->model->find($id));
                $order = $this->model->find($id);
                $order->cancelOrder($data['withdraw']);
                $to = json_encode($this->model->find($id));
                $this->eventLog($userName->name, '批量撤单,id=' . $id, $to, $from);
            }
        }
        return 1;
    }
    //撤单
    public function withdrawUpdate($id)
    {
        $page = request()->input('page');
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($this->model->find($id));
        request()->flash();
        $data = request()->all();
        $order = $this->model->find($id);
        $order->cancelOrder($data['withdraw']);
        $to = json_encode($this->model->find($id));
        $this->eventLog($userName->name, '撤单新增,id=' . $id, $to, $from);
        $url = request()->has('hideUrl') ? request('hideUrl').'&page='.$page : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }
    //ajax撤单
    public function ajaxWithdraw()
    {
        $id = request()->input('id');
        if (!empty($id)) {
            $userName = UserModel::find(request()->user()->id);
            $from = json_encode($this->model->find($id));
            request()->flash();
            $data = request()->all();
            $order = $this->model->find($id);
            $order->cancelOrder($data['withdraw']);
            $to = json_encode($this->model->find($id));
            $this->eventLog($userName->name, '撤单新增,id=' . $id, $to, $from);
        }

        return 1;
    }

    public function withdraw($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'withdraw', $response);
    }

    /**
     * 获取choies订单数据
     *
     */
    public function getChoiesOrder()
    {
        $date = date('Y-m-d');
        $url = 'http://www.choies.com/api/order_date_list?date=' . $date;
        $queryServer = curl_init();
        curl_setopt($queryServer, CURLOPT_URL, $url);
        curl_setopt($queryServer, CURLOPT_HEADER, 0);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($queryServer, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($queryServer, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($queryServer, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($queryServer);
        curl_close($queryServer);
        $channelOrders = json_decode($data, true);
        $orders = [];
        foreach ($channelOrders as $key => $channelOrder) {
            $name = substr($url, 11, 6);
            $channels = ChannelModel::where(['name' => $name])->get();
            foreach ($channels as $channel) {
                $orders[$key]['channel_id'] = $channel['id'];
                $accounts = AccountModel::where(['channel_id' => $orders[$key]['channel_id']])->get();
                foreach ($accounts as $account) {
                    $orders[$key]['channel_account_id'] = $account['id'];
                    $orders[$key]['customer_service'] = $account['customer_service_id'];
                    $orders[$key]['operator'] = $account['operator_id'];
                    $orders[$key]['affairer'] = null;
                }
            }
            $orders[$key]['ordernum'] = $channelOrder['ordernum'];
            $orders[$key]['channel_ordernum'] = $channelOrder['ordernum'];
            $orders[$key]['email'] = $channelOrder['email'];
            $orders[$key]['status'] = 'PAID';
            $orders[$key]['active'] = 'NORMAL';
            $orders[$key]['ip'] = $channelOrder['ip_address'];
            $orders[$key]['address_confirm'] = 1;
            $orders[$key]['remark'] = $channelOrder['remark'];
            if ($orders[$key]['remark'] != null && $orders[$key]['remark'] != '') {
                $orders[$key]['status'] = 'REVIEW';
            }
            $orders[$key]['affair_time'] = null;
            $orders[$key]['create_time'] = $channelOrder['date_purchased'];
            $orders[$key]['is_partial'] = 0;
            $orders[$key]['by_hand'] = 0;
            $orders[$key]['is_affair'] = 0;
            $orders[$key]['currency'] = $channelOrder['currency'];
            $orders[$key]['rate'] = $channelOrder['rate'];
            $orders[$key]['amount'] = $channelOrder['amount'];
            $orders[$key]['amount_product'] = $channelOrder['amount_products'];
            $orders[$key]['amount_coupon'] = $channelOrder['order_insurance'];
            $orders[$key]['amount_shipping'] = $channelOrder['amount_shipping'] + $orders[$key]['amount_coupon'];
            if (($orders[$key]['amount_shipping'] / $orders[$key]['rate']) < 10) {
                $orders[$key]['shipping'] = 'PACKET';
            } else {
                $orders[$key]['shipping'] = 'EXPRESS';
            }
            $orders[$key]['shipping_firstname'] = $channelOrder['shipping_firstname'];
            $orders[$key]['shipping_lastname'] = $channelOrder['shipping_lastname'];
            $orders[$key]['shipping_address'] = $channelOrder['shipping_address'];
            $orders[$key]['shipping_city'] = $channelOrder['shipping_city'];
            $orders[$key]['shipping_state'] = $channelOrder['shipping_state'];
            $orders[$key]['shipping_country'] = $channelOrder['shipping_country'];
            $orders[$key]['shipping_zipcode'] = $channelOrder['shipping_zip'];
            $orders[$key]['shipping_phone'] = $channelOrder['shipping_phone'];
            $orders[$key]['payment'] = $channelOrder['payment'];
            $orders[$key]['billing_firstname'] = $channelOrder['billing_firstname'];
            $orders[$key]['billing_lastname'] = $channelOrder['billing_lastname'];
            $orders[$key]['billing_address'] = $channelOrder['billing_address'];
            $orders[$key]['billing_city'] = $channelOrder['billing_city'];
            $orders[$key]['billing_state'] = $channelOrder['billing_state'];
            $orders[$key]['billing_country'] = $channelOrder['billing_country'];
            $orders[$key]['billing_zipcode'] = $channelOrder['billing_zip'];
            $orders[$key]['billing_phone'] = $channelOrder['billing_phone'];
            $orders[$key]['payment_date'] = $channelOrder['payment_date'];
            $orders[$key]['transaction_number'] = $channelOrder['trans_id'];
            $orders[$key]['cele_admin'] = $channelOrder['cele_admin'];
            $orders[$key]['priority'] = 0;
            $orders[$key]['package_times'] = 0;
            foreach ($channelOrder['orderitems'] as $itemKey => $channelOrderItem) {
                $orders[$key]['items'][$itemKey]['item_id'] = 0;
                $orders[$key]['items'][$itemKey]['quantity'] = $channelOrderItem['quantity'];
                $orders[$key]['items'][$itemKey]['price'] = $channelOrderItem['price'];
                $orders[$key]['items'][$itemKey]['is_active'] = 1;
                $orders[$key]['items'][$itemKey]['status'] = 'NEW';
                $orders[$key]['items'][$itemKey]['is_gift'] = $channelOrderItem['is_gift'];
                $arr = $channelOrder['orderitems'];
                $len = count($arr);
                for ($i = 0; $i < $len; $i++) {
                    $str = $arr[$i]['attributes'];
                    $array = explode(";", $str);
                    foreach ($array as $value) {
                        if ($value == '') {
                            break;
                        } else {
                            $arr[$i]['attributes'] = $value;
                            $orders[$key]['items'][$itemKey]['sku'] = $channelOrderItem['sku'] . "-" . substr($arr[$i]['attributes'],
                                    6);
                        }
                    }
                }
            }
            $obj = OrderModel::where(['ordernum' => $channelOrder['ordernum']])->get();
            if (!count($obj)) {
                $this->model->createOrder($orders[$key]);
            }
        }
    }

}