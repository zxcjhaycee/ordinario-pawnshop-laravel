<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'username' => 'admin',
        'password' => '$2y$10$F5Iu0fbep2q92g./Q52b7uQukmjbpdnM/2iop6GbjLTO6S5sZd/VO',
        'branch_id' => rand(1, 3),
        'access' => 'admin',
        'auth_code' => '1234'
    ];
});
