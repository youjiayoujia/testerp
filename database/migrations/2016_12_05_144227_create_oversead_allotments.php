<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseadAllotments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversead_allotments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('allotment_num')->comment('调拨单号')->default(NULL);
            $table->integer('out_warehouse_id')->comment('调出仓库')->default(0);
            $table->integer('in_warehouse_id')->comment('调入仓库')->default(0);
            $table->integer('logistics_id')->comment('头程物流')->default(0);
            $table->integer('allotment_by')->comment('调拨人')->default(0);
            $table->enum('status', ['new', 'pick', 'inboxing', 'inboxed', 'out', 'over'])->comment('调拨单状态')->default('new');
            $table->integer('check_by')->comment('审核人')->default(0);
            $table->enum('check_status', ['new', 'fail', 'pass'])->comment('审核状态')->default('new');
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
        Schema::drop('oversead_allotments');
    }
}
