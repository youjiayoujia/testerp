<?php
namespace App\Models\Publish\Smt;
use Illuminate\Database\Eloquent\Model;
class smtCategoryModel extends Model{
    protected $table = "smt_category_list";
    
    protected $fileable = ['category_id','category_name','pid','level','isleaf'];
    
    public $name = array();
    
    /**
     * 获取子类及所有父类的中文名称
     * @param  $category_id [description]
     * @return  array
     */
    public function getCateroryAndParentName($category_id){  
        $this->name = array();
        $this->getCategoryPid($category_id);   
        $category = $this->name;
        $rs = array_reverse($category); 
        return implode('>>', $rs);
    }
    
    
    /**
     * 根据子类ID递归的获取父类的中文名称
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function getCategoryPid($category_id){
        $tmp = $this->where('category_id','=',$category_id)->get(); 
        foreach ($tmp as $item){           
            array_push($this->name, $item['category_name']);
            if ($item['pid'] > 0){               
                $this->getCategoryPid($item['pid']);
            }
        } 
    }
       
}