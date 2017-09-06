<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductEnglishValuesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_english_values', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->nullable();
			$table->string('choies_set')->nullable();
			$table->string('name')->nullable();
			$table->string('baoguan_name')->nullable();
			$table->string('attribute_size')->nullable();
			$table->string('store')->nullable();
			$table->string('brief')->nullable();
			$table->string('description')->nullable();
			$table->string('filter_attributes')->nullable();
			$table->decimal('weight', 7, 4)->nullable()->default(0.0000);
			$table->string('unedit_reason')->nullable();
			$table->decimal('sale_usd_price', 7)->nullable();
			$table->decimal('market_usd_price', 7)->nullable();
			$table->decimal('cost_usd_price', 7)->nullable();
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
        Schema::drop('product_english_values');
    }

}
