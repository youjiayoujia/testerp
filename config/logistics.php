<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/7
 * Time: 下午3:23
 */
return [
    //对接方式
    'docking' => [
        'MANUAL' => '手工发货',
        'SELFAPI' => '内单号api',
        'API' => '物流api',
        'CODE' => '号码池',
        'CODEAPI' => '号码池+物流api',
    ],

    //面单模版
    'template' => [],

    //回邮地址
    'return_address' => [],

    //物流限制图标
    'limit_ico_src' => '/image/logistic_limit/'

];