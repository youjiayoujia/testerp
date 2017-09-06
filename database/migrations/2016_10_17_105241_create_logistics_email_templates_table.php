<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsEmailTemplatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_email_templates', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('customer');
			$table->string('address');
			$table->string('zipcode');
			$table->string('phone');
			$table->string('unit');
			$table->string('sender');
			$table->string('city')->nullable();
			$table->string('province')->nullable();
			$table->string('country_code')->nullable();
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
        Schema::drop('logistics_email_templates');
    }

}
