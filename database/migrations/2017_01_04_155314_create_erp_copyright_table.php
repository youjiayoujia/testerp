<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpCopyrightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_copyright', function(Blueprint $table) {
            $table->increments('id');
            $table->string('plat');
            $table->string('account');
            $table->string('sku')->nullable();
            $table->string('pro_id')->nullable();
            $table->string('complainant')->nullable();
            $table->string('reason')->nullable();
            $table->string('trademark')->nullable();
            $table->string('ip_number')->nullable();
            $table->string('degree')->nullable();
            $table->string('violatos_number')->nullable();
            $table->string('violatos_big_type')->nullable();
            $table->string('violatos_small_type')->nullable();
            $table->boolean('status')->default(1);
            $table->string('score')->nullable();
            $table->string('violatos_start_time')->nullable();
            $table->string('violatos_fail_time')->nullable();
            $table->string('seller')->nullable();
            $table->string('remarks')->nullable();
            $table->string('import_time');
            $table->string('import_uid');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_del')->default(0);
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
        //
    }
}
