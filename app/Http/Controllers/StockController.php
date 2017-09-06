<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use Cache;
use App\Models\UserModel;
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\Stock\TakingFormModel;
use App\Jobs\StockTaking;

class StockController extends Controller
{
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
        $this->mainIndex = route('stock.index');
        $this->mainTitle = '库存';
        $this->viewPath = 'stock.';
        $this->middleware('StockIOStatus');
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
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'warehouses' => WarehouseModel::where(['is_available' => '1'])->get(),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::where('is_available','1')->get(),
        ];

        return view($this->viewPath.'create', $response);
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
        $stock = $this->model->where(['item_id' => request('item_id'), 'warehouse_position_id' => request('warehouse_position_id')])->first();
        if(!$stock) {
            $stocks = $this->model->where(['item_id' => request('item_id'), 'warehouse_id' => request('warehouse_id')])->get();
            if($stocks->count() > 1) {
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '该仓库对应sku库位数量超过2'));
            }
        }
        $item = ItemModel::find(request('item_id'));
        $item->in(request('warehouse_position_id'), request()->input('all_quantity'), request()->input('all_quantity') * ($item->cost ? $item->cost : $item->purchase_price), 'MAKE_ACCOUNT');
        if(!empty(request('oversea_sku'))) {
            $stock = $this->model->where(['item_id' => request('item_id'), 'warehouse_position_id' => request('warehouse_position_id')])->first();
            $stock->update(['oversea_sku' => request('oversea_sku'), 'oversea_cost' => request('oversea_cost')]);
        }
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
    }

    public function showStockInfo()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '库存信息查询'),
        ];

        return view($this->viewPath.'showStockInfo', $response);
    }

    public function getTakingExcel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '1G');
        $start = 0;
        $len = 10000;
        $rows = [];
        $stocks = $this->model->skip($start)->take($len)->get();
        while($stocks->count()) {
            foreach($stocks as $stock) {
                $rows[] = [
                    'sku' => $stock->item ? $stock->item->sku : '',
                    'position' => $stock->position ? $stock->position->name : '',
                    'all_quantity' => $stock->all_quantity,
                    'quantity' => '',
                ];
            }
            $start += $len;
            unset($stocks);
            $stocks = $this->model->skip($start)->take($len)->get();
        }
        $name = 'getTakingExcel';
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function overseaSku()
    {
        $sku = request('sku');
        $stocks = $this->model->whereHas('warehouse', function($query){
            $query = $query->where('type', 'fbaLocal');
        })->whereHas('item', function($query) use ($sku){
            $query = $query->where('sku', 'like', '%'.$sku.'%');
        })->distinct()->with('item')->get(['item_id']);
        $total = $stocks->count();
        $arr = [];
        foreach($stocks as $key => $stock) {
            $arr[$key]['id'] = $stock->item_id;
            $arr[$key]['text'] = $stock->item->sku;
        }
        if($total)
            return json_encode(['results' => $arr, 'total' => $total]);
        else 
            return json_encode('false');
    }

    public function overseaPosition()
    {
        if(request()->ajax()) {
            $item_id = trim(request()->input('item_id'));
            $obj = StockModel::where(['item_id'=>$item_id])->whereHas('warehouse', function($query){
                $query = $query->where('type', 'fbaLocal');
            })->with('position')->get();
            if(!count($obj)) {
                return json_encode('none');
            }
            $arr[] = $obj;
            $arr[] = $obj->first()->available_quantity;
            
            return json_encode($arr);
        }

        return json_encode('false');
    }

    public function changePosition()
    {
        $id = request('id');
        $position_id = request('position');
        $stock = $this->model->find($id);
        if($stock) {
            $stock->update(['warehouse_position_id' => $position_id]);
            return json_encode(true);
        }
        return json_encode(false);
    }

    public function getSingleSku()
    {
        $sku = request('sku');
        $item = ItemModel::where('sku', $sku)->first();
        if(!$item) {
            return json_encode(false);
        }
        $item_id = $item->id;
        $stocks = $this->model->where('item_id', $item_id)->get();
        $str = "<table class='table table-bordered'><thead><th>仓库</th><th>库位</th><th>sku</th><th>总数量</th><th>可用数量</th><th>物流限制</th>";
        if(!request()->has('type')) {
            $str .= "<th>按钮</th>";
        }
        $str .= "</thead><tbody>";
        foreach($stocks as $stock)
        {
            $str .= "<tr><td data-id='".$stock->id."' data-warehouseId='".$stock->warehouse_id."'>".($stock->warehouse ? $stock->warehouse->name : '')."</td><td class='col-lg-2'>".($stock->position ? $stock->position->name : '')."</td><td data-itemId='".$stock->item_id."'>".($stock->item ? $stock->item->sku : '')."</td><td>".($stock->all_quantity ? $stock->all_quantity : '')."</td><td>".($stock->available_quantity ? $stock->available_quantity : '')."</td><td>".($stock->item ? $stock->item->logistics_limit : '')."</td>";
            if(!request()->has('type')) {
                $str .= "<td><button type='button' class='btn btn-info change_position'>修改库位</button></td>";
            }
            $str .= "</tr>";
        }
        $str .= "</tbody>";

        return $str;
    }

    public function getSinglePosition()
    {
        $position = request('position');
        $position = PositionModel::where('name', $position)->first();
        if(!$position) {
            return json_encode(false);
        }
        $warehouse_position_id = $position->id;
        $stocks = $this->model->where('warehouse_position_id', $warehouse_position_id)->get();
        $str = "<table class='table table-bordered'><thead><th>仓库</th><th>库位</th><th>sku</th><th>总数量</th><th>可用数量</th></thead><tbody>";
        foreach($stocks as $stock)
        {
            $str .= "<tr><td>".($stock->warehouse ? $stock->warehouse->name : '').'</td><td>'.($stock->position ? $stock->position->name : '')."</td><td>".($stock->item ? $stock->item->sku : '')."</td><td>".($stock->all_quantity ? $stock->all_quantity : '')."</td><td>".($stock->available_quantity ? $stock->available_quantity : '')."</td></tr>";
        }
        $str .= "</tbody>";

        return $str;
    }
    /**
     * 盘点更新
     *
     * @return
     *
     */
    public function createTaking()
    {
        if(!Cache::store('file')->get('stockIOStatus')) {
            return redirect(route('stockTaking.index'))->with('alert', $this->alert('fail', '盘点中...'));
        } else {
            system('supervisorctl stop laravel-queue-assignStocks');
            $job = new StockTaking();
            $job = $job->onQueue('stockTaking');
            $this->dispatch($job);
        }
        
        return redirect(route('stockTaking.index'))->with('alert', $this->alert('success', '已加入队列'));
    }

    /**
     * 获取信息 
     * 传参：sku，仓库号
     * array[0] => item号的相应对象
     * array[1] => 通过仓库和items_id 来获取对应的库存对象
     * array[2] => 对应于array[1]的position对象
     * array[3] => 获取商品单价
     *
     * @return array
     */
    public function ajaxGetMessage()
    {
        $item_id = request()->input('item_id');
        $warehouse_id = request()->input('warehouse_id');
        $type = request()->input('type');
        $obj = ItemModel::find($item_id);
        if(!$obj) {
            return json_encode('sku_none');
        }
        if($type == 'OUT') {
            $obj1 = StockModel::where(['warehouse_id'=>$warehouse_id, 'item_id'=>$item_id])->with('position')->get();
            if(!count($obj1)) {
                return json_encode('stock_none');
            }
            $arr[] = $obj1->toArray();
            $arr[] = $obj1->first()->unit_cost;
            if(count($arr)) {
                return json_encode($arr);
            } else {
                return json_encode('false');
            }
        } else {
            $obj1 = StockModel::where(['warehouse_id'=>$warehouse_id, 'item_id'=>$item_id])->with('position')->get();
            $arr[] = $obj1->count();
            if($arr[0]) {
                $arr[] = $obj1->toArray();
                $arr[] = $obj1->first()->unit_cost;
            }
            return json_encode($arr);
        }
    }

    public function ajaxGetOnlyPosition()
    {
        $warehouse_position_id = request('position');
        $item_id = request('sku');
        $stock = $this->model->where(['warehouse_position_id' => $warehouse_position_id, 'item_id' => $item_id])->first();
        if($stock) {
            return json_encode($stock->available_quantity);
        }
        return json_encode(0);
    }

    /**
     * ajax请求  sku
     *
     * @param none
     * @return obj
     * 
     */
    public function ajaxAllSku()
    {
        if(request()->ajax()) {
            $sku = trim(request()->input('sku'));
            $stocks = $this->model->whereHas('item', function($query) use ($sku){
                $query = $query->where('sku', 'like', '%'.$sku.'%');
            })->get();
            $total = $stocks->count();
            $arr = [];
            foreach($stocks as $key => $stock) {
                $arr[$key]['id'] = $stock->item_id;
                $arr[$key]['text'] = $stock->item->sku;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else 
                return json_encode('false');
        }

        return json_encode('false');
    }

    /**
     * ajax请求  sku
     *
     * @param none
     * @return obj
     * 
     */
    public function ajaxSku()
    {
        $sku = trim(request()->input('sku'));
        $warehouse = request()->has('warehouse_id') ? request('warehouse_id') : '';
        $stocks = '';
        if(!empty($warehouse)) {
            $stocks = StockModel::where('warehouse_id', $warehouse)->whereHas('item', function($query) use ($sku){
                $query = $query->where('sku', 'like', '%'.$sku.'%');
            })->distinct()->take('20')->get(['item_id']);
        } else {
            $stocks = ItemModel::where('sku', 'like', '%'.$sku.'%')->take('20')->get();
        }
        $total = $stocks->count();
        $arr = [];
        if(!empty($warehouse)) {
            foreach($stocks as $key => $stock) {
                $arr[$key]['id'] = $stock->item_id;
                $arr[$key]['text'] = $stock->item->sku;
            }
        } else {
            foreach($stocks as $key => $stock) {
                $arr[$key]['id'] = $stock->id;
                $arr[$key]['text'] = $stock->sku;
            }
        }
        if($total)
            return json_encode(['results' => $arr, 'total' => $total]);
        else 
            return json_encode('false');
    }

    /**
     * 获取库存对象，通过库位
     * 某仓库某库位的对象里面的所有sku
     *
     * @return obj
     * @var array
     *
     */
    public function ajaxGetByPosition()
    {
        $warehouse_id = trim(request()->input('warehouse_id'));
        $item_id = trim(request()->input('sku'));
        $position = trim(request()->input('position'));
        $obj = PositionModel::where('warehouse_id', $warehouse_id)->where('name', 'like', '%'.$position.'%')->take('20')->get();
        $total = $obj->count();
        $arr = [];
        foreach($obj as $key => $position) {
            $arr[$key]['id'] = $position->id;
            $arr[$key]['text'] = $position->name;
        }
        if($total)
            return json_encode(['results' => $arr, 'total' => $total]);
        else 
            return json_encode('false');
    }

    /**
     * 调拨调出仓库对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotOutWarehouse()
    {
        if(request()->ajax()) {
            $warehouse = request()->input('warehouse');
            $buf = $this->model->where('warehouse_id', $warehouse)->distinct()->with('items')->get(['item_id'])->toArray();
            if(!count($buf)) {
                return json_encode('none');
            }
            $arr[] = $buf;
            $arr[] = $this->model->where('warehouse_id', $warehouse)->first()->toArray();
            $obj = $this->model->where(['warehouse_id'=>$warehouse, 'item_id'=>$arr[0][0]['items']['id']])->get();
            foreach($obj as $val)
            {
                $tmp = $val->position ? $val->position->toArray() : '';
                $arr[2][] = $tmp;
            }
            return json_encode($arr);
        }

        return json_encode('false');
    }



    /**
     * 调拨库位对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotPosition()
    {
        if(request()->ajax()) {
            $position = PositionModel::where('name', trim(request()->input('position')))->first();
            if(!$position) {
                return json_encode(false);
            }
            $position = $position->id;
            $item_id = ItemModel::where('sku', trim(request()->input('sku')))->first()->id;
            $obj = StockModel::where(['warehouse_position_id'=>$position, 'item_id'=>$item_id])->first();
            $arr[] = $obj->toArray();
            $arr[] = $obj->unit_cost;
            if($arr) {
                return json_encode($arr);
            } else {
                return json_encode('none');
            }
        }

        return json_encode(false);
    }

    /**
     * 调拨sku对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotSku()
    {
        if(request()->ajax()) {
            $warehouse = trim(request()->input('warehouse'));
            $item_id = trim(request()->input('item_id'));
            $obj = StockModel::where(['warehouse_id'=>$warehouse, 'item_id'=>$item_id])->with('position')->get();
            if(!count($obj)) {
                return json_encode('none');
            }
            $arr[] = $obj;
            $arr[] = $obj->first()->available_quantity;
            $arr[] = $obj->first()->unit_cost;
            
            return json_encode($arr);
        }

        return json_encode('false');
    }

    

    /**
     * ajax请求   position
     *
     * @param none
     * @return boolean
     *
     */
    public function ajaxPosition()
    {
        $sku = trim(request()->input('sku'));
        $obj = ItemModel::where('sku', $sku)->first();
        if(!$obj) {
            return json_encode(false);
        }
        $position = PositionModel::where(['name' => trim(request()->input('position')), 'is_available'=>'1'])->first();
        if(!$position) {
            return json_encode(false);
        }
        $stock = StockModel::where(['item_id'=>$obj->id, 'warehouse_position_id'=>$position->id])->first();
        if($stock)
            return json_encode($stock->available_quantity);
        else
            return json_encode(false);
    }

    /**
     * 获取excel表格 
     *
     * @param none
     *
     */
    public function getExcel()
    {
        $rows = [
                    [ 
                     'sku'=>'',
                     'position'=>'',
                     'all_quantity'=>'',
                     'oversea_sku' => '',
                     'oversea_cost' => '',
                    ]
            ];
        $name = 'stock';
        Excel::create($name, function($excel) use ($rows){
            $nameSheet='库存';
            $excel->sheet($nameSheet, function($sheet) use ($rows){
                $sheet->with($rows);
            });
        })->download('csv');
    }

    /**
     * excel 导入数据
     *
     * @param
     *
     */
    public function importByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'excel', $response);
    }

    public function overseaExcelProcess()
    {
        if(request()->hasFile('excel'))
        {
            $file = request()->file('excel');
            $arr = $this->model->overseaExcelProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__, '库存变动调整单'),
                'arr' => $arr,
            ];

            return view($this->viewPath.'overseaImport', $response);
        }
    }

    /**
     * excel 导入数据
     *
     * @param
     *
     */
    public function overseaImportByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'url' => route('stock.overseaExcelProcess')
        ];

        return view($this->viewPath.'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcess()
    {
        if(request()->hasFile('excel'))
        {
            $file = request()->file('excel');
            $errors = $this->model->excelProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];
            return view($this->viewPath.'excelResult', $response);
        }
    }

    /**
     * item编辑页面库位查询
     *
     * @param none
     *
     */
    public function ajaxWarehousePosition()
    {   
        $item_id = request()->input('item_id');
        $warehouse_id = request()->input('warehouse_id');
        $position_name = trim(request()->input('warehouse_position'));   
        $obj1 = StockModel::where(['warehouse_id'=>$warehouse_id, 'item_id'=>$item_id])->with('position')->get();
        if($obj1->toArray()) {
            if(count($obj1->toArray())==2){
                foreach ($obj1 as $value) {
                    $position_id[]=$value->warehouse_position_id;
                }
                
                $buf = PositionModel::where('warehouse_id',$warehouse_id)->whereIn('id',$position_id)->get();
                $total = $buf->count();
                $arr = [];
                foreach($buf as $key => $value) {
                    $arr[$key]['id'] = $value->id;
                    $arr[$key]['text'] = $value->name;
                }
                if($total)
                    return json_encode(['results' => $arr, 'total' => 2]);
                else
                    return json_encode(false);
            }else{
                $buf = PositionModel::where('warehouse_id',$warehouse_id)->where('name','like', '%'.$position_name.'%')->get();
                $total = $buf->count();
                $arr = [];
                foreach($buf as $key => $value) {
                    $arr[$key]['id'] = $value->id;
                    $arr[$key]['text'] = $value->name;
                }
                if($total)
                    return json_encode(['results' => $arr, 'total' => $total]);
                else
                    return json_encode(false);
            }
        }else{
            $buf = PositionModel::where('warehouse_id',$warehouse_id)->where('name','like', '%'.$position_name.'%')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }
        
    }
}