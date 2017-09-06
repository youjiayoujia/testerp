<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayDescriptionTemplateTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_description_template', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->default('');
			$table->integer('site')->default(0);
			$table->integer('warehouse')->default(1);
			$table->text('description', 65535);
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
        Schema::drop('ebay_description_template');
    }

}
