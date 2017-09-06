<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id');
			$table->integer('channel_account_id');
			$table->string('ordernum');
			$table->string('channel_ordernum');
			$table->string('channel_listnum')->default('');
			$table->string('by_id');
			$table->string('email');
			$table->enum('status', array('UNPAID','PAID','PREPARED','PARTIAL','NEED','PACKED','SHIPPED','COMPLETE','CANCEL','REVIEW','PICKING'))->default('PAID');
			$table->enum('active', array('NORMAL','VERIFY','CHARGEBACK','STOP','RESUME'))->default('NORMAL');
			$table->enum('order_is_alert', array('0','1','2'))->default('0');
			$table->float('amount', 15);
			$table->string('gross_margin')->nullable();
			$table->decimal('profit_rate', 11, 9)->nullable();
			$table->float('amount_product', 15);
			$table->float('amount_shipping', 15);
			$table->float('amount_coupon', 15);
			$table->string('transaction_number');
			$table->string('customer_service')->nullable();
			$table->string('operator')->nullable();
			$table->string('payment');
			$table->string('currency');
			$table->decimal('rate', 11, 9);
			$table->enum('address_confirm', array('0','1'))->default('1');
			$table->string('shipping')->nullable();
			$table->string('shipping_firstname');
			$table->string('shipping_lastname');
			$table->string('shipping_address');
			$table->string('shipping_address1')->nullable();
			$table->string('shipping_city');
			$table->string('shipping_state');
			$table->string('shipping_country');
			$table->string('shipping_zipcode');
			$table->string('shipping_phone');
			$table->string('billing_firstname')->nullable();
			$table->string('billing_lastname')->nullable();
			$table->string('billing_address')->nullable();
			$table->string('billing_city')->nullable();
			$table->string('billing_state')->nullable();
			$table->string('billing_country')->nullable();
			$table->string('billing_zipcode')->nullable();
			$table->string('billing_phone')->nullable();
			$table->text('customer_remark', 65535);
			$table->string('withdraw_reason');
			$table->enum('withdraw', array('1','2','3','4','5','6','7','8','9','10'))->nullable();
			$table->string('cele_admin')->nullable();
			$table->integer('priority')->nullable()->default(0);
			$table->integer('package_times')->nullable()->default(0);
			$table->integer('split_times')->nullable()->default(0);
			$table->integer('split_quantity')->nullable()->default(0);
			$table->string('fulfill_by')->nullable();
			$table->enum('blacklist', array('0','1'))->nullable()->default('1');
			$table->float('platform', 15)->nullable()->default(0.00);
			$table->string('aliexpress_loginId')->nullable();
			$table->date('payment_date');
			$table->dateTime('create_time')->default('0000-00-00 00:00:00');
			$table->timestamps();
			$table->softDeletes();
			$table->enum('is_chinese', array('0','1'))->default('0');
			$table->dateTime('orders_expired_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }

}
