<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseadBoxForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversead_box_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('box id')->default(0);
            $table->string('sku')->comment('sku')->default(0);
            $table->integer('quantity')->comment('数量')->default(0);
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
        Schema::drop('oversead_box_forms');
    }
}
