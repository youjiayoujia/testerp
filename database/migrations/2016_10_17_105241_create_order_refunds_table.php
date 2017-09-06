<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('order_id');
			$table->float('refund_amount', 15);
			$table->float('price', 15);
			$table->string('refund_currency');
			$table->enum('refund', array('1','2'));
			$table->enum('type', array('FULL','PARTIAL'));
			$table->enum('reason', array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'));
			$table->text('memo', 65535)->nullable();
			$table->text('detail_reason', 65535)->nullable();
			$table->string('image')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('account_id')->nullable();
			$table->integer('channel_id')->nullable();
			$table->integer('customer_id')->nullable();
			$table->string('user_paypal_account')->nullable();
			$table->string('refund_voucher')->nullable();
			$table->enum('process_status', array('INVALID','COMPLETE','FINANCE','PAUSE','REFUSE','PENDING'))->nullable()->default('INVALID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_refunds');
    }

}
