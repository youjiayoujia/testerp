<?php
/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:21pm
 */

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\RequireModel;
use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;
use App\Models\SpuModel;
use Tool;
use Excel;

class RequireController extends Controller
{
    public function __construct(RequireModel $require)
    {
        $this->model = $require;
        $this->mainIndex = route('productRequire.index');
        $this->mainTitle = '选款需求';
        $this->viewPath = 'product.require.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $data = request()->all();
        $chose_status = '';
        if(array_key_exists('filters', $data)){
            $chose_status = config('product.product_require.status')[substr($data['filters'],strrpos($data['filters'], '.')+1)];
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'chose_status' => $chose_status,
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
            'catalogs' => CatalogModel::all(),
            'channel' =>ChannelModel::all(),
            'channel_account' => AccountModel::where("channel_id",'=',1)->get(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 数据保存
     *
     * @param none
     * @return view
     *
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $buf = $this->model->create($data);
        $data['id'] = $buf->id;
        $data['created_by'] = request()->user()->id;
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $data['id'];
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $buf->update($data);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
    }

    public function getExcel()
    {
        $rows[] = [
            '产品名' => '',
            '品类' => 'id',
            '省' => '',
            '市' => '',
            '颜色' => '',
            '材料' => '',
            '工艺' => '',
            '配件' => '',
            '类似款sku' => '',
            '竞争产品url' => '',
            '需求描述' => '',
            '期望上传日期' => '',
            '采购人' => 'id',
            'url1' => '',
            'url2' => '',
            'url3' => '',
        ];
        $name = 'CatalogRates';
        Excel::create($name, function ($excel) use ($rows) {
            $nameSheet = '导出分类税率';
            $excel->sheet($nameSheet, function ($sheet) use ($rows) {
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelStore()
    {
        if(request()->hasFile('excel'))
        {
           $file = request()->file('excel');
           $this->model->excelProcess($file);
           return redirect($this->mainIndex);
        }
    }

    public function importByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__)
        ];

        return view($this->viewPath.'excel', $response);
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
            'catalogs' => CatalogModel::all(),
            'channel' =>ChannelModel::all(),
            'channel_account' => AccountModel::where("channel_id",$model->needer_id)->get(),
        ];
        
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 数据更新
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $model->rules('update', $id));
        $data = request()->all();
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $id;
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $model->update($data);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }

    /**
     * ajax 处理请求 
     *
     * @return json
     *
     */
    public function ajaxProcess()
    {
        $id = request()->input('id');
        $status = request()->input('status');
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        
        if($status == 1) {
            $model->update(['status'=>'1', 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
        } else {
            $catalog = CatalogModel::find($model->catalog_id);
            $code_num = SpuModel::where("spu", "like", $catalog->code . "%")->get()->count();
            //创建spu，,并插入数据
            $spuobj = SpuModel::create(['spu' => Tool::createSku($catalog->code, $code_num),'product_require_id'=>$model->id,'status'=>'purchase','purchase'=>$model->purchase_id,'developer'=>$model->created_by]);
            $model->update(['status'=>'2', 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
            $channels = ChannelModel::all();
            foreach ($channels as $channel) {
                $spuobj->spuMultiOption()->create(['spu_id'=>$spuobj->id,'channel_id'=>$channel->id]);
            }    
        }

        return json_encode($status);
    }

    /**
     * ajax 批量处理请求 
     *
     * @return json
     *
     */
    public function ajaxQuantityProcess()
    {
        $buf = request()->input('buf');
        $status = request()->input('status');
        foreach($buf as $v)
        {
            $model = $this->model->find($v);
            if($model->status) {
                continue;
            }
            $model->update(['status'=>$status, 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
        }

        return json_encode('success');
    }
}