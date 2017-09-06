<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsRuleLimitsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_rule_limits', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('logistics_rule_id')->default(0);
			$table->integer('logistics_limit_id')->default(0);
			$table->enum('type', array('0','1','2'))->default('0');
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
        Schema::drop('logistics_rule_limits');
    }

}
