<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseaBoxsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_boxs', function(Blueprint $table) {
            $table->increments('id');
			$table->string('boxNum');
			$table->integer('logistics_id')->default(0);
			$table->string('tracking_no');
			$table->decimal('length', 6)->default(0.00);
			$table->decimal('width', 6)->default(0.00);
			$table->decimal('height', 6)->default(0.00);
			$table->decimal('weight', 7, 3)->default(0.000);
			$table->integer('parent_id')->default(0);
			$table->enum('status', array('0','1'))->default('0');
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
        Schema::drop('oversea_boxs');
    }

}
