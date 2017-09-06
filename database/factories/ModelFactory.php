<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(\App\Models\OrderModel::class, function ($faker) {
    $channel_id = mt_rand(2,4);
   return [
        'channel_id' => $channel_id,
        'channel_account_id' => [1,4,7][$channel_id-2],
        'channel_ordernum' => $faker->phoneNumber,
        'amount' => mt_rand(10,500)/100,
        'shipping_firstname' => $faker->firstName,
        'shipping_lastname' => $faker->lastName,
        'shipping_address' => $faker->address,
        'shipping_address1' => $faker->address,
        'shipping_city' => $faker->city,
        'shipping_state' => $faker->streetAddress,
        'shipping_country' => $faker->countryCode,
        'shipping_zipcode' => mt_rand(100000,999999),
        'shipping_phone' => $faker->phoneNumber,
   ];
});

$factory->define(\App\Models\Order\ItemModel::class, function ($faker) {
   return [
        'item_id' => \App\Models\StockModel::take('50')->get(['item_id'])[mt_rand(0,49)]['item_id'],
        'quantity' => mt_rand(1,10),
        'price' => mt_rand(14,350)/100,

   ];
});
