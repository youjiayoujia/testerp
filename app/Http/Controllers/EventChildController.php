<?php
/**
 * 汇率控制器
 * 处理汇率相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event\ChildModel;
use App\Models\Event\CategoryModel;

class EventChildController extends Controller
{
    public function __construct(ChildModel $child)
    {
        $this->model = $child;
        $this->mainIndex = route('eventChild.index');
        $this->mainTitle = '事件记录';
        $this->viewPath = 'event.child.';
    }

    public function stdClass_to_array($object)
    {
        $arr = (array)$object;
        foreach ($arr as $key => $value) {
            if (is_object($value)) {
                $arr[$key] = $this->stdClass_to_array($value);
                continue;
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_object($v)) {
                        $value[$k] = $this->stdClass_to_array($v);
                    }
                }
            }
            $arr[$key] = $value;
        }

        return $arr;
    }

    /**
     *  返回对应的操作日志,ajax请求
     *
     * @param none
     * @return html
     *
     */
    public function getInfo()
    {
        $table = request('table');
        $id = request('id');
        $rate = request('rate');
        $next_rate = (int)$rate + 1;
        $len = 20;
        $start = (int)$rate * $len;
        $category = CategoryModel::where('model_name', $table)->first();
        if (!$category) {
            return false;
        }
        $models = $category->child()->where('type_id', $id)->orderBy('when', 'desc')->skip($start)->take($len)->skip($start)->get();
        $html = '';
        foreach ($models as $key1 => $model) {
            $to = json_decode($model->to_arr);
            if ($to) {
                $to = $this->stdClass_to_array($to);
            }
            $from = json_decode($model->from_arr);
            if ($from) {
                $from = $this->stdClass_to_array($from);
            }
            if (!$from) {
                $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:" . $model->what . '<br/>操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:" . $model->who . "
                    <a class='btn btn-xs btn-primary' role='button' data-toggle='collapse' href='#collapseExample" . $rate . $key1 . "' aria-expanded='false' aria-controls='collapseExample'>数据详情</a>
                        </div>
                        <div class='panel-body collapse' id='collapseExample" . $rate . $key1 . "'><div class='col-lg-12'>";
                if($to) {
                    foreach ($to as $key => $value) {
                        $html .= "<div class='row'>to['" . $key . "']<span class='glyphicon glyphicon-arrow-right'></span>";
                        if (is_array($value)) {
                            if (count($value) == count($value, 1)) {
                                $html .= "<div class='col-lg-12'><div class='row'>";
                                foreach ($value as $k => $v) {
                                    if (!is_array($v)) {
                                        $html .= "to['" . $key . "']['" . $k . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                    }
                                }
                                $html .= '</div></div>';
                            } else {
                                foreach ($value as $k => $v) {
                                    if (is_array($v)) {
                                        $html .= "<div class='col-lg-12'><div class='row'>";
                                        foreach ($v as $k1 => $v1) {
                                            if (!is_array($v1)) {
                                                $html .= "to['" . $key . "']['" . $k . "']['" . $k1 . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v1 . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                            }
                                        }
                                        $html .= "</div></div>";
                                    }
                                }
                            }
                        } else {
                            $html .= $value;
                        }
                        $html .= "</div>";
                    }
                }
                $html .= "</div></div></div>";
                continue;
            }
            $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:" . $model->what . '<br/>操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:" . $model->who . "<a class='btn btn-xs btn-primary' role='button' data-toggle='collapse' href='#collapseExample" . $key1 . "' aria-expanded='false' aria-controls='collapseExample'>数据详情</a></div>
                        <div class='panel-body'  id='collapseExample" . $key1 . "'><div class='col-lg-12'>";
            $flag = 1;
            $this->calcTwoArr($from, $to);
            foreach ($from as $key => $value) {
                $html .= "<div class='row'>from['" . $key . "']<span class='glyphicon glyphicon-arrow-right'></span>";
                if (is_array($value)) {
                    $html .= "<div class='col-lg-12'><div class='row'>";
                    if (count($value) == count($value, 1)) {
                        $html .= "<div class='row'>";
                        foreach ($value as $k => $v) {
                            if (!is_array($v)) {
                                $html .= "to['" . $key . "']['" . $k . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v . "&nbsp;&nbsp;&nbsp;&nbsp;";
                            }
                        }
                        $html .= "</div></div>";
                    } else {
                        foreach ($value as $k => $v) {
                            if (is_array($v)) {
                                $html .= "<div class='col-lg-12'><div class='row'>";
                                foreach ($v as $k1 => $v1) {
                                    if (!is_array($v1)) {
                                        $html .= "to['" . $key . "']['" . $k . "']['" . $k1 . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v1 . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                    }
                                }
                                $html .= "</div></div>";
                            }
                        }
                    }
                } else {
                    $html .= $value;
                }
                $html .= "</div>";
            }
            $html .= "<hr/>";
            foreach ($to as $key => $value) {
                $html .= "<div class='row'>to['" . $key . "']<span class='glyphicon glyphicon-arrow-right'></span>";
                if (is_array($value)) {
                    if (count($value) == count($value, 1)) {
                        $html .= "<div class='col-lg-12'><div class='row'>";
                        foreach ($value as $k => $v) {
                            if (!is_array($v)) {
                                $html .= "to['" . $key . "']['" . $k . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v . "&nbsp;&nbsp;&nbsp;&nbsp;";
                            }
                        }
                        $html .= "</div></div>";
                    } else {
                        foreach ($value as $k => $v) {
                            if (is_array($v)) {
                                $html .= "<div class='col-lg-12'><div class='row'>";
                                foreach ($v as $k1 => $v1) {
                                    if (!is_array($v1)) {
                                        $html .= "to['" . $key . "']['" . $k . "']['" . $k1 . "']<span class='glyphicon glyphicon-arrow-right'></span>" . $v1 . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                    }
                                }
                                $html .= "</div></div>";
                            }
                        }
                    }
                } else {
                    $html .= $value;
                }
                $html .= "</div>";
            }
            $html .= '</div></div></div>';
        }
        return [$html, $next_rate];
    }
}