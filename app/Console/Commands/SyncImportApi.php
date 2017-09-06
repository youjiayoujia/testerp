<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ImportSyncApiModel;
use App\Models\Product\SupplierModel;
use Illuminate\Support\Facades\Storage;
use Tool;

class SyncImportApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncImportApi:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $datas = ImportSyncApiModel::where('status',0)->get(); //获取未同步记录
        if(!$datas->isEmpty()){
            foreach ($datas as $data){

                $form_ary = unserialize($data->data);
                if(!empty($data->type)){
                    /**
                     * 根据type 写入对应的表
                     */
                    switch ($data->type){
                        case 'supplier':
                            $res = $this->SnycImportSupplier($form_ary);
                            if($res){
                                $data->status = 1;
                                $data->times  = $data->times +1;
                                $data->save();
                                $this->info('#'.$data->relations_id.'has to sync v3 databsae');
                            }else{
                                $data->status = 0;
                                $data->times  = $data->times +1;
                                $data->error_msg = $form_ary['type'] . '失败';
                                $data->save();
                                $this->comment('#'.$data->relations_id.'has not to sync v3 database');
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }
    public function SnycImportSupplier($form_ary){
        $is_change = false;

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
        }


        return $is_change;
    }
}
