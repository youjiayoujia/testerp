<?php
/**
 * 采购条目控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseOrderConfirmModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseStaticsticsModel;
use App\Models\Purchase\PurchaseItemArrivalLogModel;
use App\Models\WarehouseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\Stock\InModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchasePostageModel;
use App\Models\Order\ItemModel as OrderItemModel;
use App\Models\Package\ItemModel as PackageItemModel;
use App\Models\PackageModel;
use App\Jobs\AssignStocks;
use Excel;
use Tool;
use DB;
use App\Jobs\Job;
use Mail;
use App\Models\StockModel;

class PurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder, PurchaseItemModel $purchaseItem, ItemModel $item,PurchaseOrderConfirmModel $purchaseOrderConfirm,PurchaseItemArrivalLogModel $arrival)
    {
        //$this->middleware('roleCheck');
        $this->model = $purchaseOrder;
        $this->arrival = $arrival;
        $this->item = $item;
        $this->purchaseOrderConfirm = $purchaseOrderConfirm;
        $this->purchaseItem = $purchaseItem;
        $this->mainIndex = route('purchaseOrder.index');
        $this->mainTitle = '采购单';
        $this->viewPath = 'purchase.purchaseOrder.';
    }


    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,
                $this->model->with('supplier', 'purchaseUser', 'warehouse', 'purchaseItem', 'purchasePostage',
                    'purchaseItem.item', 'purchaseItem.item.product', 'purchaseItem.productItem')),
            'mixedSearchFields' => $this->model->mixed_search,
        ];

        return view($this->viewPath . 'index', $response);
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
            'warehouses' => WarehouseModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->createPurchaseOrder(request()->all());

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }

    /**
     * 采购页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $yiwu = 0;
        $brr = array('2', '4', '5', '6');
        foreach ($model->purchaseItem as $p_item) {
            foreach ($p_item->productItem->product->logisticsLimit->toArray() as $key => $arr) {
                if (in_array($arr['pivot']['logistics_limits_id'], $brr)) {
                    $yiwu = 1;
                    break;
                }
            }
        }
        if ($yiwu) {
            $notIn = array('2', '4');
            $warehouse = WarehouseModel::whereNotIn('id', $notIn)->get();
        } else {
            $warehouse = WarehouseModel::all();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'purchaseItems' => PurchaseItemModel::where('purchase_order_id', $id)->get(),
            'purchasePostage' => PurchasePostageModel::where('purchase_order_id', $id)->get(),
            'purchaseSumPostage' => PurchasePostageModel::where('purchase_order_id', $id)->sum('postage'),
            'current' => count(PurchasePostageModel::where('purchase_order_id', $id)->get()->toArray()),
            'warehouses' => $warehouse,
            'hideUrl' => $hideUrl,
            'yiwu' => $yiwu,
        ];
        return view($this->viewPath . 'edit', $response);
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
            'purchaseItems' => PurchaseItemModel::where('purchase_order_id', $id)->get(),
            'purchaseItemsNum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('purchase_num'),
            'purchaseItemsArrivalNum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('arrival_num'),
            'storage_qty_sum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('storage_qty'),
            'postage' => PurchasePostageModel::where('purchase_order_id', $id)->sum('postage')
        ];
        $response['purchaseCost'] = 0;
        $response['storageCost'] = 0;
        foreach ($response['purchaseItems'] as $key => $v) {
            $response['purchaseCost'] += $v->purchase_num * $v->purchase_cost;
            $response['storageCost'] += $v->storage_qty * $v->purchase_cost;
        }
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 更新采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function update($id)
    {
        $model = $this->model->find($id);
        if ($model->status == 4) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已完成.'));
        }
        if ($model->status == 5) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已取消.'));
        }
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        $data = request()->all();
        if (isset($data['arr'])) {
            if (isset($data['post'])) {
                $post = "";
                if ($model->status < 4 && $model->status > 0) {
                    foreach ($data['post'] as $post_value) {
                        $post_array['purchase_order_id'] = $data['purchase_order_id'];
                        $post_array['post_coding'] = $post_value['post_coding'];
                        $post_array['postage'] = $post_value['postage'];
                        $post_array['user_id'] = request()->user()->id;
                        if (array_key_exists('id', $post_value)) {
                            PurchasePostageModel::where('id', $post_value['id'])->update($post_array);
                        } else {
                            PurchasePostageModel::create($post_array);
                        }


                    }
                }
            }

            foreach ($data['arr'] as $k => $v) {
                if ($v['id']) {
                    $purchaseItem = PurchaseItemModel::find($v['id']);
                    $v['lack_num'] = $v['purchase_num'];
                    $itemPurchasePrice = $purchaseItem->item->purchase_price;
                    $purchase_num = $purchaseItem->purchase_num;
                    foreach ($v as $key => $vo) {
                        $item[$key] = $vo;
                    }
                    $item['warehouse_id'] = $data['warehouse_id'];
                    /*if($v['active']>0){
                        $item['active_status']=1;
                    }*/
                    /*if($item['purchase_cost'] >0.6*$itemPurchasePrice && $item['purchase_cost'] <1.3*$itemPurchasePrice ){
                        $item['costExamineStatus']=2;
                        ItemModel::where('sku',$purchaseItem->sku)->update(['purchase_price'=>$item['purchase_cost']]); 
                    }else{
                        $item['costExamineStatus']=0;   
                    }*/
                    if ($purchaseItem->purchaseOrder->examineStatus == 1) {
                        if ($purchaseItem->status < 4) {
                            $data['examineStatus'] = 2;
                        }
                    }
                    $item['start_buying_time'] = date('Y-m-d h:i:s', time());
                    if ($purchaseItem->status != 4) {
                        $purchaseItem->update($item);
                    }
                    $data['total_purchase_cost'] += $v['purchase_cost'] * $purchase_num;
                    unset($item);
                    $purchaseItem->productItem->createOnePurchaseNeedData();
                }
            }
        }
        $num = PurchaseItemModel::where('purchase_order_id', $id)->where('costExamineStatus', '<>', 2)->count();
        if ($num == 0) {
            $data['costExamineStatus'] = 2;
        }
        $data['start_buying_time'] = date('Y-m-d h:i:s', time());
        if ($model->status != 4) {
            $model->update($data);
        }

        $to = json_encode($model);
        $this->eventLog($userName->name, '采购单信息更新,id=' . $model->id, $to, $from);
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '采购单ID' . $id . '编辑成功.'));
    }

    /**
     * 审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function changeExamineStatus($id, $examineStatus)
    {
        $url = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $userName = UserModel::find(request()->user()->id);
        $remark = '';
        $from = json_encode($model);
        $data['examineStatus'] = $examineStatus;
        if ($examineStatus == 1) {
            $remark = '审核通过';
            $data['status'] = 1;
        }
        if ($examineStatus == 3) {
            $remark = '审核不通过';
            $data['status'] = 5;
        }
        if ($examineStatus == 0) {
            $remark = '撤销审核';
            $data['status'] = 0;
        }
        $model->update($data);
        //更新采购需求数据
        $temp_arr = [];
        foreach ($model->purchaseItem as $purchaseitemModel) {
            if ($examineStatus == 1) {
                $purchaseitemModel->update(['status' => 1]);
                $purchaseitemModel->productItem->createOnePurchaseNeedData();
            }
            if ($examineStatus == 3) {
                $purchaseitemModel->update(['status' => 5]);
                $purchaseitemModel->productItem->createOnePurchaseNeedData();
            }
            if ($examineStatus == 0) {
                $purchaseitemModel->update(['status' => 0]);
                $purchaseitemModel->productItem->createOnePurchaseNeedData();
            }
        }

        $to = json_encode($model);
        $this->eventLog($userName->name, '采购单审核,id=' . $model->id, $to, $from);
        return redirect($url)->with('alert', $this->alert('success', '采购单ID' . $id . $remark.'.'));
    }

    /**
     * 导出3天未到货采购单
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function excelOrderOut($num)
    {
        if ($num == 0) {
            $this->model->allPurchaseExcelOut();
        } elseif ($num == 3) {
            $this->model->noArrivalOut();
        }
    }

    /**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelOrder($id)
    {
        $num = purchaseItemModel::where('purchase_order_id', $id)->where('status', '>', 1)->count();
        if ($num > 0) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此采购单不能取消.'));
        }
        $purchaseItem = PurchaseItemModel::where('purchase_order_id', $id)->update(['status' => 5]);
        $model = $this->model->find($id);
        $model->update(['status' => 5, 'examineStatus' => 3]);
        //更新采购需求
        $temp_arr = [];
        foreach ($model->purchaseItem as $p_item) {
            $p_item->productItem->createOnePurchaseNeedData();
        }

        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '改采购单已退回'));
    }

    /**
     * ajax 新增物流单号物流费
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxPostAdd()
    {
        if (request()->input('current')) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath . 'add', $response);
        }
        return null;
    }

    /**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */

    public function addItem($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'purchase_order_id' => $id,
        ];
        return view($this->viewPath . 'addItem', $response);
    }

    /**
     * 创建采购条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function createItem($id)
    {
        $data = request()->all();
        $this->validate(request(), $this->purchaseItem->rules('create'));
        $item = ItemModel::where('sku', $data['sku'])->where('is_available', 1)->where('status', "selling")->first();
        if (!$item) {
            return redirect(route('purchaseOrder.edit', $id))->with('alert', $this->alert('danger', 'SKU不存在或不在售.'));
        }
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        $num = PurchaseItemModel::where('active_status', '>', 0)->where('sku', $data['sku'])->count();
        $Inum = ItemModel::where('sku', $data['sku'])->where('is_available', '<>', 1)->where('status',
            "selling")->count();
        if ($num > 0 || $Inum > 0) {
            return redirect(route('purchaseOrder.edit', $id))->with('alert',
                $this->alert('danger', $this->mainTitle . '此Item存在异常不能添加进此采购单.'));
        }
        if ($model->close_status == 1) {
            return redirect(route('purchaseOrder.edit', $id))->with('alert',
                $this->alert('danger', $this->mainTitle . '该采购单已结算，不能新增Item.'));
        }
        $data['item_id'] = $item->id;
        $data['lack_num'] = $data['purchase_num'];
        $data['warehouse_id'] = $model->warehouse_id ? $model->warehouse_id : 0;
        $data['supplier_id'] = $item->supplier_id ? $item->supplier_id : 0;
        $data['purchase_order_id'] = $id;
        PurchaseItemModel::create($data);
        if ($model->examineStatus > 0) {
            $model->update(['examineStatus' => 2]);
        }

        //更新采购需求
        foreach ($this->model->find($id)->purchaseItem as $p_item) {
            $p_item->productItem->createOnePurchaseNeedData();
        }
        
        $to = json_encode($model);
        $this->eventLog($userName->name, '创建采购条目,id=' . $model->id, $to, $from);
        return redirect(route('purchaseOrder.edit', $id));
    }

    /**
     * 添加报等时间页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function updateWaitTime($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'purchase_item_id' => $id,
        ];
        return view($this->viewPath . 'waitTime', $response);
    }

    /**
     * 添加报等时间
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function updateItemWaitTime($id)
    {
        $data = request()->all();
        $purchaseItem = purchaseItemModel::find($id);
        purchaseItemModel::where('id', $id)->update([
            'wait_time' => $data['wait_time'],
            'wait_remark' => $data['wait_remark']
        ]);
        return redirect(route('purchaseOrder.edit', $purchaseItem->purchase_order_id));
    }

    /**
     * 打印
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function printOrder($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'id' => $id,
            'purchaseItems' => PurchaseItemModel::where('purchase_order_id', $id)->get(),
            'purchase_num_sum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('purchase_num'),
            'purchase_cost_sum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('purchase_cost'),
            'storage_qty_sum' => PurchaseItemModel::where('purchase_order_id', $id)->sum('storage_qty'),
            'postage_sum' => PurchasePostageModel::where('purchase_order_id', $id)->sum('postage'),
        ];
        $purchaseAccount = '';
        foreach ($response['purchaseItems'] as $key => $v) {
            $purchaseAccount += $v->purchase_num * $v->purchase_cost;
        }
        $response['purchaseAccount'] = $purchaseAccount;
        return view($this->viewPath . 'printOrder', $response);
    }

    /**
     * 打印入库单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function printInWarehouseOrder($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'printInWarehouseOrder', $response);
    }

    /**
     * 修改打印状态
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function changePrintStatus()
    {
        $id = request()->input('id');
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        $model->update(['print_status' => 1]);
        $to = json_encode($model);
        $this->eventLog($userName->name, '修改打印状态,id=' . $model->id, $to, $from);
    }

    /**
     * 收货节面打印选择尺寸
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function printpo()
    {
        $id = request()->input('id');
        $p_item = PurchaseItemModel::find($id);
        $po_id = $p_item->purchaseOrder->id;
        $response['id'] = $id;
        $response['po_id'] = $po_id;
        $response['from'] = 'purchase';
        return view($this->viewPath . 'printpo', $response);
    }

    /**
     * 收货节面打印
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function showpo()
    {
        $id = request()->input('pp_id');
        $size = request()->input('labelSize');
        $po_id = request()->input('po_id');
        $from = request()->input('from');
        if ($from == 'purchase') {
            $response['id'] = $id;
            $response['model'] = PurchaseItemModel::where('id', $id)->get()->first();
            $response['size'] = $size;
            $response['po_id'] = $po_id;
            return view($this->viewPath . 'showpo', $response);
        }
        if ($from == 'sku') {
            $response['id'] = $id;
            $response['model'] = ItemModel::find($id);
            $response['size'] = $size;
            $response['po_id'] = $po_id;
            return view('item.skushowpo', $response);
        }

    }

    /**
     * 收货节面打印采购条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function payOrder($id)
    {
        $model = $this->model->find($id);
        $model->update(['close_status' => 1]);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . "已付款"));
    }


    public function write_off($id)
    {
        $url = $_SERVER['HTTP_REFERER'];
        $off = request()->input("off");
        if ($off == 1) {
            $this->model->find($id)->update(['write_off' => $off + 1, 'status' => 4]);
            $remark = "核销成功";
        } else {
            $this->model->find($id)->update(['write_off' => $off + 1]);
            $remark = "待核销成功";
        }
        //更新采购需求
        foreach ($this->model->find($id)->purchaseItem as $p_item) {
            if ($off == 1) {
                $p_item->productItem->createOnePurchaseNeedData();
            }
        }
        
        return redirect($url)->with('alert', $this->alert('success', $this->mainTitle . $id . $remark));
    }

    public function addPost($id)
    {
        $data = request()->all();
        $model = $this->model->find($id);
        foreach ($data['post'] as $v) {
            PurchasePostageModel::create([
                'purchase_order_id' => $id,
                'post_coding' => $v['post_coding'],
                'postage' => $v['postage']
            ]);
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '成功添加运单号'));
    }

    public function recieve()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title'] = '采购收货与入库';
        return view($this->viewPath . 'recieve', $response);
    }

    public function ajaxRecieve()
    {
        $id = request()->input('id');
        $purchase_order = $this->model->find($id);

        if (!$purchase_order) {
            return redirect(route('recieve'))->with('alert', $this->alert('danger', '采购单号不存在.'));
        }
        $total_price = 0;
        foreach ($purchase_order->purchaseItem as $key => $pitem) {
            $total_price += $pitem->purchase_num * $pitem->purchase_cost;
        }
        $response = [
            'total_price' => $total_price,
            'purchase_order' => $purchase_order,
            'id' => $id,
        ];
        return view($this->viewPath . 'recieveList', $response);
    }

    public function trackingNoSearch()
    {
        $data = request()->all();
        if (count($data) == 0) {
            $result = PurchasePostageModel::all();
        } else {
            $result = new PurchasePostageModel();
            if ($data['po_id'] != '') {
                $result = $result->where("purchase_order_id", $data["po_id"]);
            }
            if ($data['status'] != '2') {
                $data['status'] ? $result = $result->where("purchase_order_id", '!=',
                    '') : $result = $result->where("purchase_order_id", 0);
            }
            if ($data['trackingNo'] != '') {
                $result = $result->where("post_coding", $data["trackingNo"]);
            }
            if ($data['date_from'] != '') {
                $result = $result->where("created_at", '>=', str_replace('/', '-', $data["date_from"]));
            }
            if ($data['date_to'] != '') {
                $result = $result->where("created_at", '<=', str_replace('/', '-', $data["date_to"]));
            }
            $result = $result->get();
        }

        $response = [
            'result' => $result,
        ];

        return view($this->viewPath . 'scanList', $response);
    }

    public function updateArriveNum()
    {
        $data = request()->input("data");
        $p_id = request()->input("p_id");
        $is_second = 0;
        if ($data != '') {
            $data = substr($data, 0, strlen($data) - 1);
            $arr = explode(',', $data);
            $count_num = PurchaseItemArrivalLogModel::where('purchase_order_id',$p_id)->count();
            if($count_num)$is_second = 1;
            foreach ($arr as $value) {
                $update_data = explode(':', $value);
                $purchase_item = PurchaseItemModel::find($update_data[0]);

                if ($purchase_item->arrival_num != $purchase_item->purchase_num) {
                    $filed['purchase_item_id'] = $purchase_item['id'];
                    $filed['sku'] = $purchase_item['sku'];
                    $filed['arrival_num'] = $purchase_item['arrival_num'] + $update_data[1] > $purchase_item['purchase_num'] ? $purchase_item['purchase_num'] : $purchase_item['arrival_num'] + $update_data[1];
                    $filed['lack_num'] = $purchase_item['purchase_num'] - $filed['arrival_num'] < 0 ? 0 : $purchase_item['purchase_num'] - $filed['arrival_num'];
                    $filed['arrival_time'] = date('Y-m-d H:i:s', time());
                    $filed['status'] = 2;
                    $purchase_item->update($filed);
                    $filed['purchase_order_id'] = $p_id;
                    $filed['user_id'] = request()->user()->id;
                    $filed['arrival_num'] = $update_data[1];
                    $filed['is_second'] = $is_second;
                    PurchaseItemArrivalLogModel::create($filed);
                }
                $purchase_item->purchaseOrder->update(['status' => 2]);
            }
        } else {
            $purchaseOrderModel = $this->model->find($p_id);
            $count_num = PurchaseItemArrivalLogModel::where('purchase_order_id',$p_id)->count();
            if($count_num)$is_second = 1;
            foreach ($purchaseOrderModel->purchaseItem as $p_item) {
                if ($p_item->purchase_num != $p_item->arrival_num) {
                    $arrival_num = $p_item->lack_num;
                    $p_item->update(['arrival_num' => $p_item->purchase_num, 'lack_num' => 0]);
                    $filed['purchase_item_id'] = $p_item->id;
                    $filed['sku'] = $p_item->sku;
                    $filed['status'] = 2;
                    $filed['purchase_order_id'] = $p_id;
                    $filed['user_id'] = request()->user()->id;
                    $filed['arrival_num'] = $arrival_num;
                    $filed['is_second'] = $is_second;
                    PurchaseItemArrivalLogModel::create($filed);
                }
            }
        }

        //更新采购需求
        foreach ($this->model->find($p_id)->purchaseItem as $p_item) {
            $p_item->productItem->createOnePurchaseNeedData();
        }

        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title'] = '采购收货';
        return view($this->viewPath . 'recieve', $response);
    }

    public function inWarehouse()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title'] = '采购入库';
        return view($this->viewPath . 'inWarehouse', $response);
    }

    public function ajaxInWarehouse()
    {
        $id = request()->input('id');

        $purchase_order = $this->model->find($id);
        if (!$purchase_order) {
            return redirect(route('recieve'))->with('alert', $this->alert('danger', '采购单号不存在.'));
        }
        /*foreach($purchase_order->purchaseItem as $purchase_item){
            if(!$purchase_item->productItem->warehousePosition){
                return redirect(route('recieve'))->with('alert', $this->alert('danger',$purchase_item->sku.'库位不存在，请先添加库位.'));
            }
            if(!$purchase_item->productItem->warehousePosition->name){
                return redirect(route('recieve'))->with('alert', $this->alert('danger',$purchase_item->sku.'库位不存在，请先添加库位.'));
            }
        }*/
        $response = [
            'purchase_order' => $purchase_order,
            'id' => $id,
        ];
        return view($this->viewPath . 'inWarehouseList', $response);
    }

    /**
     * 入库
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function updateArriveLog()
    {
        $data = request()->input("data");
        $p_id = request()->input("p_id");
        $data = substr($data, 0, strlen($data) - 1);
        $arr = explode(',', $data);
        $global = 1;
        if ($data) {
            foreach ($arr as $value) {
                $update_data = explode(':', $value);
                $arrivel_log = PurchaseItemArrivalLogModel::find($update_data[0]);
                $purchase_item = $arrivel_log->purchaseItem;
                $warehousePosition = StockModel::where('warehouse_id',
                    $purchase_item->purchaseOrder->warehouse_id)->where('item_id',
                    $purchase_item->item_id)->get()->first();

                if (!$warehousePosition) {
                    $purchase_item->update(['status' => '6']);
                    $global = 0;
                } else {
                    $filed['good_num'] = $update_data[1] > $purchase_item->arrival_num ? $purchase_item->arrival_num : $update_data[1];
                    $filed['bad_num'] = $filed['good_num'];
                    $filed['quality_time'] = date('Y-m-d H:i:s', time());

                    $arrivel_log->update($filed);
                    //purchaseitem
                    $datas['status'] = 3;
                    $datas['storage_qty'] = $purchase_item->storage_qty + $filed['good_num'];
                    $datas['unqualified_qty'] = $purchase_item->unqualified_qty + $filed['bad_num'];
                    if ($datas['storage_qty'] >= $purchase_item->purchase_num) {
                        $datas['status'] = 4;
                    }

                    $purchase_item->update($datas);
                    $purchase_item->item->in($warehousePosition->warehouse_position_id, $filed['good_num'],
                        $filed['good_num'] * $purchase_item->purchase_cost, 'PURCHASE',
                        $purchase_item->purchaseOrder->id);

                }
                //need包裹分配库存
                $packageItem = PackageItemModel::where('item_id', $purchase_item->item_id)->get();
                if (count($packageItem) > 0) {
                    foreach ($packageItem as $_packageItem) {
                        if ($_packageItem->package->status == 'NEED' && $_packageItem->package->queue_name != 'assignStocks') {
                            $_packageItem->package->update(['queue_name' => 'assignStocks']);
                            $job = new AssignStocks($_packageItem->package);
                            $job = $job->onQueue('assignStocks');
                            $this->dispatch($job);
                        }
                    }
                }
            }
        }
        if ($global) {
            $p_status = 4;
            $purchasrOrder = $this->model->find($p_id);
            foreach ($purchasrOrder->purchaseItem as $p_item) {
                if ($p_item->status != 4) {
                    $p_status = 3;
                }
            }
            $purchasrOrder->update(['status' => $p_status]);
        }

        //更新采购需求
        foreach ($this->model->find($p_id)->purchaseItem as $p_item) {
            $p_item->productItem->createOnePurchaseNeedData();
        }

        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title'] = '采购收货';
        return view($this->viewPath . 'recieve', $response);
    }

    /**
     * 新品待入库界面入库
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function newProductupdateArriveLog()
    {
        $global = 1;
        $data = request()->all();
        $colorinfo = 'success';
        $tip = "入库成功";
        $arrivel_log = PurchaseItemArrivalLogModel::where('purchase_item_id',
            $data['purchase_item_id'])->get()->first();
        $purchase_item = $arrivel_log->purchaseItem;

        $warehousePosition = StockModel::where('warehouse_id',
            $purchase_item->purchaseOrder->warehouse_id)->where('item_id', $purchase_item->item_id)->get()->first();
        $warehouse_po_id = PositionModel::where('name', $data['position'])->where('warehouse_id',
            $purchase_item->purchaseOrder->warehouse_id)->get()->first();
        //echo '<pre>';print_r(count($warehouse_po_id));exit;
        if (count($warehouse_po_id) == 0) {
            $tip = "库位不存在,请重新填写库位入库";
            $colorinfo = 'danger';
            $purchase_item->update(['status' => '6']);
            $global = 0;
        } else {
            $filed['good_num'] = $data['num'];
            $filed['quality_time'] = date('Y-m-d H:i:s', time());

            $arrivel_log->update($filed);
            //purchaseitem
            $datas['status'] = 3;
            $datas['storage_qty'] = $purchase_item->storage_qty + $filed['good_num'];
            //$datas['unqualified_qty'] = $purchase_item->unqualified_qty+$filed['bad_num'];
            if ($datas['storage_qty'] >= $purchase_item->purchase_num) {
                $datas['status'] = 4;
            }
            //

            $purchase_item->update($datas);
            $purchase_item->item->in($warehouse_po_id->id, $filed['good_num'],
                $filed['good_num'] * $purchase_item->purchase_cost, 'PURCHASE', $purchase_item->purchaseOrder->id);

        }
        //need包裹分配库存
        $packageItem = PackageItemModel::where('item_id', $purchase_item->item_id)->get();
        if (count($packageItem) > 0) {
            foreach ($packageItem as $_packageItem) {
                if ($_packageItem->package->status == 'NEED' && $_packageItem->package->queue_name != 'assignStocks') {
                    $_packageItem->package->update(['queue_name' => 'assignStocks']);
                    $job = new AssignStocks($_packageItem->package);
                    $job = $job->onQueue('assignStocks');
                    $this->dispatch($job);
                }
            }
        }

        if ($global) {
            $p_status = 4;
            $purchasrOrder = $this->model->find($data['purchase_order_id']);
            foreach ($purchasrOrder->purchaseItem as $p_item) {
                if ($p_item->status != 4) {
                    $p_status = 3;
                }
            }
            $purchasrOrder->update(['status' => $p_status]);
        }

        //更新采购需求
        foreach ($this->model->find($data['purchase_order_id'])->purchaseItem as $p_item) {
            $p_item->productItem->createOnePurchaseNeedData();
        }

        return redirect(route('purchaseItemIndex'))->with('alert', $this->alert($colorinfo, $tip));
    }

    /**
     * ajax请求  sku
     *
     * @param none
     * @return obj
     *
     */
    public function purchaseAjaxSku()
    {
        if (request()->ajax()) {
            $sku = trim(request()->input('sku'));
            $supplier_id = request()->input('supplier_id') ? request()->input('supplier_id') : '';
            /*if($supplier_id){
                $skus = ItemModel::where('sku', 'like', '%'.$sku.'%')->where('supplier_id',$supplier_id)->get();
            }else{
                $skus = ItemModel::where('sku', 'like', '%'.$sku.'%')->get();
            }*/
            $skus = ItemModel::where('sku', 'like', '%' . $sku . '%')->where('supplier_id', $supplier_id)->get();
            $total = $skus->count();
            $arr = [];
            foreach ($skus as $key => $sku) {
                $arr[$key]['id'] = $sku->sku;
                $arr[$key]['text'] = $sku->sku;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode('false');
            }
        }

        return json_encode('false');
    }

    /**
     * 批量修改采购单状态
     *
     * @param none
     * @return 1
     *
     */
    public function purchaseExmaine()
    {
        $type = request()->input('type');
        $purchase_ids = request()->input("purchase_ids");
        $arr = explode(',', $purchase_ids);
        $itemModel = new ItemModel();
        switch ($type) {
            case 'examineStatus':
                foreach ($arr as $id) {
                    $purchaseOrder = $this->model->find($id);
                    $purchaseOrder->update(['examineStatus' => 1, 'status' => 1]);
                    foreach ($purchaseOrder->purchaseItem as $purchaseitemModel) {
                        $purchaseitemModel->update(['status' => 1]);
                        $purchaseitemModel->productItem->createOnePurchaseNeedData();
                    }
                }
                break;

            case 'write_off':
                foreach ($arr as $id) {
                    $purchaseOrder = $this->model->find($id);
                    $purchaseOrder->update(['write_off' => 2, 'status' => 4]);
                    foreach ($purchaseOrder->purchaseItem as $purchaseitemModel) {
                        $purchaseitemModel->update(['status' => 5]);
                        $purchaseitemModel->productItem->createOnePurchaseNeedData();
                    }
                }
                break;
            case 'close_status':
                foreach ($arr as $id) {
                    $this->model->find($id)->update(['close_status' => 1]);
                    foreach ($this->model->find($id)->purchaseItem as $purchaseitemModel) {
                        //$purchaseitemModel->update(['status'=>4]);
                        $purchaseitemModel->productItem->createOnePurchaseNeedData();
                    }
                }
                break;
        }

        return 1;
    }

    /**
     * 批量核销和删除核销
     *
     * @param none
     * @return 1
     *
     */
    public function batchConfirm()
    {
        $type = request()->input('type');
        $purchase_ids = request()->input("purchase_ids");
        $arr = explode(',', $purchase_ids);
        
        $itemModel = new ItemModel();
        switch ($type) {
            case 'batchConfirm':
                foreach ($arr as $id) {
                    $purchaseOrderConfirmModel = $this->purchaseOrderConfirm->find($id);
                    $purchaseOrderConfirmModel->update(['status' => 2]);
                    $purchaseOrderConfirmModel->purchaseOrder->update(['write_off' => 2, 'status' => 4]);
                    foreach ($purchaseOrderConfirmModel->purchaseOrder->purchaseItem as $purchaseitemModel) {
                        $purchaseitemModel->update(['status' => 5]);
                        $purchaseitemModel->productItem->createOnePurchaseNeedData();
                    }
                }
                break;

            case 'batchDelete':
                foreach ($arr as $id) {
                    $purchaseOrderConfirmModel = $this->purchaseOrderConfirm->find($id);
                    $purchaseOrderConfirmModel->update(['status' => 3]);
                    foreach ($purchaseOrderConfirmModel->purchaseOrder->purchaseItem as $purchaseitemModel) {
                        $purchaseitemModel->update(['write_off' => 0]);
                        $purchaseitemModel->productItem->createOnePurchaseNeedData();
                    }
                }
                break;
        }

        return 1;
    }

    /**
     * 采购单提示
     *
     * @param none
     * @return obj
     *
     */
    public function view()
    {
        $purchaseOrder_id = request()->input("purchaseOrder_id");
        $purchaseOrderModel = $this->model->find($purchaseOrder_id);
        $total_price = 0;
        $data = [];
        foreach ($purchaseOrderModel->purchaseItem as $key => $purchaseItemModel) {
            $itemModel = ItemModel::find($purchaseItemModel->item_id);
            //实时计算建议采购量
            $zaitu_num = 0;//在途
            if ($purchaseItemModel->status >= 0 && $purchaseItemModel->status < 4) {
                if ($purchaseItemModel->purchaseOrder) {
                    if (!$purchaseItemModel->purchaseOrder->write_off) {
                        if ($purchaseItemModel->purchaseOrder->status >= 0 && $purchaseItemModel->purchaseOrder->status < 4) {
                            $zaitu_num += $purchaseItemModel->purchase_num - $purchaseItemModel->storage_qty;
                        }
                    }
                }
            }

            //缺货
            $data['need_total_num'] = DB::select('select sum(order_items.quantity) as num from orders,order_items,purchases where orders.status= "NEED" and 
                orders.id = order_items.order_id and orders.deleted_at is null and purchases.item_id = order_items.item_id and order_items.item_id ="' . $purchaseItemModel->item_id . '" ')[0]->num;

            $data['need_total_num'] = $data['need_total_num'] ? $data['need_total_num'] : 0;
            //虚库存
            $xu_kucun = $itemModel->available_quantity - $data['need_total_num'];
            //7天销量
            $sevenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-7 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $purchaseItemModel->item_id)
                ->sum('order_items.quantity');
            if ($sevenDaySellNum == null) {
                $sevenDaySellNum = 0;
            }

            //14天销量
            $fourteenDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-14 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $purchaseItemModel->item_id)
                ->sum('order_items.quantity');
            if ($fourteenDaySellNum == null) {
                $fourteenDaySellNum = 0;
            }

            //30天销量
            $thirtyDaySellNum = OrderItemModel::leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.status', ['PAID', 'PREPARED', 'NEED', 'PACKED', 'SHIPPED', 'COMPLETE'])
                ->where('orders.created_at', '>', date('Y-m-d H:i:s', strtotime('-30 day')))
                ->where('order_items.quantity', '<', 5)
                ->where('order_items.item_id', $purchaseItemModel->item_id)
                ->sum('order_items.quantity');
            if ($thirtyDaySellNum == null) {
                $thirtyDaySellNum = 0;
            }

            //计算趋势系数 $coefficient系数 $coefficient_status系数趋势
            if ($sevenDaySellNum == 0 || $fourteenDaySellNum == 0) {
                $coefficient_status = 3;
                $coefficient = 1;
            } else {
                if (($sevenDaySellNum / 7) / ($fourteenDaySellNum / 14 * 1.1) >= 1) {
                    $coefficient = 1.3;
                    $coefficient_status = 1;
                } elseif (($fourteenDaySellNum / 14 * 0.9) / ($sevenDaySellNum / 7) >= 1) {
                    $coefficient = 0.6;
                    $coefficient_status = 2;
                } else {
                    $coefficient = 1;
                    $coefficient_status = 4;
                }
            }
            //预交期
            $delivery = $itemModel->supplier ? $itemModel->supplier->purchase_time : 7;
            //采购建议数量
            if ($itemModel->purchase_price > 200 && $fourteenDaySellNum < 3 || $itemModel->status == 4) {
                $needPurchaseNum = 0 - $xu_kucun - $zaitu_num;
            } else {
                if ($itemModel->purchase_price > 3 && $itemModel->purchase_price <= 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (7 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($itemModel->purchase_price <= 3) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                } elseif ($itemModel->purchase_price > 40) {
                    $needPurchaseNum = ($fourteenDaySellNum / 14) * (12 + $delivery) * $coefficient - $xu_kucun - $zaitu_num;
                }
            }

            $need_purchase_num = ceil($needPurchaseNum);

            //($needPurchaseNum-$purchaseItemModel->purchase_num)<0?$data[$purchaseItemModel->id]['quantity']='采购量大于建议采购值('.($needPurchaseNum-$purchaseItemModel->purchase_num).')':$data[$purchaseItemModel->id]['quantity']='';
            $data[$purchaseItemModel->id]['quantity'] = '实时建议采购值' . $need_purchase_num;
            //计算总价
            $total_price += $purchaseItemModel->purchase_cost * $purchaseItemModel->purchase_num;
            //计算采购价和系统价格是否一致
            $purchaseItemModel->purchase_cost == $purchaseItemModel->item->purchase_price ? $data[$purchaseItemModel->id]['price'] = '' : $data[$purchaseItemModel->id]['price'] = '采购价和系统价格不一致;';

        }

        $total_price > 2000 ? $data[0]['total_price'] = '采购单总金额大于2000' : $data[0]['total_price'] = '';

        return $data;
    }

    /**
     * 采购统计数据
     *
     * @param none
     * @return obj
     *
     */
    public function purchaseStaticstics()
    {
        $model = new PurchaseStaticsticsModel();
        $this->mainIndex = route('purchaseStaticstics');
        $this->mainTitle = '采购数据统计';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($model),
            'mixedSearchFields' => $model->mixed_search,
        ];

        return view($this->viewPath . 'staticsticsIndex', $response);
    }

    /**
     * 到货记录报表
     *
     * @param none
     * @return obj
     *
     */
    public function recieveReport()
    {
        $model = new PurchaseItemArrivalLogModel();
        $this->mainIndex = route('purchaseOrder.recieveReport');
        $this->mainTitle = '到货记录';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($model),
            'mixedSearchFields' => $model->mixed_search,
        ];

        return view($this->viewPath . 'recieveReport', $response);
    }

    /**
     * 缺货报表
     *
     * @param none
     * @return obj
     *
     */
    public function outOfStock()
    {
        $user_id = request()->input('user_id');
        $sku = request()->input('sku');
        $status = request()->input('status');
        $date_from = request()->input('date_from');
        $date_to = request()->input('date_to');

        $item_id_arr = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
            ->leftjoin('items', 'items.id', '=', 'package_items.item_id')
            ->where('packages.status', 'NEED');

        if ($user_id) {
            $item_id_arr = $item_id_arr->where('items.purchase_adminer', $user_id);
        }
        if ($status) {
            $item_id_arr = $item_id_arr->where('items.status', $status);
        }
        if ($date_from) {
            $item_id_arr = $item_id_arr->where('items.created_at', '>', $date_from);
        }
        if ($date_to) {
            $item_id_arr = $item_id_arr->where('items.created_at', '<', $date_to);
        }
        if ($sku) {
            $sku_arr = explode(',', $sku);
            $item_id_arr = $item_id_arr->whereIn('items.sku', $sku_arr);
        }

        $this->mainIndex = route('purchase.outOfStock');
        $this->mainTitle = '缺货报告';

        $item_id_arr = $item_id_arr->distinct()->get(['package_items.item_id'])->toArray();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->item, $this->item->whereIn('id', $item_id_arr)),
            'warehouses' => WarehouseModel::all(),
            'users' => UserModel::all(),
            'sku' => $sku,
            'status' => $status,
            'user_name' => UserModel::find($user_id) ? UserModel::find($user_id)->name : '',
            'date_from' => $date_from,
            'date_to' => $date_to,
            'mixedSearchFields' => $this->model->mixed_search,
        ];

        return view($this->viewPath . 'outOfStockIndex', $response);

    }

    /**
     * 导出缺货报表
     *
     * @param none
     * @return obj
     *
     */
    public function exportOutOfStockCsv()
    {
        $sku = request()->input('sku');
        $status = request()->input('status');
        $date_from = request()->input('date_from');
        $date_to = request()->input('date_to');
        $user_id = request()->input('user_id');
        /*$item_id_arr = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
            ->where('packages.status','NEED')
            ->distinct()
            ->get(['package_items.item_id'])
            ->toArray();*/
        $item_id_arr = PackageItemModel::leftjoin('packages', 'packages.id', '=', 'package_items.package_id')
            ->leftjoin('items', 'items.id', '=', 'package_items.item_id')
            ->where('packages.status', 'NEED');

        if ($user_id) {
            $item_id_arr = $item_id_arr->where('items.purchase_adminer', $user_id);
        }
        if ($status) {
            $item_id_arr = $item_id_arr->where('items.status', $status);
        }
        if ($date_from) {
            $item_id_arr = $item_id_arr->where('items.created_at', '>', $date_from);
        }
        if ($date_to) {
            $item_id_arr = $item_id_arr->where('items.created_at', '<', $date_to);
        }
        if ($sku) {
            $sku_arr = explode(',', $sku);
            $item_id_arr = $item_id_arr->whereIn('items.sku', $sku_arr);
        }

        $item_id_arr = $item_id_arr->distinct()->get(['package_items.item_id'])->toArray();

        $rows = [];
        $warehouses = WarehouseModel::all();
        
        foreach ($item_id_arr as $item_id) {
            $model = $this->item->find($item_id['item_id']);
            foreach ($warehouses as $warehouse) {
                $rows[] = [
                    'sku号' => $model->sku,
                    '所属仓库' => $warehouse->name,
                    '物品名称' => $model->c_name,
                    '在途' => $model->transit_quantity[$warehouse->id]['normal'],
                    '特采在途' => $model->transit_quantity[$warehouse->id]['special'],
                    '欠货数量' => $model->out_of_stock,
                    '虚库存' => $model->warehouse_quantity[$warehouse->id]['available_quantity'],
                    '实库存' => $model->warehouse_quantity[$warehouse->id]['all_quantity'],
                    '最近采购时间' => $model->recently_purchase_time,
                    '缺货时间' => $model->out_of_stock_time,
                ];
            }
        }
        $name = 'export_exception';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * 导出采购单
     *
     * @param $id
     * @return csv
     */
    public function purchaseOrdersOut()
    {
        $purchaseOrder = $this->autoList($this->model,$this->model->with('supplier', 'purchaseUser', 'warehouse', 'purchaseItem', 
            'purchasePostage','purchaseItem.item', 'purchaseItem.item.product', 'purchaseItem.productItem'),$fields = ['*'], $pageSize = 10000);
        $rows = [];
        foreach ($purchaseOrder as $model) {
            $total_num = 0;
            foreach ($model->purchaseItem as $purchase_item) {
                $total_num += $purchase_item->purchase_num;
            }
            $rows[] = [
                '采购单号' => $model->id,
                '外部单号' => $model->post_coding,
                '付款方式' => config('purchase.purchaseOrder.pay_type')[$model->pay_type],
                '物流方式' => $model->carriage_type > -1 ? config('purchase.purchaseOrder.carriage_type')[$model->carriage_type] : '',
                '采购负责人' => $model->purchaseUser->name,
                '入库仓库' => $model->warehouse->name,
                '商品总金额' => $model->total_purchase_cost,
                '总数量' => $total_num,
                '运费' => $model->total_postage,
                '订单总金额' => $model->total_purchase_cost,
                '供应商编号' => $model->supplier_id,
                '下单时间' => $model->created_at,
            ];
        }
        $name = 'export_exception';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * 导出到货记录
     *
     * @param $id
     * @return csv
     */
    public function purchaseArrivalLogOut()
    {
        $purchaseItemArrivalLogModel = $this->autoList($this->arrival, null, $fields = ['*'], $pageSize = 10000);
        $rows = [];
        
        foreach ($purchaseItemArrivalLogModel as $recieve) {
            $rows[] = [
                '采购单号' => $recieve->purchase_order_id,
                'sku' => $recieve->purchaseItem->productItem->sku,
                '库位' => $recieve->purchaseItem->warehouse_position_name,
                '到货数量' => $recieve->arrival_num,
                '入库数量' => $recieve->good_num,
                '名称' => $recieve->purchaseItem->productItem->c_name,
                '到货时间' => $recieve->created_at,
                '收货人' => $recieve->user?$recieve->user->name:'',
            ];
        }
        $name = 'export_exception';
        Excel::create($name, function ($excel) use ($rows) {
            $excel->sheet('', function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * 下单7天未到货
     *
     * @param none
     * @return obj
     *
     */
    public function sevenPurchaseSku()
    {
        $time = date('Y-m-d H:i:s', time() - 60 * 60 * 24 * 7);
        $purchaseOrder = $this->model->where('created_at', '<', $time)->whereIn('status', ['1', '2', '3'])->get();

        //邮件模板数据
        $data = ['email' => '549991570@qq.com', 'name' => 'youjiatest@163.com', 'purchaseOrder' => $purchaseOrder];
        //发送邮件
        Mail::send('purchase.purchaseOrder.mailSevenPurchase', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('采购单7天未到货');
        });
    }

    /**
     * 未入库采购单邮件
     *
     * @param none
     * @return obj
     *
     */
    public function printButNotWarehouseIn()
    {
        $purchaseOrder = $this->model->where('print_num', '>', '0')->whereIn('status', ['1', '2', '3'])->get();
        //邮件模板数据
        $data = ['email' => '549991570@qq.com', 'name' => 'youjiatest@163.com', 'purchaseOrder' => $purchaseOrder];
        //发送邮件
        Mail::send('purchase.purchaseOrder.mailPrintButNotWarehouseIn', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('已打印入库单,未入库采购单明细');
        });
    }

    /**
     * 上传excel表格修改采购单付款状态付款
     *
     * @param none
     * @return obj
     *
     */
    public function excelPayOff()
    {
        $this->mainTitle = '采购单批量付款';
        $this->mainIndex = route('purchaseOrder.excelPayOff');
        $data = [];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $data,
            'success' =>0,
            'fail' =>0,
        ];

        return view($this->viewPath . 'payOffIndex', $response);
    }

    /**
     * 上传excel表格修改采购单付款状态付款
     *
     * @param none
     * @return obj
     *
     */
    public function excelPayOffExecute()
    {
        if(empty($_FILES['upload']['tmp_name'])) {
            return redirect(route('purchaseOrder.excelPayOff'))->with('alert', $this->alert('danger', '请上传表格!'));
        }
        $csv = Excel::load($_FILES['upload']['tmp_name'])->noHeading()->toArray();
        $result = [];
        $success = 0;
        $fail = 0;
        foreach ($csv as $key => $value) {
            if($key==0)continue;
            $purchaseOrderModel = $this->model->find($value['1']);

            if(count($purchaseOrderModel)){
                if($purchaseOrderModel->close_status=='1'){
                    $result[$key]['status'] = '2';
                    $result[$key]['id'] = $value['1'];
                    $fail++;
                }else{
                    $purchaseOrderModel->update(['close_status'=>'1']);
                    $result[$key]['status'] = '1';
                    $result[$key]['id'] = $value['1'];
                    $success++;
                }  
            }else{
                $result[$key]['status'] = '0';
                $result[$key]['id'] = $value['1'];
                $fail++;
            }
        }
        
      
        $this->mainIndex = route('purchaseOrder.excelPayOff');
        $this->mainTitle = '采购单批量付款';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data'  => $result,
            'success' =>$success,
            'fail' =>$fail,
        ];

        return view($this->viewPath . 'payOffIndex', $response);
    }

    /**
     * 采购单审核界面
     *
     * @param none
     * @return obj
     *
     */
    public function writeOffIndex()
    {
        $this->mainTitle = '采购单核销';
        $this->mainIndex = route('purchaseOrder.writeOffIndex');
        $data = [];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data'  => $this->autoList($this->purchaseOrderConfirm),
            'mixedSearchFields' => $this->purchaseOrderConfirm->mixed_search,
        ];

        return view($this->viewPath . 'writeOffIndex', $response);
    }

    public function purchaseOrderConfirmCsvFormat(){
        $rows = [
            [
                '单据号'=>'94406',
                '实际核销金额'=>'12.38',
                '核销原因'=>'已打回支付宝',
                '退款凭据' => '交易号 20150902200040011100220043025031',
                '退款日期'=>'2015-09-01',
            ]
        ];

        Excel::create('采购单核销CSV格式', function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function purchaseOrderPayOffCsvFormat(){
        $rows = [
            [
                '采购单据号'=>'10001',
            ],
            [
                '采购单据号'=>'10002',
            ]
        ];

        Excel::create('采购单付款CSV格式', function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function purchaseOrderConfirmCsvFormatExecute(){
        if(!isset($_FILES['excel']['tmp_name'])) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请上传表格!'));
        }
        $csv = Excel::load($_FILES['excel']['tmp_name'],'gb2312')->noHeading()->toArray();
        unset($csv[0]); //删除表头
        foreach($csv as $data){
            $purchaseOrderModel = $this->model->find($data['1']);
            if(!$purchaseOrderModel){
                return redirect(route('purchaseOrder.writeOffIndex'))->with('alert', $this->alert('danger', '采购单号'.$data['1'].'不存在,请重新查看并将此采购单号后的数据重新导入!'));
            }
            $purchaseOrderConfirmModel = $this->purchaseOrderConfirm->where('po_id',$data['1'])->get()->toArray();
            if($purchaseOrderConfirmModel){
                return redirect(route('purchaseOrder.writeOffIndex'))->with('alert', $this->alert('danger', '采购单号'.$data['1'].'已导入数据,请重新查看并将此采购单号后的数据重新导入!'));
            }
            $result = [];
            $result['po_id'] = $data['1'];
            $result['real_money'] = $data['2'];
            $result['reason'] = $data['3'];
            $result['credential'] = $data['4'];
            $result['refund_time'] = $data['5'];
            $result['create_user'] = request()->user()->id;
            $result['po_user'] = $purchaseOrderModel->purchaseUser->id;
            $result['status'] = 1;
            $fee = 0;
            foreach($purchaseOrderModel->purchaseItem as $purchaseItem){
                $fee += $purchaseItem->storage_qty*$purchaseItem->purchase_cost;
            }
            $result['no_delivery_money'] = $purchaseOrderModel->total_purchase_cost+$purchaseOrderModel->total_postage-$fee;
            $this->purchaseOrderConfirm->create($result);
        }
        return redirect(route('purchaseOrder.writeOffIndex'))->with('alert', $this->alert('success', '导入成功'));
    }

}








