<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequiresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requires', function(Blueprint $table) {
            $table->increments('id');
			$table->text('img1', 65535)->nullable();
			$table->text('img2', 65535)->nullable();
			$table->text('img3', 65535)->nullable();
			$table->text('img4', 65535)->nullable();
			$table->text('img5', 65535)->nullable();
			$table->text('img6', 65535)->nullable();
			$table->text('url1', 65535)->nullable();
			$table->text('url2', 65535)->nullable();
			$table->text('url3', 65535)->nullable();
			$table->string('color');
			$table->string('material');
			$table->string('technique');
			$table->string('parts');
			$table->string('name', 128);
			$table->integer('catalog_id');
			$table->string('province');
			$table->string('city');
			$table->string('similar_sku')->nullable();
			$table->string('competition_url')->nullable();
			$table->text('remark', 65535)->nullable();
			$table->date('expected_date');
			$table->integer('needer_id');
			$table->integer('needer_shop_id');
			$table->integer('purchase_id');
			$table->string('created_by');
			$table->enum('status', array('0','1','2','3'))->default('0');
			$table->integer('handle_id')->nullable();
			$table->date('handle_time')->nullable();
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
        Schema::drop('product_requires');
    }

}
