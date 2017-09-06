<?php
    return [
        'ALL_TYPE' => [
            'IN.PURCHASE' => '采购入库',
            'IN.PRODUCE' => '做货入库',
            'IN.ALLOTMENT' => '调拨入库',
            'IN.BOUNCE' => '退件入库',
            'IN.CANCEL' => 'package取消入库',
            'IN.INVENTORY_PROFIT' => '盘盈入库',
            'IN.ADJUSTMENT' => '调整入库',
            'IN.MAKE_ACCOUNT' => '开帐入库', 
            'OUT.PACKAGE' => '发货出库',
            'OUT.ALLOTMENT' => '调拨出库',
            'OUT.SHORTAGE' => '盘亏出库',
            'OUT.ADJUSTMENT' => '调整出库',
            'OUT.SCRAP' => '报废出库',
        ],
        'INNER_TYPE' => [
            'PURCHASE' => '采购',
            'PRODUCE' => '做货',
            'BOUNCE' => '退件',
            'PACKAGE_CANCEL' => 'package取消',
            'INVENTORY_PROFIT' => '盘盈',
            'MAKE_ACCOUNT' => '开帐', 
            'PACKAGE' => '发货',
            'ALLOTMENT' => '调拨',
            'OVERSEA_ALLOTMENT' => '海外调拨',
            'SHORTAGE' => '盘亏',
            'ADJUSTMENT' => '调整',
            'SCRAP' => '报废',
        ],
    ];

    /**
     *  'allotment' => [
    *        'new' => 'new',
     *       'pick' => '拣货中',
      *      'out' => '出库',
       *     'check' => '对单入库中',
        *    'over' => '调拨结束',
     *   ],
     */