<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Branch;
use Faker\Generator as Faker;

$factory->define(Branch::class, function (Faker $faker) {
    return [
        'branch' => $faker->city,
        'address' => $faker->address,
        'tin' => rand(100, 999)."-".rand(100,999)."-".rand(100,999)."-".rand(1000,9999),
        'contact_number' => '09'.rand(100000000, 999999999)
    ];
});
