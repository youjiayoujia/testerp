<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('us_rate',7,2)->comment('us_rate')->default(0);
            $table->decimal('uk_rate',7,2)->comment('uk_rate')->default(0.25);
            $table->decimal('eu_rate',7,2)->comment('eu_rate')->default(0.25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('us_rate');
            $table->dropColumn('uk_rate');
            $table->dropColumn('eu_rate');
        });
    }
}
