<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id');
			$table->integer('channel_account_id');
			$table->integer('order_id');
			$table->integer('warehouse_id');
			$table->integer('logistics_id');
			$table->integer('picklist_id');
			$table->integer('assigner_id');
			$table->integer('shipper_id');
			$table->enum('type', array('SINGLE','SINGLEMULTI','MULTI'))->default('SINGLE');
			$table->decimal('cost', 10);
			$table->decimal('cost1', 10);
			$table->decimal('weight', 10,3);
			$table->decimal('actual_weight', 10,3);
			$table->decimal('length', 10);
			$table->decimal('width', 10);
			$table->decimal('height', 10);
			$table->string('tracking_no');
			$table->string('logistics_order_number');
			$table->string('tracking_link');
			$table->enum('is_mark', array('0','1'))->default('0');
			$table->enum('is_upload', array('0','1','2'))->default('0');
			$table->string('email');
			$table->string('shipping_firstname');
			$table->string('shipping_lastname');
			$table->string('shipping_address');
			$table->string('shipping_address1')->nullable();
			$table->string('shipping_city');
			$table->string('shipping_state');
			$table->string('shipping_country');
			$table->string('shipping_zipcode');
			$table->string('shipping_phone');
			$table->enum('is_auto', array('0','1'))->default('1');
			$table->text('remark', 65535);
			$table->enum('is_tonanjing', array('0','1'))->default('0');
			$table->enum('is_over', array('0','1'))->default('0');
			$table->dateTime('logistics_assigned_at')->default('0000-00-00 00:00:00');
			$table->dateTime('logistics_order_at')->default('0000-00-00 00:00:00');
			$table->dateTime('printed_at')->default('0000-00-00 00:00:00');
			$table->dateTime('shipped_at')->default('0000-00-00 00:00:00');
			$table->dateTime('delivered_at')->default('0000-00-00 00:00:00');
			$table->timestamps();
			$table->softDeletes();
			$table->string('lazada_package_id')->default('');
			$table->enum('status', array('ERROR','SHIPPED','PACKED','PICKING','PROCESSING','TRACKINGFAILED','NEED','ASSIGNFAILED','ASSIGNED','WAITASSIGN','NEW'))->nullable()->default('NEW');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages');
    }

}
