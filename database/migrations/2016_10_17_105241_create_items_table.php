<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('catalog_id')->nullable();
			$table->string('product_id')->nullable()->default('0');
			$table->string('sku')->nullable()->default('0');
			$table->string('name')->nullable();
			$table->string('c_name')->nullable();
			$table->text('competition_url');
			$table->decimal('weight', 6,3)->nullable()->default(0.00);
			$table->decimal('package_weight', 6,3)->nullable();
			$table->string('inventory')->nullable();
			$table->integer('warehouse_id')->default(0);
			$table->string('warehouse_position')->nullable();
			$table->string('alias_name')->nullable();
			$table->string('alias_cname')->nullable();
			$table->integer('supplier_id')->nullable();
			$table->string('supplier_sku')->nullable();
			$table->string('second_supplier_id')->nullable();
			$table->string('second_supplier_sku')->nullable();
			$table->string('supplier_info')->nullable();
			$table->text('purchase_url')->nullable();
			$table->decimal('purchase_price', 7)->nullable();
			$table->decimal('purchase_carriage', 5)->nullable();
			$table->integer('purchase_adminer')->nullable();
			$table->float('cost')->nullable();
			$table->string('product_size')->nullable();
			$table->string('package_size')->nullable();
			$table->decimal('height', 5)->nullable();
			$table->decimal('width', 5)->nullable();
			$table->decimal('length', 5)->nullable();
			$table->decimal('package_height', 5)->nullable();
			$table->decimal('package_width', 5)->nullable();
			$table->decimal('package_length', 5)->nullable();
			$table->string('carriage_limit')->nullable();
			$table->string('package_limit')->nullable();
			$table->string('status')->nullable();
			$table->integer('new_status')->nullable()->default('0');
			$table->boolean('is_available')->nullable()->default(1);
			$table->string('remark')->nullable();
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
        Schema::drop('items');
    }

}
