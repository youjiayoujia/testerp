<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelSuggestFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_suggest_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id')->default(0);
			$table->string('channel_sku');
			$table->string('fnsku');
			$table->integer('fba_all_quantity')->default(0);
			$table->integer('fba_available_quantity')->default(0);
			$table->integer('all_quantity')->default(0);
			$table->integer('sales_in_seven')->default(0);
			$table->integer('sales_in_fourteen')->default(0);
			$table->integer('suggest_quantity')->default(0);
			$table->integer('account_id')->default(0);
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
        Schema::drop('channel_suggest_forms');
    }

}
