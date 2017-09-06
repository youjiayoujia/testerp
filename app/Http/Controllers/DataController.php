<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 11:05
 */
namespace App\Http\Controllers;

use Tool;
use App\Models\Sellmore\ProductModel as smProduct;
use App\Models\Sellmore\SupplierModel as smSupplier;
use App\Models\Sellmore\AmazonModel as smAmazon;
use App\Models\Sellmore\WishModel as smWish;
use App\Models\Sellmore\SmtModel as smSmt;
use App\Models\Sellmore\LazadaModel as smLazada;
use App\Models\Sellmore\CdModel as smCd;
use App\Models\Sellmore\EbayModel as smEbay;
use App\Models\Sellmore\EbayDeveloperModel as smEbayDeveloper;
use App\Models\Sellmore\ShipmentCategoryModel as smShipmentCategory;
use App\Models\Logistics\CatalogModel;
use App\Models\Sellmore\ShipmentModel as smShipment;
use App\Models\Sellmore\AmaLogisticsModel as smAmaLogistics;
use App\Models\Sellmore\WishLogisticsModel as smWishLogistics;
use App\Models\Sellmore\DhgateLogisticsModel as smDhgateLogistics;
use App\Models\Sellmore\LazadaLogisticsModel as smLazadaLogistics;
use App\Models\Sellmore\AliExpressLogisticsModel as smAliExpressLogistics;
use App\Models\Sellmore\ShipmentSupplierModel as smShipmentSupplier;
use App\Models\Sellmore\StockModel as smStock;
use App\Models\Logistics\SupplierModel as originSupplier;
use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\StockModel;
use App\Models\Product\SupplierModel;
use App\Models\Channel\AccountModel;
use App\Models\Warehouse\PositionModel;
use App\Models\LogisticsModel;
use App\Models\ChannelModel;
use App\Models\SpuModel;
use App\Models\ProductModel;

class DataController extends Controller
{
    public function __construct(smProduct $model)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
    }

    // public function index()
    // {
    //     if (WarehouseModel::count() < 1) {
    //         exit('先导入仓库信息,深圳仓ID=1,义乌仓ID=2');
    //     }
    //     if (SupplierModel::count() < 1) {
    //         exit('先导入供货商信息');
    //     }

    //     $len = 1000;
    //     $start = 0;
    //     $smProducts = smProduct::orderBy('products_id', 'desc')->skip($start)->take($len)->get();
    //     while ($smProducts->count()) {
    //         $start += $len;
    //         foreach ($smProducts as $smProduct) {
    //             $spu = SpuModel::create(['spu' => $smProduct->products_sku]);
    //             $arr = [];
    //             if ($smProduct->products_with_battery) {
    //                 $arr[] = 1;
    //             }
    //             if ($smProduct->products_with_adapter) {
    //                 $arr[] = 4;
    //             }
    //             if ($smProduct->products_with_fluid) {
    //                 $arr[] = 5;
    //             }
    //             if ($smProduct->products_with_powder) {
    //                 $arr[] = 2;
    //             }
    //             $buf = [
    //                 'model' => $smProduct->products_sku,
    //                 'parts' => $smProduct->products_parts_info ? $smProduct->products_parts_info : '',
    //                 'declared_cn' => $smProduct->products_declared_cn ? $smProduct->products_declared_cn : '',
    //                 'declared_en' => $smProduct->products_declared_en ? $smProduct->products_declared_en : '',
    //                 'declared_value' => $smProduct->products_declared_value ? $smProduct->products_declared_value : '',
    //                 'package_limit' => count($arr) ? implode(',', $arr) : '',
    //                 'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
    //                 'name' => $smProduct->products_name_en ? $smProduct->products_name_en : '',
    //                 'c_name' => $smProduct->products_name_cn ? $smProduct->products_name_cn : '',
    //                 'supplier_id' => $smProduct->products_suppliers_id ? $smProduct->products_suppliers_id : '',
    //                 'warehouse_id' => $smProduct->product_warehouse_id == 1000 ? 1 : 2,
    //                 'hs_code' => $smProduct->product_hscode ? $smProduct->product_hscode : '',
    //             ];
    //             $tmp_product = $spu->products()->create($buf);

    //             //体积
    //             $volumes = ['product_size' => '', 'package_size' => ''];
    //             if ($smProduct->products_volume) {
    //                 $volumes = unserialize($smProduct->products_volume);
    //                 $volumes['product_size'] = isset($volumes['bp']) ? $volumes['bp']['length'] . '*' . $volumes['bp']['width'] . '*' . $volumes['bp']['height'] : '';
    //                 $volumes['package_size'] = isset($volumes['ap']) ? $volumes['ap']['length'] . '*' . $volumes['ap']['width'] . '*' . $volumes['ap']['height'] : '';
    //             }
    //             //供货商
    //             $supplier = SupplierModel::find($smProduct->products_suppliers_id);
    //             $supplierId = $supplier ? $supplier->id : 0;
    //             $secondSupplierId = 0;
    //             if ($smProduct->products_suppliers_ids) {
    //                 $supplierIds = explode(',', $smProduct->products_suppliers_ids);
    //                 if (isset($supplierIds[0])) {
    //                     if ($supplierIds[0] != $smProduct->products_suppliers_id) {
    //                         $secondSupplier = SupplierModel::find($supplierIds[0]);
    //                         $secondSupplierId = $secondSupplier ? $secondSupplier->id : 0;
    //                     }
    //                 }
    //             }
    //             //仓库
    //             $warehouseId = $smProduct->product_warehouse_id == 1000 ? 1 : 2;
    //             //库位
    //             if ($smProduct->products_location) {
    //                 $position = PositionModel::Where('name', $smProduct->products_location)->first();
    //                 if (!$position) {
    //                     PositionModel::create([
    //                         'name' => $smProduct->products_location,
    //                         'warehouse_id' => $warehouseId
    //                     ]);
    //                 }
    //             }
    //             $data = [
    //                 'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
    //                 'sku' => $smProduct->products_sku,
    //                 'name' => $smProduct->products_title,
    //                 'c_name' => $smProduct->products_name_cn,
    //                 'weight' => $smProduct->products_weight,
    //                 'warehouse_id' => $warehouseId,
    //                 'warehouse_position' => $smProduct->products_location,
    //                 'supplier_id' => $supplierId,
    //                 'second_supplier_id' => $secondSupplierId,
    //                 'purchase_url' => $smProduct->productsPhotoStandard,
    //                 'purchase_price' => $smProduct->products_value,
    //                 'purchase_carriage' => '',
    //                 'cost' => $smProduct->products_value,
    //                 'product_size' => $volumes['product_size'],
    //                 'package_size' => $volumes['package_size'],
    //                 'carriage_limit' => '',
    //                 'package_limit' => '',
    //                 'status' => $smProduct->products_status_2,
    //                 'is_available' => $smProduct->productsIsActive,
    //                 'remark' => $smProduct->products_warring_string,
    //                 'id' => $smProduct->products_id,
    //             ];
    //             $tmp_product->item()->create($data);
    //         }
    //         $smProducts = smProduct::orderBy('products_id', 'desc')->skip($start)->take($len)->get();
    //     }
    // }

    // public function shipmentSupplier()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $smCds = smShipmentSupplier::skip($start)->take($len)->get();
    //     while ($smCds->count()) {
    //         $start += $len;
    //         foreach ($smCds as $smCd) {
    //             $cd = [
    //                 'id' => $smCd->suppliers_id,
    //                 'name' => $smCd->suppliers_name,
    //                 'client_manager' => $smCd->suppliers_services ? $smCd->suppliers_services : '',
    //                 'manager_tel' => $smCd->suppliers_services_phoneorqq,
    //                 'technician' => $smCd->suppliers_driver,
    //                 'technician_tel' => $smCd->suppliers_driver_phone,
    //                 'remark' => $smCd->suppliers_remark ? $smCd->suppliers_remark : '',
    //                 'bank' => $smCd->suppliers_bank,
    //                 'card_number' => $smCd->suppliers_card_number,
    //             ];
    //             originSupplier::create($cd);
    //         }
    //         $smCds = smShipmentSupplier::skip($start)->take($len)->get();
    //     }
    // }

    // public function shipment()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $smShipments = smShipment::skip($start)->take($len)->get();
    //     while ($smShipments->count()) {
    //         $start += $len;
    //         foreach ($smShipments as $smShipment) {
    //             $shipment = [
    //                 'id' => $smShipment->shipmentID,
    //                 'code' => $smShipment->shipmentTitle,
    //                 'name' => $smShipment->shipmentDescription,
    //                 'warehouse_id' => $smShipment->shipment_warehouse_id == '1025' ? '2' : '1',
    //                 'logistics_catalog_id' => $smShipment->shipmentCategoryID,
    //             ];
    //             LogisticsModel::create($shipment);
    //         }

    //         $smShipments = smShipment::skip($start)->take($len)->get();
    //     }
    // }

    // public function shipmentCategory()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
    //     while ($smShipmentCategorys->count()) {
    //         $start += $len;
    //         foreach ($smShipmentCategorys as $smShipmentCategory) {
    //             $shipmentCategory = [
    //                 'id' => $smShipmentCategory->shipmentCatID,
    //                 'name' => $smShipmentCategory->shipmentCatName
    //             ];
    //             CatalogModel::create($shipmentCategory);
    //         }

    //         $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
    //     }
    // }

    public function aliExpressLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
        $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $dhgate->logistics_key]]);
                    }
                }
            }
            $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        }
    }

    public function cdiscountLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;

        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $model = LogisticsModel::find($smShipment->shipmentID);
                if ($model) {
                    if ($smShipment->shipmentCdiscountCodeID) {
                        $model->channelName()->attach([$id => ['name' => $smShipment->shipmentAMZCode]]);
                    }
                } else {
                    var_dump($smShipment->shipmentID);
                }
            }
            $smShipments = smShipment::skip($start)->take($len)->get();
        }
    }

    public function lazadaLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $dhgate->logistics_name]]);
                    }
                }
            }
            $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        }
    }

    public function dhgateLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Dhgate'])->first()->id;
        $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $dhgate->logistics_name]]);
                    }
                }
            }
            $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        }
    }

    public function wishLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
        $wishes = smWishLogistics::skip($start)->take($len)->get();
        while ($wishes->count()) {
            $start += $len;
            foreach ($wishes as $wish) {
                if ($wish->logisticses) {
                    foreach ($wish->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $wish->logistics_name]]);
                    }
                }
            }
            $wishes = smShipment::skip($start)->take($len)->get();
        }
    }

    public function amaLogistics()
    {
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;

        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $model = LogisticsModel::find($smShipment->shipmentID);
                if ($model) {
                    $model->channelName()->attach([$id => ['name' => $smShipment->shipmentAMZCode]]);
                } else {
                    var_dump($smShipment->shipmentID);
                }
            }
            $smShipments = smShipment::skip($start)->take($len)->get();
        }
    }


    

    // public function transfer_ebay()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'Ebay'])->first()->id;
    //     $smEbays = smEbay::skip($start)->take($len)->get();
    //     while ($smEbays->count()) {
    //         $start += $len;
    //         foreach ($smEbays as $smEbay) {
    //             $ebay = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'ebay_developer_account' => $smEbay->developer->developer_account,
    //                 'ebay_developer_devid' => $smEbay->developer->devid,
    //                 'ebay_developer_appid' => $smEbay->developer->appid,
    //                 'ebay_developer_certid' => $smEbay->developer->certid,
    //                 'ebay_token' => $smEbay->user_token,
    //                 'ebay_eub_developer' => $smEbay->eub_developer_id ? $smEbay->eub_developer_id : '',
    //                 'customer_service_id' => $smEbay->sf_order,
    //             ];
    //             AccountModel::create($ebay);
    //         }
    //         $smEbays = smEbay::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_cd()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;
    //     $smCds = smCd::skip($start)->take($len)->get();
    //     while ($smCds->count()) {
    //         $start += $len;
    //         foreach ($smCds as $smCd) {
    //             $cd = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'cd_currency_type' => $smCd->currency_type,
    //                 'cd_currency_type_cn' => $smCd->currency_type_cn,
    //                 'cd_account' => $smCd->account,
    //                 'cd_token_id' => $smCd->token_id,
    //                 'cd_pw' => $smCd->pw,
    //                 'cd_sales_account' => $smCd->sales_account,
    //                 'cd_expires_in' => $smCd->expires_in
    //             ];
    //             AccountModel::create($cd);
    //         }
    //         $smCds = smCd::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_lazada()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
    //     $smLazadas = smLazada::skip($start)->take($len)->get();
    //     while ($smLazadas->count()) {
    //         $start += $len;
    //         foreach ($smLazadas as $smLazada) {
    //             $lazada = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'lazada_access_key' => $smLazada->Key,
    //                 'lazada_user_id' => $smLazada->lazada_user_id,
    //                 'lazada_site' => $smLazada->site,
    //                 'lazada_currency_type' => $smLazada->currency_type,
    //                 'lazada_currency_type_cn' => $smLazada->currency_type_cn,
    //                 'lazada_api_host' => $smLazada->api_host,
    //             ];

    //             AccountModel::create($lazada);
    //         }
    //         $smLazadas = smLazada::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_smt()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
    //     $smSmts = smSmt::skip($start)->take($len)->get();
    //     while ($smSmts->count()) {
    //         $start += $len;
    //         foreach ($smSmts as $smSmt) {
    //             $smt = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'aliexpress_member_id' => $smSmt->member_id,
    //                 'aliexpress_appkey' => $smSmt->appkey,
    //                 'aliexpress_appsecret' => $smSmt->appsecret,
    //                 'aliexpress_returnurl' => $smSmt->returnurl,
    //                 'aliexpress_refresh_token' => $smSmt->refresh_token,
    //                 'aliexpress_access_token' => $smSmt->access_token,
    //                 'aliexpress_access_token_date' => $smSmt->access_token_date,
    //                 'operator_id' => $smSmt->customerservice_id,
    //                 'customer_service_id' => $smSmt->customerservice_id,
    //             ];
    //             AccountModel::create($smt);
    //         }
    //         $smSmts = smSmt::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_wish()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
    //     $smWishes = smWish::skip($start)->take($len)->get();
    //     while ($smWishes->count()) {
    //         $start += $len;
    //         foreach ($smWishes as $smWish) {
    //             $wish = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'account' => $smWish->account_name,
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'wish_publish_code' => $smWish->publish_code,
    //                 'wish_client_id' => $smWish->client_id,
    //                 'wish_client_secret' => $smWish->client_secret,
    //                 'wish_redirect_uri' => $smWish->redirect_uri,
    //                 'wish_refresh_token' => $smWish->refresh_token,
    //                 'wish_access_token' => $smWish->access_token,
    //                 'wish_expiry_time' => $smWish->expiry_time,
    //                 'wish_proxy_address' => $smWish->proxy_address ? $smWish->proxy_address : '',
    //                 'wish_sku_resolve' => $smWish->sku_type,
    //                 'is_available' => $smWish->status,
    //             ];
    //             AccountModel::create($wish);
    //         }
    //         $smWishes = smWish::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_stock()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $smStocks = smStock::skip($start)->take($len)->get();
    //     while ($smStocks->count()) {
    //         $start += $len;
    //         foreach ($smStocks as $smStock) {
    //             if ($smStock->item) {
    //                 $position = PositionModel::Where('name', $smStock->item->warehouse_position)->first();
    //                 if ($position) {
    //                     $smStock->item->in($position->id, $smStock->actual_stock,
    //                         $smStock->item->cost * $smStock->actual_stock, 'MAKE_ACCOUNT');
    //                 }
    //             }
    //         }
    //         $smStocks = smStock::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_supplier()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $smSuppliers = smSupplier::skip($start)->take($len)->get();
    //     while ($smSuppliers->count()) {
    //         $start += $len;
    //         foreach ($smSuppliers as $smSupplier) {
    //             $supplier = [
    //                 'id' => $smSupplier->suppliers_id,
    //                 'name' => $smSupplier->suppliers_name,
    //                 'contact_name' => $smSupplier->suppliers_name,
    //                 'address' => $smSupplier->suppliers_address,
    //                 'company' => $smSupplier->suppliers_company,
    //                 'url' => $smSupplier->suppliers_website,
    //                 'official_url' => $smSupplier->suppliers_website,
    //                 'telephone' => $smSupplier->suppliers_phone,
    //                 'purchase_time' => $smSupplier->supplierArrivalMinDays,
    //                 'bank_account' => $smSupplier->suppliers_bank,
    //                 'bank_code' => $smSupplier->suppliers_card_number,
    //                 'examine_status' => $smSupplier->suppliers_status,
    //                 'email' => $smSupplier->supplier_email ? $smSupplier->supplier_email : '',
    //                 'created_at' => $smSupplier->create_time,
    //                 'updated_at' => $smSupplier->modify_time,
    //             ];
    //             SupplierModel::create($supplier);
    //         }
    //         $smSuppliers = smSupplier::skip($start)->take($len)->get();
    //     }
    // }

    // public function transfer_amazon()
    // {
    //     $len = 100;
    //     $start = 0;
    //     $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;
    //     $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
    //     while ($smAmazons->count()) {
    //         $start += $len;
    //         foreach ($smAmazons as $smAmazon) {
    //             $url = parse_url($smAmazon->place_site);
    //             $amazon = [
    //                 'channel_id' => $id,
    //                 'country_id' => '0',
    //                 'account' => $smAmazon->seller_account,
    //                 'alias' => $smAmazon->seller_account,
    //                 'order_prefix' => $smAmazon->seller_account,
    //                 'sync_cycle' => '0',
    //                 'sync_days' => 30,
    //                 'sync_pages' => 100,
    //                 'amazon_api_url' => ($url['scheme']."://".$url['host']),
    //                 'amazon_marketplace_id' => $smAmazon->place_id,
    //                 'amazon_seller_id' => $smAmazon->merchant_id,
    //                 'amazon_accesskey_id' => $smAmazon->access_key,
    //                 'amazon_accesskey_secret' => $smAmazon->secret_key,
    //                 'is_available' => $smAmazon->status,
    //             ];
    //             AccountModel::create($amazon);
    //         }
    //         $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
    //     }
    // }
}