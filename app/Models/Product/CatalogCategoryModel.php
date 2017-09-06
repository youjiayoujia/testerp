<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/30
 * Time: 11:08
 */
namespace App\Models\product;
use App\Base\BaseModel;
use Tool;
class CatalogCategoryModel extends BaseModel{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'catalog_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['id','cn_name','en_name'];
    public $searchFields = ['cn_name'  => '中文名称','en_name' => '英文名称'];

    protected $rules = [
        'create' => [
            'cn_name' => 'required|unique:catalog_category,cn_name',
            'en_name' => 'required|unique:catalog_category,en_name',
        ],
        'update' => [
            'cn_name' => 'required|unique:catalog_category,cn_name',
            'en_name' => 'required|unique:catalog_category,en_name',
        ]
    ];

    public function catalogs()
    {
        return $this->hasMany('App\Models\CatalogModel', 'catalog_category_id', 'id');
    }

}