<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseaReportFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_report_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('item_id')->default(0);
			$table->integer('warehouse_position_id')->default(0);
			$table->string('sku');
			$table->string('fnsku');
			$table->integer('report_quantity')->default(0);
			$table->integer('out_quantity')->default(0);
			$table->integer('inbox_quantity')->default(0);
			$table->string('boxNum')->default('0');
			$table->integer('in_quantity')->default(0);
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
        Schema::drop('oversea_report_forms');
    }

}
