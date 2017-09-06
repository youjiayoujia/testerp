<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelAccountsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_accounts', function(Blueprint $table) {
            $table->increments('id');
			$table->string('account');
			$table->string('alias');
			$table->integer('channel_id');
			$table->integer('country_id');
			$table->string('order_prefix');
			$table->float('sync_cycle');
			$table->integer('sync_days');
			$table->integer('sync_pages');
			$table->string('domain');
			$table->string('image_domain');
			$table->string('service_email');
			$table->integer('operator_id');
			$table->integer('customer_service_id');
			$table->enum('is_clearance', array('0','1'))->default('0');
			$table->enum('is_available', array('0','1'))->default('0');
			$table->string('amazon_api_url');
			$table->string('amazon_marketplace_id');
			$table->string('amazon_seller_id');
			$table->string('amazon_accesskey_id');
			$table->string('amazon_accesskey_secret');
			$table->string('wish_publish_code');
			$table->string('wish_client_id');
			$table->string('wish_client_secret');
			$table->string('wish_redirect_uri');
			$table->string('wish_refresh_token');
			$table->string('wish_access_token');
			$table->dateTime('wish_expiry_time');
			$table->string('wish_proxy_address');
			$table->enum('wish_sku_resolve', array('1','2'))->default('1');
			$table->string('aliexpress_member_id');
			$table->string('aliexpress_appkey');
			$table->string('aliexpress_appsecret');
			$table->string('aliexpress_returnurl');
			$table->string('aliexpress_refresh_token');
			$table->string('aliexpress_access_token');
			$table->dateTime('aliexpress_access_token_date');
			$table->string('ebay_developer_account');
			$table->string('ebay_developer_devid');
			$table->string('ebay_developer_appid');
			$table->string('ebay_developer_certid');
			$table->text('ebay_token', 65535);
			$table->string('ebay_eub_developer');
			$table->string('lazada_access_key')->nullable();
			$table->string('lazada_user_id')->nullable();
			$table->string('lazada_site')->nullable();
			$table->string('lazada_currency_type')->nullable();
			$table->string('lazada_currency_type_cn')->nullable();
			$table->string('lazada_api_host')->nullable();
			$table->enum('joom_sku_resolve', array('1','2'));
			$table->string('joom_proxy_address')->default('');
			$table->string('joom_expiry_time')->default('');
			$table->string('joom_access_token', 355)->default('');
			$table->string('joom_refresh_token', 355)->default('');
			$table->string('joom_redirect_uri')->default('');
			$table->string('joom_client_secret')->default('');
			$table->string('joom_client_id')->default('');
			$table->string('joom_publish_code')->default('');
			$table->string('cd_currency_type')->nullable();
			$table->string('cd_currency_type_cn')->nullable();
			$table->string('cd_account')->nullable();
			$table->string('cd_token_id')->nullable();
			$table->string('cd_pw')->nullable();
			$table->string('cd_sales_account')->nullable();
			$table->integer('cd_expires_in')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->text('message_secret', 65535)->nullable();
			$table->text('message_token', 65535)->nullable();
			$table->integer('catalog_rates_channel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('channel_accounts');
    }

}
