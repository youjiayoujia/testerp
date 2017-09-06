<?php
/** Ebay 自动补货脚本
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-11-05
 * Time: 13:28
 */

namespace App\Console\Commands;

use Tool;
use Channel;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayReplenishmentLogModel;
use App\Models\ItemModel;


class AutoEbayAdd extends Command
{
    use  DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoEbayAdd:account{accountID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ebay 自动补货';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $accountId = $this->argument('accountID');
        $account = AccountModel::find($accountId);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $timeFrom = date('Y-m-d H:i:s', strtotime('-10 hours'));
        $timeTo = date('Y-m-d H:i:s');
        $is_do = true;
        $page = 0;
        do {
            $page++;
            $result = $this->getListOrders($channel, $timeFrom, $timeTo, $page);
            if ($result) {
                foreach ($result as $v) {
                    $v['token_id'] = $accountId;
                    $v['update_time'] = date('Y-m-d H:i:s', time());
                    $v['is_api_success'] = 2;
                    $is_has = EbayReplenishmentLogModel::where(['order_id' => $v['order_id']])->first();
                    if (empty($is_has)) {
                        $check_sku = $this->getErpSku($v['sku']);
                        $item = ItemModel::where(['sku' => $check_sku])->first();
                        if (empty($item)) {
                            $v['remark'] = $check_sku . '对应ERP信息未找到';
                            EbayReplenishmentLogModel::create($v);
                            continue;
                        }
                        if (in_array($item->status, array('sellWaiting', 'stopping'))) {
                            $v['remark'] = $check_sku . '的状态为' . $item->status . '不进行补货';
                            EbayReplenishmentLogModel::create($v);
                            continue;
                        }
                        if (in_array($item->status, array('cleaning', 'saleOutStopping', 'unSellTemp'))) { //需判断库存
                            $is_disable = true;
                            if ($item->AvailableQuantity + $item->NormalTransitQuantity > 5) {
                                $is_disable = false;
                            }
                            if ($is_disable) {
                                $v['remark'] = $check_sku . '的状态为' . $item->status . '虚库存+在途库存 小于5';
                                EbayReplenishmentLogModel::create($v);
                                continue;
                            }
                        }
                        $sku_info = $this->getProductDetail($v['item_id'], $channel);
                        if ($sku_info) {
                            foreach ($sku_info as $sku) {
                                if (strtoupper($sku['sku']) == strtoupper($v['sku'])) {
                                    $v['quantity'] = $v['quantity'] + $sku['quantity'];
                                    $is_has = true;
                                    break;
                                }
                            }
                            if ($is_has) {
                                $is_success = $this->changeQuantity($v, $channel);
                                if ($is_success) {
                                    $v['is_api_success'] = 1;
                                    EbayReplenishmentLogModel::create($v);
                                }
                            }
                        }
                    }
                }
            }else{
                $is_do = false;
            }
        } while ($is_do);


    }

    private function  getListOrders($channel, $timeFrom, $timeTo, $page)
    {
        $returnOrder = array();
        $returnMustBe = 'OrderArray.Order.OrderID,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Name,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.LastModifiedTime,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.Status,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.eBayPaymentStatus,';
        $returnMustBe .= 'OrderArray.Order.BuyerCheckoutMessage,';
        $returnMustBe .= 'OrderArray.Order.ExternalTransaction.ExternalTransactionID,';
        $returnMustBe .= 'OrderArray.Order.ShippingDetails.SellingManagerSalesRecordNumber,';
        $returnMustBe .= 'OrderArray.Order.Total,';
        $returnMustBe .= 'OrderArray.Order.OrderStatus,';
        $returnMustBe .= 'OrderArray.Order.PaymentMethods,';
        $returnMustBe .= 'OrderArray.Order.CreatedTime,';
        $returnMustBe .= 'OrderArray.Order.BuyerUserID,';
        $returnMustBe .= 'OrderArray.Order.PaidTime,';
        $returnMustBe .= 'OrderArray.Order.ShippedTime,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.ItemID,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.SKU,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.Site,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.SKU,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.QuantityPurchased,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.VariationSpecifics.NameValueList,';//广告属性
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.ShippingDetails.SellingManagerSalesRecordNumber,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.TransactionID,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.TransactionPrice,';
        $returnMustBe .= 'PageNumber,';
        $returnMustBe .= 'PaginationResult.TotalNumberOfEntries,';
        $returnMustBe .= 'PaginationResult.TotalNumberOfPages';
        $requestXmlBody = '<DetailLevel>ReturnAll</DetailLevel>';
        $requestXmlBody .= '<ErrorLanguage>zh_CN</ErrorLanguage>';
        $requestXmlBody .= '<OutputSelector>' . $returnMustBe . '</OutputSelector>';
        $requestXmlBody .= '<Version>745</Version>';
        $requestXmlBody .= '<WarningLevel>High</WarningLevel>';
        $requestXmlBody .= '<IncludeFinalValueFee>false</IncludeFinalValueFee>';
        $requestXmlBody .= '<CreateTimeFrom>' . $timeFrom . '</CreateTimeFrom>';
        $requestXmlBody .= '<CreateTimeTo>' . $timeTo . '</CreateTimeTo>';
        $requestXmlBody .= '<OrderRole>Seller</OrderRole>';
        $requestXmlBody .= '<OrderStatus>All</OrderStatus>';
        $requestXmlBody .= '<Pagination>';
        $requestXmlBody .= '<EntriesPerPage>100</EntriesPerPage>';
        $requestXmlBody .= '<PageNumber>' . $page . '</PageNumber>';
        $requestXmlBody .= '</Pagination>';
        $response = $channel->buildEbayBody($requestXmlBody, 'GetOrders');
        if (isset($response->OrderArray->Order) && !empty($response->OrderArray->Order)) {
            $orders = $response->OrderArray->Order;
            foreach ($orders as $order) {
                $result = $this->parseOrder($order);
                foreach ($result as $v) {
                    $returnOrder[] = $v;
                }
            }
            return $returnOrder;
        } else {
            return false;
        }
    }

    private function getProductDetail($itemId, $channel, $site = 0)
    {
        $requestXmlBody = '<ItemID>' . $itemId . '</ItemID>';
        $response = $channel->buildEbayBody($requestXmlBody, 'GetItem');
        if ($response->Ack == 'Success') {
            $sku_info = array();
            $Variations = isset($response->Item->Variations->Variation) ? $response->Item->Variations->Variation : '';
            if (!empty($Variations)) {
                $i = 0;
                foreach ($Variations as $variation) {
                    $sku_info[$i]['sku'] = (string)$variation->SKU;
                    $sku_info[$i]['quantity'] = (int)$variation->Quantity - (int)$variation->SellingStatus->QuantitySold;
                    $i++;
                }
            } else {
                $sku_info[0]['sku'] = (string)$response->Item->SKU;
                $sku_info[0]['quantity'] = (int)$response->Item->Quantity - (int)$response->Item->SellingStatus->QuantitySold;


            }

            return $sku_info;
        } else {
            return false;
        }
    }


    function changeQuantity($data, $channel)
    {
        $requestXmlBody = '<InventoryStatus>';
        $requestXmlBody .= '<ItemID>' . $data['item_id'] . '</ItemID>';
        if ($data['is_mul']) {
            $requestXmlBody .= '<SKU>' . $data['sku'] . '</SKU>';
        }
        $requestXmlBody .= '<Quantity>' . $data['quantity'] . '</Quantity>';
        $requestXmlBody .= '</InventoryStatus>';
        $response = $channel->buildEbayBody($requestXmlBody, 'ReviseInventoryStatus');
        if ($response->Ack == 'Success' || $response->Ack == 'Warning') {
            return true;
        } else {
            return false;
        }
    }


    private function parseOrder($order)
    {
        $returnOrder = array();
        $detail = array();
        if (isset($order->TransactionArray->Transaction[0])) {
            foreach ($order->TransactionArray->Transaction as $sku) {
                $detail[] = $this->parseItem($sku);
            }
        } else {
            $detail[] = $this->parseItem($order->TransactionArray->Transaction);
        }

        foreach ($detail as $key => $de) {
            $returnOrder[$key]['sku'] = $de['sku'];
            $returnOrder[$key]['quantity'] = $de['quantity'];
            $returnOrder[$key]['order_id'] = (string)$order->OrderID;
            $returnOrder[$key]['item_id'] = $de['item_id'];
            $returnOrder[$key]['is_mul'] = $de['is_mul'];
        }

        return $returnOrder;


    }

    private function parseItem($Transaction)
    {
        $detail = array();
        if (isset($Transaction->Variation->SKU)) {
            $channel_sku = (string)$Transaction->Variation->SKU;
            $detail['is_mul'] = true;
        } else {
            $channel_sku = (string)$Transaction->Item->SKU;
            $detail['is_mul'] = false;
        }
        $detail['sku'] = $channel_sku;
        $detail['quantity'] = intval($Transaction->QuantityPurchased);
        $detail['item_id'] = (string)$Transaction->Item->ItemID;
        return $detail;

    }


    private function getErpSku($sku)
    {
        $tmpSku = explode('+', $sku);
        $returnSku = array();
        foreach ($tmpSku as $k => $sku) {
            if (stripos($sku, '[') !== false) {
                $sku = preg_replace('/\[.*\]/', '', $sku);
            }
            if (stripos($sku, '(') !== false) {
                $sku = preg_replace('/\(.*\)/', '', $sku);
            }
            $tmpErpSku = explode('*', $sku);
            $i = count($tmpErpSku) - 1;
            $newSku = $tmpErpSku[$i];
            $newSku = explode("#", $newSku);
            $newSku = $newSku[0];
            $returnSku[] = $newSku;

        }

        return implode('+', $returnSku);
    }
}

