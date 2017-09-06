<?php
/**
 * 海外仓头程物流控制器
 *
 * 2016-12.05
 * @author: MC<178069409>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\Box\BoxModel;
use App\Models\Oversea\Allotment\AllotmentModel;

class BoxController extends Controller
{
    public function __construct(BoxModel $box)
    {
        $this->model = $box;
        $this->mainIndex = route('overseaBox.index');
        $this->mainTitle = '箱子';
        $this->viewPath = 'oversea.box.';
    }

    public function createbox()
    {
        $id = request('id');
        $model = $this->model->create(['parent_id' => $id]);
        $allotment = AllotmentModel::find($id);
        $model->update(['boxnum' => $allotment->allotment_num.'-'.$model->id]);
        if($model) {
            return $model->boxnum;
        } else {
            return false;
        }
    }
}