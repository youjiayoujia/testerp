<?php
/**
 * 导航配置文件
 *
 * name: 主导航名称
 * icon: 导航图标
 * url: 导航链接
 * subnavigations: 子菜单集合
 *      name: 子菜单名称
 *      url: 子菜单链接
 *      icon: 子菜单图标
 *
 * @author Vincent <nyewon@gmail.com>
 */
return [
    //产品导航
    [
        'name' => '产品',
        'icon' => 'tags',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '产品管理' => [
                [
                    'name' => '分类',
                    'url' => 'CatalogCategory.index',
                    'icon' => '',
                ],
                [
                    'name' => '品类Category',
                    'url' => 'catalog.index',
                    'icon' => '',
                ],
                [
                    'name' => '产品SPU',
                    'url' => 'spu.index',
                    'icon' => '',
                ],
                [
                    'name' => '产品SKU',
                    'url' => 'item.index',
                    'icon' => '',
                ],
                [
                    'name' => '图片',
                    'url' => 'productImage.index',
                    'icon' => '',
                ],
                [
                    'name' => '图片标签',
                    'url' => 'label.index',
                    'icon' => '',
                ],
                [
                    'name' => '留言板',
                    'url' => 'item.questionIndex',
                    'icon' => '',
                ],
            ],
            '选款' => [
                [
                    'name' => '选款需求',
                    'url' => 'productRequire.index',
                    'icon' => '',
                ],
                [
                    'name' => '选款Model',
                    'url' => 'product.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //订单导航
    [
        'name' => '订单',
        'icon' => 'list-alt',
        'url' => '',
        'type' => '',
        'subnavigations' => [
            [
                'name' => '订单',
                'url' => 'order.index',
                'icon' => '',
            ],
            [
                'name' => '未付款订单',
                'url' => 'unpaidOrder.index',
                'icon' => '',
            ],
            [
                'name' => '风控订单',
                'url' => 'orderBlacklist.index',
                'icon' => '',
            ],
            [
                'name' => '风控订单地址验证',
                'url' => 'blacklistAddress.index',
                'icon' => '',
            ],
            [
                'name' => '标记发货规则设置',
                'url' => 'orderMarkLogic.index',
                'icon' => '',
            ],
        ],
    ],
    //仓储导航
    [
        'name' => '仓储',
        'icon' => 'home',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '收货质检' => [
                [
                    'name' => '包裹收货扫描',
                    'url' => 'purchaseList.create',
                    'icon' => '',
                ],
                [
                    'name' => '包裹收货列表',
                    'url' => 'purchaseList.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购收货和入库',
                    'url' => 'recieve',
                    'icon' => '',
                ],
                [
                    'name' => '新品待入库',
                    'url' => 'purchaseItemIndex',
                    'icon' => '',
                ],
            ],
            '发货' => [
                [
                    'name' => '包裹',
                    'url' => 'package.index',
                    'icon' => '',
                ],
                [
                    'name' => '拣货',
                    'url' => 'pickList.index',
                    'icon' => '',
                ],
                // [
                //     'name' => '效能统计',
                //     'url' => 'pickList.performanceStatistics',
                //     'icon' => '',
                // ],
                [
                    'name' => '创建模板',
                    'url' => 'exportPackage.index',
                    'icon' => '',
                ],
                [
                    'name' => '模板数据导出',
                    'url' => 'exportPackage.exportPackageView',
                    'icon' => '',
                ],
                [
                    'name' => '退货处理',
                    'url' => 'package.returnGoods',
                    'icon' => '',
                ],
                [
                    'name' => '退货上架',
                    'url' => 'package.returnGoodsInShelf',
                    'icon' => '',
                ],
                [
                    'name' => '物流对账',
                    'url' => 'shipmentCost.index',
                    'icon' => '',
                ],
            ],
            '仓储' => [
                [
                    'name' => '仓库',
                    'url' => 'warehouse.index',
                    'icon' => '',
                ],
                [
                    'name' => '库位',
                    'url' => 'warehousePosition.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存',
                    'url' => 'stock.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存查询',
                    'url' => 'stock.showStockInfo',
                    'icon' => '',
                ],
                [
                    'name' => '出入库记录',
                    'url' => 'stockInOut.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存调整',
                    'url' => 'stockAdjustment.index',
                    'icon' => '',
                ],
                [
                    'name' => 'hold库存',
                    'url' => 'stockHold.index',
                    'icon' => '',
                ],
                [
                    'name' => 'unhold库存',
                    'url' => 'stockUnhold.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存调拨',
                    'url' => 'stockAllotment.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存盘点',
                    'url' => 'stockTaking.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存结转',
                    'url' => 'stockCarryOver.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //物流导航
    [
        'name' => '物流',
        'icon' => 'plane',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '物流管理' => [
                [
                    'name' => '物流分类',
                    'url' => 'logisticsCatalog.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流方式',
                    'url' => 'logistics.index',
                    'icon' => '',
                ],
                [
                    'name' => '回邮模版',
                    'url' => 'logisticsEmailTemplate.index',
                    'icon' => '',
                ],
                [
                    'name' => '面单模版',
                    'url' => 'logisticsTemplate.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流商',
                    'url' => 'logisticsSupplier.index',
                    'icon' => '',
                ],
                [
                    'name' => '收款信息',
                    'url' => 'logisticsCollectionInfo.index',
                    'icon' => '',
                ],
//                [
//                    'name' => '物流分配规则',
//                    'url' => 'logisticsRule.index',
//                    'icon' => '',
//                ],
                [
                    'name' => '渠道展示编码',
                    'url' => 'logisticsTransport.index',
                    'icon' => '',
                ],
                [
                    'name' => '渠道回传编码',
                    'url' => 'logisticsChannelName.index',
                    'icon' => '',
                ],
                [
                    'name' => '物流分区',
                    'url' => 'logisticsPartition.index',
                    'icon' => '',
                ],
            ]
        ],
    ],
    //采购导航
    [
        'name' => '采购',
        'icon' => 'shopping-cart',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '采购管理' => [
                [
                    'name' => '采购需求',
                    'url' => 'require.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购单',
                    'url' => 'purchaseOrder.index',
                    'icon' => '',
                ],
                [
                    'name' => '采购单批量付款',
                    'url' => 'purchaseOrder.excelPayOff',
                    'icon' => '',
                ],
                [
                    'name' => '采购单核销',
                    'url' => 'purchaseOrder.writeOffIndex',
                    'icon' => '',
                ],
            ],
            '供应链' => [
                [
                    'name' => '供货商',
                    'url' => 'productSupplier.index',
                    'icon' => '',
                ],
            ],
            '采购账号' => [
                [
                    'name' => '阿里巴巴账号',
                    'url' => 'purchaseAccount.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    [
        'name' => ' 刊登',
        'icon' => 'glyphicon glyphicon-send',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            'Wish刊登' => [
                [
                    'name' => 'Wish草稿列表',
                    'url' => 'wish.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Wish在线数据列表',
                    'url' => 'wish.indexOnlineProduct',
                    'icon' => '',
                ],
                [
                    'name' => 'Wish销售代码设置',
                    'url' => 'wishSellerCode.index',
                    'icon' => '',
                ],
            ],
            'Aliexpress刊登' => [
                [
                    'name' => 'SMT待发布产品列表',
                    'url' => 'smt.waitPost',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT产品草稿列表',
                    'url' => 'smt.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT在线产品列表',
                    'url' => 'smt.onlineProductIndex',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT售后模版管理',
                    'url' => 'smtAfterSale.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT框架模版管理',
                    'url' => 'smtTemplate.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT产品分组',
                    'url' => 'smtProduct.groupManage',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT服务模版',
                    'url' => 'smtProduct.serviceManage',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT运费模版',
                    'url' => 'smtProduct.freightManage',
                    'icon' => '',
                ],

                [
                    'name' => 'SMT调价任务列表',
                    'url' => 'smtPriceTask.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT销售代码设置',
                    'url' => 'smtSellerCode.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT帐号管理',
                    'url' => 'smtAccountManage.index',
                    'icon' => '',
                ],            
                
            ],
            'Ebay刊登' => [
                [
                    'name' => 'Ebay站点信息',
                    'url' => 'ebayDetail.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay草稿列表',
                    'url' => 'ebayPublish.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay在线列表',
                    'url' => 'ebayOnline.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay销售代码设置',
                    'url' => 'ebaySellerCode.index',
                    'icon' => '',
                ],

                [
                    'name' => 'Ebay账号设置',
                    'url' => 'ebayAccountSet.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay描述模板设置',
                    'url' => 'ebayDescription.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay数据模板设置',
                    'url' => 'ebayDataTemplate.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay店铺分类设置',
                    'url' => 'ebayStoreCategory.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Ebay曝光规则设置',
                    'url' => 'ebayTiming.index',
                    'icon' => '',
                ],

            ],
            '侵权管理' => [
                [
                'name' => '侵权列表',
                'url' => 'copyright.index',
                'icon' => '',
                ],
            ],
            '数据监控' => [
                [
                    'name' => 'Ebay数据监控',
                    'url' => 'ebayProduct.index',
                    'icon' => '',
                ],
                [
                    'name' => 'SMT数据监控',
                    'url' => 'smtMonitor.index',
                    'icon' => '',
                ],

                [
                    'name' => 'Lazada数据监控',
                    'url' => 'lazada.index',
                    'icon' => '',
                ],
                [
                    'name' => 'Joom数量监控',
                    'url' => 'joomonline.index',
                    'icon' => '',
                ],
                [
                    'name' => 'wish数量监控',
                    'url' => 'WishQuantityCheck.index',
                    'icon' => '',
                ],
            ]
        ]
    ],
    //CRM导航
    [
        'name' => 'CRM',
        'icon' => 'envelope',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '邮件管理' => [
                [
                    'name' => '信息',
                    'url' => 'message.index',
                    'icon' => '',
                ],
                [
                    'name' => '发送队列',
                    'url' => 'messageReply.index',
                    'icon' => '',
                ],
            ],
            '邮件模板管理' => [
                [
                    'name' => '模板类型',
                    'url' => 'messageTemplateType.index',
                    'icon' => '',
                ],
                [
                    'name' => '信息模板',
                    'url' => 'messageTemplate.index',
                    'icon' => '',
                ],
            ],
            '平台纠纷' => [
                [
                    'name' => 'ebay cases',
                    'url' => 'ebayCases.index',
                    'icon' => '',
                ],
                [
                    'name' => 'aliexpress issues',
                    'url' => 'AliexpressIssue.index',
                    'icon' => '',
                ],
            ],
            '平台批量操作' => [
                [
                    'name' => 'Aliexpress批量订单留言',
                    'url' => 'aliexpressReturnOrderMessages',
                    'icon' => '',
                ],
            ],
            '评论列表' => [
                [
                    'name' => 'Ebay feedBack',
                    'url' => 'ebayFeedBack.index',
                    'icon' => '',
                ]
            ],
            '程序智能处理' => [
                [
                    'name' => '自动回复设置',
                    'url' => 'autoReply.index',
                    'icon' => '',
                ],
            ],
        ]
    ],
    [
        'name' => '海外仓',
        'icon' => 'plane',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '海外仓模块' => [
                [
                    'name' => '头程物流',
                    'url' => 'firstLeg.index',
                    'icon' => '',
                ],
                [
                    'name' => '海外调拨',
                    'url' => 'overseaAllotment.index',
                    'icon' => '',
                ],
                [
                    'name' => '库存调整单',
                    'url' => 'overseaStockAdjustment.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
    //报表导航
    [
        'name' => '报表',
        'icon' => 'list-alt',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '采购报表' => [
                [
                    'name' => '采购数据统计',
                    'url' => 'purchaseStaticstics',
                    'icon' => '',
                ],
                [
                    'name' => '缺货报告',
                    'url' => 'purchase.outOfStock',
                    'icon' => '',
                ],
            ],
            'CRM报表' => [
                [
                    'name' => 'CRM消息回复统计',
                    'url' => 'crmStatistics.index',
                    'icon' => '',
                ],
                [
                    'name' => 'ebay差评统计',
                    'url' => 'feeback.feedBackStatistics',
                    'icon' => '',
                ],
            ],
            '物流报表' => [
                [
                    'name' => '物流发货统计',
                    'url' => 'package.logisticsDelivery',
                    'icon' => '',
                ],

            ],
            'SKU报表' => [
                [
                    'name' => '到货记录',
                    'url' => 'purchaseOrder.recieveReport',
                    'icon' => '',
                ],
                [
                    'name' => 'EbaySku销量报表',
                    'url' => 'ebaySkuSaleReport.index',
                    'icon' => '',
                ],
                [
                    'name' => 'EBAY销量额统计',
                    'url' => 'ebayAmountStatistics.index',
                    'icon' => '',
                ],
            ],
            '包裹报表' => [
                [
                    'name' => '包裹信息',
                    'url' => 'allReport.report',
                    'icon' => '',
                ],
                [
                    'name' => '拣货排行榜',
                    'url' => 'pickReport.index',
                    'icon' => '',
                ],
                [
                    'name' => '包装排行榜',
                    'url' => 'packReport.index',
                    'icon' => '',
                ],
            ],
            '订单报表' => [
                [
                    'name' => '退款统计',
                    'url' => 'refund.refundStatistics',
                    'icon' => '',
                ],
            ],
        ]
    ],
    //监控导航
    [
        'name' => '监控',
        'icon' => 'scale',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '定时任务' => [
                [
                    'name' => '任务日志',
                    'url' => 'logCommand.index',
                    'icon' => '',
                ],
            ],
            '队列' => [
                [
                    'name' => '队列日志',
                    'url' => 'logQueue.index',
                    'icon' => '',
                ],
                [
                    'name' => '失败队列',
                    'url' => 'jobFailed.index',
                    'icon' => '',
                ],
            ],
            '接口' => [
                [
                    'name' => '同步接口',
                    'url' => 'syncApi.index',
                    'icon' => '',
                ],
                [
                    'name' => '接收接口',
                    'url' => 'importSyncApi.index',
                    'icon' => '',
                ],
            ],
        ]
    ],
    [
        'name' => '财务',
        'icon' => 'glyphicon glyphicon-lock',
        'url' => '',
        'type' => '',
        'subnavigations' => [
            [
                'name' => '退款中心',
                'url' => 'refundCenter.index',
                'icon' => 'glyphicon glyphicon-loc',
            ],
        ],
    ],
    //系统导航
    [
        'name' => '系统',
        'icon' => 'cog',
        'url' => '',
        'type' => 'group',
        'subnavigations' => [
            '渠道' => [
                [
                    'name' => '渠道列表',
                    'url' => 'channel.index',
                    'icon' => '',
                ],
                [
                    'name' => '账号列表',
                    'url' => 'channelAccount.index',
                    'icon' => '',
                ],
                [
                    'name' => '地域渠道',
                    'url' => 'CatalogRatesChannel.index',
                    'icon' => ''
                ],
            ],
            '用户' => [
                [
                    'name' => '用户列表',
                    'url' => 'user.index',
                    'icon' => '',
                ],
                [
                    'name' => '角色列表',
                    'url' => 'role.index',
                    'icon' => '',
                ],
                [
                    'name' => '权限列表',
                    'url' => 'permission.index',
                    'icon' => '',
                ]
            ],
            '设置' => [
                [
                    'name' => '物流限制',
                    'url' => 'logisticsLimits.index',
                    'icon' => '',
                ],
                [
                    'name' => '包装限制',
                    'url' => 'wrapLimits.index',
                    'icon' => '',
                ],
                [
                    'name' => '收货包装',
                    'url' => 'recieveWraps.index',
                    'icon' => '',
                ],
                [
                    'name' => '汇率',
                    'url' => 'currency.index',
                    'icon' => '',
                ],
                [
                    'name' => '国家分区',
                    'url' => 'countriesSort.index',
                    'icon' => '',
                ],
                [
                    'name' => '国家信息',
                    'url' => 'countries.index',
                    'icon' => '',
                ],
                [
                    'name' => '国家转换',
                    'url' => 'countriesChange.index',
                    'icon' => '',
                ],
                [
                    'name' => 'paypal列表',
                    'url' => 'paypal.index',
                    'icon' => '',
                ],
                [
                    'name' => 'paypal固定费率',
                    'url' => 'paypal.ShowPaypalRate',
                    'icon' => '',
                ],
                [
                    'name' => '邮件推送',
                    'url' => 'mail_push.index',
                    'icon' => '',
                ],
            ],
        ],
    ],
];