<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentCostItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_cost_items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->string('hang_number');
			$table->integer('package_id')->default(0);
			$table->string('type');
			$table->integer('logistics_id')->default(0);
			$table->dateTime('shipped_at')->default('0000-00-00 00:00:00');
			$table->string('code');
			$table->string('destination');
			$table->float('all_weight')->default(0.00);
			$table->float('theory_weight')->default(0.00);
			$table->float('all_cost')->default(0.00);
			$table->float('theory_cost')->default(0.00);
			$table->string('channel_name');
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
        Schema::drop('shipment_cost_items');
    }

}
