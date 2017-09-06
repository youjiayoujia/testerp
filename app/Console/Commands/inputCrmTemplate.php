<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Message\Template\TypeModel;
use App\Models\Message\TemplateModel;

class inputCrmTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inputCrmTemplate:insert';

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
        $sql  = 'SELECT a.*,b.* FROM erp_email_mod_class as a INNER JOIN erp_email_mod as b 
                 ON a.modClassID = b.modClassID WHERE b.modEnable = 1';
        $data = DB::select($sql);
        $templates = '';
        foreach($data as $row){
            $templates[$row->platform][$row->modClassName][] =
                        [
                            'title'   => $row->modTitle,
                            'content' => $row->modContent,
                        ];

        }

        foreach($templates as $key => $template){
            //获取或者创建一级分类
            $type = TypeModel::where('parent_id','=',0)->where('name', $key)->first();
            if(empty($type)){ //创建
                $type = new TypeModel;
                $type->parent_id = 0;
                $type->name      = $key;
                $type->save();
            }
            foreach($template as $category => $items){
                //获取或者创建二级分类
                $sec_type = TypeModel::where('parent_id',$type->id)->where('name',$category)->first();
                if(empty($sec_type)){
                    $sec_type = new TypeModel;
                    $sec_type->parent_id = $type->id;
                    $sec_type->name      = $category;
                    $sec_type->save();
                }
                foreach($items as $item){
                    if(!empty($item['title']) && !empty($item['content'])){

                        $template = TemplateModel::firstOrNew(['name' => $item['title']]);
                        $template->content = $item['content'];
                        $template->type_id = $sec_type->id;
                        $template->save();
                        $this->info('#type:'.$key.'->'.$category.'->'.$item['title']);
                    }
                }

            }
        }
    }
}
