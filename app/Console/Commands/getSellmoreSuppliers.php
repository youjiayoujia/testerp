<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product\SupplierModel;
use Illuminate\Support\Facades\Storage;
use App\Models\Product\SupplierAttachmentModel;
use Tool;


class getSellmoreSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suppliers:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取旧系统的供应商数据同步到新系统';

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
        $count = 0;
        for($i = 1; $i>0; $i++){

            $this->comment('start page #'.$i);

            $url = 'http://v2.erp.moonarstore.com/admin/auto/getSuppliers/getSuppliersData?key=SLME5201314&page='.$i;
            $data = json_decode($this->getCurlData($url));
            if(empty($data) || count($data) == 0){
                break;
            }else{
                foreach ($data as $value){

                    if ($value->suppliers_id <= 26034)
                        continue;

                    if(!empty(SupplierModel::find($value->suppliers_id))){
                        continue;
                    }
                    $pay_type = $value->pay_method;
                    if(!empty($value->attachment_url)){
                        $img_src      = 'http://erp.moonarstore.com'.substr($value->attachment_url,1);
                        $content      = file_get_contents($img_src);
                        $suffix       = strstr(substr($value->attachment_url,1),'.');
                        $filename     = Tool::randString(16,false);
                        $uploads_file = '/supplier/'.$filename.$suffix;

                        Storage::put($uploads_file,$content);
                        $qualifications = $filename.$suffix;

                        $filename = $qualifications;
                        $supplier_id = $value->suppliers_id;
                        SupplierAttachmentModel::create(compact('supplier_id', 'filename'));
                    }else{
                        $qualifications = '';
                    }
                    $insert = [
                        'id'              => $value->suppliers_id,
                        'name'            => $value->suppliers_company,
                        'address'         => $value->suppliers_address,
                        'company'         => $value->suppliers_company,
                        'contact_name'    => $value->suppliers_name,
                        'telephone'       => $value->suppliers_mobile,
                        'official_url'    => $value->suppliers_website,
                        'qq'              => $value->suppliers_qq,
                        'wangwang'        => $value->suppliers_wangwang,
                        'bank_account'    => $value->suppliers_bank,
                        'bank_code'       => $value->suppliers_card_number,
                        'examine_status'  => $value->suppliers_status,
                        'purchase_time'   => $value->supplierArrivalMinDays,
                        'created_by'      => $value->user_id,
                        'pay_type'        => isset(config('product.sellmore.pay_type')[$pay_type]) ? config('product.sellmore.pay_type')[$pay_type] : 'OTHER_PAY',
                        //'qualifications'  => $qualifications
                    ];

                    if(!empty($insert)){

                        SupplierModel::create($insert);
                        $this->info($value->suppliers_id.' insert success');
                        $count += 1;
                    }
                }
            }
        }
        $this->comment('the end, total #'.$count);

    }

    /**
     * Curl http Get 数据
     * 使用方法：
     */
    public function getCurlData($remote_server)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $output;
    }
}
