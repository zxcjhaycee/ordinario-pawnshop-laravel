<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Item_type::class, function (Faker $faker) {
    return [
        'item_category_id' => rand(1, 3),
        'item_type' => $faker->name
    ];
});
