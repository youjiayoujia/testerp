<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeIsToWishPublishProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('wish_publish_product', function (Blueprint $table) {
            $table->string('sku_perfix')->comment('sku前缀')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wish_publish_product', function (Blueprint $table) {
            $table->dropColumn('sku_perfix');
        });
    }
}
