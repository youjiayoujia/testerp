<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbaStockInfosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_stock_infos', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id')->default(0);
			$table->integer('account_id')->default(0);
			$table->string('channel_sku');
			$table->string('fnsku');
			$table->string('asin');
			$table->string('title');
			$table->integer('mfn_fulfillable_quantity');
			$table->integer('afn_warehouse_quantity');
			$table->integer('afn_fulfillable_quantity');
			$table->integer('afn_unsellable_quantity');
			$table->integer('afn_reserved_quantity');
			$table->integer('afn_total_quantity');
			$table->float('per_unit_volume');
			$table->integer('afn_inbound_working_quantity');
			$table->integer('afn_inbound_shipped_quantity');
			$table->integer('afn_inbound_receiving_quantity');
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
        Schema::drop('fba_stock_infos');
    }

}
