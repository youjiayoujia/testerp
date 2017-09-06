<?php
/*Time:2016-12-16
 * 同步sellmore 旧系统wish_you token数据到  v3erp.
 * User: hejiancheng
 */
namespace App\Http\Controllers;

use App\Models\Product\SupplierModel;
use App\Models\AccounttokenModel;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportSyncApiModel;
use Tool;

class SyncWishyoutokenController extends Controller
{

    public function __construct(ImportSyncApiModel $syncApi)
    {
        $this->model = $syncApi;
        //$this->mainIndex = route('importSyncApi.index');
        //$this->mainTitle = '接收接口';
        //$this->viewPath = 'importSyncApi.';
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function SyncSuppliersFromSell()
    {
        $result = request()->all();
        if(!isset($result['key']) || (isset($result['key']) && $result['key'] !== 'c41d732151244f4ca6717004f6598d96')){   //密钥
            return json_encode(['status' => 'fail','info' => 'api_unpermission']);
        }
        if(!isset($result['account']) || !isset($result['account_token']) || !isset($result['type']) || $result['type'] !== 'wish_you'){
            return json_encode(['status' => 'fail','info' => 'data error1']);
        }
        $options = array();
        $options['account_token']           =  $result['account_token'];
        $thiswish = AccounttokenModel::where('account', $result['account'])->first();
        if (!$thiswish) {
            return json_encode(['status' => 'fail','info' => 'data error2']);
        }
        $res = AccounttokenModel::where('account', $result['account'])->update($options);
        if($res){
            return json_encode(['status' => 'success','info' => '账号'.$result['account'].'的token修改成功！']);
        }else{
            return json_encode(['status' => 'success','info' => '账号'.$result['account'].'的token修改失败！']);
        }
    }

}
