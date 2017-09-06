<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesDetailTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_detail', function(Blueprint $table) {
            $table->increments('id');
			$table->string('issue_list_id');
			$table->string('resultMemo')->nullable();
			$table->string('orderId')->nullable();
			$table->string('gmtCreate')->nullable();
			$table->integer('issueReasonId')->nullable();
			$table->string('buyerAliid')->nullable();
			$table->string('issueStatus')->nullable();
			$table->string('issueReason')->nullable();
			$table->string('productName')->nullable();
			$table->text('productPrice', 16777215)->nullable();
			$table->text('buyerSolutionList', 16777215)->nullable();
			$table->text('sellerSolutionList', 16777215)->nullable();
			$table->text('platformSolutionList', 16777215)->nullable();
			$table->text('refundMoneyMax', 16777215)->nullable();
			$table->text('refundMoneyMaxLocal', 16777215)->nullable();
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
        Schema::drop('aliexpress_issues_detail');
    }

}
