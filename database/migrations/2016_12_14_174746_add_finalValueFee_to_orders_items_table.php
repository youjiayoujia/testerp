<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalValueFeeToOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_items', function (Blueprint $table) {
            $table->float('final_value_fee')->comment('ebay成交费')->default(0.00)->after('transaction_id');
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
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('final_value_fee');
        });
    }
}
