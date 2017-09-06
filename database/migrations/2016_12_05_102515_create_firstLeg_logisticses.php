<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirstLegLogisticses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firstLeg_logisticses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('warehouse_id')->comment('仓库')->default(0);
            $table->string('name')->comment('物流方式')->default(NULL);
            $table->string('transport')->comment('运输方式')->default(NULL);
            $table->string('formula')->comment('公式')->default(NULL);
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
        Schema::drop('firstLeg_logisticses');
    }
}
