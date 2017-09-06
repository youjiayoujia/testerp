<?php
namespace App\Models\Product;
use App\Base\BaseModel;

class ProductEnglishValueModel extends BaseModel
{
    protected $table = 'product_english_values';
    protected $fillable = [
            'product_id','unedit_reason','sale_usd_price','market_usd_price','cost_usd_price',
            'choies_set',
            'name',
            'baoguan_name',
            'attribute_size',
            'store',
            'brief',
            'description',
            'filter_attributes',
            'weight'
    ];
    
}
