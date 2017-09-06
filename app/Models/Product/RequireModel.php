<?php

namespace App\Models\Product;

use App\Base\BaseModel;
use App\Models\CatalogModel;

class RequireModel extends BaseModel
{
	protected $table = 'product_requires';

	protected $fillable = [
            'img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'color', 'url1', 'url2', 'url3', 'material', 'technique', 'parts',
            'name', 'province', 'city', 'similar_sku', 'competition_url', 
            'remark', 'expected_date', 'needer_id', 'needer_shop_id', 
            'created_by', 'status', 'handle_id', 'handle_time', 'catalog_id','purchase_id'
            ];

    // 规则验证
    public $rules = [
        'create' => [   
                'name' => 'required|max:255|unique:product_requires,name',
                //'needer_id' => 'required',
                //'needer_shop_id' => 'required'
        ],
        'update' => [   
                'name' => 'required|max:255|unique:product_requires,name, {id}',
                //'needer_id' => 'required',
                //'needer_shop_id' => 'required',
        ]
    ];

    public function getMixedSearchAttribute()
    {
        $catalogs = CatalogModel::all();
        $arr = [];
        foreach($catalogs as $key => $single) {
            $arr[$single->id] = $single->c_name;
        }
        return [
            'relatedSearchFields' => [
            ],
            'filterFields' => ['name'],
            'filterSelects' => [
                'status' => ['0' => '新需求', '1' => '未找到', '2' => '已找到', '3' => '已创建']
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'sectionGangedDouble' => [
                'first' => ['catalog' => ['catalogCategory' => ['cn_name'=>CatalogCategoryModel::all()->pluck('cn_name', 'cn_name')]]],
                'second' => ['catalog' => ['c_name' => CatalogModel::all()->pluck('c_name', 'c_name')]]
            ],
        ];
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id');
    }

    //查询
    public $searchFields = ['name'=>'名称'];
    
    /**
     *  移动文件 
     *
     *  @param $fd 类文件指针
     *  @param $name 文件名 
     *  @param $path 路径
     *
     *  @return path
     */
    public function move_file($fd, $name, $path)
    {
        $dstname = $name.'.'.$fd->getClientOriginalExtension();
        if(file_exists($path.'/'.$dstname))
            unlink($path.'/'.$dstname);
        $fd->move($path,$dstname);

        return "/".$path."/".$dstname;
    }


    public function excelProcess($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        return $this->excelDataProcess($path.'excelProcess.xls');
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
        foreach($arr as $key=> $require)
        {
            $require = $this->returnCode($require);
            $require = array_values($require);
            $this->create(['name' => $require[0], 
                           'catalog_id' => $require[1],
                           'province' => $require[2],
                           'city' => $require[3],
                           'color' => $require[4],
                           'material' => $require[5],
                           'technique' => $require[6],
                           'parts' => $require[7],
                           'similar_sku' => $require[8],
                           'competition_url' => $require[9],
                           'remark' => $require[10],
                           'expected_date' => $require[11],
                           'purchase_id' => $require[12],
                           'url1' => $require[13],
                           'url2' => $require[14],
                           'url3' => $require[15],
                           ]);
        }
    }

    public function returnCode($arr)
    {
        foreach($arr as $key => $value) {
            $arr[$key] = iconv('gb2312','utf-8',$value);
        }

        return $arr;
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

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function createdByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'created_by', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function userName()
    {
        return $this->belongsTo('App\Models\UserModel', 'handle_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_id', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function neederName()
    {
        return $this->belongsTo('App\Models\UserModel', 'needer_id', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function catalogByName()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'needer_id', 'id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'needer_shop_id', 'id');
    }
}