<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentCostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_costs', function(Blueprint $table) {
            $table->increments('id');
			$table->string('shipmentCostNum');
			$table->decimal('all_weight', 7, 3);
			$table->decimal('theory_weight', 7, 3);
			$table->float('all_shipment_cost')->default(0.00);
			$table->float('theory_shipment_cost')->default(0.00);
			$table->text('average_price', 65535);
			$table->integer('import_by')->default(0);
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipment_costs');
    }

}
