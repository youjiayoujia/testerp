<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OverseaStockAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_stock_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->comment('时间')->default('0000-00-00');
            $table->integer('adjust_by')->comment('调整人')->default(0);
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
        Schema::drop('oversea_stock_adjustments');
    }
}
