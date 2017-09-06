<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtProductDetail extends BaseModel
{
    protected $table = 'smt_product_detail';

    protected $fillable = [
        'productId',
        'aeopAeProductPropertys',
        'imageURLs',
        'detail',
        'keyword',
        'productMoreKeywords1',
        'productMoreKeywords2',
        'productUnit',
        'isImageDynamic',
        'isImageWatermark',
        'lotNum',
        'bulkOrder',
        'packageType',
        'isPackSell',
        'bulkDiscount',
        'promiseTemplateId',
        'freightTemplateId',
        'templateId',
        'shouhouId',
        'detail_title',
        'sizechartId',
        'src',
        'detailPicList',
        'detailLocal',
        'relationProductIds',
        'relationLocation'
    ];
    
    protected $searchFields = [];
    
    protected $rules = [];
}
