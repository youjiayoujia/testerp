<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OverseaStockAdjustmentForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_stock_adjustment_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->string('sku')->comment('sku')->default('');
            $table->decimal('oversea_cost', 6,3)->comment('单价')->default(0);
            $table->string('oversea_sku')->comment('单价')->default(0);
            $table->string('warehouse_position')->comment('库位')->default('');
            $table->enum('type', ['in', 'out'])->comment('出入库')->default('out');
            $table->integer('quantity')->comment('数量')->default(0);
            $table->string('remark')->comment('备注')->default('');
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
        Schema::drop('oversea_stock_adjustment_forms');
    }
}
