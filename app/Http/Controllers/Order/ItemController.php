<?php
/**
 * 产品控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/23
 * Time: 下午5:43
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\ItemModel;

class ItemController extends Controller
{
    public function __construct(ItemModel $item)
    {
        $this->model = $item;
        $this->mainIndex = route('orderItem.index');
        $this->mainTitle = '产品管理';
        $this->viewPath = 'order.item.';
    }

    public function destroy($id)
    {
        $model = $this->model->find($id);

        $model->destroy($id);
        return redirect(route('order.index'));
    }

}