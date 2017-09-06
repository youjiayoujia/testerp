<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 14:07
 */

namespace App\Http\Controllers\Publish\Ebay;

use Tool;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayDescriptionTemplateModel;



class EbayDescriptionTemplateController extends Controller
{
    public function __construct(EbayDescriptionTemplateModel $description)
    {
        $this->model = $description;
        $this->mainIndex = route('ebayDescription.index');
        $this->mainTitle = 'Ebay描述模板设置';
        $this->viewPath = 'publish.ebay.descriptionTemplate.';

    }


    public function store(){
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['description'] =htmlspecialchars($data['description']);
        $model = $this->model->create($data);
        $this->eventLog(request()->user()->id, '数据新增', serialize($model));
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '新增成功'));
    }


    public function update($id)
    {

        $model = $this->model->find($id);
        $from = serialize($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data = request()->all();
        $data['description'] =htmlspecialchars($data['description']);
        $model->update($data);
        $to = serialize($model);
        $this->eventLog(request()->user()->id, '数据更新', $to, $from);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '数据更新成功'));
    }

}