<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpSalesPlatformTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_sales_platform', function(Blueprint $table) {
            $table->increments('platID');
			$table->string('platTitle');
			$table->float('platOperateFee')->default(0.00);
			$table->float('platFeeRate')->default(0.00);
			$table->float('maxForDiscount')->default(0.00);
			$table->float('platFeeDiscount')->default(0.00);
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
        Schema::drop('erp_sales_platform');
    }

}
