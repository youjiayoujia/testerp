<?php
/**
 * @author guoou 2016/09/14
 */
namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;

class SmtAccountManageController extends Controller
{
    public function __construct(){
       $this->model = new AccountModel();
       $this->mainIndex = route('smtAccountManage.index'); 
       $this->mainTitle = "SMT帐号管理";
       $this->viewPath = 'publish.smt.';
       $this->channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
    }
    
    public function index(){
        $list = $this->model->where('channel_id',$this->channel_id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$list),
        ];
        return view($this->viewPath . 'smt_account_list', $response);
    }
    
    /**
     * 根据type确定检测 or刷新token
     */
    public function doAction(){
        $token_id = request()->input('token_id');
        $type = trim(request()->input('type'));
        
        $account_info = AccountModel::findOrFail($token_id);
        $smtApi = Channel::driver($account_info->channel->driver, $account_info->api_config);
        if($type == 'check'){
            //通过请求smtAPI来确定token是否正常
            $parameter = "cateId=0";
            $result = $smtApi->getJsonData("api.getChildrenPostCategoryById",$parameter);
            $result = json_decode($result, true);
            if(isset($result['success']) && $result['success']){
                $this->ajax_return("账号正常",1);
            }else{
                $msg = isset($result['error_message'])?$result['error_message']:"API调用失败";
                $this->ajax_return($msg,2);
            }
        }elseif($type == 'refresh'){
            $result =    $smtApi->resetAccessToken();
            $result = json_decode($result,true);
            if(isset($result['access_token'])){
                $data = array();
                $data['aliexpress_access_token'] = $result['access_token'];
                $data['aliexpress_access_token_date'] = date("Y-m-d H:i:s",time());
                $this->model->where('id',$token_id)->update($data);
                $this->ajax_return("刷新成功",1);
            }else{
                $msg = isset($result['error_description'])?$result['error_description']:'API请求错误';
                $msg = isset($result['error_message'])?$result['error_message']:$msg;
                $this->ajax_return($msg,2);
            }
        }else{
            $this->ajax_return("未知的错误",2);
        }
    }
    
    /**
     * smt帐号重新授权
     */
    public function resetAuthorization(){
        $token_id = request()->input('token_id');
        $code = trim(request()->input('code')); 
        
        $account_info = AccountModel::findOrFail($token_id);
        $smtApi = Channel::driver($account_info->channel->driver, $account_info->api_config);
        $result = $smtApi->getAppCode($code);
        $result = json_decode($result,true);
        if(isset($result['success']) && $result['success']){
            $update_data = array();
            $update_data['aliexpress_refresh_token']=$result['refresh_token'];
            $update_data['aliexpress_access_token']=$result['access_token'];
            $update_data['aliexpress_access_token_date']=date('Y-m-d H:i:s',time());
            //$update_data['next_call_time']=date('Y-m-d H:i:s', strtotime(mb_substr($result['refresh_token_timeout'], 0, 14)));
            $this->model->where('id',$token_id)->update($update_data);
            $this-> ajax_return('授权成功',1);
        }else{
            $this-> ajax_return('授权失败',2);
        }
        
    }
    
    //json返回数据结构
    public function ajax_return($info='', $status=1, $data='') {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit( json_encode($result) );
    }
    
}
