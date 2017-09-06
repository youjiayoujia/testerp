<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductDetailTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_detail', function(Blueprint $table) {
            $table->increments('id');
			$table->string('productId');
			$table->text('aeopAeProductPropertys', 65535);
			$table->text('imageURLs', 65535);
			$table->string('detail');
			$table->string('keyword');
			$table->string('productMoreKeywords1');
			$table->string('productMoreKeywords2');
			$table->integer('productUnit');
			$table->boolean('isImageDynamic')->default(0);
			$table->boolean('isImageWatermark')->default(0);
			$table->integer('lotNum');
			$table->integer('bulkOrder');
			$table->boolean('packageType');
			$table->boolean('isPackSell');
			$table->boolean('bulkDiscount');
			$table->integer('promiseTemplateId');
			$table->integer('freightTemplateId');
			$table->integer('templateId');
			$table->integer('shouhouId');
			$table->text('detail_title', 65535);
			$table->integer('sizechartId');
			$table->string('src');
			$table->text('detailPicList', 65535);
			$table->text('detailLocal', 65535);
			$table->string('relationProductIds');
			$table->string('relationLocation');
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
        Schema::drop('smt_product_detail');
    }

}
