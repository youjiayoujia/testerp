<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseaItemCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_item_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item id')->default(0);
            $table->string('code')->comment('仓库编码')->default('');
            $table->decimal('cost', 6, 3)->comment('海外仓库存成本')->default(0);
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
        Schema::drop('oversea_item_costs');
    }
}
