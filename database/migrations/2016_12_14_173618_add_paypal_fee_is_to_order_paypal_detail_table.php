<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaypalFeeIsToOrderPaypalDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_paypal_detail', function (Blueprint $table) {
            $table->float('feeAmt')->comment('手续费金额')->default(0.00)->after('paypal_country');
            $table->string('currencyCode')->comment('币种')->default('')->after('feeAmt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('order_paypal_detail', function (Blueprint $table) {
            $table->dropColumn('feeAmt');
            $table->dropColumn('currencyCode');

        });
    }
}
