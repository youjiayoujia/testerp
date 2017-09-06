<?php
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
/**
 *
 * Route::get('a/b', ['uses' => 'dddController@b', 'as' => 'a.b']);   路由规范
 * 注意a/b  b  a.b 这三部分的样式就OK了
 *
 */
Route::get('test1', ['uses' => 'TestController@test1', 'as' => 'test1']);
Route::get('test2', ['uses' => 'TestController@test2', 'as' => 'test2']);
Route::get('test3', ['uses' => 'TestController@test3', 'as' => 'test3']);
Route::get('test4', ['uses' => 'TestController@test4', 'as' => 'test4']);
Route::get('test_3', 'TestController@test_3');
Route::post('api/curlApiChangeWarehousePositon',
    ['uses' => 'ItemController@curlApiChangeWarehousePositon', 'as' => 'item.curlApiChangeWarehousePositon']);
Route::any('api/skuHandleApi', ['uses' => 'ItemController@skuHandleApi', 'as' => 'item.skuHandleApi']);
Route::any('api/SyncSellmoreData',
    ['uses' => 'SyncSellmoreDataController@SyncSuppliersFromSell', 'as' => 'SyncSellmoreData']);
Route::any('api/SyncWishyoutoken',
    ['uses' => 'SyncWishyoutokenController@SyncSuppliersFromSell', 'as' => 'SyncWishyoutoken']);
Route::any('api/skuSupplierApi', ['uses' => 'ItemController@skuSupplierApi', 'as' => 'item.skuSupplierApi']);

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::group(['middleware' => 'roleCheck'], function () {
    //Home
    Route::any('/', ['as' => 'dashboard.index', 'uses' => 'PackageController@flow']);
    //国家
    Route::get('barcodeGen/{content}/{height?}/{orientation?}/{type?}/{length?}',
        ['uses' => 'CountriesController@barcodePrint', 'as' => 'barcodeGen']);
    Route::resource('countries', 'CountriesController');
    //国家分类
    Route::resource('countriesSort', 'CountriesSortController');

    //国家转换
    Route::get('ajaxCountryTo',
        ['uses' => 'CountriesChangeController@ajaxCountryTo', 'as' => 'ajaxCountryTo']);
    Route::resource('countriesChange', 'CountriesChangeController');

    Route::get('eventChild/getInfo', ['uses' => 'EventChildController@getInfo', 'as' => 'eventChild.getInfo']);
    Route::resource('eventChild', 'EventChildController');
    //3宝package
    Route::resource('bao3Package', 'Bao3PackageController');
    //产品图片路由
    Route::any('productImage/imageLable', ['uses' => 'Product\ImageController@imageLable', 'as' => 'imageLable']);
    Route::any('productImage/createImage', ['uses' => 'Product\ImageController@createImage', 'as' => 'createImage']);
    Route::any('productImage/createSpuImage',
        ['uses' => 'Product\ImageController@createSpuImage', 'as' => 'createSpuImage']);
    Route::resource('productImage', 'Product\ImageController');
    //reported smissing  reportedMissingCreate
    Route::post('reportedMissingCreate', 'product\ReportedMissingController@store');
    Route::resource('reportedMissing', 'Product\ReportedMissingController');
    //包装限制
    Route::resource('wrapLimits', 'WrapLimitsController');
    //收货包装
    Route::resource('recieveWraps', 'RecieveWrapsController');
    Route::any('catalog/checkName', ['uses' => 'CatalogController@checkName', 'as' => 'checkName']);
    //汇率
    Route::resource('currency', 'CurrencyController');
    //关帐
    Route::resource('stockShut', 'Stock\ShutController');
    //hold库存
    Route::resource('stockHold', 'Stock\HoldController');
    //unhold库存
    Route::resource('stockUnhold', 'Stock\UnholdController');
    //入库
    Route::resource('stockIn', 'Stock\InController');


    //海外仓库存调整
    Route::resource('overseaStockAdjustment', 'Oversea\StockAdjustmentController');
    //海外仓调拨  
    Route::get('overseaAllotment/printBox/{id}', ['uses' => 'Oversea\AllotmentController@printBox', 'as' => 'overseaAllotment.printBox']); 
    Route::get('overseaAllotment/returnAllInfo/{id}', ['uses' => 'Oversea\AllotmentController@returnAllInfo', 'as' => 'overseaAllotment.returnAllInfo']); 
    Route::post('overseaAllotment/returnAllInfoStore/{id}', ['uses' => 'Oversea\AllotmentController@returnAllInfoStore', 'as' => 'overseaAllotment.returnAllInfoStore']); 
    Route::get('overseaAllotment/inboxOver/{str}/{id}', ['uses' => 'Oversea\AllotmentController@inboxOver', 'as' => 'overseaAllotment.inboxOver']);
    Route::get('overseaAllotment/allotmentInStock/{id}', ['uses' => 'Oversea\AllotmentController@allotmentInStock', 'as' => 'overseaAllotment.allotmentInStock']); 
    Route::post('overseaAllotment/returnBoxInfoStore/{id}', ['uses' => 'Oversea\AllotmentController@returnBoxInfoStore', 'as' => 'overseaAllotment.returnBoxInfoStore']); 
    Route::get('overseaAllotment/returnBoxInfo/{id}', ['uses' => 'Oversea\AllotmentController@returnBoxInfo', 'as' => 'overseaAllotment.returnBoxInfo']); 
    Route::get('overseaAllotment/inboxStore/{str}/{id}', ['uses' => 'Oversea\AllotmentController@inboxStore', 'as' => 'overseaAllotment.inboxStore']); 
    Route::get('overseaAllotment/inboxed/{id}', ['uses' => 'Oversea\AllotmentController@inboxed', 'as' => 'overseaAllotment.inboxed']);   
    Route::get('overseaAllotment/pick/{id}', ['uses' => 'Oversea\AllotmentController@pick', 'as' => 'overseaAllotment.pick']);   
    Route::post('overseaAllotment/checkResult/{id}', ['uses' => 'Oversea\AllotmentController@checkResult', 'as' => 'overseaAllotment.checkResult']);   
    Route::get('overseaAllotment/check/{id}', ['uses' => 'Oversea\AllotmentController@check', 'as' => 'overseaAllotment.check']);   
    Route::get('overseaAllotment/add', ['uses' => 'Oversea\AllotmentController@ajaxAllotmentAdd', 'as' => 'overseaAllotment.add']); 
    Route::resource('overseaAllotment', 'Oversea\AllotmentController');
    //海外仓头程物流
    Route::get('firstLeg/sectionAdd',
        ['uses' => 'Oversea\FirstLegController@sectionAdd', 'as' => 'firstLeg.sectionAdd']);
    Route::resource('firstLeg', 'Oversea\FirstLegController');

    //箱子信息
    Route::get('overseaBox/createbox', ['uses' => 'Oversea\BoxController@createbox', 'as' => 'overseaBox.createbox']);
    Route::resource('overseaBox', 'Oversea\BoxController');
    //Fba库存信息
    // Route::get('fbaStock/updateStock',
    //     ['uses' => 'Oversea\StockController@updateStock', 'as' => 'fbaStock.updateStock']);
    // Route::resource('fbaStock', 'Oversea\StockController');

    //包装排行榜
    Route::get('packReport/download', ['uses' => 'PackReportController@download', 'as' => 'packReport.download']);
    Route::get('packReport/changeData', ['uses' => 'PackReportController@changeData', 'as' => 'packReport.changeData']);
    Route::get('packReport/createData', ['uses' => 'PackReportController@createData', 'as' => 'packReport.createData']);
    Route::resource('packReport', 'PackReportController');

    //拣货排行榜
    Route::get('pickReport/download', ['uses' => 'PickReportController@download', 'as' => 'pickReport.download']);
    Route::get('pickReport/createData', ['uses' => 'PickReportController@createData', 'as' => 'pickReport.createData']);
    Route::resource('pickReport', 'PickReportController');
    //海外仓箱子
    // Route::get('box/boxSub', ['uses' => 'Oversea\BoxController@boxSub', 'as' => 'box.boxSub']);
    // Route::resource('box', 'Oversea\BoxController');
    //申请表
    // Route::post('report/packageStore/{id}',
    //     ['uses' => 'Oversea\ReportController@packageStore', 'as' => 'report.packageStore']);
    // Route::get('report/sendExec', ['uses' => 'Oversea\ReportController@sendExec', 'as' => 'report.sendExec']);
    // Route::get('report/shipment', ['uses' => 'Oversea\ReportController@shipment', 'as' => 'report.shipment']);
    // Route::get('report/check/{id}', ['uses' => 'Oversea\ReportController@check', 'as' => 'report.check']);
    // Route::post('report/checkResult/{id}',
    //     ['uses' => 'Oversea\ReportController@checkResult', 'as' => 'report.checkResult']);
    // Route::get('report/createBox', ['uses' => 'Oversea\ReportController@createBox', 'as' => 'report.createBox']);
    // Route::get('report/ctrlZ', ['uses' => 'Oversea\ReportController@ctrlZ', 'as' => 'report.ctrlZ']);
    // Route::get('report/reportFormUpdate',
    //     ['uses' => 'Oversea\ReportController@reportFormUpdate', 'as' => 'report.reportFormUpdate']);
    // Route::get('report/package/{id}', ['uses' => 'Oversea\ReportController@package', 'as' => 'report.package']);
    // Route::get('report/pick/{id}', ['uses' => 'Oversea\ReportController@pick', 'as' => 'report.pick']);
    // Route::get('report/add', ['uses' => 'Oversea\ReportController@add', 'as' => 'report.add']);
    // Route::resource('report', 'Oversea\ReportController');
    //建议采购
    // Route::get('suggestForm/createForms',
    //     ['uses' => 'Oversea\SuggestFormController@createForms', 'as' => 'suggestForm.createForms']);
    // Route::resource('suggestForm', 'Oversea\SuggestFormController');

    //物流对账正确信息详情
    Route::get('shipmentCostError/showError/{id}',
        ['uses' => 'ShipmentCostErrorController@showError', 'as' => 'shipmentCostError.showError']);
    Route::resource('shipmentCostError', 'ShipmentCostErrorController');

    //物流对账正确信息详情
    Route::get('shipmentCostError/showError/{id}',
        ['uses' => 'ShipmentCostErrorController@showError', 'as' => 'shipmentCostError.showError']);
    Route::resource('shipmentCostError', 'ShipmentCostErrorController');

    //物流对账正确信息详情
    Route::get('shipmentCostItem/showInfo/{id}',
        ['uses' => 'ShipmentCostItemController@showInfo', 'as' => 'shipmentCostItem.showInfo']);
    Route::resource('shipmentCostItem', 'ShipmentCostItemController');
    //出库
    Route::resource('stockOut', 'Stock\OutController');
    //出入库
    Route::post('inOut/exportResult', ['uses' => 'Stock\InOutController@exportResult', 'as' => 'inOut.exportResult']);
    Route::get('inOut/export', ['uses' => 'Stock\InOutController@export', 'as' => 'inOut.export']);
    Route::resource('stockInOut', 'Stock\InOutController');
    //供货商变更历史
    Route::resource('supplierChangeHistory', 'Product\SupplierChangeHistoryController');
    //供货商评级
    Route::resource('supplierLevel', 'Product\SupplierLevelController');
    //物流对账
    Route::get('shipmentCost/destroyRows/{arr}',
        ['uses' => 'ShipmentCostController@destroyRows', 'as' => 'shipmentCost.destroyRows']);
    Route::get('shipmentCost/showError/{id}',
        ['uses' => 'ShipmentCostController@showError', 'as' => 'shipmentCost.showError']);
    Route::post('shipmentCost/importProcess',
        ['uses' => 'ShipmentCostController@importProcess', 'as' => 'shipmentCost.importProcess']);
    Route::get('shipmentCost/import', ['uses' => 'ShipmentCostController@import', 'as' => 'shipmentCost.import']);
    Route::get('shipmentCost/export', ['uses' => 'ShipmentCostController@export', 'as' => 'shipmentCost.export']);
    Route::resource('shipmentCost', 'ShipmentCostController');
    //供货商
    Route::get('productSupplier/ajaxSupplier',
        ['uses' => 'Product\SupplierController@ajaxSupplier', 'as' => 'ajaxSupplier']);
    Route::post('productSupplier/levelStore',
        ['uses' => 'Product\SupplierController@levelStore', 'as' => 'productSupplier.levelStore']);
    Route::get('productSupplier/createLevel',
        ['uses' => 'Product\SupplierController@createLevel', 'as' => 'productSupplier.createLevel']);
    Route::resource('productSupplier', 'Product\SupplierController');
    //选款需求
    Route::post('productRequire/excelStore',
        ['uses' => 'Product\RequireController@excelStore', 'as' => 'productRequire.excelStore']);
    Route::get('productRequire/getExcel',
        ['uses' => 'Product\RequireController@getExcel', 'as' => 'productRequire.getExcel']);
    Route::get('productRequire/importByExcel',
        ['uses' => 'Product\RequireController@importByExcel', 'as' => 'productRequire.importByExcel']);
    Route::get('productRequire/ajaxQuantityProcess',
        ['uses' => 'Product\RequireController@ajaxQuantityProcess', 'as' => 'productRequire.ajaxQuantityProcess']);
    Route::get('productRequire/ajaxProcess',
        ['uses' => 'Product\RequireController@ajaxProcess', 'as' => 'productRequire.ajaxProcess']);
    Route::resource('productRequire', 'Product\RequireController');
    Route::any('ajaxReturnLogistics',
        ['as' => 'product.ajaxReturnLogistics', 'uses' => 'ProductController@ajaxReturnLogistics']);
    Route::any('ajaxReutrnCatalogs',
        ['uses' => 'ProductController@ajaxReutrnCatalogs', 'as' => 'ajaxReutrnCatalogs']);
    Route::any('submitItemEdit',
        ['uses' => 'ProductController@submitItemEdit', 'as' => 'product.submitItemEdit']);
    Route::any('edititemattribute/{id}', ['uses' => 'ProductController@editItemAttribute']);

    //通关报关
    Route::post('customsClearance/exportProduct',
        ['uses' => 'CustomsClearanceController@exportProduct', 'as' => 'customsClearance.exportProduct']);
    Route::get('customsClearance/exportFailModel',
        ['uses' => 'CustomsClearanceController@exportFailModel', 'as' => 'customsClearance.exportFailModel']);
    Route::get('customsClearance/exportFailItem',
        ['uses' => 'CustomsClearanceController@exportFailItem', 'as' => 'customsClearance.exportFailItem']);
    Route::post('customsClearance/exportNXB',
        ['uses' => 'CustomsClearanceController@exportNXB', 'as' => 'customsClearance.exportNXB']);
    Route::post('customsClearance/exportEUB',
        ['uses' => 'CustomsClearanceController@exportEUB', 'as' => 'customsClearance.exportEUB']);
    Route::post('customsClearance/exportEUBWeight',
        ['uses' => 'CustomsClearanceController@exportEUBWeight', 'as' => 'customsClearance.exportEUBWeight']);
    Route::post('customsClearance/exportProductZY',
        ['uses' => 'CustomsClearanceController@exportProductZY', 'as' => 'customsClearance.exportProductZY']);
    Route::post('customsClearance/exportProductEUB',
        ['uses' => 'CustomsClearanceController@exportProductEUB', 'as' => 'customsClearance.exportProductEUB']);
    Route::post('customsClearance/exportProductFed',
        ['uses' => 'CustomsClearanceController@exportProductFed', 'as' => 'customsClearance.exportProductFed']);
    Route::get('customsClearance/bao3packageindex',
        ['uses' => 'CustomsClearanceController@bao3packageindex', 'as' => 'customsClearance.bao3packageindex']);
    Route::get('customsClearance/downloadToNanjing',
        ['uses' => 'CustomsClearanceController@downloadToNanjing', 'as' => 'customsClearance.downloadToNanjing']);
    Route::get('customsClearance/downloadOver',
        ['uses' => 'CustomsClearanceController@downloadOver', 'as' => 'customsClearance.downloadOver']);
    Route::post('customsClearance/updateNanjing',
        ['uses' => 'CustomsClearanceController@updateNanjing', 'as' => 'customsClearance.updateNanjing']);
    Route::post('customsClearance/updateOver',
        ['uses' => 'CustomsClearanceController@updateOver', 'as' => 'customsClearance.updateOver']);
    Route::get('customsClearance/bao3index',
        ['uses' => 'CustomsClearanceController@bao3index', 'as' => 'customsClearance.bao3index']);
    Route::post('customsClearance/updateNumber',
        ['uses' => 'CustomsClearanceController@updateNumber', 'as' => 'customsClearance.updateNumber']);
    Route::get('customsClearance/downloadUpdateProduct', [
        'uses' => 'CustomsClearanceController@downloadUpdateProduct',
        'as' => 'customsClearance.downloadUpdateProduct'
    ]);
    Route::get('customsClearance/downloadNumber',
        ['uses' => 'CustomsClearanceController@downloadNumber', 'as' => 'customsClearance.downloadNumber']);
    Route::get('customsClearance/downloadUploadProduct', [
        'uses' => 'CustomsClearanceController@downloadUploadProduct',
        'as' => 'customsClearance.downloadUploadProduct'
    ]);
    Route::post('customsClearance/uploadProduct',
        ['uses' => 'CustomsClearanceController@uploadProduct', 'as' => 'customsClearance.uploadProduct']);
    Route::post('customsClearance/updateProduct',
        ['uses' => 'CustomsClearanceController@updateProduct', 'as' => 'customsClearance.updateProduct']);
    Route::resource('customsClearance', 'CustomsClearanceController');
    //仓库
    Route::resource('warehouse', 'WarehouseController');
    //库存调整
    Route::post('stockAdjustment/checkResult/{id}',
        ['uses' => 'Stock\AdjustmentController@checkResult', 'as' => 'stockAdjustment.checkResult']);
    Route::get('stockAdjustment/adjustAdd',
        ['uses' => 'Stock\AdjustmentController@ajaxAdjustAdd', 'as' => 'stockAdjustment.adjustAdd']);
    Route::get('stockAdjustment/check/{id}',
        ['uses' => 'Stock\AdjustmentController@Check', 'as' => 'stockAdjustment.check']);
    Route::resource('stockAdjustment', 'Stock\AdjustmentController');
    //库位
    Route::get('position/ajaxCheckPosition',
        ['uses' => 'Warehouse\PositionController@ajaxCheckPosition', 'as' => 'position.ajaxCheckPosition']);
    Route::post('position/excelProcess',
        ['uses' => 'Warehouse\PositionController@excelProcess', 'as' => 'position.excelProcess']);
    Route::get('position/importByExcel',
        ['uses' => 'Warehouse\PositionController@importByExcel', 'as' => 'position.importByExcel']);
    Route::get('position/getExcel', ['uses' => 'Warehouse\PositionController@getExcel', 'as' => 'position.getExcel']);
    Route::get('position/getPosition',
        ['uses' => 'Warehouse\PositionController@ajaxGetPosition', 'as' => 'position.getPosition']);
    Route::resource('warehousePosition', 'Warehouse\PositionController');
    //库存
    Route::post('stock/overseaImportStore', ['uses' => 'StockController@overseaImportStore', 'as' => 'stock.overseaImportStore']);
    Route::post('stock/overseaExcelProcess', ['uses' => 'StockController@overseaExcelProcess', 'as' => 'stock.overseaExcelProcess']);
    Route::get('stock/overseaImportByExcel', ['uses' => 'StockController@overseaImportByExcel', 'as' => 'stock.overseaImportByExcel']);
    Route::get('stock/getTakingExcel', ['uses' => 'StockController@getTakingExcel', 'as' => 'stock.getTakingExcel']);
    Route::get('stock/ajaxAllSku', ['uses' => 'StockController@ajaxAllSku', 'as' => 'stock.ajaxAllSku']);
    Route::get('stock/overseaPosition', ['uses' => 'StockController@overseaPosition', 'as' => 'stock.overseaPosition']);
    Route::get('stock/overseaSku', ['uses' => 'StockController@overseaSku', 'as' => 'stock.overseaSku']);
    Route::get('stock/changePosition', ['uses' => 'StockController@changePosition', 'as' => 'stock.changePosition']);
    Route::any('itemAjaxWarehousePosition',
        ['uses' => 'StockController@ajaxWarehousePosition', 'as' => 'itemAjaxWarehousePosition']);
    Route::get('stock/getSinglePosition',
        ['uses' => 'StockController@getSinglePosition', 'as' => 'stock.getSinglePosition']);
    Route::get('stock/getSingleSku', ['uses' => 'StockController@getSingleSku', 'as' => 'stock.getSingleSku']);
    Route::get('stock/showStockInfo', ['uses' => 'StockController@showStockInfo', 'as' => 'stock.showStockInfo']);
    Route::get('stock/getExcel', ['uses' => 'StockController@getExcel', 'as' => 'stock.getExcel']);
    Route::post('stock/excelProcess', ['uses' => 'StockController@excelProcess', 'as' => 'stock.excelProcess']);
    Route::get('stock/importByExcel', ['uses' => 'StockController@importByExcel', 'as' => 'stock.importByExcel']);
    Route::get('stock/ajaxPosition', ['uses' => 'StockController@ajaxPosition', 'as' => 'stock.ajaxPosition']);
    Route::get('stock/ajaxSku', ['uses' => 'StockController@ajaxSku', 'as' => 'stock.ajaxSku']);
    Route::get('stock/createTaking', ['uses' => 'StockController@createTaking', 'as' => 'stock.createTaking']);
    Route::get('stock/allotSku', ['uses' => 'StockController@ajaxAllotSku', 'as' => 'stock.allotSku']);
    Route::get('stock/allotOutWarehouse',
        ['uses' => 'StockController@ajaxAllotOutWarehouse', 'as' => 'stock.allotOutWarehouse']);
    Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);
    Route::get('stock/getMessage', ['uses' => 'StockController@ajaxGetMessage', 'as' => 'stock.getMessage']);
    Route::get('stock/ajaxGetByPosition',
        ['uses' => 'StockController@ajaxGetByPosition', 'as' => 'stock.ajaxGetByPosition']);
    Route::get('stock/ajaxGetOnlyPosition',
        ['uses' => 'StockController@ajaxGetOnlyPosition', 'as' => 'stock.ajaxGetOnlyPosition']);
    Route::resource('stock', 'StockController');

    /*Route::group(['prefix' => 'admin', 'middleware' => 'roleCheck'], function() {
        Route::get('purchaseOrder/purchaseStaticstics', ['uses' => 'Purchase\PurchaseOrderController@purchaseStaticstics', 'as' => 'purchaseStaticstics']);
    });*/
    //采购条目
    //新品待入库
    Route::any('purchaseItem/purchaseItemIndex',
        ['uses' => 'Purchase\PurchaseItemController@purchaseItemIndex', 'as' => 'purchaseItemIndex']);
    Route::any('purchaseItem/cancelThisItem/{id}', 'Purchase\PurchaseItemController@cancelThisItem');
    Route::any('purchaseItem/deletePurchaseItem',
        ['uses' => 'Purchase\PurchaseItemController@deletePurchaseItem', 'as' => 'deletePurchaseItem']);
    Route::any('/purchaseItem/costExamineStatus/{id}/{costExamineStatus}',
        'Purchase\PurchaseItemController@costExamineStatus');
    Route::resource('purchaseItem', 'Purchase\PurchaseItemController');
    Route::any('beExamine', ['uses' => 'Product\SupplierController@beExamine', 'as' => 'beExamine']);
    //采购需求
    Route::any('/addPurchaseOrder', 'Purchase\RequireController@addPurchaseOrder');
    Route::resource('require', 'Purchase\RequireController');
    Route::any('purchase/require/createAllPurchaseOrder', [
        'uses' => 'Purchase\RequireController@createAllPurchaseOrder',
        'as' => 'purchaseRequire.createAllPurchaseOrder'
    ]);
    Route::any('purchaseOrder/exportOutOfStockCsv',
        ['uses' => 'Purchase\PurchaseOrderController@exportOutOfStockCsv', 'as' => 'purchase.exportOutOfStockCsv']);
    //未结算订单
    Route::resource('closePurchaseOrder', 'Purchase\ClosePurchaseOrderController');
    //采购单
    Route::get('purchase/purchaseAjaxSku',
        ['uses' => 'Purchase\PurchaseOrderController@purchaseAjaxSku', 'as' => 'purchaseAjaxSku']);
    //采购统计报表
    Route::get('purchaseOrder/purchaseStaticstics',
        ['uses' => 'Purchase\PurchaseOrderController@purchaseStaticstics', 'as' => 'purchaseStaticstics']);
    //到货记录报表
    Route::get('purchaseOrder/recieveReport',
        ['uses' => 'Purchase\PurchaseOrderController@recieveReport', 'as' => 'purchaseOrder.recieveReport']);
    //缺货报表
    Route::get('purchaseOrder/outOfStock',
        ['uses' => 'Purchase\PurchaseOrderController@outOfStock', 'as' => 'purchase.outOfStock']);
    //表格修改付款状态界面
    Route::get('purchaseOrder/excelPayOff',
        ['uses' => 'Purchase\PurchaseOrderController@excelPayOff', 'as' => 'purchaseOrder.excelPayOff']);
    //表格修改付款状态
    Route::any('purchaseOrder/excelPayOffExecute',
        ['uses' => 'Purchase\PurchaseOrderController@excelPayOffExecute', 'as' => 'purchaseOrder.excelPayOffExecute']);

    Route::get('purchaseOrder/sevenPurchaseSku',
        ['uses' => 'Purchase\PurchaseOrderController@sevenPurchaseSku', 'as' => 'purchase.sevenPurchaseSku']);
    Route::get('purchaseOrder/printButNotWarehouseIn',
        ['uses' => 'Purchase\PurchaseOrderController@printButNotWarehouseIn', 'as' => 'purchase.printButNotWarehouseIn']);
    Route::any('/purchaseOrder/addPost/{id}', 'Purchase\PurchaseOrderController@addPost');
    Route::any('PurchaseOrder/trackingNoSearch',
        ['uses' => 'Purchase\PurchaseOrderController@trackingNoSearch', 'as' => 'trackingNoSearch']);
    Route::any('purchaseOrder/changePrintStatus',
        ['uses' => 'Purchase\PurchaseOrderController@changePrintStatus', 'as' => 'changePrintStatus']);
    Route::any('purchaseOrder/payOrder/{id}',
        ['uses' => 'Purchase\PurchaseOrderController@payOrder', 'as' => 'payOrder']);
    Route::any('purchaseOrder/purchaseExmaine',
        ['uses' => 'Purchase\PurchaseOrderController@purchaseExmaine', 'as' => 'purchaseExmaine']);
    //批量删除和核销
    Route::any('purchaseOrder/batchConfirm',
        ['uses' => 'Purchase\PurchaseOrderController@batchConfirm', 'as' => 'purchaseOrder.batchConfirm']);
    Route::any('purchaseList/export/{str}', ['uses' => 'Purchase\PurchaseListController@export', 'as' => 'purchaseList.export']);
    Route::any('purchaseList/ajaxScan', ['uses' => 'Purchase\PurchaseListController@ajaxScan', 'as' => 'ajaxScan']);
    Route::any('purchaseOrder/recieve', ['uses' => 'Purchase\PurchaseOrderController@recieve', 'as' => 'recieve']);
    Route::any('purchaseOrder/printInWarehouseOrder/{id}', [
        'uses' => 'Purchase\PurchaseOrderController@printInWarehouseOrder',
        'as' => 'purchaseOrder.printInWarehouseOrder'
    ]);
    Route::any('purchaseOrder/printpo', ['uses' => 'Purchase\PurchaseOrderController@printpo', 'as' => 'printpo']);
    Route::any('purchaseOrder/showpo',
        ['uses' => 'Purchase\PurchaseOrderController@showpo', 'as' => 'purchase.showpo']);
    Route::any('purchaseOrder/ajaxInWarehouse',
        ['uses' => 'Purchase\PurchaseOrderController@ajaxInWarehouse', 'as' => 'ajaxInWarehouse']);
    Route::any('purchaseOrder/inWarehouse',
        ['uses' => 'Purchase\PurchaseOrderController@inWarehouse', 'as' => 'inWarehouse']);
    Route::any('purchaseOrder/ajaxRecieve',
        ['uses' => 'Purchase\PurchaseOrderController@ajaxRecieve', 'as' => 'ajaxRecieve']);
    //采购单提示
    Route::any('purchaseOrder/view',
        ['uses' => 'Purchase\PurchaseOrderController@view', 'as' => 'purchaseOrder.view']);

    Route::any('purchaseOrder/updateArriveNum',
        ['uses' => 'Purchase\PurchaseOrderController@updateArriveNum', 'as' => 'updateArriveNum']);
    Route::any('purchaseOrder/updateArriveLog',
        ['uses' => 'Purchase\PurchaseOrderController@updateArriveLog', 'as' => 'updateArriveLog']);
    //新品待入库界面入库
    Route::any('purchaseOrder/newProductupdateArriveLog',
        ['uses' => 'Purchase\PurchaseOrderController@newProductupdateArriveLog', 'as' => 'newProductupdateArriveLog']);

    Route::any('/purchaseOrder/updateItemWaitTime/{id}', 'Purchase\PurchaseOrderController@updateItemWaitTime');
    Route::any('/purchaseOrder/updateWaitTime/{id}', 'Purchase\PurchaseOrderController@updateWaitTime');
    Route::any('/purchaseOrder/createItem/{id}', 'Purchase\PurchaseOrderController@createItem');
    Route::any('/purchaseOrder/addItem/{id}', 'Purchase\PurchaseOrderController@addItem');
    Route::any('purchaseOrder/changeExamineStatus/{id}/{examinStatus}',
        'Purchase\PurchaseOrderController@changeExamineStatus');
    Route::any('purchaseOrder/examinePurchaseOrder', 'Purchase\PurchaseOrderController@examinePurchaseOrder');
    Route::any('purchaseOrder/excelOut/{id}', 'Purchase\PurchaseOrderController@excelOut');
    Route::any('purchaseOrder/write_off/{id}', 'Purchase\PurchaseOrderController@write_off');
    //采购单核销界面
    Route::any('purchaseOrder/writeOffIndex',
        ['uses' => 'Purchase\PurchaseOrderController@writeOffIndex', 'as' => 'purchaseOrder.writeOffIndex']);
    //采购单导出
    Route::any('purchaseOrder/purchaseOrdersOut',
        ['uses' => 'Purchase\PurchaseOrderController@purchaseOrdersOut', 'as' => 'purchaseOrder.purchaseOrdersOut']);
    //到货记录
    Route::any('purchaseOrder/purchaseArrivalLogOut',
        ['uses' => 'Purchase\PurchaseOrderController@purchaseArrivalLogOut', 'as' => 'purchaseOrder.purchaseArrivalLogOut']);
    //采购单核销格式导出
    Route::any('purchaseOrderConfirmCsvFormat', ['uses' => 'Purchase\PurchaseOrderController@purchaseOrderConfirmCsvFormat', 'as' => 'purchaseOrderConfirmCsvFormat']);
    //采购单付款格式导出
    Route::any('purchaseOrderPayOffCsvFormat', ['uses' => 'Purchase\PurchaseOrderController@purchaseOrderPayOffCsvFormat', 'as' => 'purchaseOrderPayOffCsvFormat']);
    //采购单核销导入
    Route::any('purchaseOrderConfirmCsvFormatExecute', ['uses' => 'Purchase\PurchaseOrderController@purchaseOrderConfirmCsvFormatExecute', 'as' => 'purchaseOrderConfirmCsvFormatExecute']);
    
    Route::any('purchaseOrder/excelOrderOut/{num}', 'Purchase\PurchaseOrderController@excelOrderOut');
    Route::any('/purchaseOrder/cancelOrder/{id}', 'Purchase\PurchaseOrderController@cancelOrder');
    Route::any('/purchaseOrder/printOrder/{id}', 'Purchase\PurchaseOrderController@printOrder');
    Route::any('postAdd', ['uses' => 'Purchase\PurchaseOrderController@ajaxPostAdd', 'as' => 'postAdd']);
    Route::resource('purchaseOrder', 'Purchase\PurchaseOrderController');
    //打印采购单
    Route::any('/checkWarehouse/address', 'Purchase\PrintPurchaseOrderController@warehouseAddress');
    Route::any('/checkWarehouse', 'Purchase\PrintPurchaseOrderController@checkWarehouse');
    Route::resource('printPurchaseOrder', 'Purchase\PrintPurchaseOrderController');
    //采购列表
    Route::any('purchaseItemArrival',
        ['uses' => 'Purchase\PurchaseListController@purchaseItemArrival', 'as' => 'purchaseItemArrival']);
    Route::any('selectPurchaseOrder',
        ['uses' => 'Purchase\PurchaseListController@selectPurchaseOrder', 'as' => 'selectPurchaseOrder']);
    Route::any('deletePostage', ['uses' => 'Purchase\PurchaseListController@deletePostage', 'as' => 'deletePostage']);
    Route::any('binding', ['uses' => 'Purchase\PurchaseListController@binding', 'as' => 'binding']);
    Route::any('purchaseList/stockIn/{id}', 'Purchase\PurchaseListController@stockIn');
    Route::any('purchaseList/generateDarCode/{id}', 'Purchase\PurchaseListController@generateDarCode');
    Route::any('purchaseList/printBarCode/{id}', 'Purchase\PurchaseListController@printBarCode');
    Route::any('purchaseList/activeChange/{id}', 'Purchase\PurchaseListController@activeChange');
    Route::any('purchaseList/updateActive/{id}', 'Purchase\PurchaseListController@updateActive');
    Route::any('/changeItemWeight', 'Purchase\PurchaseListController@changeItemWeight');
    Route::any('/changePurchaseItemPostcoding', 'Purchase\PurchaseListController@changePurchaseItemPostcoding');
    Route::any('/changePurchaseItemStorageQty', 'Purchase\PurchaseListController@changePurchaseItemStorageQty');
    Route::any('examinePurchaseItem',
        ['uses' => 'Purchase\PurchaseListController@examinePurchaseItem', 'as' => 'examinePurchaseItem']);
    Route::resource('purchaseList', 'Purchase\PurchaseListController');
    //异常条目采购
    Route::resource('purchaseAbnormal', 'Purchase\PurchaseAbnormalController');
    //异常单采购
    Route::any('purchaseOrderAbnormal/cancelOrder/{id}', 'Purchase\PurchaseOrderAbnormalController@cancelOrder');
    Route::resource('purchaseOrderAbnormal', 'Purchase\PurchaseOrderAbnormalController');
    //采购入库
    Route::any('/purchaseStockIn/updateStorage', 'Purchase\PurchaseStockInController@updateStorage');
    Route::get('/manyStockIn', ['uses' => 'Purchase\PurchaseStockInController@manyStockIn', 'as' => 'manyStockIn']);
    Route::resource('purchaseStockIn', 'Purchase\PurchaseStockInController');
    //采购条目
    Route::any('/purchaseItemList/postExcelReduction', 'Purchase\PurchaseItemListController@postExcelReduction');
    Route::any('/purchaseItemList/excelReductionUpdatePost',
        'Purchase\PurchaseItemListController@excelReductionUpdatePost');
    Route::any('/purchaseItemList/excelReductionUpdate', 'Purchase\PurchaseItemListController@excelReductionUpdate');
    Route::any('/purchaseItemList/purchaseItemPriceExcel',
        'Purchase\PurchaseItemListController@purchaseItemPriceExcel');
    Route::any('/purchaseItemList/purchaseItemPostExcel', 'Purchase\PurchaseItemListController@purchaseItemPostExcel');
    Route::any('/purchaseItemList/excelReduction', 'Purchase\PurchaseItemListController@excelReduction');
    Route::any('/purchaseItemList/reduction', 'Purchase\PurchaseItemListController@purchaseItemReduction');
    Route::any('/purchaseItemList/reductionUpdate', 'Purchase\PurchaseItemListController@reductionUpdate');
    Route::any('/purchaseItemList/itemReductionUpdate/{id}', 'Purchase\PurchaseItemListController@itemReductionUpdate');
    Route::resource('purchaseItemList', 'Purchase\PurchaseItemListController');
    Route::resource('purchaseAccount', 'Purchase\AccountController');

    //品类路由
    Route::any('catalog/ajaxCatalog', ['uses' => 'CatalogController@ajaxCatalog', 'as' => 'ajaxCatalog']);
    Route::resource('catalog', 'CatalogController');
    Route::get('catalog/exportCatalogRates/{str}',
        ['uses' => 'CatalogController@exportCatalogRates', 'as' => 'catalog.exportCatalogRates']);
    Route::get('catalog/editCatalogRates/{str}',
        ['uses' => 'CatalogController@editCatalogRates', 'as' => 'catalog.editCatalogRates']);
    Route::any('updateCatalogRates', ['uses' => 'CatalogController@updateCatalogRates', 'as' => 'updateCatalogRates']);
    Route::any('catalogCsvFormat', ['uses' => 'CatalogController@catalogCsvFormat', 'as' => 'catalogCsvFormat']);
    Route::any('addLotsOfCatalogs', ['uses' => 'CatalogController@addLotsOfCatalogs', 'as' => 'addLotsOfCatalogs']);

    //item路由
    Route::get('item/sectionGangedDouble', ['uses' => 'ItemController@sectionGangedDouble', 'as' => 'item.sectionGangedDouble']);
    Route::get('item.getModel', ['uses' => 'ItemController@getModel', 'as' => 'item.getModel']);
    Route::get('item/print', ['uses' => 'ItemController@printsku', 'as' => 'item.print']);
    Route::get('itemUser/ajaxSupplierUser',
        ['uses' => 'ItemController@ajaxSupplierUser', 'as' => 'item.ajaxSupplierUser']);
    Route::any('item/changePurchaseAdmin/{id}',
        ['uses' => 'ItemController@changePurchaseAdmin', 'as' => 'changePurchaseAdmin']);
    Route::any('item/question/{id}', ['uses' => 'ItemController@question', 'as' => 'item.question']);
    
    Route::any('item/changeNewSku/{id}', ['uses' => 'ItemController@changeNewSku', 'as' => 'item.changeNewSku']);

    Route::any('item/addSupplier/{id}', ['uses' => 'ItemController@addSupplier', 'as' => 'item.addSupplier']);
    Route::any('item/questionStatus', ['uses' => 'ItemController@questionStatus', 'as' => 'item.questionStatus']);
    Route::any('item/extraQuestion', ['uses' => 'ItemController@extraQuestion', 'as' => 'item.extraQuestion']);
    Route::any('item/answer', ['uses' => 'ItemController@answer', 'as' => 'item.answer']);
    Route::any('item/questionIndex', ['uses' => 'ItemController@questionIndex', 'as' => 'item.questionIndex']);
    //Route::any('item/skushowpo', ['uses' => 'Purchase\PurchaseOrderController@showpo', 'as' => 'purchase.skushowpo']);
    Route::get('item.getImage', ['uses' => 'ItemController@getImage', 'as' => 'item.getImage']);
    Route::any('item/uploadSku', ['uses' => 'ItemController@uploadSku', 'as' => 'item.uploadSku']);
    Route::any('item/batchDelete', ['uses' => 'ItemController@batchDelete', 'as' => 'item.batchDelete']);
    Route::any('item/batchEdit', ['uses' => 'ItemController@batchEdit', 'as' => 'batchEdit']);
    Route::any('item/batchUpdate', ['uses' => 'ItemController@batchUpdate', 'as' => 'batchUpdate']);
    Route::any('item/oneKeyUpdateSku', ['uses' => 'ItemController@oneKeyUpdateSku', 'as' => 'item.oneKeyUpdateSku']);
    Route::resource('item', 'ItemController');
    //渠道路由
    Route::resource('channel', 'ChannelController');
    Route::resource('CatalogRatesChannel', 'Channel\CatalogRatesChannelController');
    //渠道账号路由
    Route::any('channelAccount/getAccountUser',
        ['uses' => 'Channel\AccountController@getAccountUser', 'as' => 'getAccountUser']);
    Route::post('channelAccount/updateApi/{id}',
        ['uses' => 'Channel\AccountController@updateApi', 'as' => 'channelAccount.updateApi']);
    Route::resource('channelAccount', 'Channel\AccountController');
    //库存调拨
    Route::post('allotment/checkResult/{id}',
        ['uses' => 'Stock\AllotmentController@checkResult', 'as' => 'allotment.checkResult']);
    Route::get('allotment/over/{id}', ['uses' => 'Stock\AllotmentController@allotmentOver', 'as' => 'allotment.over']);
    Route::post('allotment/getLogistics/{id}',
        ['uses' => 'Stock\AllotmentController@getLogistics', 'as' => 'allotment.getLogistics']);
    Route::get('allotment/new', ['uses' => 'Stock\AllotmentController@ajaxAllotmentNew', 'as' => 'allotment.new']);
    Route::get('allotment/checkout',
        ['uses' => 'Stock\AllotmentController@checkout', 'as' => 'allotment.checkout']);
    Route::get('allotment/add', ['uses' => 'Stock\AllotmentController@ajaxAllotmentAdd', 'as' => 'allotment.add']);
    Route::post('allotment/checkformUpdate/{id}',
        ['uses' => 'Stock\AllotmentController@checkformupdate', 'as' => 'allotment.checkformUpdate']);
    Route::get('allotment/checkform/{id}',
        ['uses' => 'Stock\AllotmentController@checkform', 'as' => 'allotment.checkform']);
    Route::get('allotment/pick/{id}', ['uses' => 'Stock\AllotmentController@allotmentpick', 'as' => 'allotment.pick']);
    Route::get('allotment/check/{id}',
        ['uses' => 'Stock\AllotmentController@allotmentCheck', 'as' => 'allotment.check']);
    Route::resource('stockAllotment', 'Stock\AllotmentController');
    //库存结转
    Route::post('stockCarryOver/showStockView',
        ['uses' => 'Stock\CarryOverController@showStockView', 'as' => 'stockCarryOver.showStockView']);
    Route::get('stockCarryOver/showStock',
        ['uses' => 'Stock\CarryOverController@showStock', 'as' => 'stockCarryOver.showStock']);
    Route::get('stockCarryOver/createCarryOver',
        ['uses' => 'Stock\CarryOverController@createCarryOver', 'as' => 'stockCarryOver.createCarryOver']);
    Route::post('stockCarryOver/createCarryOverResult',
        ['uses' => 'Stock\CarryOverController@createCarryOverResult', 'as' => 'stockCarryOver.createCarryOverResult']);
    Route::resource('stockCarryOver', 'Stock\CarryOverController');
    //库存盘点
    Route::get('StockTaking/takingAdjustmentShow/{id}',
        ['uses' => 'Stock\TakingController@takingAdjustmentShow', 'as' => 'StockTaking.takingAdjustmentShow']);
    Route::get('StockTaking/takingCreate',
        ['uses' => 'Stock\TakingController@ajaxtakingCreate', 'as' => 'stockTaking.takingCreate']);
    Route::get('StockTaking/takingCheck/{id}',
        ['uses' => 'Stock\TakingController@takingCheck', 'as' => 'stockTaking.takingCheck']);
    Route::post('StockTaking/takingCheckResult/{id}',
        ['uses' => 'Stock\TakingController@takingCheckResult', 'as' => 'stockTaking.takingCheckResult']);
    Route::resource('stockTaking', 'Stock\TakingController');
    //物流限制
    Route::resource('logisticsLimits', 'Logistics\LimitsController');

    //物流渠道路由
    Route::resource('logisticsChannelName', 'Logistics\ChannelNameController');
    //物流路由
    Route::get('logisticsCode/one/{id}',
        ['uses' => 'Logistics\CodeController@one', 'as' => 'logisticsCode.one']);
    Route::get('logisticsZone/one/{id}',
        ['uses' => 'Logistics\ZoneController@one', 'as' => 'logisticsZone.one']);
    Route::get('logisticsRule/one/{id}',
        ['uses' => 'Logistics\RuleController@one', 'as' => 'logisticsRule.one']);
    Route::get('logistics/getLogistics',
        ['uses' => 'LogisticsController@getLogistics', 'as' => 'logistics.getLogistics']);
    Route::get('logistics/ajaxSupplier',
        ['uses' => 'LogisticsController@ajaxSupplier', 'as' => 'logistics.ajaxSupplier']);
    Route::get('logistics/ajaxLogistics',
        ['uses' => 'Logistics\TemplateController@ajaxLogistics', 'as' => 'logistics.ajaxLogistics']);
    Route::any('template/preview',
        ['uses' => 'Logistics\TemplateController@preview', 'as' => 'template.preview']);
    Route::get('queren', ['uses' => 'Logistics\TemplateController@queren', 'as' => 'queren']);
    Route::get('logistics/createData', ['uses' => 'LogisticsController@createData', 'as' => 'logistics.createData']);
    Route::get('logisticsZone/createData', ['uses' => 'Logistics\ZoneController@createData', 'as' => 'logisticsZone.createData']);
    Route::resource('logistics', 'LogisticsController');
    Route::resource('logisticsSupplier', 'Logistics\SupplierController');
    Route::resource('logisticsCollectionInfo', 'Logistics\CollectionInfoController');
    Route::resource('logisticsCode', 'Logistics\CodeController');
    Route::get('updateEnable', ['uses' => 'LogisticsController@updateEnable', 'as' => 'updateEnable']);
    Route::get('logisticsZone/getCountries',
        ['uses' => 'Logistics\ZoneController@getCountries', 'as' => 'logisticsZone.getCountries']);
    Route::get('logisticsZone/sectionAdd',
        ['uses' => 'Logistics\ZoneController@sectionAdd', 'as' => 'logisticsZone.sectionAdd']);
    Route::resource('logisticsZone', 'Logistics\ZoneController');
    Route::get('country', ['uses' => 'Logistics\ZoneController@country', 'as' => 'country']);
    Route::get('zoneShipping', ['uses' => 'Logistics\ZoneController@zoneShipping', 'as' => 'zoneShipping']);
    Route::get('count', ['uses' => 'Logistics\ZoneController@count', 'as' => 'count']);
    Route::get('countExpress/{id}', ['uses' => 'Logistics\ZoneController@countExpress', 'as' => 'countExpress']);
    Route::get('countPacket/{id}', ['uses' => 'Logistics\ZoneController@countPacket', 'as' => 'countPacket']);
    Route::get('batchAddTrCode/{logistic_id}',
        ['uses' => 'Logistics\CodeController@batchAddTrCode', 'as' => 'batchAddTrCode']);
    Route::post('logisticsCodeFn', ['uses' => 'Logistics\CodeController@batchAddTrCodeFn', 'as' => 'logisticsCodeFn']);
    Route::get('scanAddTrCode/{logistic_id}',
        ['uses' => 'Logistics\CodeController@scanAddTrCode', 'as' => 'scanAddTrCode']);
    Route::post('scanAddTrCodeFn', ['uses' => 'Logistics\CodeController@scanAddTrCodeFn', 'as' => 'scanAddTrCodeFn']);
    Route::get('logisticsRule/createData',
        ['uses' => 'Logistics\RuleController@createData', 'as' => 'logisticsRule.createData']);
    Route::resource('logisticsRule', 'Logistics\RuleController');
    Route::get('bhw', ['uses' => 'Logistics\RuleController@bhw', 'as' => 'bhw']);
    Route::resource('logisticsCatalog', 'Logistics\CatalogController');
    Route::resource('logisticsEmailTemplate', 'Logistics\EmailTemplateController');
    Route::resource('logisticsTemplate', 'Logistics\TemplateController');
    Route::get('confirm',
        ['uses' => 'Logistics\TemplateController@confirm', 'as' => 'confirm']);
    Route::resource('logisticsTransport', 'Logistics\TransportController');
    Route::resource('logisticsPartition', 'Logistics\PartitionController');
    Route::get('view/{id}', ['uses' => 'Logistics\TemplateController@view', 'as' => 'view']);
    Route::get('templateMsg/{id}', ['uses' => 'PackageController@templateMsg', 'as' => 'templateMsg']);
    //拣货单异常
    Route::get('errorList/exportException/{arr}',
        ['uses' => 'Picklist\ErrorListController@exportException', 'as' => 'errorList.exportException']);
    Route::resource('errorList', 'Picklist\ErrorListController');
    //拣货路由
    Route::post('pickList/createNewPickStore',
        ['uses' => 'PickListController@createNewPickStore', 'as' => 'pickList.createNewPickStore']);
    Route::get('pickList/createNewPick',
        ['uses' => 'PickListController@createNewPick', 'as' => 'pickList.createNewPick']);
    Route::get('pickList/printInfo',
        ['uses' => 'PickListController@printInfo', 'as' => 'pickList.printInfo']);
    Route::get('pickList/changePickBy',
        ['uses' => 'PickListController@changePickBy', 'as' => 'pickList.changePickBy']);

    Route::get('pickList/pickCode/{id}',
        ['uses' => 'PickListController@pickCode', 'as' => 'pickList.pickCode']);

    Route::post('pickList/confirmPickBy',
        ['uses' => 'PickListController@confirmPickBy', 'as' => 'pickList.confirmPickBy']);
    Route::any('pickList/printPackageDetails/{id}/{status}',
        ['uses' => 'PickListController@printPackageDetails', 'as' => 'pickList.printPackageDetails']);
    Route::any('pickList/printException/',
        ['uses' => 'PickListController@printException', 'as' => 'pickList.printException']);
    Route::post('pickList/statisticsProcess',
        ['uses' => 'PickListController@statisticsProcess', 'as' => 'pickList.statisticsProcess']);
    Route::get('pickList/performanceStatistics',
        ['uses' => 'PickListController@performanceStatistics', 'as' => 'pickList.performanceStatistics']);
    Route::get('pickList/oldPrint', ['uses' => 'PickListController@oldPrint', 'as' => 'pickList.oldPrint']);
    Route::get('pickList/updatePrint', ['uses' => 'PickListController@updatePrint', 'as' => 'pickList.updatePrint']);
    Route::post('pickList/processBase', ['uses' => 'PickListController@processBase', 'as' => 'pickList.processBase']);
    Route::get('pickList/indexPrintPickList/{content}',
        ['uses' => 'PickListController@indexPrintPickList', 'as' => 'pickList.indexPrintPickList']);
    Route::post('pickList/inboxStore/{id}', ['uses' => 'PickListController@inboxStore', 'as' => 'pickList.inboxStore']);
    Route::post('pickList/createPickStore',
        ['uses' => 'PickListController@createPickStore', 'as' => 'pickList.createPickStore']);
    Route::get('pickList/createPick', ['uses' => 'PickListController@createPick', 'as' => 'pickList.createPick']);
    Route::get('pickList/inboxResult',
        ['uses' => 'PickListController@ajaxInboxResult', 'as' => 'pickList.inboxResult']);
    Route::get('pickList/inbox/{id}', ['uses' => 'PickListController@inbox', 'as' => 'pickList.inbox']);
    Route::get('pickList/packageItemUpdate',
        ['uses' => 'PickListController@ajaxPackageItemUpdate', 'as' => 'pickList.packageItemUpdate']);
    Route::post('pickList/packageStore/{id}',
        ['uses' => 'PickListController@packageStore', 'as' => 'pickList.packageStore']);
    Route::get('pickList/package/{id}', ['uses' => 'PickListController@pickListPackage', 'as' => 'pickList.package']);
    Route::get('pickList/print/{id}', ['uses' => 'PickListController@printPickList', 'as' => 'pickList.print']);
    Route::get('pickList/type', ['uses' => 'PickListController@ajaxType', 'as' => 'pickList.type']);
    Route::resource('pickList', 'PickListController');
    //产品管理路由
    Route::any('productInfo', ['uses' => 'ProductController@productInfo', 'as' => 'productInfo']);
    Route::any('productBatchEdit', ['uses' => 'ProductController@productBatchEdit', 'as' => 'productBatchEdit']);
    Route::any('productBatchUpdate', ['uses' => 'ProductController@productBatchUpdate', 'as' => 'productBatchUpdate']);
    Route::any('product/getCatalogProperty', 'ProductController@getCatalogProperty');
    Route::get('examine', ['uses' => 'ProductController@examine', 'as' => 'examine']);
    Route::get('productMultiEdit', ['uses' => 'ProductController@productMultiEdit', 'as' => 'productMultiEdit']);
    Route::any('productMultiUpdate', ['uses' => 'ProductController@productMultiUpdate', 'as' => 'productMultiUpdate']);
    Route::get('choseShop', ['uses' => 'ProductController@choseShop', 'as' => 'choseShop']);
    Route::any('product/examineProduct',
        ['uses' => 'Product\EditProductController@examineProduct', 'as' => 'examineProduct']);
    Route::any('product/editImage',
        ['uses' => 'Product\EditProductController@productEditImage', 'as' => 'productEditImage']);
    Route::any('product/updateImage',
        ['uses' => 'Product\EditProductController@productUpdateImage', 'as' => 'productUpdateImage']);
    Route::resource('product', 'ProductController');
    Route::any('examineProduct/examineAll',
        ['uses' => 'Product\ExamineProductController@examineAll', 'as' => 'productExamineAll']);
    Route::resource('ExamineProduct', 'Product\ExamineProductController');
    Route::any('ajaxReturnPrice',
        ['as' => 'product.ajaxReturnPrice', 'uses' => 'ProductController@ajaxReturnPrice']);
    Route::resource('CatalogCategory', 'Product\CatalogCategoryController');


    //产品渠道
    Route::any('beChosed', ['uses' => 'Product\SelectProductController@beChosed', 'as' => 'beChosed']);
    Route::any('product/price', ['uses' => 'Product\EditProductController@price', 'as' => 'productPrice']);
    Route::resource('EditProduct', 'Product\EditProductController');
    Route::resource('SelectProduct', 'Product\SelectProductController');
    Route::resource('PublishProduct', 'Product\PublishProductController');
    Route::get('cancelExamineAmazonProduct',
        [
            'uses' => 'Product\Channel\AmazonController@cancelExamineAmazonProduct',
            'as' => 'cancelExamineAmazonProduct'
        ]);
    //订单管理路由
    Route::get('order/logisticsFee',
        ['uses' => 'OrderController@logisticsFee', 'as' => 'order.logisticsFee']);
    Route::get('order/createVirtualPackage',
        ['uses' => 'OrderController@createVirtualPackage', 'as' => 'order.createVirtualPackage']);
    Route::get('refund/{id}', ['uses' => 'OrderController@refund', 'as' => 'refund']);

    Route::get('order/ajaxCountry', ['uses' => 'OrderController@ajaxCountry', 'as' => 'order.ajaxCountry']);
    Route::get('order/ajaxSku', ['uses' => 'OrderController@ajaxSku', 'as' => 'order.ajaxSku']);
    Route::get('orderStatistics', ['uses' => 'OrderController@orderStatistics', 'as' => 'orderStatistics']);
    Route::resource('order', 'OrderController');
    Route::resource('ebaySkuSaleReport', 'Order\EbaySkuSaleReportController');
    Route::resource('ebayAmountStatistics', 'Order\EbayAmountStatisticsController');
    Route::resource('orderItem', 'Order\ItemController');
    Route::resource('unpaidOrder', 'Order\UnpaidOrderController');
    Route::get('orderAdd', ['uses' => 'OrderController@ajaxOrderAdd', 'as' => 'orderAdd']);
    Route::resource('orderBlacklist', 'Order\BlacklistController');
    Route::resource('blacklistAddress', 'Order\BlacklistAddressController');
    Route::any('withdrawAll', ['uses' => 'OrderController@withdrawAll', 'as' => 'withdrawAll']);
    Route::any('partReview', ['uses' => 'OrderController@partReview', 'as' => 'partReview']);
    Route::any('blacklist/listAll', ['uses' => 'Order\BlacklistController@listAll', 'as' => 'listAll']);
    Route::get('updateStatus', ['uses' => 'OrderController@updateStatus', 'as' => 'updateStatus']);
    Route::get('updatePrepared', ['uses' => 'OrderController@updatePrepared', 'as' => 'updatePrepared']);
    Route::get('updateNormal', ['uses' => 'OrderController@updateNormal', 'as' => 'updateNormal']);
    Route::get('updateRecover', ['uses' => 'OrderController@updateRecover', 'as' => 'updateRecover']);
    Route::get('withdraw/{id}', ['uses' => 'OrderController@withdraw', 'as' => 'withdraw']);
    Route::post('withdrawUpdate/{id}', ['uses' => 'OrderController@withdrawUpdate', 'as' => 'withdrawUpdate']);
    Route::any('ajaxWithdraw', ['uses' => 'OrderController@ajaxWithdraw', 'as' => 'ajaxWithdraw']);
    Route::any('refund/{id}', ['uses' => 'OrderController@refund', 'as' => 'refund']);
    Route::get('remark/{id}', ['uses' => 'OrderController@remark', 'as' => 'remark']);
    Route::post('remarkUpdate/{id}', ['uses' => 'OrderController@remarkUpdate', 'as' => 'remarkUpdate']);
    Route::post('refundUpdate/{id}', ['uses' => 'OrderController@refundUpdate', 'as' => 'refundUpdate']);
    Route::any('ajaxAddRefund', ['uses' => 'OrderController@ajaxAddRefund', 'as' => 'ajaxAddRefund']);
    Route::get('getBlacklist', ['uses' => 'Order\BlacklistController@getBlacklist', 'as' => 'getBlacklist']);
    Route::any('exportAll', ['uses' => 'Order\BlacklistController@exportAll', 'as' => 'exportAll']);
    Route::any('exportPart', ['uses' => 'Order\BlacklistController@exportPart', 'as' => 'exportPart']);
    Route::post('uploadBlacklist', ['uses' => 'Order\BlacklistController@uploadBlacklist', 'as' => 'uploadBlacklist']);
    Route::get('invoice/{id}', ['uses' => 'OrderController@invoice', 'as' => 'invoice']);
    Route::get('downloadUpdateBlacklist',
        ['uses' => 'Order\BlacklistController@downloadUpdateBlacklist', 'as' => 'downloadUpdateBlacklist']);
    //订单投诉
    Route::resource('orderComplaint', 'Order\OrderComplaintController');
    //物流报表
    Route::get('package/logisticsDelivery',
        ['uses' => 'PackageController@logisticsDelivery', 'as' => 'package.logisticsDelivery']);

    //sku报表
    Route::get('sku/saleReport',
        ['uses' => 'OrderController@saleReport', 'as' => 'sku.saleReport']);
    Route::get('sku/amountStatistics',
        ['uses' => 'OrderController@amountStatistics', 'as' => 'sku.amountStatistics']);

    //包裹报表
    Route::get('allReport/createData',
        ['uses' => 'AllReportController@createData', 'as' => 'allReport.createData']);
    Route::get('allReport/report',
        ['uses' => 'AllReportController@packageReport', 'as' => 'allReport.report']);
    Route::resource('allReport', 'AllReportController');
    //包裹导出
    Route::get('exportPackage/getTnoReturnExcel',
        ['uses' => 'ExportPackageController@getTnoReturnExcel', 'as' => 'exportPackage.getTnoReturnExcel']);
    Route::get('exportPackage/getTnoExcelById',
        ['uses' => 'ExportPackageController@getTnoExcelById', 'as' => 'exportPackage.getTnoExcelById']);
    Route::get('exportPackage/getTnoExcel',
        ['uses' => 'ExportPackageController@getTnoExcel', 'as' => 'exportPackage.getTnoExcel']);
    Route::get('exportPackage/extraField',
        ['uses' => 'ExportPackageController@extraField', 'as' => 'exportPackage.extraField']);
    Route::get('exportPackage/extraField',
        ['uses' => 'ExportPackageController@extraField', 'as' => 'exportPackage.extraField']);
    Route::post('exportPackage/exportPackageDetail',
        ['uses' => 'ExportPackageController@exportPackageDetail', 'as' => 'exportPackage.exportPackageDetail']);
    Route::get('exportPackage/exportPackageView',
        ['uses' => 'ExportPackageController@exportPackageView', 'as' => 'exportPackage.exportPackageView']);
    Route::resource('exportPackage', 'ExportPackageController');

    //包裹管理路由
    Route::get('package/sectionGanged',
        ['uses' => 'PackageController@sectionGanged', 'as' => 'package.sectionGanged']);
    Route::get('package/multiPlace/{arr}',
        ['uses' => 'PackageController@multiPlace', 'as' => 'package.multiPlace']);
    Route::get('package/downloadLogisticsTno',
        ['uses' => 'PackageController@downloadLogisticsTno', 'as' => 'package.downloadLogisticsTno']);
    Route::get('package/getAllInfo',
        ['uses' => 'PackageController@getAllInfo', 'as' => 'package.getAllInfo']);
    Route::get('package/showAllView',
        ['uses' => 'PackageController@showAllView', 'as' => 'package.showAllView']);
    Route::get('package/errorToShipped',
        ['uses' => 'PackageController@errorToShipped', 'as' => 'package.errorToShipped']);
    Route::get('package/exportInfo',
        ['uses' => 'PackageController@exportInfo', 'as' => 'package.exportInfo']);
    Route::get('package/ajaxReturnInShelf',
        ['uses' => 'PackageController@ajaxReturnInShelf', 'as' => 'package.ajaxReturnInShelf']);
    Route::get('package/returnGoodsInShelf',
        ['uses' => 'PackageController@returnGoodsInShelf', 'as' => 'package.returnGoodsInShelf']);
    Route::get('package/processingAssignStocks',
        ['uses' => 'PackageController@processingAssignStocks', 'as' => 'package.processingAssignStocks']);
    Route::get('package/ajaxRealTime',
        ['uses' => 'PackageController@ajaxRealTime', 'as' => 'package.ajaxRealTime']);
    Route::get('package/recycle',
        ['uses' => 'PackageController@recycle', 'as' => 'package.recycle']);
    Route::get('package/retrack',
        ['uses' => 'PackageController@retrack', 'as' => 'package.retrack']);
    Route::get('package/autoFailAssignLogistics',
        ['uses' => 'PackageController@autoFailAssignLogistics', 'as' => 'package.autoFailAssignLogistics']);
    Route::get('package/bagInfo',
        ['uses' => 'PackageController@bagInfo', 'as' => 'package.bagInfo']);
    Route::get('package/packageReport',
        ['uses' => 'PackageController@packageReport', 'as' => 'package.packageReport']);
    Route::get('package/removePackages/{arr}',
        ['uses' => 'PackageController@removePackages', 'as' => 'package.removePackages']);
    Route::get('package/removeLogistics/{arr}',
        ['uses' => 'PackageController@removeLogistics', 'as' => 'package.removeLogistics']);
    Route::get('package/changeLogistics/{arr}/{id}',
        ['uses' => 'PackageController@changeLogistics', 'as' => 'package.changeLogistics']);
    Route::get('package/putNeedQueue',
        ['uses' => 'PackageController@putNeedQueue', 'as' => 'package.putNeedQueue']);
    Route::post('package/processReturnGoods',
        ['uses' => 'PackageController@processReturnGoods', 'as' => 'package.processReturnGoods']);
    Route::get('package/returnGoods',
        ['uses' => 'PackageController@returnGoods', 'as' => 'package.returnGoods']);
    Route::get('package/forceOutPackage',
        ['uses' => 'PackageController@forceOutPackage', 'as' => 'package.forceOutPackage']);
    Route::get('package/implodePackage/{arr}',
        ['uses' => 'PackageController@implodePackage', 'as' => 'package.implodePackage']);
    Route::get('package/actSplitPackage/{arr}/{id}',
        ['uses' => 'PackageController@actSplitPackage', 'as' => 'package.actSplitPackage']);
    Route::get('package/returnSplitPackage',
        ['uses' => 'PackageController@returnSplitPackage', 'as' => 'package.returnSplitPackage']);
    Route::get('package/downloadTrackingNo',
        ['uses' => 'PackageController@downloadTrackingNo', 'as' => 'package.downloadTrackingNo']);
    Route::post('package/editTrackStore/{id}',
        ['uses' => 'PackageController@editTrackStore', 'as' => 'package.editTrackStore']);
    Route::get('package/editTrackingNo/{id}',
        ['uses' => 'PackageController@editTrackingNo', 'as' => 'package.editTrackingNo']);
    Route::get('package/ajaxUpdatePackageLogistics',
        ['uses' => 'PackageController@ajaxUpdatePackageLogistics', 'as' => 'package.ajaxUpdatePackageLogistics']);
    Route::get('package/ajaxReturnPackageId',
        ['uses' => 'PackageController@ajaxReturnPackageId', 'as' => 'package.ajaxReturnPackageId']);
    Route::get('package/multiPackage', ['uses' => 'PackageController@multiPackage', 'as' => 'package.multiPackage']);
    Route::get('package/ctrlZ', ['uses' => 'PackageController@ctrlZ', 'as' => 'package.ctrlZ']);
    Route::get('package/manualLogistics',
        ['uses' => 'PackageController@manualLogistics', 'as' => 'package.manualLogistics']);
    Route::get('package/manualShipping',
        ['uses' => 'PackageController@manualShipping', 'as' => 'package.manualShipping']);
    Route::get('package/setManualLogistics',
        ['uses' => 'PackageController@setManualLogistics', 'as' => 'package.setManualLogistics']);
    Route::get('package/ajaxQuantityProcess',
        ['uses' => 'PackageController@ajaxQuantityProcess', 'as' => 'package.ajaxQuantityProcess']);
    Route::get('package/downloadType', ['uses' => 'PackageController@downloadType', 'as' => 'package.downloadType']);
    Route::get('package/downloadFee', ['uses' => 'PackageController@downloadFee', 'as' => 'package.downloadFee']);
    Route::get('package/allocateLogistics/{id}',
        ['uses' => 'PackageController@allocateLogistics', 'as' => 'package.allocateLogistics']);
    Route::post('package/storeAllocateLogistics/{id}',
        ['uses' => 'PackageController@storeAllocateLogistics', 'as' => 'package.storeAllocateLogistics']);
    Route::post('package/excelProcessFee/{type}',
        ['uses' => 'PackageController@excelProcessFee', 'as' => 'package.excelProcessFee']);
    Route::get('package/returnTrackno', ['uses' => 'PackageController@returnTrackno', 'as' => 'package.returnTrackno']);
    Route::post('package/excelProcess', ['uses' => 'PackageController@excelProcess', 'as' => 'package.excelProcess']);
    Route::get('package/returnFee', ['uses' => 'PackageController@returnFee', 'as' => 'package.returnFee']);
    Route::get('package/exportManualPackage/{str}',
        ['uses' => 'PackageController@exportManualPackage', 'as' => 'package.exportManualPackage']);
    Route::get('package/ajaxWeight', ['uses' => 'PackageController@ajaxWeight', 'as' => 'package.ajaxWeight']);
    Route::post('package/exportData', ['uses' => 'PackageController@exportData', 'as' => 'package.exportData']);
    Route::get('package/shippingStatistics',
        ['uses' => 'PackageController@shippingStatistics', 'as' => 'package.shippingStatistics']);
    Route::get('package/ajaxShippingExec',
        ['uses' => 'PackageController@ajaxShippingExec', 'as' => 'package.ajaxShippingExec']);
    Route::get('package/shipping', ['uses' => 'PackageController@shipping', 'as' => 'package.shipping']);
    Route::get('package/ajaxPackageSend',
        ['uses' => 'PackageController@ajaxPackageSend', 'as' => 'package.ajaxPackageSend']);
    Route::any('package/ajaxGetOrder', ['uses' => 'PackageController@ajaxGetOrder', 'as' => 'package.ajaxGetOrder']);
    Route::get('package/assignLogistics',
        ['uses' => 'PackageController@assignLogistics', 'as' => 'package.assignLogistics']);
    Route::get('package/placeLogistics',
        ['uses' => 'PackageController@placeLogistics', 'as' => 'package.placeLogistics']);
    Route::get('package/flow',
        ['uses' => 'PackageController@flow', 'as' => 'package.flow']);
    Route::get('preview/{id}',
        ['uses' => 'PackageController@preview', 'as' => 'preview']);
    Route::resource('package', 'PackageController');

    Route::get('account', ['uses' => 'OrderController@account', 'as' => 'account']);
    Route::get('getMsg', ['uses' => 'OrderController@getMsg', 'as' => 'getMsg']);
    Route::get('getChoiesOrder', ['uses' => 'OrderController@getChoiesOrder', 'as' => 'getChoiesOrder']);
    Route::get('getCode', ['uses' => 'OrderController@getCode', 'as' => 'getCode']);
    Route::get('getAliExpressOrder', ['uses' => 'OrderController@getAliExpressOrder', 'as' => 'getAliExpressOrder']);
    //用户路由
    Route::resource('user', 'UserController');
    //图片标签
    Route::resource('label', 'LabelController');
    Route::resource('paypal', 'PaypalController');
    Route::any('updatePaypalRates', ['uses' => 'PaypalController@updatePaypalRates', 'as' => 'paypal.update_rates']);
    Route::any('ShowPaypalRate', ['uses' => 'PaypalController@ShowPaypalRate', 'as' => 'paypal.ShowPaypalRate']);
    //editOnlineProduct

    Route::post('wish/editOnlineProductStore',
        ['uses' => 'Publish\Wish\WishPublishController@editOnlineProductStore', 'as' => 'wish.editOnlineProductStore']);
    Route::get('wish/ajaxOperateOnlineProduct', [
        'uses' => 'Publish\Wish\WishPublishController@ajaxOperateOnlineProduct',
        'as' => 'wish.ajaxOperateOnlineProduct'
    ]);
    Route::get('wish/ajaxEditOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@ajaxEditOnlineProduct', 'as' => 'wish.ajaxEditOnlineProduct']);
    Route::get('wish/indexOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@indexOnlineProduct', 'as' => 'wish.indexOnlineProduct']);
    Route::get('wish/editOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@editOnlineProduct', 'as' => 'wish.editOnlineProduct']);
    Route::get('wish/ajaxGetInfo', ['uses' => 'Publish\Wish\WishPublishController@ajaxGetInfo', 'as' => 'wish.ajaxGetInfo']);
    Route::get('wish/ajaxGetSkuPicture', ['uses' => 'Publish\Wish\WishPublishController@ajaxGetSkuPicture', 'as' => 'wish.ajaxGetSkuPicture']);
    Route::get('wish/ajaxGenerateSku', ['uses' => 'Publish\Wish\WishPublishController@ajaxGenerateSku', 'as' => 'wish.ajaxGenerateSku']);
    Route::resource('wish', 'Publish\Wish\WishPublishController');

    Route::resource('WishQuantityCheck', 'Publish\Wish\WishQuantityCheckController');
    Route::post('wishQuantity/ajaxModifySku',
        ['uses' => 'Publish\Wish\WishQuantityCheckController@ajaxModifySku', 'as' => 'wishQuantity.ajaxModifySku']);
    Route::post('wishQuantity/BatchOperation',
        ['uses' => 'Publish\Wish\WishQuantityCheckController@BatchOperation', 'as' => 'wishQuantity.BatchOperation']);
    Route::resource('wishSellerCode', 'Publish\Wish\WishSellerCodeController');


    Route::get('ebayDetail/ajaxUpdate',
        ['uses' => 'Publish\Ebay\EbayDetailController@ajaxUpdate', 'as' => 'ebayDetail.ajaxUpdate']);
    Route::get('ebayDetail/ajaxIsUse',
        ['uses' => 'Publish\Ebay\EbayDetailController@ajaxIsUse', 'as' => 'ebayDetail.ajaxIsUse']);
    Route::get('ebayDetail/getEbayShipping',
        ['uses' => 'Publish\Ebay\EbayDetailController@getEbayShipping', 'as' => 'ebayDetail.getEbayShipping']);
    Route::get('ebayDetail/getEbayReturnPolicy',
        ['uses' => 'Publish\Ebay\EbayDetailController@getEbayReturnPolicy', 'as' => 'ebayDetail.getEbayReturnPolicy']);
    Route::get('ebayDetail/getEbaySite',
        ['uses' => 'Publish\Ebay\EbayDetailController@getEbaySite', 'as' => 'ebayDetail.getEbaySite']);
    Route::resource('ebayDetail', 'Publish\Ebay\EbayDetailController');


    Route::get('ebayProduct/ajaxGetLog',
        ['uses' => 'Publish\Ebay\EbayDataMonitorController@ajaxGetLog', 'as' => 'ebayProduct.ajaxGetLog']);

    Route::any('ebay/batchUpdate',
        ['uses' => 'Publish\Ebay\EbayDataMonitorController@batchUpdate', 'as' => 'ebay.batchUpdate']);
    Route::any('ebay/productBatchEdit',
        ['uses' => 'Publish\Ebay\EbayDataMonitorController@productBatchEdit', 'as' => 'ebay.productBatchEdit']);
    Route::resource('ebayProduct', 'Publish\Ebay\EbayDataMonitorController');


    Route::resource('ebaySellerCode', 'Publish\Ebay\EbaySellerCodeController');
    Route::resource('ebayTiming', 'Publish\Ebay\EbayTimingSetController');

    Route::any('ebayOnline/batchUpdate',
        ['uses' => 'Publish\Ebay\EbayOnlineController@batchUpdate', 'as' => 'ebayOnline.batchUpdate']);
    Route::any('ebayOnline/productBatchEdit',
        ['uses' => 'Publish\Ebay\EbayOnlineController@productBatchEdit', 'as' => 'ebayOnline.productBatchEdit']);
    Route::any('ebayOnline/singleUpdate',
        ['uses' => 'Publish\Ebay\EbayOnlineController@singleUpdate', 'as' => 'ebayOnline.singleUpdate']);
    Route::any('ebayOnline/productSingleEdit',
        ['uses' => 'Publish\Ebay\EbayOnlineController@productSingleEdit', 'as' => 'ebayOnline.productSingleEdit']);

    Route::resource('ebayOnline', 'Publish\Ebay\EbayOnlineController');


    Route::get('ebayPublish/ajaxSuggestCategory', [
        'uses' => 'Publish\Ebay\EbayPublishController@ajaxSuggestCategory',
        'as' => 'ebayPublish.ajaxSuggestCategory'
    ]);
    Route::get('ebayPublish/ajaxSetDataTemplate', [
        'uses' => 'Publish\Ebay\EbayPublishController@ajaxSetDataTemplate',
        'as' => 'ebayPublish.ajaxSetDataTemplate'
    ]);
    Route::get('ebayPublish/ajaxInitErpData',
        ['uses' => 'Publish\Ebay\EbayPublishController@ajaxInitErpData', 'as' => 'ebayPublish.ajaxInitErpData']);
    Route::get('ebayPublish/ajaxInitCategory',
        ['uses' => 'Publish\Ebay\EbayPublishController@ajaxInitCategory', 'as' => 'ebayPublish.ajaxInitCategory']);
    Route::get('ebayPublish/ajaxInitSpecifics',
        ['uses' => 'Publish\Ebay\EbayPublishController@ajaxInitSpecifics', 'as' => 'ebayPublish.ajaxInitSpecifics']);
    Route::get('ebayPublish/ajaxInitCondition',
        ['uses' => 'Publish\Ebay\EbayPublishController@ajaxInitCondition', 'as' => 'ebayPublish.ajaxInitCondition']);
    Route::get('ebayPublish/ajaxInitSite',
        ['uses' => 'Publish\Ebay\EbayPublishController@ajaxInitSite', 'as' => 'ebayPublish.ajaxInitSite']);
    Route::get('ebayPublish/returnDraft',
        ['uses' => 'Publish\Ebay\EbayPublishController@returnDraft', 'as' => 'ebayPublish.returnDraft']);
    Route::resource('ebayPublish', 'Publish\Ebay\EbayPublishController');

    Route::get('ebayStoreCategory/ajaxUpdateStoreCategory', [
        'uses' => 'Publish\Ebay\EbayStoreCategoryController@ajaxUpdateStoreCategory',
        'as' => 'ebayStoreCategory.ajaxUpdateStoreCategory'
    ]);
    Route::resource('ebayStoreCategory', 'Publish\Ebay\EbayStoreCategoryController');
    Route::resource('ebayAccountSet', 'Publish\Ebay\EbayAccountSetController');
    Route::resource('ebayDescription', 'Publish\Ebay\EbayDescriptionTemplateController');
    Route::resource('ebayDataTemplate', 'Publish\Ebay\EbayDataTemplateController');


    Route::group(['prefix' => 'smt', 'namespace' => 'Publish\Smt'], function () {
        Route::get('onlineProductIndex',
            ['uses' => 'SmtController@onlineProductIndex', 'as' => 'smt.onlineProductIndex']);
        Route::get('showChildCategory',
            ['uses' => 'SmtController@showChildCategory', 'as' => 'smt.showChildCategory']);
        Route::get('showCommandCategoryList',
            ['uses' => 'SmtController@showCommandCategoryList', 'as' => 'smt.showCommandCategoryList']);
        Route::post('doAction',
            ['uses' => 'SmtController@doAction', 'as' => 'smt.doAction']);
        Route::post('create',
            ['uses' => 'SmtController@addProduct', 'as' => 'smt.addProduct']);
        Route::post('smt/batchPost',
            ['uses' => 'SmtController@batchPost', 'as' => 'smt.batchPost']);
        Route::post('recommendProductList',
            ['uses' => 'SmtController@recommendProductList', 'as' => 'smt.recommendProductList']);
        Route::post('batchDel',
            ['uses' => 'SmtController@batchDel', 'as' => 'smt.batchDel']);
        Route::post('ajaxUploadDirImage',
            ['uses' => 'SmtController@ajaxUploadDirImage', 'as' => 'smt.ajaxUploadDirImage']);
        Route::post('ajaxUploadOneCustomPic',
            ['uses' => 'SmtController@ajaxUploadOneCustomPic', 'as' => 'smt.ajaxUploadOneCustomPic']);
        Route::post('getskuinfo',
            ['uses' => 'SmtController@getskuinfo', 'as' => 'smt.getskuinfo']);
        Route::post('ajaxUploadDirImageByNewSys',
            ['uses' => 'SmtController@ajaxUploadDirImageByNewSys', 'as' => 'smt.ajaxUploadDirImageByNewSys']);

        Route::get('editOnlineProduct',
            ['uses' => 'SmtController@editOnlineProduct', 'as' => 'smt.editOnlineProduct']);
        Route::get('ajaxOperateOnlineProduct',
            ['uses' => 'SmtController@ajaxOperateOnlineProduct', 'as' => 'smt.ajaxOperateOnlineProduct']);
        Route::get('waitPost',
            ['uses' => 'SmtController@waitPostList', 'as' => 'smt.waitPost']);
        Route::get('changeStatusToWait',
            ['uses' => 'SmtController@changeStatusToWait', 'as' => 'smt.changeStatusToWait']);
        Route::post('batchModify',
            ['uses' => 'SmtController@batchModify', 'as' => 'smt.batchModify']);
        Route::post('batchCreateDraft',
            ['uses' => 'SmtController@batchCreateDraft', 'as' => 'smt.batchCreateDraft']);
    });

    Route::resource('smt', 'Publish\Smt\SmtController');

    Route::group(['prefix' => 'smtProduct', 'namespace' => 'Publish\Smt'], function () {
        Route::get('selectRelationProducts',
            ['uses' => 'SmtProductController@selectRelationProducts', 'as' => 'smtProduct.selectRelationProducts']);
        Route::post('getProductGroup',
            ['uses' => 'SmtProductController@getProductGroup', 'as' => 'smtProduct.getProductGroup']);
        Route::post('getServiceTemplateList',
            ['uses' => 'SmtProductController@getServiceTemplateList', 'as' => 'smtProduct.getServiceTemplateList']);
        Route::post('getFreightTemplateList',
            ['uses' => 'SmtProductController@getFreightTemplateList', 'as' => 'smtProduct.getFreightTemplateList']);
        Route::post('getProductModuleList',
            ['uses' => 'SmtProductController@getProductModuleList', 'as' => 'smtProduct.getProductModuleList']);
        Route::post('ajaxGetPlatTemplateList',
            ['uses' => 'SmtProductController@ajaxGetPlatTemplateList', 'as' => 'smtProduct.ajaxGetPlatTemplateList']);
        Route::post('ajaxSmtAfterServiceList',
            [
                'uses' => 'AfterSalesServiceController@ajaxSmtAfterServiceList',
                'as' => 'afterSales.ajaxSmtAfterServiceList'
            ]);
        Route::get('batchModifyProduct',
            ['uses' => 'SmtProductController@batchModifyProduct', 'as' => 'smtProduct.batchModifyProduct']);
        Route::post('synchronizationProduct',
            ['uses' => 'SmtProductController@synchronizationProduct', 'as' => 'smtProduct.synchronizationProduct']);
        Route::get('showAccountToCopyProduct',
            ['uses' => 'SmtProductController@showAccountToCopyProduct', 'as' => 'smtProduct.showAccountToCopyProduct']);
        Route::post('copyToDraft',
            ['uses' => 'SmtProductController@copyToDraft', 'as' => 'smtProduct.copyToDraft']);
        Route::get('groupManage',
            ['uses' => 'SmtProductController@groupManage', 'as' => 'smtProduct.groupManage']);
        Route::get('serviceManage',
            ['uses' => 'SmtProductController@serviceManage', 'as' => 'smtProduct.serviceManage']);
        Route::get('freightManage',
            ['uses' => 'SmtProductController@freightManage', 'as' => 'smtProduct.freightManage']);
        Route::get('getFreightDetailById',
            ['uses' => 'SmtProductController@getFreightDetailById', 'as' => 'smtProduct.getFreightDetailById']);
        Route::post('showAccountProductGroup',
            ['uses' => 'SmtProductController@showAccountProductGroup', 'as' => 'smtProduct.showAccountProductGroup']);
        Route::post('SynchronousDataByAccount',
            ['uses' => 'SmtProductController@SynchronousDataByAccount', 'as' => 'smtProduct.SynchronousDataByAccount']);
        Route::post('copyAllAccountNew',
            ['uses' => 'SmtProductController@copyAllAccountNew', 'as' => 'smtProduct.copyAllAccountNew']);
        Route::post('getCategoryInfo',
            ['uses' => 'SmtProductController@getCategoryInfo', 'as' => 'smtProduct.getCategoryInfo']);
        Route::post('getCategoryAttributesById',
            ['uses' => 'SmtProductController@getCategoryAttributesById', 'as' => 'smtProduct.getCategoryAttributesById']);
        Route::post('batchModifyBand',
            ['uses' => 'SmtProductController@batchModifyBand', 'as' => 'smtProduct.batchModifyBand']);
        
        

    });
    Route::resource('smtProduct', 'Publish\Smt\SmtProductController');

    Route::post('smtMonitor/editSingleSkuStock',
        [
            'uses' => 'Publish\Smt\SmtOnlineMonitorController@editSingleSkuStock',
            'as' => 'smtMonitor.editSingleSkuStock'
        ]);
    Route::post('smtMonitor/editSingleSkuPrice',
        [
            'uses' => 'Publish\Smt\SmtOnlineMonitorController@editSingleSkuPrice',
            'as' => 'smtMonitor.editSingleSkuPrice'
        ]);
    Route::post('smtMonitor/manualUpdateProductInfo',
        [
            'uses' => 'Publish\Smt\SmtOnlineMonitorController@manualUpdateProductInfo',
            'as' => 'smtMonitor.manualUpdateProductInfo'
        ]);
    Route::post('smtMonitor/ajaxOperateOnlineProductStatus',
        [
            'uses' => 'Publish\Smt\SmtOnlineMonitorController@ajaxOperateOnlineProductStatus',
            'as' => 'smtMonitor.ajaxOperateOnlineProductStatus'
        ]);
    Route::post('smtMonitor/batchEditSkuStock',
        ['uses' => 'Publish\Smt\SmtOnlineMonitorController@batchEditSkuStock', 'as' => 'smtMonitor.batchEditSkuStock']);
    Route::post('smtMonitor/batchEditSkuPrice',
        ['uses' => 'Publish\Smt\SmtOnlineMonitorController@batchEditSkuPrice', 'as' => 'smtMonitor.batchEditSkuPrice']);
    Route::post('smtMonitor/ajaxOperateProductSkuStockStatus',
        [
            'uses' => 'Publish\Smt\SmtOnlineMonitorController@ajaxOperateProductSkuStockStatus',
            'as' => 'smtMonitor.ajaxOperateProductSkuStockStatus'
        ]);

    Route::resource('smtMonitor', 'Publish\Smt\SmtOnlineMonitorController');
    Route::resource('smtSellerCode', 'Publish\Smt\SmtSellerCodeController');

    Route::post('smtAfterSale/ajaxGetTokenList',
        [
            'uses' => 'Publish\Smt\AfterSalesServiceController@ajaxGetTokenList',
            'as' => 'smtAfterSale.ajaxGetTokenList'
        ]);

    Route::resource('smtAfterSale', 'Publish\Smt\AfterSalesServiceController');
    Route::post('smtTemplate/copyTemplate',
        ['uses' => 'Publish\Smt\SmtTemplateController@copyTemplate', 'as' => 'smtTemplate.copyTemplate']);

    Route::resource('smtTemplate', 'Publish\Smt\SmtTemplateController');

    Route::post('smtAccountManage/doAction',
        ['uses' => 'Publish\Smt\SmtAccountManageController@doAction', 'as' => 'smtAccountManage.doAction']);
    Route::post('smtAccountManage/resetAuthorization',
        [
            'uses' => 'Publish\Smt\SmtAccountManageController@resetAuthorization',
            'as' => 'smtAccountManage.resetAuthorization'
        ]);

    Route::resource('smtAccountManage', 'Publish\Smt\SmtAccountManageController');

    Route::post('smtPriceTask/batchDelete',
        ['uses' => 'Publish\Smt\SmtPriceTaskController@batchDelete', 'as' => 'smtPriceTask.batchDelete']);
    Route::post('smtPriceTask/createPriceTask',
        ['uses' => 'Publish\Smt\SmtPriceTaskController@createPriceTask', 'as' => 'smtPriceTask.createPriceTask']);
    Route::post('smtPriceTask/getSmtPriceTask',
        ['uses' => 'Publish\Smt\SmtPriceTaskController@getSmtPriceTask', 'as' => 'smtPriceTask.getSmtPriceTask']);
    Route::resource('smtPriceTask', 'Publish\Smt\SmtPriceTaskController');
    
    Route::any('downloadTemplate', ['uses' => 'Publish\Smt\CopyrightController@downloadTemplate', 'as' => 'downloadTemplate']);
    Route::any('exportAllData', ['uses' => 'Publish\Smt\CopyrightController@exportAllData', 'as' => 'exportAllData']);
    Route::any('exportPartData', ['uses' => 'Publish\Smt\CopyrightController@exportPartData', 'as' => 'exportPartData']);
    Route::any('deletePartData', ['uses' => 'Publish\Smt\CopyrightController@deletePartData', 'as' => 'deletePartData']);
    Route::post('getAllAccountByPlatID', ['uses' => 'Publish\Smt\CopyrightController@getAllAccountByPlatID', 'as' => 'copyright.getAllAccountByPlatID']);
    
    Route::any('importCopyrightData', ['uses' => 'Publish\Smt\CopyrightController@importCopyrightData', 'as' => 'importCopyrightData']);
    Route::resource('copyright', 'Publish\Smt\CopyrightController');

    Route::any('upload',
        ['uses' => 'KindeditorController@upload', 'as' => 'upload']);
    Route::any('uploadToProject',
        ['uses' => 'KindeditorController@uploadToProject', 'as' => 'uploadToProject']);

    Route::post('lazada/setQuantity',
        ['uses' => 'Publish\Lazada\LazadaOnlineMonitorController@setQuantity', 'as' => 'lazada.setQuantity']);
    Route::post('lazada/setPrice',
        ['uses' => 'Publish\Lazada\LazadaOnlineMonitorController@setPrice', 'as' => 'lazada.setPrice']);
    Route::post('lazada/setSellerSkuStatus',
        [
            'uses' => 'Publish\Lazada\LazadaOnlineMonitorController@setSellerSkuStatus',
            'as' => 'lazada.setSellerSkuStatus'
        ]);
    Route::post('lazada/setSalePrice',
        ['uses' => 'Publish\Lazada\LazadaOnlineMonitorController@setSalePrice', 'as' => 'lazada.setSalePrice']);

    Route::get('lazada/productBatchEdit',
        ['uses' => 'Publish\Lazada\LazadaOnlineMonitorController@productBatchEdit', 'as' => 'lazada.productBatchEdit']);
    Route::any('lazada/batchUpdate',
        ['uses' => 'Publish\Lazada\LazadaOnlineMonitorController@batchUpdate', 'as' => 'lazada.batchUpdate']);

    Route::resource('lazada', 'Publish\Lazada\LazadaOnlineMonitorController');
    //joom Online Monitor
    Route::resource('joomonline', 'Publish\Joom\JoomOnlineMonitorController');
    Route::post('joomonline/setSellerinventory',
        [
            'uses' => 'Publish\Joom\JoomOnlineMonitorController@setSellerinventory',
            'as' => 'joomonline.setSellerinventory'
        ]);
    Route::post('joomonline/setPrice',
        ['uses' => 'Publish\Joom\JoomOnlineMonitorController@setPrice', 'as' => 'joomonline.setPrice']);
    Route::post('joomonline/setshipping',
        ['uses' => 'Publish\Joom\JoomOnlineMonitorController@setshipping', 'as' => 'joomonline.setshipping']);
    Route::get('setstatus',
        ['uses' => 'Publish\Joom\JoomOnlineMonitorController@setstatus', 'as' => 'joomonline.setstatus']);
    Route::get('JoomproductBatchEdit',
        ['uses' => 'Publish\Joom\JoomOnlineMonitorController@productBatchEdit', 'as' => 'joomonline.productBatchEdit']);
    Route::any('batchUpdate',
        ['uses' => 'Publish\Joom\JoomOnlineMonitorController@batchUpdate', 'as' => 'joomonline.batchUpdate']);

    //开启工作流
    Route::any('message/startWorkflow',
        ['as' => 'message.startWorkflow', 'uses' => 'MessageController@startWorkflow']);
    Route::any('workflow/doCompleteMsg','MessageController@doCompleteMsg')->name('workflow.doCompleteMsg');
    //关闭工作流
    Route::any('message/endWorkflow',
        ['as' => 'message.endWorkflow', 'uses' => 'MessageController@endWorkflow']);
    //稍后处理
    Route::any('message/{id}/dontRequireReply',
        ['as' => 'message.dontRequireReply', 'uses' => 'MessageController@dontRequireReply']);
    //workflow稍后处理
    Route::any('message/workflowDontRequireReply','MessageController@workflowDontRequireReply')
        ->name('message.workflowDontRequireReply');
    //wish support
    Route::any('message/WishSupportReplay',
        ['as' => 'message.WishSupportReplay', 'uses' => 'MessageController@WishSupportReplay']);

    //无需回复
    Route::any('message/{id}/notRequireReply',
        ['as' => 'message.notRequireReply', 'uses' => 'MessageController@notRequireReply']);
    //workfole无需回复
    Route::any('message/workflowNoReply',
        ['as' => 'message.workflowNoReply', 'uses' => 'MessageController@workflowNoReply']);
    //处理信息
    Route::any('message/process',
        ['as' => 'message.process', 'uses' => 'MessageController@process']);
    //邮件内容
    Route::any('message/{id}/content',
        ['as' => 'message.content', 'uses' => 'MessageController@content']);
    //邮件信息
    Route::resource('message', 'MessageController');
    //邮件转发控制器
    Route::any('message/{id}/foremail',
        ['as' => 'message.foremail', 'uses' => 'MessageController@foremail']);
    Route::get('forwardemail/edit/{id}', 'Message\ForemailController@edit');
    //转交他人
    Route::any('message/{id}/assignToOther',
        ['as' => 'message.assignToOther', 'uses' => 'MessageController@assignToOther']);
    //workflow转交他人
    Route::any('message/workflowAssignToOther',
        ['as' => 'message.workflowAssignToOther', 'uses' => 'MessageController@workflowAssignToOther']);
    Route::resource('message', 'MessageController');
    //设置关联订单
    Route::any('message/{id}/setRelatedOrders',
        ['as' => 'message.setRelatedOrders', 'uses' => 'MessageController@setRelatedOrders']);
    //取消关联订单
    Route::any('message/{id}/cancelRelatedOrder/{relatedOrderId}',
        ['as' => 'message.cancelRelatedOrder', 'uses' => 'MessageController@cancelRelatedOrder']);
    //无需关联订单
    Route::any('message/{id}/notRelatedOrder',
        ['as' => 'message.notRelatedOrder', 'uses' => 'MessageController@notRelatedOrder']);
    Route::resource('message', 'MessageController');
    //信息模版类型路由
    Route::any('messageTemplateType/ajaxGetChildren',
        ['as' => 'messageTemplateType.ajaxGetChildren', 'uses' => 'Message\Template\TypeController@ajaxGetChildren']);
    Route::any('messageTemplateType/ajaxGetTemplates',
        ['as' => 'messageTemplateType.ajaxGetTemplates', 'uses' => 'Message\Template\TypeController@ajaxGetTemplates']);
    Route::resource('messageTemplateType', 'Message\Template\TypeController');
    //信息模版路由
    Route::any('messageTemplate/ajaxGetTemplate',
        ['as' => 'messageTemplate.ajaxGetTemplate', 'uses' => 'Message\TemplateController@ajaxGetTemplate']);
    //回复信息
    Route::any('message/{id}/reply',
        ['as' => 'message.reply', 'uses' => 'MessageController@reply']);
    //工作流回复信息
    Route::any('workflow/reply','MessageController@workflowReply')->name('workflow.reply');
    //信息模版路由
    Route::any('messageTemplate/ajaxGetTemplate',
        ['as' => 'messageTemplate.ajaxGetTemplate', 'uses' => 'Message\TemplateController@ajaxGetTemplate']);
    Route::resource('messageTemplate', 'Message\TemplateController');
    //新增单个无需回复
    Route::any('message/{id}/notRequireReply_1',
        ['as' => 'message.notRequireReply_1', 'uses' => 'MessageController@notRequireReply_1']);
    //转发邮件
    Route::resource('message_log', 'Message\Messages_logController');
    //回复队列路由
    Route::resource('messageReply', 'Message\ReplyController');
    //消息自动回复规则
    Route::resource('autoReply', 'Message\AutoReplyController');

    Route::any('ajaxGetTranInfo',
        ['as' => 'ajaxGetTranInfo', 'uses' => 'MessageController@ajaxGetTranInfo']);
    Route::any('aliexpressReturnOrderMessages',
        ['as' => 'aliexpressReturnOrderMessages', 'uses' => 'MessageController@aliexpressReturnOrderMessages']);
    Route::any('aliexpressCsvFormat',
        ['as' => 'aliexpressCsvFormat', 'uses' => 'MessageController@aliexpressCsvFormat']);
    Route::any('doSendAliexpressMessages',
        ['as' => 'doSendAliexpressMessages', 'uses' => 'MessageController@doSendAliexpressMessages']);
    Route::any('SendEbayMessage', ['uses' => 'MessageController@SendEbayMessage', 'as' => 'message.sendEbayMessage']);
    Route::any('ebayUnpaidCase', ['uses' => 'MessageController@ebayUnpaidCase', 'as' => 'message.ebayUnpaidCase']);
    Route::any('ajaxGetMsgInfo', ['uses' => 'MessageController@ajaxGetMsgInfo', 'as' => 'ajaxGetMsgInfo']);
    Route::any('changeMultipleStatus', 'MessageController@changeMultipleStatus')->name('changeMultipleStatus');
    Route::any('wishRefundOrder', 'MessageController@wishRefundOrder')->name('wishRefundOrder');

    //用户路由
    Route::get('productUser/ajaxUser', ['uses' => 'UserController@ajaxUser', 'as' => 'ajaxUser']);
    Route::any('user/role', ['uses' => 'UserController@per', 'as' => 'role']);
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    //图片标签
    Route::resource('label', 'LabelController');
    Route::resource('paypal', 'PaypalController');

    //日志
    Route::resource('logCommand', 'Log\CommandController');
    Route::resource('logQueue', 'Log\QueueController');

    //队列
    Route::resource('jobFailed', 'Job\FailedController');
    //标记发货规则设置
    Route::resource('orderMarkLogic', 'Order\OrderMarkLogicController');
    Route::resource('ebayCases', 'Message\EbayCasesController');
    Route::any('MessageToBuyer', ['as' => 'MessageToBuyer', 'uses' => 'Message\EbayCasesController@MessageToBuyer']);
    Route::any('AddTrackingDetails',
        ['as' => 'AddTrackingDetails', 'uses' => 'Message\EbayCasesController@AddTrackingDetails']);
    Route::any('RefundBuyer', ['as' => 'case.RefundBuyer', 'uses' => 'Message\EbayCasesController@RefundBuyer']);
    Route::any('PartRefundBuyer',
        ['as' => 'case.PartRefundBuyer', 'uses' => 'Message\EbayCasesController@PartRefundBuyer']);

    Route::resource('ebayFeedBack', 'Message\FeedBack\EbayFeedBackController');
    Route::any('feedBackStatistics',
        ['uses' => 'Message\FeedBack\EbayFeedBackController@feedBackStatistics', 'as' => 'feeback.feedBackStatistics']);
    Route::resource('refundCenter', 'RefundCenterController');
    Route::any('doPaypalRefund', ['uses' => 'RefundCenterController@doPaypalRefund', 'as' => 'refund.dopaypalrefund']);
    Route::any('batchProcessStatus',
        ['uses' => 'RefundCenterController@batchProcessStatus', 'as' => 'refund.batchProcessStatus']);
    Route::any('RefundCsvFormat', ['uses' => 'RefundCenterController@RefundCsvFormat', 'as' => 'refund.cvsformat']);
    Route::any('financeExport', ['uses' => 'RefundCenterController@financeExport', 'as' => 'refund.financeExport']);
    Route::any('changeReundNoteStatus',
        ['uses' => 'RefundCenterController@changeReundNoteStatus', 'as' => 'refund.changeReundNoteStatus']);
    Route::any('refundStatistics',
        ['as' => 'refund.refundStatistics', 'uses' => 'RefundCenterController@refundStatistics']);
    Route::any('getChannelAccount',
        ['as' => 'refund.getChannelAccount', 'uses' => 'RefundCenterController@getChannelAccount']);
    Route::any('exportRefundDetail',
        ['as' => 'refund.exportRefundDetail', 'uses' => 'RefundCenterController@exportRefundDetail']);

    Route::resource('AliexpressIssue', 'Message\Dispute\AliexpressIssueController');
    Route::any('doRefuseIssues',
        ['uses' => 'Message\Dispute\AliexpressIssueController@doRefuseIssues', 'as' => 'aliexpress.doRefuseIssues']);
    Route::any('MutiChangePlatform', 'Message\Dispute\AliexpressIssueController@MutiChangePlatformProcess')->name('aliexpress.MutiChangePlatform');
    Route::resource('crmStatistics', 'Message\StatisticsController');
    //spu
    Route::get('spu/dispatchUser', ['uses' => 'SpuController@dispatchUser', 'as' => 'dispatchUser']);
    Route::get('spu/checkPrivacy', ['uses' => 'SpuController@checkPrivacy', 'as' => 'spu.checkPrivacy']);
    Route::get('spu/doAction', ['uses' => 'SpuController@doAction', 'as' => 'doAction']);
    Route::get('spu/actionBack', ['uses' => 'SpuController@actionBack', 'as' => 'actionBack']);
    Route::get('spu/spuTemp', ['uses' => 'SpuController@spuTemp', 'as' => 'spuTemp']);
    Route::get('spu/saveRemark', ['uses' => 'SpuController@saveRemark', 'as' => 'saveRemark']);
    //上传产品信息表格
    Route::get('spu/insertData', ['uses' => 'SpuController@insertData', 'as' => 'insertData']);
    Route::any('spu/uploadSku', ['uses' => 'SpuController@uploadSku', 'as' => 'spu.uploadSku']);

    Route::get('spu/spuMultiEdit', ['uses' => 'SpuController@spuMultiEdit', 'as' => 'spu.MultiEdit']);
    Route::any('spuMultiUpdate', ['uses' => 'SpuController@spuMultiUpdate', 'as' => 'spu.MultiUpdate']);
    Route::any('spuInfo', ['uses' => 'SpuController@spuInfo', 'as' => 'spu.Info']);
    Route::any('spu/insertLan', ['uses' => 'SpuController@insertLan', 'as' => 'spu.insertLan']);
    Route::resource('spu', 'SpuController');
    //接口路由
    Route::resource('syncApi', 'SyncApiController');
    Route::resource('importSyncApi', 'SyncSellmoreDataController');
    //系统模块
    Route::resource('mail_push', 'MailPushController');
});


//getEbayInfo
Route::any('getEbayProduct', ['uses' => 'TestController@getEbayProduct']);
Route::any('testOnesku', ['uses' => 'TestController@oneSku']);
Route::any('testPaypal', ['uses' => 'TestController@testPaypal']);
Route::any('testLazada', ['uses' => 'TestController@testLazada']);
Route::any('testReturnTrack', ['uses' => 'TestController@testReturnTrack']);
Route::any('getEbayInfo', ['uses' => 'TestController@getEbayInfo']);

Route::any('testtest', ['uses' => 'TestController@test', 'as' => 'test1']);
Route::any('test', ['uses' => 'TestController@index']);
Route::any('aliexpressOrdersList', ['uses' => 'TestController@aliexpressOrdersList']);
Route::any('lazadaOrdersList', ['uses' => 'TestController@lazadaOrdersList']);
Route::any('cdiscountOrdersList', ['uses' => 'TestController@cdiscountOrdersList']);
Route::any('getwishproduct', ['uses' => 'TestController@getWishProduct']);
Route::any('jdtestcrm', ['uses' => 'TestController@jdtestCrm']);
Route::any('testAutoReply', ['uses' => 'TestController@testAutoReply']);
Route::any('testEbayCases', ['uses' => 'TestController@testEbayCases']);
Route::any('getSmtIssue', ['uses' => 'TestController@getSmtIssue']);
Route::any('getjoomproduct', ['uses' => 'TestController@getJoomProduct']);
Route::any('joomOrdersList', ['uses' => 'TestController@joomOrdersList']);
Route::any('joomToShipping', ['uses' => 'TestController@joomToShipping']);
Route::any('joomrefreshtoken', ['uses' => 'TestController@joomrefreshtoken']);

Route::any('testReply/{id}',
    ['as' => 'test.testReply', 'uses' => 'TestController@testReply']);
Route::any('tryGetLogtisticsNo/{id}',[ 'uses' => 'TestController@tryGetLogtisticsNo']);
Route::any('testCancelOrder', ['uses' => 'TestController@testAutoCancelOrder']);



