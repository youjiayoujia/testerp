<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
			$table->string('model')->nullable();
			$table->integer('spu_id')->nullable()->default(0);
			$table->string('name')->nullable();
			$table->string('c_name')->nullable();
			$table->string('competition_url')->nullable();
			$table->string('alias_name')->nullable();
			$table->string('alias_cname')->nullable();
			$table->integer('catalog_id')->nullable()->default(0);
			$table->integer('supplier_id')->nullable()->default(0);
			$table->string('supplier_sku')->nullable();
			$table->string('second_supplier_id')->nullable()->default('0');
			$table->string('second_supplier_sku')->nullable();
			$table->string('supplier_info')->nullable();
			$table->string('purchase_url')->nullable();
			$table->integer('purchase_day')->nullable()->default(0);
			$table->string('product_sale_url')->nullable();
			$table->decimal('purchase_price')->nullable()->default(0.00);
			$table->decimal('purchase_carriage', 6)->nullable()->default(0.00);
			$table->string('product_size')->nullable();
			$table->integer('warehouse_id')->default(0);
			$table->string('quality_standard')->nullable();
			$table->string('carriage_limit')->nullable();
			$table->string('package_limit')->nullable();
			$table->string('package_size')->nullable();
			$table->decimal('package_height', 5)->nullable();
			$table->decimal('package_width', 5)->nullable();
			$table->decimal('package_length', 5)->nullable();
			$table->decimal('height', 5)->nullable();
			$table->decimal('width', 5)->nullable();
			$table->decimal('length', 5)->nullable();
			$table->integer('upload_user')->nullable()->default(0);
			$table->integer('purchase_adminer')->nullable()->default(0);
			$table->integer('edit_user')->nullable()->default(0);
			$table->integer('edit_image_user')->nullable()->default(0);
			$table->integer('examine_user')->nullable()->default(0);
			$table->integer('revocation_user')->nullable()->default(0);
			$table->string('default_image')->nullable()->default('0');
			$table->string('size_description')->nullable();
			$table->string('description')->nullable();
			$table->decimal('package_weight', 5)->nullable();
			$table->decimal('weight', 5)->nullable()->default(0.00);
			$table->string('url1');
			$table->string('url2');
			$table->string('url3');
			$table->string('status')->nullable()->default('0');
			$table->string('edit_status')->nullable();
			$table->string('examine_status')->nullable();
			$table->string('remark')->nullable();
			$table->string('image_edit_not_pass_remark')->nullable();
			$table->string('data_edit_not_pass_remark')->nullable();
			$table->string('hs_code')->nullable();
			$table->string('unit')->nullable();
			$table->string('specification_model')->nullable();
			$table->boolean('clearance_status')->default(0);
			$table->string('notify')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('parts');
			$table->string('declared_cn');
			$table->string('declared_en');
			$table->decimal('declared_value', 5)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }

}
