<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Item_category::class, function (Faker $faker) {
    return ['item_category' => $faker->name];
});
