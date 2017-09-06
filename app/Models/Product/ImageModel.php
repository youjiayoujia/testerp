<?php
/**
 * 图片模型
 *
 * 2016-01-04
 * @author tup<836466300@qq.com>
 */

namespace App\Models\product;

use Storage;
use App\Base\BaseModel;
use Tool;
use Zipper;

class ImageModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['spu_id', 'product_id', 'type', 'path', 'name'];

    public $searchFields = ['id'=>'id'];

    public $rules = [
        'create' => [
            //'model' => 'required',
            //'type' => 'required',
            //'image0' => 'required',
        ],
        'update' => [],
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => ['spu_id'],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    public function getSrcAttribute()
    {
        return $this->path . $this->name;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id','id');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\LabelModel','image_labels','image_id','label_id')->withTimestamps();
    }

    /**
     * 创建图片(单张)
     *
     * @param $data ['spu_id','product_id','type']
     * @param $files
     * @param string $uploadType
     */
    public function singleCreate($data, $file = null, $key)
    {
        if (!array_key_exists('type', $data)) {
            $data['type'] = 'original';
        }
        if ($data['type'] != 'public') {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['type'] . '/';
        } else {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
        }
        if ($this->valid($file->getClientOriginalName())) {
            $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($file->getRealPath()));
            $imageModel = $this->create($data);
            return $imageModel->id;
        }
    }

    //todo: file size, real mime
    private function valid($fileName)
    {
        $extension = Tool::getFileExtension($fileName);
        return in_array($extension, config('product.image.extensions'));
    }

    /**
     * 创建图片
     *
     * @param $data ['spu_id','product_id','type']
     * @param $files
     * @param string $uploadType
     */
    public function imageCreate($data, $files = null)
    {
        $buf = [];
        $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['is_link'] . '/';
        $disk = Storage::disk('product');
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key => $file) {
                    if ($this->valid($file->getClientOriginalName())) {
                        $buf1 = explode(' ', microtime());
                        $tmp = (float)$buf1[1] + (float)$buf1[0];
                        $data['name'] = $tmp . '.' . $file->getClientOriginalExtension();
                        $buf['filename'] = $data['name'];
                        $buf['tags'] = $this->tagEng($data['tag']);
                        $times = explode(' ', microtime());
                        $buf['lastUpdated']['sec'] = $times['1'];
                        $buf['lastUpdated']['usec'] = substr($times['0'],2,6);
                        Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($file->getRealPath()));
                        $imageModel = $this->create($data);
                        $arr[] = $data['is_link'];
                        $imageModel->labels()->attach($arr);
                        if($data['tag']!=''){
                            $imageModel->labels()->attach($data['tag']);
                        }
                    }
                }
                break;
            case 'zip':
                foreach ($files as $file) {
                    Tool::dir($data['path']);
                    $zipper = Zipper::make($file->getRealPath());
                    $zipFiles = $zipper->listFiles();
                    foreach ($zipFiles as $key => $name) {
                        if ($this->valid($name)) {
                            $data['name'] = time() . $key . '.' . Tool::getFileExtension($name);
                            file_put_contents($data['path'] . $data['name'], $zipper->getFileContent($name));
                            $this->create($data);
                        }
                    }
                }
                break;
        }
        
        return json_encode($buf);
    }

    public function tagEng($arr)
    {
        $buf = [];
        foreach($arr as $key => $value) {
            switch($value) {
                case 1:
                    $buf[] = 'photo';
                    break;
                case 2:
                    $buf[] = 'link';
                    break;
                case 3:
                    $buf[] = 'normal';
                    break;
                case 4:
                    $buf[] = 'shape';
                    break;
                case 5:
                    $buf[] = 'front';
                    break;
                case 6:
                    $buf[] = 'logo';
                    break;
                case 7:
                    $buf[] = 'color';
                    break;
                case 8:
                    $buf[] = 'size';
                    break;
            }
        }

        return $buf;
    }

    /**
     * 创建图片
     *
     * @param $data ['spu_id','product_id','type']
     * @param $files
     * @param string $uploadType
     */
    public function skuMessageImage($file = null)
    {
        $data = [];
        $data['path'] = config('product.question.uploadPath') . '/';
        $disk = Storage::disk('product');
        if ($this->valid($file->getClientOriginalName())) {
            $data['name'] = time() . rand(1,100). '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($file->getRealPath())); 
        }
        return $data['path'].$data['name'];      
    }

    /**
     * 更新图片
     *
     * @param $id
     * @param $data
     * @param $file
     * @return mixed
     * @throws FileException
     */
    public function updateImage($id, $file,$data)
    {
        $image = $this->findOrFail($id);
        $arr[] = $data['is_link'];
        foreach($data['image_type'] as $data){
            $arr[] = $data;
        }
        
        $image->labels()->sync($arr);
        return;
    }

    /**
     * 删除图片
     *
     * @param int $id
     * @return mixed
     */
    public function imageDestroy($id)
    {
        $image = $this->findOrFail($id);
        if (is_file($image->src)) {
            unlink($image->src);
        }

        return $this->destroy($id);
    }

}
