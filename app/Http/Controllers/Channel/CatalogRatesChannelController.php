<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/8/29
 * Time: 17:46
 */
namespace App\Http\Controllers\Channel;
use App\Http\Controllers\Controller;
use App\Models\Channel\CatalogRatesModel;

class CatalogRatesChannelController extends Controller
{
    public function __construct(CatalogRatesModel $catalogRates)
    {
        $this->model = $catalogRates;
        $this->mainIndex = route('CatalogRatesChannel.index');
        $this->mainTitle = '分类税率渠道';
        $this->viewPath = 'channel.catalog_rates.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $account = $this->model->find($id);
        if (!$account) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $account,
            'drivers' => config('channel.drivers'),
        ];
        return view($this->viewPath . 'edit', $response);
    }

}