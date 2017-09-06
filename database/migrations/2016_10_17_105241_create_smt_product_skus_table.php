<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductSkusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_skus', function(Blueprint $table) {
            $table->increments('id');
			$table->string('skuMark');
			$table->string('skuCode');
			$table->string('smtSkuCode');
			$table->string('productId');
			$table->string('sku_active_id');
			$table->decimal('skuPrice');
			$table->decimal('skuStock');
			$table->integer('skuPropertyId');
			$table->string('propertyValueDefinitionName');
			$table->decimal('profitRate');
			$table->dateTime('synchronizationTime');
			$table->integer('isRemove')->default(0);
			$table->dateTime('last_turndown_date');
			$table->integer('is_new');
			$table->integer('is_erp');
			$table->integer('ipmSkuStock');
			$table->text('aeopSKUProperty', 65535);
			$table->integer('overSeaValId');
			$table->decimal('lowerPrice');
			$table->boolean('updated');
			$table->boolean('discountRate');
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
        Schema::drop('smt_product_skus');
    }

}
