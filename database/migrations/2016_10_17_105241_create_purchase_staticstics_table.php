<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseStaticsticsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_staticstics', function(Blueprint $table) {
            $table->increments('id');
			$table->string('purchase_adminer');
			$table->integer('sku_num');
			$table->string('need_purchase_num');
			$table->integer('fifteenday_need_order_num');
			$table->string('fifteenday_total_order_num');
			$table->string('need_percent');
			$table->string('need_total_num');
			$table->string('avg_need_day');
			$table->string('long_need_day');
			$table->string('purchase_order_exceed_time');
			$table->string('month_order_num');
			$table->string('month_order_money');
			$table->string('total_carriage');
			$table->string('save_money');
			$table->string('get_time');
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
        Schema::drop('purchase_staticstics');
    }

}
