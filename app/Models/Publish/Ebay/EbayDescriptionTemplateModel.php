<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-13
 * Time: 14:44
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbaySiteModel;
class EbayDescriptionTemplateModel extends BaseModel
{
    protected $table = 'ebay_description_template';
    protected $fillable = [
        'name',
        'site',
        'warehouse',
        'description',
    ];

    public $searchFields = ['name'=>'模板名称'];

    protected $rules = [
        'create' => [
        ],
        'update' => []
    ];


    public function getMixedSearchAttribute()
    {
        $ebaySite = new EbaySiteModel();
        return [
            'filterSelects' => [
                'site' => $ebaySite->getSite('site_id'),
                'warehouse' => config('ebaysite.warehouse')
            ]
        ];
    }

    public function getLastDescription($id, $picture, $tittle, $content)
    {
        $description = $this->where('id', $id)->first()->description;
        $content = htmlspecialchars_decode($content);
        $template_picture = '';
           if ($description) {
               $description = htmlspecialchars_decode($description);
               $description =  str_replace('{{tittle}}', $tittle, $description); //替换标题
               $description =  str_replace('{{description}}', $content, $description); //替换标题

               if(!empty($picture)){
                   foreach($picture as $pic){
                       $template_picture .= '<div style="margin-bottom:20px;"><img width="800px" height="800px" src="'.$pic.'"></div>';
                   }
               }

               $description = str_replace('{{picture}}',$template_picture, $description);
           }else{
               if(!empty($picture)){
                   foreach($picture as $pic){
                       $template_picture .= '<div style="margin-bottom:20px;"><img width="800px" height="800px" src="'.$pic.'"></div>';
                   }
               }
               $description  = $tittle.$template_picture.$content;
           }

        return $description;
    }

}