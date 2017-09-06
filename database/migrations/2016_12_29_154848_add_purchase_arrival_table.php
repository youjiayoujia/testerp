<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseArrivalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_item_arrival_logs', function (Blueprint $table) {
            $table->integer('purchase_order_id')->comment('采购单据号')->default(NULL);
            $table->integer('user_id')->comment('收货人')->default(NULL);
            $table->tinyInteger('is_second')->comment('是否为二次收货')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_item_arrival_logs', function (Blueprint $table) {
            $table->dropColumn('purchase_order_id');
            $table->dropColumn('user_id');
            $table->dropColumn('is_second');
        });
    }
}
