<?php

namespace App\Http\Controllers\Publish\Smt;

use Illuminate\Http\Request;
use App\Models\ErpCopyrightModel;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use Excel;
use App\Models\ChannelModel;

class CopyrightController extends Controller
{
    public function __construct(){
        $this->viewPath = "publish.copyright.";
        $this->model = new ErpCopyrightModel();
        $this->mainIndex = route('copyright.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->flash();
        $this->mainTitle='侵权列表';  
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model), 
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }
    
    public function importCopyrightData(){
        if(!isset($_FILES['excel']['tmp_name'])) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请上传表格!'));
        }

        $CSV = Excel::load($_FILES['excel']['tmp_name'],'gb2312')->noHeading()->toArray();
        $CSV = array_shift($CSV);       
        unset($CSV[0]);  
        foreach($CSV as $key => $value){
            if(empty($value[1]) || empty($value[2]) || empty($value[16])){
                //return redirect($this->mainIndex)->with('alert', $this->alert('danger', '必填字段(帐号、平台、违规状态)不能为空，请重新填写!'));
                unset($CSV[$key]);
            }
        }
        
        if(count($CSV) < 1){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '请先填写数据'));
        }     
        $result = $this->model->excelInsertCoprightData($CSV);
        if($result){
            return redirect(route('copyright.index'))->with('alert', $this->alert('success', '导入侵权数据成功!'));
        }else{
            return redirect(route('copyright.index'))->with('alert', $this->alert('danger', '导入侵权数据失败'));
        }
    }
    
    /**
     * 侵权模版下载
     */
    public function downloadTemplate(){      
        $cellData  = [
            [
                '帐号' => 'M13',
                '平台' => 'smt',
                'SKU' => 'CA1226',
                '广告ID' => '',
                '投诉人' => 'AAUXX KOREA CO., LTD',
                '侵权原因' => '外观专利',
                '商标名' => '',
                '知识产权编号' => '',
                '严重度' => '',
                '违规标号' => '',
                '违规大类' => '',
                '违规小类' => '',
                '分值' => '',
                '违规生效时间' => '',
                '违规失效时间' => '',
                '违规状态' => '有效',
                '销售' => '',
                '备注' => '',
                '联系人' => '',
                '电话' => '',
                '邮箱' => '',      
            ]
        ];
        /*$cellData  = [
            [
              '帐号','平台','SKU','广告ID','投诉人','侵权原因','商标名','知识产权编号','严重度','违规标号','违规大类','违规小类','分值','违规生效时间','违规失效时间','违规状态','销售','备注','联系人','电话','邮箱'
            ],
        ];*/ 
        $sheetName = 'copyrightTemplate';
        $this->exportExcel($cellData, '侵权数据模版');
    }
    
    /**
     * 导出到xls处理
     * @param array $rows 导出的数据数组
     * @param string $name 导出文件名
     */
    public function exportExcel($rows,$name){
        ob_end_clean();
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('copyright_data', function($sheet) use ($rows){
                $sheet->setWidth(array(
                    'A'     =>  10            
                ));
                $sheet->fromArray($rows);
                
            });
        })->download('xls');
    }
    
    /**
     * 导出部分侵权数据到excel
     */
    public function exportPartData(){
       $copyright_ids = request()->input('copyright_ids'); 
       $copyright_id_arr = explode(',', $copyright_ids);
       $cellData = $this->model->exportPartData($copyright_id_arr);
       $this->exportExcel($cellData, '侵权数据列表' );
    }
    
    /**
     * 导出全部数据到excel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportAllData(){
        $cellData = $this->model->getAll();        
        if($cellData){  
             $this->exportExcel($cellData, '侵权数据列表');
        }else{
            return redirect(route('copyright.index'))->with('alert', $this->alert('danger', '没有相关侵权数据!'));
        }                    
    }
    
    /**
     * 批量删除侵权数据
     */
    public function deletePartData(){
        $copyright_ids = request()->input('copyright_ids');
        $copyright_id_arr = explode(',', $copyright_ids);
        $this->model->whereIn('id', $copyright_id_arr)->delete();
        return redirect(route('copyright.index'))->with('alert', $this->alert('success', '删除成功!'));
    }
    
    public function getAllAccountByPlatID(){
        $plat = request()->input('plat');
        $token_info = array();
        if($plat == 6){  //速卖通
            $channel_id = ChannelModel::where('driver','aliexpress')->first()->id;                         
        }elseif ($plat == 13){ //wish
            $channel_id = ChannelModel::where('driver','wish')->first()->id;                        
        }else{
            $channel_id = ChannelModel::where('driver','ebay')->first()->id;
        }
        $account_arr = AccountModel::where('channel_id',$channel_id)->get();
        foreach($account_arr as $account){
            $token_info[$account->id] = $account->alias;
        }
        $result =  array('data'=>$token_info,'info'=>'','status'=>1);     
        exit(json_encode($result));
    }
    
}
