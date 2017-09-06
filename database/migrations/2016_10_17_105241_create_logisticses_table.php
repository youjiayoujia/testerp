<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logisticses', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('priority');
			$table->string('code');
			$table->string('name');
			$table->integer('warehouse_id');
			$table->integer('logistics_supplier_id');
			$table->string('type');
			$table->string('url');
			$table->string('driver')->nullable();
			$table->enum('docking', array('MANUAL','SELFAPI','API','CODE','CODEAPI'))->default('MANUAL');
			$table->integer('logistics_catalog_id')->nullable()->default(0);
			$table->integer('logistics_email_template_id')->nullable()->default(0);
			$table->integer('logistics_template_id')->nullable()->default(0);
			$table->string('pool_quantity')->nullable();
			$table->string('limit')->nullable();
			$table->string('logistics_code')->nullable();
			$table->enum('is_enable', array('0','1'));
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
        Schema::drop('logisticses');
    }

}
