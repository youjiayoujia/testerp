<?php

namespace App\Models;

use Exception;
use App\Models\ItemModel;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\CountriesModel;

class CustomsClearanceModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customes_clearances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'cn_name', 'hs_code', 'unit', 'f_model', 'status', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['model'];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id', 'id');
    }

    /**
     * 整体流程处理excel
     *
     * @param $file 文件指针 
     *
     */
    public function excelProcess($file, $name)
    {
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->$name($path.'excelProcess.xls');
    }

    public function exportProductFed($arr)
    {
        return $this->exportProductBase($arr, 'FedEx');
    }

    public function exportProductEUB($arr)
    {
        return $this->exportProductBase($arr, 'EUB');
    }

    public function exportProductZY($arr)
    {
        return $this->exportProductBase($arr, '中邮');
    }

    public function exportProductBase($arr, $name)
    {
        $rows = [];
        foreach($arr as $id)
        {
            $package = PackageModel::find($id);
            if(!$package) {
                throw new Exception('package ID为'.$id.'不存在');
            }
            $order = $package->order;
            if(!$order) {
                throw new Exception('package ID为'.$id.'的order不存在');
            }
            $logistics = $package->logistics;
            if(!$logistics) {
                throw new Exception('package ID为'.$id.'的物流不存在');
            }
            $packageitems = $package->items;
            foreach($packageitems as $packageitem) 
            {
                $item = $packageitem->item;
                if(!$item) {
                    throw new Exception('package ID为'.$id.'的packageitem有问题');
                }
                $model = $item->product ? $item->product->clearance : '';
                if(!$model) {
                    throw new Exception('package ID为'.$id.'的3宝item有问题');
                }
                if(!array_key_exists($model->id, $rows)) {
                    $rows[$model->id] = [
                        '原始订单号' =>  $order->id,
                        '进出口标志' => 'E',
                        '物流企业代码' => $logistics->code == $name ? $logistics->id : '!该对应的package对应的物流不是'.$name,
                        '物流企业运单号' => $package->tracking_no,
                        '订单商品货款' => $order->amount,
                        '商品订单运费' => $order->amount_shipping,
                        '订单税款总额' => '',
                        '收货人名称' => $order->shipping_lastname.' '.$order->shipping_firstname,
                        '收货人地址' => $order->shipping_address,
                        '收货人电话' => $order->shipping_phone,
                        '收货人国家' => $order->shipping_country,
                        '企业商品货号' => $packageitem->item ? $packageitem->item->product ? $packageitem->item->product->model : '' : '',
                        '商品数量' => $packageitem->quantity,
                        '计量单位' => $model->unit,
                        '币制代码' => '502',
                        '商品总价' => round($packageitem->quantity * $item->cost, 2),
                    ];
                    continue;
                }
                $rows[$model->id]['商品数量'] += $packageitem->quantity;
                $rows[$model->id]['商品总价'] += round($packageitem->quantity * $item->cost, 2);
            }
        }

        return $rows;
    }

    public function exportFailModel()
    {
        $clearances = $this->where('status', '0')->get();
        $rows = '';
        foreach($clearances as $model)
        {
            $rows[] = [
                    'model' => ($model->product ? $model->product->model : $model->product_id),
                    'cn_name' => $model->cn_name,
                    'hs_code' => $model->hs_code,
                    'unit' => $model->unit,
                    'f_model' => $model->f_model,
                    'status' => $model->status
                ];
        }

        return $rows;
    }

    public function exportNXB($arr)
    {
        return $this->exportLogisticsState($arr, 'NXB');
    }

    public function exportEUB($arr)
    {
        return $this->exportLogisticsState($arr, 'EUB');
    }

    public function exportEUBWeight($arr)
    {
        $rows = '';
        foreach($arr as $id)
        {
            $package = PackageModel::find($id);
            if(!$package) {
                throw new Exception('package ID为'.$id.'不存在');
            }
            $logistics = $package->logistics;
            if(!$logistics || $logistics->code != 'EUB') {
                throw new Exception('package ID为'.$id.'对应的物流有误');
            }
            $rows[] = [
                'tracking_no' => $package->tracking_no,
                'shipping_country' => $package->shipping_country,
                'weight' => $package->weight,
            ];
        }

        return $rows;
    }

    public function exportLogisticsState($arr, $short_code)
    {
        $rows = '';
        foreach($arr as $id)
        {
            $package = PackageModel::find($id);
            if(!$package) {
                $rows[] = [
                    '邮件号码' =>'package  ID为'.$id.'对应的package不存在',
                    '寄达局名称' => '',
                    '邮件重量' => '',
                    '单位重量' => '',
                    '寄达局邮编' => '',
                    '英文国家名' => '',
                    '英文州名' => '',
                    '英文城市名' => '',
                    '收件人姓名' => '',
                    '收件人地址' => '',
                    '收件人电话' => '',
                    '寄件人姓名' => '',
                    '寄件人省名' => '',
                    '寄件人城市名' => '',
                    '寄件人地址' => '',
                    '寄件人电话' => '',
                    '内件类型代码' => '',
                    '内件名称' => '',
                    '内件英文名称' =>'',
                    '内件数量' => '',
                    '单位' => '',
                    '产地' => '',
                ];
                continue;
            }
            $logistics = $package->logistics;
            if(!$logistics || $logistics->code != $short_code) {
                throw new Exception('package ID为'.$id.'对应的物流有误');
            }
            $order = $package->order;
            if(!$order) {
                throw new Exception('package ID为'.$id.'对应的order不存在');
            }
            $country = CountriesModel::where('code', $order->shipping_country)->first();
            if(!$country) {
                throw new Exception('对应的国家不存在');
            }
            $packageitem = $package->items->first();
            if(!$packageitem) {
                throw new Exception('package ID为'.$id.'对应的item不存在');
            }
            $item = $packageitem->item;
            if(!$item) {
                throw new Exception('package ID为'.$id.'的packageitem中对应的item不存在');
            }
            $rows[] = [
                    '邮件号码' =>$package->tracking_no,
                    '寄达局名称' => $country ? $country->cn_name : $order->shipping_country,
                    '邮件重量' => $package->weight,
                    '单位重量' => $package->weight,
                    '寄达局邮编' => $order->shipping_zipcode,
                    '英文国家名' => $country ? $country->name : $order->shipping_country,
                    '英文州名' => $order->shipping_country,
                    '英文城市名' => $order->shipping_state,
                    '收件人姓名' => $order->shipping_lastname.' '.$order->shipping_first,
                    '收件人地址' => $order->shipping_address,
                    '收件人电话' => $order->shipping_phone,
                    '寄件人姓名' => $package->shipping_first.' '.$package->shipping_lastname,
                    '寄件人省名' => $package->shipping_state,
                    '寄件人城市名' => $package->shipping_city,
                    '寄件人地址' => $package->shipping_address,
                    '寄件人电话' => $package->shipping_phone,
                    '内件类型代码' => '1',
                    '内件名称' => $item->name,
                    '内件英文名称' =>$item->c_name,
                    '内件数量' => $packageitem->quantity,
                    '单价' => $item->cost,
                    '产地' => 'CN',
                ];
        }

        return $rows;
    }

    public function exportFailItem()
    {
        $items = ItemModel::where('is_sale', '1')->get();
        $rows = '';
        foreach($items as $item)
        {
            $product = $item->product;
            if(!$product) {
                throw new Exception("item  id为".$item->id."的item 对应的Model不存在");
            }
            if(!$product->clearance) {
                $rows[] = [
                    'item' => $item->id,
                    'model' => $product->model,
                    'hs_code' => '对应的Model未备案',
                    'f_model' => '',
                ];
                continue;
            }
            $clearance = $product->clearance;
            if(!$clearance->hs_code || !$clearance->f_model) {
                $rows[] = [
                    'item' => $item->id,
                    'model' => $product->model,
                    'hs_code' => $clearance->hs_code,
                    'f_model' => $clearance->f_model,
                ];
            } 
        }
        
        return $rows;
    }

    public function exportProduct($arr)
    {
        $rows = '';
        foreach($arr as $val)
        {
            $product = ProductModel::where('model', trim($val))->first();
            $tmp_clearance = '';
            if($product) {
                $tmp_clearance = $product->clearance;
            }
            if(!$tmp_clearance) {
                $rows[] = [
                        '企业商品货号'=>$val,
                        '商品上架品名'=>'该Model不存在',
                        '商品名称'=>'',
                        '规格型号'=>'',
                        '商品编码(HS编码)选填'=>'',
                        '第一计量单位'=>'',
                        '第二计量单位'=>'',
                        '备案价格'=>'',
                        '币制'=>'',
                        '品牌'=>'',
                        '海关行邮税编码'=>'',
                        '产品链接'=>'',
                ];
                continue;
            }
            $arr = explode('/', $tmp_clearance->unit);
            $rows[] = [
                         '企业商品货号'=>($tmp_clearance->product ? $tmp_clearance->product->model : ''),
                         '商品上架品名'=>($tmp_clearance->product ? $tmp_clearance->product->c_name : ''),
                         '商品名称'=>$tmp_clearance->cn_name,
                         '规格型号'=>$tmp_clearance->f_model,
                         '商品编码(HS编码)选填'=>$tmp_clearance->hs_code,
                         '第一计量单位'=>($arr[0] ? '^'.$arr[0] : ''),
                         '第二计量单位'=>(array_key_exists('1', $arr) ? '^'.$arr[1] : ''),
                         '备案价格'=>'',
                         '币制'=>'502',
                         '品牌'=>'choice',
                         '海关行邮税编码'=>'',
                         '产品链接'=>($tmp_clearance->product ? $tmp_clearance->product->product_sale_url : ''),
                ];
        }

        return $rows;
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelNumberProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $clearance)
        {
            $clearance['code'] = iconv('gb2312','utf-8',$clearance['code']);
            $clearance['number'] = iconv('gb2312','utf-8',$clearance['number']);
            if(!CountriesModel::where(['code' => $clearance['code']])->count()) {
                $error[] = $key;
                continue;
            }
            $model = CountriesModel::where('code', $clearance['code'])->first();
            $model->update(['number' => $clearance['number']]);
        }

        return $error;
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelNanjingProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $clearance)
        {
            $clearance['package_id'] = iconv('gb2312','utf-8',$clearance['package_id']);
            $clearance['is_tonanjing'] = iconv('gb2312','utf-8',$clearance['is_tonanjing']);
            if(!PackageModel::where(['id' => $clearance['package_id']])->count()) {
                $error[] = $key;
                continue;
            }
            $model = PackageModel::where(['id' => $clearance['package_id']])->first();
            if($clearance['is_tonanjing'] == $model->is_tonanjing) {
                $error[] = $key;
                continue;
            }
            $model->update(['is_tonanjing' => $clearance['is_tonanjing'], 'is_over' => '0']);
        }

        return $error;
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelOverProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $clearance)
        {
            $clearance['package_id'] = iconv('gb2312','utf-8',$clearance['package_id']);
            $clearance['is_over'] = iconv('gb2312','utf-8',$clearance['is_over']);
            if(!PackageModel::where(['id' => $clearance['package_id']])->count()) {
                $error[] = $key;
                continue;
            }
            $model = PackageModel::where(['id' => $clearance['package_id']])->first();
            if($clearance['is_over'] == $model->is_over) {
                $error[] = $key;
                continue;
            }
            $model->update(['is_over' => $clearance['is_over']]);
        }

        return $error;
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelUpdateProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $clearance)
        {
            $clearance['model'] = iconv('gb2312','utf-8',$clearance['model']);
            $clearance['cn_name'] = iconv('gb2312','utf-8',$clearance['cn_name']);
            $clearance['hs_code'] = iconv('gb2312','utf-8',$clearance['hs_code']);
            $clearance['unit'] = iconv('gb2312','utf-8',str_replace('^', '', $clearance['unit']));
            $clearance['f_model'] = iconv('gb2312','utf-8',$clearance['f_model']);
            $clearance['status'] = iconv('gb2312','utf-8',$clearance['status']);
            if(!ProductModel::where(['model' => $clearance['model']])->count()) {
                $error[] = $key;
                continue;
            }
            $product = ProductModel::where(['model' => $clearance['model']])->first();
            $clearance['product_id'] = $product->id;
            if(!$this->where('product_id', $clearance['product_id'])->count()) {
                $error[] = $key;
                continue;
            }
            $model = $this->where('product_id', $clearance['product_id'])->first();
            $model->update($clearance);
        }

        return $error;
    }

    /**
     * 处理excel数据
     *
     * @param $path excel文件路径
     *
     */
    public function excelDataProcess($path)
    {
        $fd = fopen($path, 'r');
        $arr = [];
        while(!feof($fd))
        {
            $row = fgetcsv($fd);
            $arr[] = $row;
        }
        fclose($fd);
        if(!$arr[count($arr)-1]) {
            unset($arr[count($arr)-1]);
        }
        $arr = $this->transfer_arr($arr);
        $error[] = $arr;
        foreach($arr as $key=> $clearance)
        {
            $clearance['model'] = iconv('gb2312','utf-8',$clearance['model']);
            $clearance['cn_name'] = iconv('gb2312','utf-8',$clearance['cn_name']);
            $clearance['hs_code'] = iconv('gb2312','utf-8',$clearance['hs_code']);
            $clearance['unit'] = iconv('gb2312','utf-8',str_replace('^', '', $clearance['unit']));
            $clearance['f_model'] = iconv('gb2312','utf-8',$clearance['f_model']);
            if(!ProductModel::where(['model' => $clearance['model'], 'examine_status' => 'pass'])->count()) {
                $error[] = $key;
                continue;
            }
            $product = ProductModel::where(['model' => $clearance['model']])->first();
            $clearance['product_id'] = $product->id;
            if($this->where('product_id', $clearance['product_id'])->count()) {
                $error[] = $key;
                continue;
            }
            $this->create($clearance);
        }

        return $error;
    }

    public function transfer_arr($arr)
    {
        $buf = [];
        foreach($arr as $key => $value)
        {
            $tmp = [];
            if($key != 0) {
                foreach($value as $k => $v)
                {
                    $tmp[$arr[0][$k]] = $v;
                }
            $buf[] = $tmp;
            }
        }

        return $buf;
    }
}
