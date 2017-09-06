<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuPublishRecordsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_publish_records', function(Blueprint $table) {
            $table->increments('id');
			$table->string('SKU');
			$table->integer('userID');
			$table->dateTime('publishTime');
			$table->integer('platTypeID');
			$table->integer('publishPlat');
			$table->string('sellerAccount');
			$table->string('itemNumber');
			$table->text('publishViewUrl', 65535);
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
        Schema::drop('sku_publish_records');
    }

}
