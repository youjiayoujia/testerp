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
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\StorageLogModel;

class PurchaseStockInController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseStockIn)
    {
        $this->model = $purchaseStockIn;
        $this->mainIndex = route('purchaseStockIn.index');
        $this->mainTitle = '采购入库';
        $this->viewPath = 'purchase.purchaseStockIn.';
    }


    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('storageStatus', '>', 0)),
        ];
        return view($this->viewPath . 'index', $response);
    }


    /**
     * 批量入库
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateStorage()
    {
        $data = request()->all();
        if ($data['storageInType'] == 1) {
            $data['storage_qty'] = 1;
        }
		$storageNum=$data['storage_qty'];
        $purchaseItemList = $this->model->where('sku', $data['sku'])->where('status',
            '2')->orderby('storageStatus')->get();
        $arrival_num = $this->model->where('sku', $data['sku'])->where('status', '2')->sum('arrival_num');
        $storage_qty = $this->model->where('sku', $data['sku'])->where('status', '2')->sum('storage_qty');
        $storage_num = $arrival_num - $storage_qty;
        //echo $arrival_num;echo ','.$storage_num;exit;
        if ($storage_num == 0) {
            if ($data['storageInType'] == 1) {
                return redirect(route('purchaseStockIn.create'))->with('alert',$this->alert('danger', $this->mainTitle . '没有可入库条目.'));
            } else {
                return redirect('manyStockIn')->with('alert', $this->alert('danger', $this->mainTitle . '没有可入库条目.'));
            }
        }
        foreach ($purchaseItemList as $key => $vo) {
            if ($vo->bar_code) {
                if ($data['storageInType'] == 1) {
                    if (($data['storage_qty'] + $vo->storage_qty) < $vo->purchase_num) {
                        $storage['storage_qty'] = $vo->storage_qty + $data['storage_qty'];
                        $storage['storageStatus'] = 1;
                        $data['storage_qty'] = 0;
                    } elseif (($data['storage_qty'] + $vo->storage_qty) == $vo->purchase_num) {
                        $storage['storage_qty'] = $vo->storage_qty + $data['storage_qty'];
                        $storage['storageStatus'] = 2;
                        $data['storage_qty'] = 0;
                    }
                    $stoeagelog['storage_quantity'] = 1;
                } else {
                    if (($data['storage_qty'] + $vo->storage_qty) < $vo->purchase_num) {
                        $storage['storage_qty'] = $data['storage_qty'] + $vo->storage_qty;
                        $storage['storageStatus'] = 1;
                        $stoeagelog['storage_quantity'] = $data['storage_qty'];
                        $data['storage_qty'] = 0;
                    } elseif (($data['storage_qty'] + $vo->storage_qty) == $vo->purchase_num) {
                        $storage['storage_qty'] = $vo->purchase_num;
                        $storage['storageStatus'] = 2;
                        $stoeagelog['storage_quantity'] = $data['storage_qty'];
                        $data['storage_qty'] = 0;
                    } else {
                        $storage['storage_qty'] = $vo->purchase_num;
                        $storage['storageStatus'] = 2;
                        $stoeagelog['storage_quantity'] = $vo->purchase_num - $vo->storage_qty;
                        $data['storage_qty'] = $data['storage_qty'] - $vo->purchase_num;
                    }
                }
            }
            $this->model->find($vo->id)->update($storage);
            $stoeagelog['user_id'] = 1;
            $stoeagelog['purchaseItemId'] = $vo->id;
            if ($stoeagelog['storage_quantity'] > 0) {
                StorageLogModel::create($stoeagelog);
                $stock = StockModel::find($vo->stock_id);
                ItemModel::find($stock->item_id)->in($stock->warehouse_position_id,$stoeagelog['storage_quantity'], $vo->purchase_cost * $stoeagelog['storage_quantity'],"PURCHASE", $vo->id, $remark = '订单采购入库！',0);
            }
        } 
        if ($data['storageInType'] == 1) {
			return redirect(route('purchaseStockIn.create'))->with('alert',$this->alert('success', $this->mainTitle .$data['sku'].'入库成功1件！'));
        } else {
			return redirect('manyStockIn')->with('alert', $this->alert('success', $this->mainTitle . $data['sku'].'入库成功'.$storageNum.'件！'));
        }
    }

    /**
     * 多件入库界面
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manyStockIn()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'stockIn', $response);
    }
}









