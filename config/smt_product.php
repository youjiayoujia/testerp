<?php
/**
 * smt产品配置文件
 */
return [
    //订单状态
    'productStatusType' => [
        'onSelling' => 'onSelling',
        'offline' => 'offline',
        'auditing' => 'auditing',
        'editingRequired' => 'editingRequired',
    ],

    'is_erp' => [
        '1' => 'sku匹配',
        '0' => 'sku不匹配',
    ],
    
    'multiattribute' => [
        '0' => '单属性',
        '1' => '多属性',
    ],
    
    'skuStockStatus' => [
        '0' => '等于0',
        '1' => '大于0',
    ],
    
    'status' => [
        'selling' => '在售',
        'sellWaiting' => '待售',
        'cleaning' => '清库存中',
        'stopping' => '停产',
        'saleOutStoping' => '卖完下架',
        'unSellTemp' => '货源待定',
        'trySale' => '试销(卖多少采多少)',
    ],
 
]; 