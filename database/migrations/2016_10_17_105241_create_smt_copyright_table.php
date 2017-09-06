<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCopyrightTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_copyright', function(Blueprint $table) {
            $table->increments('id');
			$table->string('account');
			$table->string('sku');
			$table->string('pro_id');
			$table->string('complainant');
			$table->string('reason');
			$table->string('trademark');
			$table->string('ip_number');
			$table->string('degree');
			$table->string('violatos_number');
			$table->string('violatos_big_type');
			$table->string('violatos_small_type');
			$table->boolean('status')->default(1);
			$table->string('score');
			$table->string('violatos_start_time');
			$table->string('violatos_fail_time');
			$table->string('seller');
			$table->string('remarks');
			$table->string('import_time');
			$table->string('import_uid');
			$table->boolean('is_del')->default(1);
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
        Schema::drop('smt_copyright');
    }

}
