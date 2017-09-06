<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsSuppliersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_suppliers', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('customer_id')->nullable();
			$table->string('url')->nullable();
			$table->string('password')->nullable();
			$table->string('secret_key');
			$table->enum('is_api', array('0','1'));
			$table->string('client_manager');
			$table->string('manager_tel');
			$table->string('technician');
			$table->string('technician_tel');
			$table->string('remark');
			$table->string('customer_service_name');
			$table->string('customer_service_qq');
			$table->string('customer_service_tel');
			$table->string('finance_name');
			$table->string('finance_qq');
			$table->string('finance_tel');
			$table->string('driver');
			$table->string('driver_tel');
			$table->integer('logistics_collection_info_id');
			$table->string('credentials');
			$table->timestamps();
			$table->softDeletes();
			$table->string('bank');
			$table->string('card_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logistics_suppliers');
    }

}
