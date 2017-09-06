<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function(Blueprint $table) {
            $table->integer('id', true);
			$table->string('name', 100);
			$table->string('code', 2)->unique('code');
			$table->string('cn_name', 100);
			$table->string('area');
			$table->integer('number');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('parent_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
    }

}
