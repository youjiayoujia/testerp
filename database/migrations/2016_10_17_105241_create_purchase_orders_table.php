<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('type')->nullable();
			$table->integer('carriage_type')->nullable();
			$table->integer('status')->default(0);
			$table->integer('examineStatus')->default(0);
			$table->integer('costExamineStatus')->default(0);
			$table->integer('close_status')->default(0);
			$table->integer('supplier_id');
			$table->integer('user_id')->nullable();
			$table->integer('purchase_userid')->nullable();
			$table->integer('update_userid')->nullable();
			$table->integer('warehouse_id');
			$table->integer('is_certificate')->nullable();
			$table->date('start_buying_time')->nullable();
			$table->integer('assigner')->nullable();
			$table->enum('print_status', array('0','1'))->default('0');
			$table->integer('print_num')->nullable()->default(0);
			$table->enum('write_off', array('0','1','2'))->default('0');
			$table->enum('pay_type', array('ONLINE','BANK_PAY','CASH_PAY','OTHER_PAY'))->default('ONLINE');
			$table->float('total_postage', 15, 8)->nullable();
			$table->string('post_coding')->nullable();
			$table->float('total_purchase_cost', 15, 8)->nullable();
			$table->string('remark')->nullable();
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
        Schema::drop('purchase_orders');
    }

}
