<?php
/**
 * 图片控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ImageModel;
use App\Models\ProductModel;
use App\Models\LabelModel;

class ImageController extends Controller
{

    public function __construct(ImageModel $image)
    {
        $this->model = $image;
        $this->mainIndex = route('productImage.index');
        $this->mainTitle = '产品图片';
		$this->viewPath = 'product.image.';
    }
    
	
	public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
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
        ];
        return view($this->viewPath . 'createone', $response);
    }

    /**
     * 图片上传
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {   
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        //print_r($data);exit;
        $productModel = ProductModel::where("model",$data['model'])->first();
        
        $data['product_id'] = $productModel->id;
        $data['spu_id'] = $productModel->spu->id;
        $data['is_link'] = $data['is_link'];
        $data['tag'] = explode(',', substr($data['image_type'],0, strlen($data['image_type'])-1));
        $data['uploadType'] = 'image';
        $path = $this->model->imageCreate($data, request()->files);    
        
        return $path;
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
        $label_arr = [];
        foreach($model->labels as $imageLabel){
            $label_arr[] = $imageLabel->pivot->label_id;
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'label_arr' =>$label_arr,
            'labels'=> LabelModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }


    /**
     * 图片更新
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('update'));
        $this->model->updateImage($id, request()->file('image'),request()->all());
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '更新成功.'));
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->model->imageDestroy($id);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '删除成功.'));
    }

    public function createImage()
    {
        $model = request()->input('model');
        $productModel = ProductModel::where("model",$model)->first();
        if (!$productModel) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  'MODEL不存在.'));
        }
        $image = $productModel->imageAll;
        
        foreach ($image as $value) {
            $url[] = asset($value->path)."/".$value->name;
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model'=> $model,
            'labels'=> LabelModel::all(),
            'images' => $image,
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function createSpuImage()
    {
        $spu_id = request()->input('spu_id');
        
        $productModel = ProductModel::where("spu_id",$spu_id)->get();
        if (!count($productModel)) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  'SPU不存在或者MODEL不存在.'));
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'productModel'=> $productModel,
            'labels'=> LabelModel::all(),
        ];
        return view($this->viewPath . 'spucreate', $response);
    }
}









