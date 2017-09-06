<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirstLegSectionPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firstLeg_sectionPrices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->decimal('weight_from', 6, 3)->comment('起始重量')->default(0);
            $table->decimal('weight_to', 6, 3)->comment('结束重量')->default(0);
            $table->decimal('cost', 6, 2)->comment('单价')->default(0);
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
        Schema::drop('firstLeg_sectionPrices');
    }
}
