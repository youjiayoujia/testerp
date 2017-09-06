<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductListTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_list', function(Blueprint $table) {
            $table->increments('id');
			$table->string('product_url');
			$table->integer('token_id');
			$table->string('productId')->unique('smt_product_list_productid_unique');
			$table->integer('user_id');
			$table->string('ownerMemberId');
			$table->string('ownerMemberSeq');
			$table->string('subject');
			$table->decimal('productPrice');
			$table->decimal('productMinPrice');
			$table->decimal('productMaxPrice');
			$table->string('productStatusType');
			$table->dateTime('gmtCreate');
			$table->dateTime('gmtModified');
			$table->dateTime('wsOfflineDate');
			$table->string('wsDisplay');
			$table->integer('quantitySold1');
			$table->integer('groupId');
			$table->integer('categoryId');
			$table->integer('packageLength');
			$table->integer('packageWidth');
			$table->integer('packageHeight');
			$table->string('grossWeight');
			$table->smallInteger('deliveryTime');
			$table->smallInteger('wsValidNum');
			$table->boolean('multiattribute');
			$table->boolean('isRemove');
			$table->integer('old_token_id');
			$table->string('old_productId');
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
        Schema::drop('smt_product_list');
    }

}
