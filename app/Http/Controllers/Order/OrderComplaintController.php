<?php
/**
 * 投诉订单控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/23
 * Time: 下午5:43
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderComplaintModel;

class OrderComplaintController extends Controller
{
    public function __construct(OrderComplaintModel $complaint)
    {
        $this->model = $complaint;
        $this->mainIndex = route('orderComplaint.index');
        $this->mainTitle = '订单投诉';
        $this->viewPath = 'order.orderComplaint.';
    }
	
	
	/**
     * 新增订单投诉
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $data=request()->all();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->create(request()->all());
        return redirect($this->mainIndex);
    }
		
}