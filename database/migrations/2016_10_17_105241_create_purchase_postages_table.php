<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasePostagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_postages', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('purchase_item_id')->nullable()->default(0);
			$table->integer('purchase_order_id')->nullable();
			$table->string('post_coding')->nullable();
			$table->decimal('postage')->nullable()->default(0.00);
			$table->integer('user_id')->nullable();
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
        Schema::drop('purchase_postages');
    }

}
