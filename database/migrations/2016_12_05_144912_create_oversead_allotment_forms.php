<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseadAllotmentForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversead_allotment_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item id')->default(0);
            $table->integer('warehouse_position_id')->comment('库位id')->default(0);
            $table->integer('quantity')->comment('数量')->default(0);
            $table->integer('inboxed_quantity')->comment('包装数量')->default(0);
            $table->integer('parent_id')->comment('调拨单id')->default(0);
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
        Schema::drop('oversead_allotment_forms');
    }
}
