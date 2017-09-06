<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypals', function(Blueprint $table) {
            $table->increments('id');
			$table->string('paypal_email_address');
			$table->string('paypal_account');
			$table->string('paypal_password');
			$table->text('paypal_token', 65535);
			$table->enum('is_enable', array('1','2'))->default('1');
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
        Schema::drop('paypals');
    }

}
