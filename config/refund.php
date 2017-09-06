<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/6
 * Time: 14:46
 */
return [
    'process' => [
        'PENDING'  => '未处理',
        'REFUSE'   => '暂停',
        'PAUSE'    => '暂不处理',
        'FINANCE'  => '财务退款',
        'COMPLETE' => '已退款',
        'INVALID'  => '已作废',
        'FAILED'   => '退款失败',
    ],
    'type' => [
        'FULL' => '全部退款',
        'PARTIAL' => '部分退款'
    ],

    //退款方式
    'refund' => [
        '1' => 'Paypal',
        '2' => '销售平台'
    ],
    'image_path' => './uploads/refund/',

];