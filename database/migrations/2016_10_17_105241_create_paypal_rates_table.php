<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalRatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_rates', function(Blueprint $table) {
            $table->increments('id');
			$table->float('transactions_fee_big');
			$table->float('transactions_fee_small');
			$table->float('fixed_fee_big');
			$table->float('fixed_fee_small');
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
        Schema::drop('paypal_rates');
    }

}
