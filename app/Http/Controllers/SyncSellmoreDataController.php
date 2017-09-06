<?php
/**
 * 同步sellmore 旧系统数据到  v3erp
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/29
 * Time: 10:32
 */
namespace App\Http\Controllers;

use App\Models\Product\SupplierModel;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportSyncApiModel;
use Tool;

class SyncSellmoreDataController extends Controller
{

    public function __construct(ImportSyncApiModel $syncApi)
    {
        $this->model = $syncApi;
        $this->mainIndex = route('importSyncApi.index');
        $this->mainTitle = '接收接口';
        $this->viewPath = 'importSyncApi.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SyncSuppliersFromSell()
    {
        //
        $is_change = false;
        $form_ary = request()->all();
        if(empty($form_ary['secretKey'])){
            return json_encode(['status' => 'fail']);
        }
        if($form_ary['secretKey'] == 'VSxtAts2fQlTLc1KCLaM'){
            $importSync = new ImportSyncApiModel;

            $importSync->relations_id = $form_ary['suppliers_id'];
            $importSync->type         = 'supplier';
            $importSync->route        = 'api/SyncSellmoreData';
            $importSync->data         = serialize($form_ary);
            $importSync->status       = 0;
            $importSync->times        = 0;
            $is_save                  = $importSync->save();
            if($is_save){
                return json_encode(['status' => 'success']);

            }else{
                return json_encode(['status' => 'fail']);
            }
/*
            switch ($form_ary['type']){
                case 'add':
                    $model_data['id']             =  $form_ary['suppliers_id'];
                    $model_data['company']        =  $form_ary['suppliers_company'];
                    $model_data['official_url']   =  $form_ary['suppliers_website'];
                    $model_data['address']        =  $form_ary['suppliers_address'];
                    $model_data['type']           =  $form_ary['suppliers_type'];
                    $model_data['purchase_time']  =  $form_ary['supplierArrivalMinDays'];
                    $model_data['bank_account']   =  $form_ary['suppliers_bank'];
                    $model_data['bank_code']      =  $form_ary['suppliers_card_number'];
                    $model_data['contact_name']   =  $form_ary['suppliers_name'];
                    $model_data['telephone']      =  $form_ary['suppliers_mobile'];
                    $model_data['wangwang']       =  $form_ary['suppliers_wangwang'];
                    $model_data['qq']             =  $form_ary['suppliers_qq'];
                    $model_data['pay_type']       =  isset(config('product.sellmore.pay_type')[$form_ary['pay_method']]) ? config('product.sellmore.pay_type')[$form_ary['pay_method']] : 'OTHER_PAY';


                    //$model_data['qualifications'] =  $form_ary['attachment_url'];

                    $is_exist = SupplierModel::find($form_ary['suppliers_id']);
                    if(!empty($is_exist)){
                        $is_change = false;
                    }else{
                        if(!empty($form_ary['attachment_url'])){
                            $tmp_ary      =  explode('.',$form_ary['attachment_url']);
                            $suffix       = $tmp_ary[count($tmp_ary)-1]; //后缀
                            $content      = file_get_contents($form_ary['attachment_url']);
                            $filename     = Tool::randString(16,false);
                            $uploads_file = '/supplier/'.$filename.'.'.$suffix;
                            Storage::put($uploads_file,$content);
                            $model_data['qualifications']  = $filename.'.'.$suffix;
                        }else{
                            $model_data['qualifications'] = '';
                        }
                        $res = SupplierModel::create($model_data);
                        if(!empty($res)){
                            $is_change = true;
                        }else{
                            $is_change = false;
                        }
                    }



                    break;
                case 'update':
                    $model_data['id']             =  $form_ary['suppliers_id'];
                    $model_data['company']        =  $form_ary['suppliers_company'];
                    $model_data['official_url']   =  $form_ary['suppliers_website'];
                    $model_data['address']        =  $form_ary['suppliers_address'];
                    $model_data['type']           =  $form_ary['suppliers_type'];
                    $model_data['purchase_time']  =  $form_ary['supplierArrivalMinDays'];
                    $model_data['bank_account']   =  $form_ary['suppliers_bank'];
                    $model_data['bank_code']      =  $form_ary['suppliers_card_number'];
                    $model_data['contact_name']   =  $form_ary['suppliers_name'];
                    $model_data['telephone']      =  $form_ary['suppliers_mobile'];
                    $model_data['wangwang']       =  $form_ary['suppliers_wangwang'];
                    $model_data['qq']             =  $form_ary['suppliers_qq'];
                    $model_data['pay_type']       =  isset(config('product.sellmore.pay_type')[$form_ary['pay_method']]) ? config('product.sellmore.pay_type')[$form_ary['pay_method']] : 'OTHER_PAY';
                    unset($model_data['suppliers_id']);
                    $res = SupplierModel::find($form_ary['suppliers_id'])->update($model_data);
                    if($res == true){
                        if(!empty($form_ary['attachment_url'])){
                            $tmp_ary      =  explode('.',$form_ary['attachment_url']);
                            $suffix       = $tmp_ary[count($tmp_ary)-1]; //后缀
                            $content      = file_get_contents($form_ary['attachment_url']);
                            $filename     = Tool::randString(16,false);
                            $uploads_file = '/supplier/'.$filename.'.'.$suffix;
                            Storage::put($uploads_file,$content);
                            $model_data['qualifications']  = $filename.'.'.$suffix;
                        }else{
                            $model_data['qualifications'] = '';
                        }
                        $is_change = true;
                    }else{
                        $is_change = false;

                    }
                    break;
                default:
                    break;
            }*/
        }else{
            return json_encode(['status' => 'fail']);
        }
    }

}
