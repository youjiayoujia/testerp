<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\ShipmentCategoryModel as smShipmentCategory;
use App\Models\Sellmore\ShipmentModel as smShipment;
use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\SupplierModel as originSupplier;
use App\Models\LogisticsModel;
use App\Models\Sellmore\ShipmentSupplierModel as smShipmentSupplier;

class TransferLogistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:logistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
        while ($smShipmentCategorys->count()) {
            $start += $len;
            foreach ($smShipmentCategorys as $smShipmentCategory) {
                $originNum++;
                $shipmentCategory = [
                    'id' => $smShipmentCategory->shipmentCatID,
                    'code' => $smShipmentCategory->shipmentCatName
                ];
                $exist = CatalogModel::where(['id' => $smShipmentCategory->shipmentCatID])->first();
                if($exist) {
                    $exist->update($shipmentCategory);
                    $updatedNum++;
                } else {
                    CatalogModel::create($shipmentCategory);
                    $createdNum++;
                } 
            }
            $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
        }
        $this->info('Transfer [ShipmentCategory]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        /************************************/
        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $originNum++;
                $shipment = [
                    'id' => $smShipment->shipmentID,
                    'name' => $smShipment->shipmentTitle,
                    'warehouse_id' => $smShipment->shipment_warehouse_id == '1025' ? '2' : '1',
                    'logistics_catalog_id' => $smShipment->shipmentCategoryID,
                    'docking' => 'CODE',
                    'is_enable' => '1',
                ];
                $exist = LogisticsModel::where(['id' => $smShipment->shipmentID])->first();
                if($exist) {
                    $exist->update($shipment);
                    $updatedNum++;
                } else {
                    LogisticsModel::create($shipment);
                    $createdNum++;
                } 
            }

            $smShipments = smShipment::skip($start)->take($len)->get();
        }
        $this->info('Transfer [Shipment]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);

        /******************************************/
        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $smCds = smShipmentSupplier::skip($start)->take($len)->get();
        while ($smCds->count()) {
            $start += $len;
            foreach ($smCds as $smCd) {
                $originNum++;
                $cd = [
                    'id' => $smCd->suppliers_id,
                    'name' => $smCd->suppliers_name,
                    'client_manager' => $smCd->suppliers_services ? $smCd->suppliers_services : '',
                    'manager_tel' => $smCd->suppliers_services_phoneorqq,
                    'technician' => $smCd->suppliers_driver,
                    'technician_tel' => $smCd->suppliers_driver_phone,
                    'remark' => $smCd->suppliers_remark ? $smCd->suppliers_remark : '',
                    'bank' => $smCd->suppliers_bank,
                    'card_number' => $smCd->suppliers_card_number,
                ];
                $exist = originSupplier::where(['id' => $smCd->suppliers_id])->first();
                if($exist) {
                    $exist->update($cd);
                    $updatedNum++;
                } else {
                    originSupplier::create($cd);
                    $createdNum++;
                } 
            }
            $smCds = smShipmentSupplier::skip($start)->take($len)->get();
        }
        $this->info('Transfer [ShipmentSupplier]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);
    }
}
