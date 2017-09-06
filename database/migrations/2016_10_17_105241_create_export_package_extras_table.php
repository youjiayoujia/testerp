<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportPackageExtrasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_package_extras', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id');
			$table->string('fieldName');
			$table->string('fieldValue');
			$table->string('fieldLevel');
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
        Schema::drop('export_package_extras');
    }

}
