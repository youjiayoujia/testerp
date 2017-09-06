<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesListTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_list', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id');
			$table->string('issue_id')->nullable();
			$table->string('issueStatus')->nullable();
			$table->string('issueType')->nullable();
			$table->string('gmtCreate')->nullable();
			$table->string('issueProcessDTOs')->nullable();
			$table->string('reasonChinese')->nullable();
			$table->string('orderId')->nullable();
			$table->string('reasonEnglish')->nullable();
			$table->string('gmtModified')->nullable();
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
        Schema::drop('aliexpress_issues_list');
    }

}
