<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('po_id')->comment('采购单号')->default(0);
            $table->integer('status')->comment('核销状态(同采购单)')->default(1);
            $table->decimal('real_money',10,3)->comment('实际核销金额')->default(0);
            $table->decimal('no_delivery_money',10,3)->comment('未到货金额')->default(0);
            $table->string('reason')->comment('核销原因')->default(NULL);
            $table->string('credential')->comment('退款凭证')->default(NULL);
            $table->integer('po_user')->comment('采购人')->default(0);
            $table->timestamp('refund_time')->comment('退款时间')->default(NULL);
            $table->integer('create_user')->comment('上传人')->default(0);
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
        Schema::drop('purchase_order_confirms');
    }
}
