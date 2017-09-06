<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayAccountSetTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_account_set', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id')->default(0);
			$table->string('big_paypal')->default('');
			$table->string('small_paypal')->default('');
			$table->text('currency', 65535);
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
        Schema::drop('ebay_account_set');
    }

}
