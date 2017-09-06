<?php
/**
 * 售后服务控制器
 * @author haiou 2016/7/27
 */
namespace App\Http\Controllers\Publish\Smt;

use Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Publish\Smt\afterSalesService;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;

class AfterSalesServiceController extends Controller
{
    public function __construct(afterSalesService $afterSalesServiceModel){
        $this->model = $afterSalesServiceModel;
        $this->mainTitle = '售后模版';
        $this->mainIndex = route('smtAfterSale.index');
        $this->viewPath = "publish.smt.smtAfterSale.";
    }

    /**
     * 速卖通售后模版列表
     * @see \App\Http\Controllers\Controller::index()
     */
    public function index(){
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
        
    }
    
    
    public function edit($id){
        $model = $this->model->where('id',$id)->first();
        
        //获取smt平台帐号
        $accountList = array();
        $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
        $account_list = AccountModel::where('channel_id',$channel_id)->get();
        foreach($account_list as $account){
            $accountList[$account['id']] = $account->toArray();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' =>$model,
            'accoutList' => $accountList,
        ];
        return view($this->viewPath . 'edit', $response);
    }
    public function store(){
        $data = array();
        $data['id'] = request()->input('id');
        $data['plat'] = request()->input('plat');
        $data['token_id'] = request()->input('token_id');
        $data['name'] = request()->input('name');
        $data['content'] = request()->input('content');
        $content = $data['content'];
        //匹配下图片，要是http开头的就上传到smt图片银行，不是的话，还是继续保存在这
        if ($data['plat'] == 6 && !empty($data['token_id']) && $content) { //速卖通平台，账号必须要存在
            $pattern = '/<img[^>]+src\s*=\s*"?([^>"\s]+)"?[^>]*>/im';
            preg_match_all($pattern, $content, $matches);
            if (!empty($matches[1])) { //有匹配上是本地的图片
                $account = AccountModel::findOrFail($data['token_id']);
                $smtApi = Channel::driver($account->channel->driver, $account->api_config);
                $api       = 'api.uploadImage';              
                foreach ($matches[1] as $key => $match) {
                    if (!preg_match('/http:\/\/.*/i', $match)) { //不以http开头，说明肯定是被上传到本地了
                        $url    = '';
                        $result = $smtApi->uploadBankImage($api,public_path().$match); //上传图片                        
                        if ($result['status'] == 'SUCCESS' || $result['status'] == 'DUPLICATE') {
                            $url = $result['photobankUrl']; //返回的url链接
                        }
                        $content = str_replace($match, $url, $content);
                    }
                }
            }
        }

        $data['content'] = htmlspecialchars($content); 
        if(isset($data['id']) && $data['id']){            
            $this->model->where('id',$data['id'])->update($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '编辑成功!'));
        }else {
            $this->model->create($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '新增成功!'));
        }
       
    }
    
    /**
     * 异步获取速卖通售后服务模板列表,返回下拉框的选项，方便调用统一接口
     */
    public function ajaxSmtAfterServiceList(){
        //账号
        $token_id = Input::get('token_id');    
        if ($token_id){
            $data = $this->model->where(['plat' => 6, 'token_id' => $token_id])->get();
            $options = '';
            if ($data){
                foreach ($data as $row){
                    $options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            }
            unset($data);
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('账号错误', 'false');
        }
    }
    
    //json返回数据结构
    public function ajax_return($info='', $status=1, $data='') {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit( json_encode($result) );
    }
    
    /**
     * 异步获取对应平台的账号
     */
    public function ajaxGetTokenList(){
        $plat = request()->input('plat'); 
        if (!$plat){
            $this->ajax_return('平台错误，找不到对应的账号', false);
        }else {
            $data = array();
            switch ($plat){
                case 6: //SMT平台
                    $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
                    $account_list = AccountModel::where('channel_id',$channel_id)->get();
                    foreach($account_list as $account){                        
                        $data[$account['id']] = $account->toArray();
                    }
                    break;
            }
            if ($data){
                $this->ajax_return('', true, $data);
            }else {
                $this->ajax_return('没有找到对应的账号', false);
            }        
        }
    }
    
    
}
