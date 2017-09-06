<?php
/**
 * 产品品类控制器
 * 产品品类CURD
 * @author: youjia
 * Date: 2015-12-28 17:57:09
 */

namespace App\Http\Controllers;

use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Catalog\CatalogChannelsModel;
use App\Models\Channel\CatalogRatesModel;
use App\Models\Catalog\RatesChannelsModel;
use App\Models\Product\CatalogCategoryModel;
use Excel;

class CatalogController extends Controller
{
    public function __construct(CatalogModel $catalog)
    {
        $this->model = $catalog;
        $this->mainIndex = route('catalog.index');
        $this->mainTitle = '品类Category';
        $this->viewPath = 'catalog.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $CatalogRatesModel = CatalogRatesModel::all();
        $response = [
            'metas'             => $this->metas(__FUNCTION__),
            'CatalogRatesModel' => $CatalogRatesModel,
            'catalogCategory'   => CatalogCategoryModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 保存品类
     * 2015-12-18 14:38:20 YJ
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        //封装数据
        $data = request()->all();
        $extra['sets'] = request()->input('sets');
        $extra['variations'] = request()->input('variations');
        $extra['features'] = request()->input('features');
        //创建品类
        $this->model->createCatalog($data, $extra);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }


    /**
     * 更新品类
     *
     * 2015-12-18 14:46:59 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function update($id)
    {
        $catalogModel = $this->model->find($id);
        if (!$catalogModel) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        //封装数据
        $data = request()->all();
        $extra['sets'] = request()->input('sets');
        $extra['variations'] = request()->input('variations');
        $extra['features'] = request()->input('features');
        //更新品类信息
        $catalogModel->updateCatalog($data, $extra);
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '更新成功.'));
    }

    /**
     * 软删除品类
     * 2015-12-18 14:47:08 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function destroy($id)
    {
        $catalogModel = $this->model->find($id);
        $catalogModel->destoryCatalog();
        return redirect(route('catalog.index'))->with('alert', $this->alert('success', '删除成功.'));
    }

    /**
     * 检查分类名是否存在
     * 2015-12-18 14:47:08 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function checkName()
    {
        $catalog_name = request()->input('catalog_name');
        return $this->model->checkName($catalog_name);
    }

    public function index(){
        request()->flash();
        $channels = CatalogRatesModel::all();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'channels' => $channels,
        ];
        return view($this->viewPath . 'index',$response);

    }

    /**
     * 导出分类平台税率
     * 
     */
    public function exportCatalogRates(){

        $filters = request()->input('filter');
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        $cvsArray = [];
        $i = 1;
        $cvsArray[0] = '';
        $th = false;
        foreach ($this->model->whereIn('id',$catalogIds)->get() as $itemCatalog){
            $channelsData = $this->model->find($itemCatalog->id)->channels;
            //$cvsArray [$i][$itemCatalog->name] = $itemCatalog->name;
            $cvsArray [$i][] = $i;
            $cvsArray [$i][] = $itemCatalog->c_name;
            foreach ($channelsData as $itemChannel){
                if(in_array($itemChannel['id'],$channelIds)){
                    //<th>
                    if($i ==1){
                        $th[] = $itemChannel->name;
                    }
                    $cvsArray [$i][] = $itemChannel->pivot->rate;
                }
            }
            $i++;
        }

        if($th == false){
            return redirect(route('catalog.index'))->with('alert', $this->alert('danger', '包含没有添加税率的分类记录，请先编辑，再导出!'));
        }

        $cvsArray[0] = array_merge(['序号','分类名'],$th);

        $name = 'CatalogRates';
        Excel::create($name, function ($excel) use ($cvsArray) {
            $nameSheet = '导出分类税率';
            $excel->sheet($nameSheet, function ($sheet) use ($cvsArray) {
                $sheet->fromArray($cvsArray);
            });
        })->download('csv');
    }

    /**
     * 编辑税率
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCatalogRates(){

        $filters = request()->input('filter');
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        $catalogs = $this->model->whereIn('id',$catalogIds)->get();
        $channels = CatalogRatesModel::whereIn('id',$channelIds)->get();
        $response =[
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $catalogs,
            'channels' => $channels,
            'filters' => $filters
        ];
        return view($this->viewPath . 'edit_rate',$response);
    }

    /**
     * 更新税率
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCatalogRates(){

        $requestArray = request()->input();
        $filters = $requestArray['filter'];
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        foreach ($catalogIds as $catalogId){
            foreach ($channelIds as $channelId){
                $CatalogChannel = RatesChannelsModel::where('catalog_id','=',$catalogId)->where('channel_id','=',$channelId)->first();
                if(isset($requestArray[$channelId]) && !empty($CatalogChannel)){
                    $CatalogChannel->rate = $requestArray[$channelId];
                    $CatalogChannel->save();
                }else{
                    $obj = new RatesChannelsModel;
                    $obj->rate = $requestArray[$channelId];
                    $obj->catalog_id = $catalogId;
                    $obj->channel_id = $channelId;
                    $obj->save();
                }
            }
        }

        return redirect(route('catalog.index'))->with('alert', $this->alert('success', '操作成功!'));
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $channels = CatalogRatesModel::all();
        foreach ($channels as $channel){
            $channels_all[$channel->id] = $channel;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels_all' => $channels_all,
            'catalogCategory'   => CatalogCategoryModel::all(),
            'hideUrl' => $hideUrl,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function  exportExcel($rows,$name){
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
    public function catalogCsvFormat(){
        $rows = [
            [
                '一级分类中文名'=>'玩具及爱好(实例：不用删除)',
                '一级分类英文'=>'toy and hobby',
                '二级中文分类名称'=>'鞋子',
                '二级分类英文名称' => 'shoes',
                '前缀'=>'XL',
                'Set属性'=>'name1:value1,value2;name2:value1,value2',
                'variation属性'=>'name1:value1,value2;name2:value1,value2',
                'Feature属性(说明：1，文本；2，单选 ；3，多选 ) '=>'1-value;2-name1:value1,value2,value3;3-nname:value1,value2,value3',
            ]
        ];

        $channels = CatalogRatesModel::all();
        foreach ($channels as $channel){
            $rows[0][$channel->name] = '20,40';
        }


        $this->exportExcel($rows, '批量添加产品品类csv格式');
    }
    public function addLotsOfCatalogs(){
        if(!isset($_FILES['excel']['tmp_name'])) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请上传表格!'));
        }
        $CSV = Excel::load($_FILES['excel']['tmp_name'],'gb2312')->noHeading()->toArray();
        unset($CSV[0]); //删除表头
        if($CSV[1][2] == 'toy and hobby') //删除实例行
            unset($CSV[1]);
        
        foreach($CSV as $key => $value){
            if(empty($value[1]) || empty($value[2]) || empty($value[3]) || empty($value[3])){
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', '必填字段不能为空，请核修改重新填写!'));
            }
        }
        
        if(count($CSV) < 1){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请先填写数据'));
        }
        
        $result = $this->model->csvInsertCatalogs($CSV);
        if($result){
            return redirect(route('catalog.index'))->with('alert', $this->alert('success', '批量插入成功!'));
        }else{
            return redirect(route('catalog.index'))->with('alert', $this->alert('danger', '批量导入失败'));
        }


    }

    /**
     * 检查属性格式有效性
     * @param $AttributeAry
     * @param $type
     * return bool
     */
    public function doCheckAttribute($AttributeAry,$type){
        $result = TRUE;
        switch ($type){
            case 'set':
            case 'variation':
                $check_ary = explode(';',trim($AttributeAry));
                foreach ($check_ary as $item){
                    $result = strstr($item,':');
                    if($result == FALSE){
                        break;
                    }
                }
                break;
            default:
                return;
        }
        return $result;

    }

    /**
     * ajax获取产品分类
     * @param $AttributeAry
     * @param $type
     * return bool
     */
    public function ajaxCatalog(){
        if(request()->ajax()) {
            $catalog = trim(request()->input('catalog'));
            $buf = CatalogModel::where('c_name', 'like', '%'.$catalog.'%')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->c_name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }

        return json_encode(false);
    }   
}
