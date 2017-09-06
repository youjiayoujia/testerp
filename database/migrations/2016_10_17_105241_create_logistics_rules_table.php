<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsRulesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_rules', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('type_id');
			$table->float('weight_from', 15, 3);
			$table->float('weight_to', 15, 3);
			$table->enum('is_clearance', array('0','1'));
			$table->timestamps();
			$table->softDeletes();
			$table->decimal('order_amount_from', 7)->default(0.00);
			$table->decimal('order_amount_to', 7)->default(0.00);
			$table->string('name');
			$table->enum('weight_section', array('0','1'))->default('0');
			$table->enum('order_amount_section', array('0','1'))->default('0');
			$table->enum('channel_section', array('0','1'))->default('0');
			$table->enum('catalog_section', array('0','1'))->default('0');
			$table->enum('country_section', array('0','1'))->default('0');
			$table->enum('limit_section', array('0','1'))->default('0');
			$table->enum('account_section', array('0','1'))->default('0');
			$table->enum('transport_section', array('0','1'))->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logistics_rules');
    }

}
