<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySiteTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_site', function(Blueprint $table) {
            $table->increments('id');
			$table->string('site')->default('');
			$table->integer('site_id')->default(0);
			$table->string('currency')->default('');
			$table->integer('detail_version')->default(0);
			$table->string('returns_accepted')->default('');
			$table->string('returns_with_in')->default('');
			$table->string('shipping_costpaid_by')->default('');
			$table->string('refund')->default('');
			$table->enum('is_use', array('0','1'))->default('1');
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
        Schema::drop('ebay_site');
    }

}
