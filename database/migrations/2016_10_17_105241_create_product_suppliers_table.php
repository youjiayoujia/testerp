<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSuppliersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_suppliers', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 128);
			$table->string('province');
			$table->string('city');
			$table->string('address');
			$table->string('company');
			$table->enum('type', array('0','1','2'))->default('0');
			$table->string('url', 64);
			$table->string('official_url', 64);
			$table->string('contact_name', 16);
			$table->string('telephone');
			$table->string('qq')->nullable();
			$table->string('wangwang')->nullable();
			$table->integer('level_id')->default(1);
			$table->integer('created_by');
			$table->integer('purchase_time')->nullable();
			$table->string('bank_account')->nullable();
			$table->string('bank_code')->nullable();
			$table->enum('pay_type', array('ONLINE','BANK_PAY','CASH_PAY','OTHER_PAY'))->default('ONLINE');
			$table->string('qualifications')->nullable();
			$table->string('examine_status')->nullable()->default('0');
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
        Schema::drop('product_suppliers');
    }

}
