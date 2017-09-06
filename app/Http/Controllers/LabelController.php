<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;

use App\Models\LabelModel;

class LabelController extends Controller
{
    public function __construct(LabelModel $label)
    {
        $this->model = $label;
        $this->mainIndex = route('label.index');
        $this->mainTitle = '图片标签';
        $this->viewPath = 'label.';
        
    }

    

}