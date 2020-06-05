<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Rate::class, function (Faker $faker) {
    return [
        'branch_id' => rand(1, 5),
        'item_type_id' => rand(1, 5),
        'karat' => rand(9, 24),
        'gram' => rand(4170, 9999) / 10000,
        'regular_rate' => rand(300, 1700),
        'special_rate' => rand(400, 1800),
        'description' => null
    ];
});
