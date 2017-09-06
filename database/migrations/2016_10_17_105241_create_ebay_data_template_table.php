<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayDataTemplateTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_data_template', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->default('');
			$table->integer('site')->default(0);
			$table->integer('warehouse')->default(1);
			$table->string('start_weight')->default('');
			$table->string('end_weight')->default('');
			$table->string('start_price')->default('');
			$table->string('end_price')->default('');
			$table->string('location')->default('');
			$table->string('country')->default('');
			$table->string('postal_code')->default('');
			$table->string('dispatch_time_max')->default('');
			$table->text('buyer_requirement', 65535);
			$table->text('return_policy', 65535);
			$table->text('shipping_details', 65535);
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
        Schema::drop('ebay_data_template');
    }

}
