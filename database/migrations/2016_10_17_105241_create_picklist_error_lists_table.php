<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistErrorListsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_error_lists', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id')->default(0);
			$table->string('packageNum');
			$table->integer('warehouse_position_id')->default(0);
			$table->integer('warehouse_id')->default(0);
			$table->integer('quantity')->default(0);
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
        Schema::drop('picklist_error_lists');
    }

}
